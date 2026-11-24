<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Admin dan petugas: seluruh fitur perpustakaan, wajib login
Route::middleware(['auth', 'nocache'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class)->only('index');
    // Peminjaman: daftar, form, simpan, detail (tanpa edit dan hapus
    // agar riwayat transaksi tidak berubah sembarangan)
    Route::resource('borrowings', BorrowingController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Pengembalian buku
    Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])
        ->name('borrowings.return');
});

// Hanya admin: wajib login DAN lolos Gate manage-users
Route::middleware(['auth', 'nocache', 'can:manage-users'])->group(function () {
    Route::resource('users', UserController::class)->except('show');
});