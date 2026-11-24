@extends('layouts.app')

@section('content')
    @can('create', App\Models\Book::class)
        <p><a href="{{ route('books.create') }}">+ Tambah Buku</a></p>
    @endcan

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th colspan="3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->year }}</td>
                    <td>{{ $book->category->name ?? '-' }}</td>
                    <td>{{ $book->stock }}</td>

                    <td>
                        @can('view', $book)
                            <a href="{{ route('books.show', $book) }}">Detail</a>
                        @endcan
                    </td>

                    <td>
                        @can('update', $book)
                            <a href="{{ route('books.edit', $book) }}">Edit</a>
                        @endcan
                    </td>

                    <td>
                        @can('delete', $book)
                            <form action="{{ route('books.destroy', $book) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Belum ada data buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection