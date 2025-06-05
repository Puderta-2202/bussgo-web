<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\AdminSistemController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 1. Mengarahkan rute root (/) ke halaman login admin
Route::get('/', function () {
    return redirect()->route('admin.login.form');
});

// Grup Rute untuk Admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Rute untuk tamu admin (belum login)
    // Middleware 'guest:admin_sistem' memastikan rute ini hanya bisa diakses jika admin BELUM login.
    // Jika sudah login, akan di-redirect ke 'admin.dashboard' (atau sesuai konfigurasi di RedirectIfAuthenticated).
    Route::middleware('guest:admin_sistem')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login.form');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
        Route::get('register', [AdminAuthController::class, 'showRegistrationForm'])->name('register.form');
        Route::post('register', [AdminAuthController::class, 'register'])->name('register.submit');
    });

    // Rute untuk admin yang sudah login
    // Middleware 'auth:admin_sistem' memastikan rute ini hanya bisa diakses jika admin SUDAH login.
    // Jika belum login, akan di-redirect ke 'admin.login.form' (atau sesuai konfigurasi di Authenticate middleware).
    Route::middleware('auth:admin_sistem')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Rute dashboard admin yang memanggil DashboardController
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Tambahkan rute CRUD Anda di sini nanti di dalam grup middleware 'auth:admin_sistem'
        // Contoh:
        Route::resource('bus', App\Http\Controllers\Admin\BusController::class);
        Route::get('admins', [AdminAuthController::class, 'indexAdmins'])->name('admins.index');
        Route::get('admins/create', [AdminAuthController::class, 'createAdmin'])->name('admins.create'); // Asumsi method createAdmin ada
        Route::post('admins', [AdminAuthController::class, 'register'])->name('admins.store'); // Atau storeAdmin jika ada
        Route::get('admins/{adminSistem}/edit', [AdminAuthController::class, 'editAdmin'])->name('admins.edit');
        Route::put('admins/{adminSistem}', [AdminAuthController::class, 'updateAdmin'])->name('admins.update');
        Route::delete('admins/{adminSistem}', [AdminAuthController::class, 'destroyAdmin'])->name('admins.destroy');
    });
});

// Hapus atau komentari rute 'welcome' bawaan jika masih ada
// Route::get('/welcome', function () {
//     return view('welcome');
// });