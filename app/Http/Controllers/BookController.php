<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    // READ: daftar buku beserta kategorinya (eager loading)
    public function index()
    {
        $books = Book::with('category')->get();

        return view('books.index', compact('books'));
    }

    // CREATE: tampilkan form, kirim daftar kategori
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('books.create', compact('categories'));
    }

    // CREATE: simpan data
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        Book::create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil ditambahkan.');
    }

    // READ: detail satu buku
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    // UPDATE: tampilkan form edit, kirim buku dan daftar kategori
    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();

        return view('books.edit', compact('book', 'categories'));
    }

    // UPDATE: simpan perubahan
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate($this->rules($book), $this->messages());

        $book->update($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    // DELETE: hapus buku
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil dihapus.');
    }

    // Aturan validasi, dipakai bersama oleh store() dan update()
    private function rules(?Book $book = null): array
    {
        return [
            'title' => [
                'required', 'string', 'min:3', 'max:255',
                Rule::unique('books', 'title')->ignore($book?->id),
            ],
            'author' => [
                'required', 'string', 'max:255',
                'regex:/^[\pL\s.\'-]+$/u',
            ],
            'year' => [
                'required', 'integer', 'digits:4',
                'between:1900,' . date('Y'),
            ],
            'stock' => ['required', 'integer', 'min:0', 'max:1000'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    // Pesan kesalahan dalam bahasa Indonesia
    private function messages(): array
    {
        return [
            'title.required' => 'Judul buku wajib diisi.',
            'title.min'      => 'Judul buku minimal :min karakter.',
            'title.max'      => 'Judul buku maksimal :max karakter.',
            'title.unique'   => 'Judul buku sudah terdaftar.',

            'author.required' => 'Nama penulis wajib diisi.',
            'author.regex'    => 'Nama penulis hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung.',

            'year.required' => 'Tahun terbit wajib diisi.',
            'year.integer'  => 'Tahun terbit harus berupa angka bulat.',
            'year.digits'   => 'Tahun terbit harus terdiri dari 4 digit.',
            'year.between'  => 'Tahun terbit harus antara :min sampai :max.',

            'stock.required' => 'Stok wajib diisi.',
            'stock.integer'  => 'Stok harus berupa angka bulat.',
            'stock.min'      => 'Stok tidak boleh kurang dari :min.',
            'stock.max'      => 'Stok tidak boleh lebih dari :max.',

            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.integer'  => 'Kategori tidak valid.',
            'category_id.exists'   => 'Kategori yang dipilih tidak tersedia.',
        ];
    }
}