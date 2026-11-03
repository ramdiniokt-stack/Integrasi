<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// Halaman awal: arahkan ke daftar buku (jika belum login, auth akan mengirim ke /login)
Route::get('/', function () {
    return redirect()->route('books.index');
});

// Hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

// Logout hanya untuk yang sudah login, dan harus lewat POST
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Semua halaman di bawah ini dilindungi: wajib login
Route::middleware(['auth', 'nocache'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::resource('books', BookController::class);

    // Halaman sederhana. Ganti dengan controller Anda jika sudah dibuat.
    Route::view('/categories', 'pages.simple', ['title' => 'Categories'])
        ->name('categories.index');

    Route::view('/members', 'pages.simple', ['title' => 'Members'])
        ->name('members.index');
});