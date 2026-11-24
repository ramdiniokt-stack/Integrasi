<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Book::class);

        $books = Book::with('category')->orderBy('id')->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        Gate::authorize('create', Book::class);

        $categories = Category::orderBy('name')->get();

        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Book::class);

        $data = $this->validateBook($request);

        Book::create($data);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        Gate::authorize('view', $book);

        $book->load('category');

        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        Gate::authorize('update', $book);

        $categories = Category::orderBy('name')->get();

        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        Gate::authorize('update', $book);

        $data = $this->validateBook($request);

        $book->update($data);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        Gate::authorize('delete', $book);

        // buku yang sudah pernah dipinjam tidak boleh dihapus
        if ($book->borrowingDetails()->exists()) {
            return redirect()
                ->route('books.index')
                ->with('error', 'Buku tidak dapat dihapus karena sudah memiliki riwayat peminjaman.');
        }

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil dihapus.');
    }

    private function validateBook(Request $request): array
    {
        return $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'year'        => 'required|integer|min:1000|max:' . date('Y'),
            'stock'       => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ], [
            'title.required'     => 'Judul wajib diisi.',
            'author.required'    => 'Penulis wajib diisi.',
            'year.required'      => 'Tahun wajib diisi.',
            'year.integer'       => 'Tahun harus berupa angka.',
            'year.min'           => 'Tahun tidak valid.',
            'year.max'           => 'Tahun tidak boleh melebihi tahun ini.',
            'stock.required'     => 'Stok wajib diisi.',
            'stock.integer'      => 'Stok harus berupa angka.',
            'stock.min'          => 'Stok tidak boleh negatif.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
        ]);
    }
}