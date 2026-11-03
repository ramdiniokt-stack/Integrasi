@extends('layouts.app')

@section('content')
    <h2>Tambah Buku</h2>

    <form action="{{ route('books.store') }}" method="POST">
        @csrf

        @include('books._form')

        <button type="submit">Simpan</button>
        <a href="{{ route('books.index') }}">Batal</a>
    </form>
@endsection