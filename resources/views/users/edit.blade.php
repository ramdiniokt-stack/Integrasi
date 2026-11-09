@extends('layouts.app')

@section('content')
    <h2>Edit Pengguna</h2>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Nama</label><br>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
        </div>
        <br>

        <div>
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
        </div>
        <br>

        <div>
            <label for="password">Password (kosongkan jika tidak diganti)</label><br>
            <input type="password" name="password" id="password">
        </div>
        <br>

        <div>
            <label for="role">Role</label><br>
            <select name="role" id="role">
                <option value="petugas" @selected(old('role', $user->role) === 'petugas')>Petugas</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
            </select>
        </div>
        <br>

        <button type="submit">Perbarui</button>
        <a href="{{ route('users.index') }}">Batal</a>
    </form>
@endsection