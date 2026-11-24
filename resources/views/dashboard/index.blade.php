@extends('layouts.app')

@section('content')
    <h2>Dashboard</h2>

    <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->role }}).</p>

    <h3>Ringkasan</h3>
    <table>
        <tr>
            <td>Jumlah kategori</td>
            <td>: {{ $totalCategories }}</td>
        </tr>
        <tr>
            <td>Jumlah buku</td>
            <td>: {{ $totalBooks }}</td>
        </tr>
        <tr>
            <td>Total stok tersedia</td>
            <td>: {{ $totalStock }}</td>
        </tr>
        <tr>
            <td>Jumlah anggota</td>
            <td>: {{ $totalMembers }}</td>
        </tr>
        <tr>
            <td>Jumlah transaksi peminjaman</td>
            <td>: {{ $totalBorrowings }}</td>
        </tr>
        <tr>
            <td>Transaksi masih berjalan</td>
            <td>: {{ $activeBorrowings }}</td>
        </tr>
        <tr>
            <td>Transaksi terlambat</td>
            <td>: {{ $overdueBorrowings }}</td>
        </tr>
    </table>

    <h3>Transaksi Terbaru</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Anggota</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($latestBorrowings as $borrowing)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $borrowing->member->name }}</td>
                    <td>{{ $borrowing->borrowing_date->format('d-m-Y') }}</td>
                    <td>{{ $borrowing->due_date->format('d-m-Y') }}</td>
                    <td>{{ $borrowing->status === 'borrowed' ? 'Dipinjam' : 'Dikembalikan' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection