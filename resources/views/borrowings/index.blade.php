@extends('layouts.app')

@section('content')
    <h2>Peminjaman</h2>

    <p><a href="{{ route('borrowings.create') }}">+ Peminjaman Baru</a></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Anggota</th>
                <th>Petugas</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($borrowings as $borrowing)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $borrowing->member->name }}</td>
                    <td>{{ $borrowing->user->name }}</td>
                    <td>{{ $borrowing->borrowing_date->format('d-m-Y') }}</td>
                    <td>{{ $borrowing->due_date->format('d-m-Y') }}</td>
                    <td>{{ $borrowing->return_date?->format('d-m-Y') ?? '-' }}</td>
                    <td>
                        @if ($borrowing->status === 'borrowed')
                            Dipinjam
                            @if ($borrowing->due_date->lt(today()))
                                (terlambat)
                            @endif
                        @else
                            Dikembalikan
                        @endif
                    </td>
                    <td><a href="{{ route('borrowings.show', $borrowing) }}">Detail</a></td>
                    <td>
                        @if ($borrowing->status === 'borrowed')
                            <form action="{{ route('borrowings.return', $borrowing) }}" method="POST"
                                  onsubmit="return confirm('Proses pengembalian buku ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit">Kembalikan</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Belum ada transaksi peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection