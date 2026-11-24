<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BorrowingController extends Controller
{
    /**
     * Daftar transaksi peminjaman (riwayat).
     */
    public function index()
    {
        $borrowings = Borrowing::with(['member', 'user', 'details.book'])
            ->latest('id')
            ->get();

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Form peminjaman baru.
     */
    public function create()
    {
        $members = Member::orderBy('name')->get();
        $books   = Book::orderBy('title')->get();

        return view('borrowings.create', compact('members', 'books'));
    }

    /**
     * Simpan transaksi peminjaman dan kurangi stok.
     */
    public function store(Request $request)
    {
        // Buang baris buku yang kosong sebelum divalidasi
        $items = collect($request->input('items', []))
            ->filter(fn ($item) => ! empty($item['book_id']))
            ->values()
            ->all();

        $request->merge(['items' => $items]);

        // Ketentuan 6: validasi input
        $request->validate([
            'member_id'        => 'required|exists:members,id',
            'items'            => 'required|array|min:1',
            'items.*.book_id'  => 'required|exists:books,id|distinct',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'member_id.required'        => 'Anggota wajib dipilih.',
            'member_id.exists'          => 'Anggota tidak ditemukan.',
            'items.required'            => 'Pilih minimal satu buku.',
            'items.min'                 => 'Pilih minimal satu buku.',
            'items.*.book_id.exists'    => 'Buku tidak ditemukan.',
            'items.*.book_id.distinct'  => 'Buku yang sama dipilih lebih dari sekali.',
            'items.*.quantity.required' => 'Jumlah buku wajib diisi.',
            'items.*.quantity.integer'  => 'Jumlah buku harus berupa angka.',
            'items.*.quantity.min'      => 'Jumlah buku minimal 1.',
        ]);

        // Ketentuan 3 dan 4: simpan transaksi dan kurangi stok.
        // Semua langkah ada dalam satu transaksi database: jika satu buku
        // gagal, seluruh perubahan dibatalkan (tidak ada data setengah jadi).
        DB::transaction(function () use ($request, $items) {
            $borrowing = Borrowing::create([
                'user_id'        => auth()->id(),
                'member_id'      => $request->member_id,
                'borrowing_date' => now()->toDateString(),
                'due_date'       => now()->addDays(7)->toDateString(),
                'status'         => 'borrowed',
            ]);

            foreach ($items as $index => $item) {
                // lockForUpdate mencegah dua transaksi bersamaan
                // memakai stok terakhir yang sama
                $book = Book::lockForUpdate()->findOrFail($item['book_id']);

                if ($book->stock < 1) {
                    throw ValidationException::withMessages([
                        "items.$index.book_id" => "Buku \"{$book->title}\" tidak dapat dipinjam karena stok habis.",
                    ]);
                }

                if ($book->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        "items.$index.quantity" => "Stok buku \"{$book->title}\" tidak mencukupi (tersisa {$book->stock}).",
                    ]);
                }

                $borrowing->details()->create([
                    'book_id'  => $book->id,
                    'quantity' => $item['quantity'],
                ]);

                $book->decrement('stock', $item['quantity']);
            }
        });

        return redirect()
            ->route('borrowings.index')
            ->with('success', 'Transaksi peminjaman berhasil disimpan. Stok buku telah dikurangi.');
    }

    /**
     * Detail satu transaksi.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['member', 'user', 'details.book']);

        return view('borrowings.show', compact('borrowing'));
    }

        /**
     * Proses pengembalian: isi tanggal kembali, ubah status, tambah stok.
     */
    public function returnBook(Borrowing $borrowing)
    {
        $returned = DB::transaction(function () use ($borrowing) {
            // ambil ulang dengan kunci agar tidak terproses dua kali
            $borrowing = Borrowing::with('details.book')
                ->lockForUpdate()
                ->findOrFail($borrowing->id);

            if ($borrowing->status !== 'borrowed') {
                return false;
            }

            $borrowing->update([
                'return_date' => now()->toDateString(),
                'status'      => 'returned',
            ]);

            foreach ($borrowing->details as $detail) {
                $detail->book->increment('stock', $detail->quantity);
            }

            return true;
        });

        if (! $returned) {
            return redirect()
                ->route('borrowings.index')
                ->with('error', 'Transaksi ini sudah dikembalikan sebelumnya.');
        }

        return redirect()
            ->route('borrowings.index')
            ->with('success', 'Buku berhasil dikembalikan. Stok buku telah ditambah.');
    }
}