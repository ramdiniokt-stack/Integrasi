@extends('layouts.app')

@section('content')
    <h2>Peminjaman Baru</h2>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('borrowings.store') }}" method="POST">
        @csrf

        <div>
            <label for="member_id">Anggota</label><br>
            <select name="member_id" id="member_id">
                <option value="">-- Pilih anggota --</option>
                @foreach ($members as $member)
                    <option value="{{ $member->id }}"
                        @selected((string) old('member_id') === (string) $member->id)>
                        {{ $member->name }} ({{ $member->member_code }})
                    </option>
                @endforeach
            </select>
        </div>
        <br>

        <p>Buku yang dipinjam (isi minimal satu baris):</p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Buku</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 3; $i++)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <select name="items[{{ $i }}][book_id]">
                                <option value="">-- Pilih buku --</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}"
                                        @disabled($book->stock < 1)
                                        @selected((string) old("items.$i.book_id") === (string) $book->id)>
                                        {{ $book->title }} (stok: {{ $book->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[{{ $i }}][quantity]" min="1"
                                   value="{{ old("items.$i.quantity", 1) }}">
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <br>

        <p>Lama peminjaman 7 hari sejak hari ini.</p>

        <button type="submit">Simpan Peminjaman</button>
        <a href="{{ route('borrowings.index') }}">Batal</a>
    </form>
@endsection