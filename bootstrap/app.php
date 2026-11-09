<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // alias middleware
        $middleware->alias([
            'nocache' => \App\Http\Middleware\PreventBackHistory::class,
            'role'    => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // tamu yang membuka halaman terlindungi dikirim ke form login
        $middleware->redirectGuestsTo(fn () => route('login'));

        // pengguna yang sudah login dan membuka /login dikirim ke daftar buku
        $middleware->redirectUsersTo(fn () => route('books.index'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();