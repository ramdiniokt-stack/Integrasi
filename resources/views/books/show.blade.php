@extends('layouts.app')

@section('content')
    <h2>Detail Buku</h2>

    <table>
        <tr>
            <th align="left">Judul</th>
            <td>: {{ $book->title }}</td>
        </tr>
        <tr>
            <th align="left">Penulis</th>
            <td>: {{ $book->author }}</td>
        </tr>
        <tr>
            <th align="left">Tahun</th>
            <td>: {{ $book->year }}</td>
        </tr>
        <tr>
            <th align="left">Stok</th>
            <td>: {{ $book->stock }}</td>
        </tr>
    </table>

    <p><a href="{{ route('books.index') }}">&laquo; Kembali ke daftar buku</a></p>
@endsection