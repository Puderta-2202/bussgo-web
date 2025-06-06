<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth; // <-- Pastikan ini diimpor

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // --- LOGIKA PENGALIHAN UNTUK PENGGUNA YANG SUDAH LOGIN ---
        $middleware->redirectUsersTo(function ($request) {

            // PERBAIKAN DI SINI:
            // Periksa apakah pengguna yang sedang login menggunakan guard 'admin_sistem'.
            if (Auth::guard('admin_sistem')->check()) {
                // Jika ya, selalu arahkan ke dashboard admin.
                return route('admin.dashboard');
            }

            // Untuk guard lain (misalnya 'web' untuk user biasa), arahkan ke '/home'.
            // Karena Anda belum memiliki halaman '/home', ini akan 404 jika ada user biasa yang login.
            return '/home';
        });

        // Konfigurasi untuk pengguna yang belum login (sudah benar)
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->routeIs('admin.*')) {
                return route('admin.login.form');
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
