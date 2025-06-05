<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Penting untuk Auth
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens; // Uncomment jika Anda berencana menggunakan Sanctum untuk API admin

class AdminSistem extends Authenticatable // Ubah Model menjadi Authenticatable
{
    use HasFactory, Notifiable; // Tambahkan HasApiTokens jika menggunakan Sanctum

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Laravel akan mencari 'admin_sistems' jika ini tidak ditentukan.
     * Karena nama tabel Anda 'admin_sistem' (singular), kita perlu menentukannya.
     *
     * @var string
     */
    protected $table = 'admin_sistem';

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
    ];

    /**
     * Atribut yang seharusnya disembunyikan saat serialisasi (misalnya saat diubah ke array atau JSON).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        // 'remember_token', // Tambahkan jika Anda menggunakan fitur "remember_me" untuk admin dan ada kolomnya
    ];

    /**
     * Atribut yang seharusnya di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime', // Jika Anda menambahkan kolom ini
        'password' => 'hashed', // Untuk Laravel 9+, otomatis hash saat diset
    ];

    /**
     * Kolom 'id' adalah primary key dan auto-increment (default Laravel).
     * Kolom 'created_at' dan 'updated_at' juga dikelola otomatis (default Laravel).
     * Jadi, tidak perlu konfigurasi eksplisit untuk $primaryKey, $incrementing, atau $timestamps
     * selama nama kolomnya 'id', 'created_at', dan 'updated_at'.
     */

    // Jika Anda membuat guard autentikasi khusus untuk admin (misalnya 'admin_sistem')
    // Anda mungkin perlu mendefinisikan ini, meskipun biasanya konfigurasi guard ada di config/auth.php
    // protected $guard = 'admin_sistem';
}
