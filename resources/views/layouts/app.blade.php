<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan')</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 16px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f3f3f3; }
        label { display: block; margin-top: 12px; }
        input, select { padding: 6px; width: 100%; max-width: 360px; }
        .success { background: #d4edda; padding: 10px; margin-bottom: 16px; }
        .error { color: #c00; font-size: 14px; margin: 4px 0 0; }
    </style>
</head>
<body>
    <h1>Perpustakaan</h1>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @yield('content')
</body>
</html>