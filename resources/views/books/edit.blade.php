@extends('layouts.app')

@section('content')
    <h2>Edit Buku</h2>

    <form action="{{ route('books.update', $book) }}" method="POST">
        @csrf
        @method('PUT')

        @include('books._form')

        <button type="submit">Perbarui</button>
        <a href="{{ route('books.index') }}">Batal</a>
    </form>
@endsection