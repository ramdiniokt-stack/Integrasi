@extends('layouts.app')

@section('content')
    <h2>Detail Peminjaman</h2>

    <table>
        <tr>
            <th align="left">Anggota</th>
            <td>: {{ $borrowing->member->name }} ({{ $borrowing->member->member_code }})</td>
        </tr>
        <tr>
            <th align="left">Petugas</th>
            <td>: {{ $borrowing->user->name }}</td>
        </tr>
        <tr>
            <th align="left">Tanggal pinjam</th>
            <td>: {{ $borrowing->borrowing_date->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th align="left">Jatuh tempo</th>
            <td>: {{ $borrowing->due_date->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th align="left">Tanggal kembali</th>
            <td>: {{ $borrowing->return_date?->format('d-m-Y') ?? '-' }}</td>
        </tr>
        <tr>
            <th align="left">Status</th>
            <td>: {{ $borrowing->status === 'borrowed' ? 'Dipinjam' : 'Dikembalikan' }}</td>
        </tr>
    </table>

    <h3>Buku yang dipinjam</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowing->details as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->book->title }}</td>
                    <td>{{ $detail->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>

    @if ($borrowing->status === 'borrowed')
        <form action="{{ route('borrowings.return', $borrowing) }}" method="POST"
              onsubmit="return confirm('Proses pengembalian buku ini?')">
            @csrf
            @method('PATCH')
            <button type="submit">Kembalikan</button>
        </form>
    @endif

    <p><a href="{{ route('borrowings.index') }}">&laquo; Kembali ke daftar peminjaman</a></p>
@endsection