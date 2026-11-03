<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
</head>
<body>
    <h1>Library System</h1>

    <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a> |
        <a href="{{ route('books.index') }}">Books</a> |
        <a href="{{ route('categories.index') }}">Categories</a> |
        <a href="{{ route('members.index') }}">Members</a>

        @auth
            | Halo, {{ Auth::user()->name }}

            {{-- Ketentuan 6: tombol logout --}}
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @endauth
    </nav>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @yield('content')

    <footer>
        <p>&copy; {{ date('Y') }} Library System</p>
    </footer>
</body>
</html>