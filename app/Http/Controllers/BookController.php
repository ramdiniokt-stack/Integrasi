<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Daftar buku.
     */
    public function index()
    {
        $books = Book::orderBy('id')->get();

        return view('books.index', compact('books'));
    }

    /**
     * Form tambah buku.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Simpan buku baru.
     */
    public function store(Request $request)
    {
        $data = $this->validateBook($request);

        Book::create($data);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil ditambahkan.');
    }

    /**
     * Detail satu buku.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Form ubah buku.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Simpan perubahan buku.
     */
    public function update(Request $request, Book $book)
    {
        $data = $this->validateBook($request);

        $book->update($data);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Hapus buku.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil dihapus.');
    }

    /**
     * Aturan validasi yang dipakai store dan update.
     */
    private function validateBook(Request $request): array
    {
        return $request->validate([
            'title'  => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'year'   => 'required|integer|min:1000|max:' . date('Y'),
            'stock'  => 'required|integer|min:0',
        ], [
            'title.required'  => 'Judul wajib diisi.',
            'author.required' => 'Penulis wajib diisi.',
            'year.required'   => 'Tahun wajib diisi.',
            'year.integer'    => 'Tahun harus berupa angka.',
            'year.min'        => 'Tahun tidak valid.',
            'year.max'        => 'Tahun tidak boleh melebihi tahun ini.',
            'stock.required'  => 'Stok wajib diisi.',
            'stock.integer'   => 'Stok harus berupa angka.',
            'stock.min'       => 'Stok tidak boleh negatif.',
        ]);
    }
}