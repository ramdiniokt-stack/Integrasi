@extends('layouts.app')
@section('title', $book->title)

@section('content')
    <h2>{{ $book->title }}</h2>

    <table>
        <tr><th>Penulis</th><td>{{ $book->author }}</td></tr>
        <tr><th>Kategori</th><td>{{ $book->category?->name ?? 'Belum ada kategori' }}</td></tr>
        <tr><th>Tahun</th><td>{{ $book->year }}</td></tr>
        <tr><th>Stok</th><td>{{ $book->stock }}</td></tr>
    </table>

    <p>
        <a href="{{ route('books.edit', $book) }}">Edit</a>
        <a href="{{ route('books.index') }}">Kembali</a>
    </p>
@endsection