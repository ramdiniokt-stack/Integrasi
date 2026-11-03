@extends('layouts.app')

@section('content')
    <h2>Dashboard</h2>

    <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>.</p>
    <p>Anda login sebagai {{ Auth::user()->email }}.</p>

    <ul>
        <li>Total buku: {{ $totalBooks }}</li>
        <li>Total kategori: {{ $totalCategories }}</li>
    </ul>

    <p><a href="{{ route('books.index') }}">Lihat daftar buku</a></p>
@endsection