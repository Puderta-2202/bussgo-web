<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Uncomment jika Anda ingin implementasi verifikasi email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Uncomment jika Anda akan menggunakan Sanctum untuk API

class User extends Authenticatable // implements MustVerifyEmail // Uncomment jika MustVerifyEmail digunakan
{
    use HasApiTokens, HasFactory, Notifiable; // HasApiTokens jika perlu

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Laravel secara default akan mencari tabel 'users', yang sudah sesuai.
     * Jadi, properti $table tidak wajib didefinisikan secara eksplisit di sini.
     *
     * protected $table = 'users';
     */

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     * Sesuaikan dengan kolom-kolom di tabel 'users' Anda.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'no_handphone',
        'email_verified_at', // Biasanya diatur oleh Laravel atau proses verifikasi
        'password',
        'saldo',
        'alamat',
    ];

    /**
     * Atribut yang seharusnya disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang seharusnya di-cast ke tipe data tertentu.
     *
     * @return array<string, string>
     */
    protected function casts(): array // Syntax untuk Laravel 9+
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Otomatis hash saat diset
            'saldo_buspay' => 'decimal:2', // Pastikan presisi saldo benar
        ];
    }
    // Untuk Laravel 8 dan di bawahnya, gunakan properti $casts:
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    //     'password' => 'hashed', // Hanya untuk Laravel 9+, untuk versi lama gunakan mutator setPasswordAttribute
    //     'saldo_buspay' => 'decimal:2',
    // ];

    // Jika menggunakan Laravel < 9 dan ingin auto-hashing untuk password, gunakan mutator:
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }


    /**
     * Relasi One-to-Many: Satu User bisa memiliki banyak Pemesanan.
     */
    public function pemesanans() // atau pemesanan jika Anda lebih suka singular untuk relasi hasMany
    {
        // Asumsi Model untuk tabel 'pemesanan' Anda adalah 'Pemesanan'
        // dan foreign key di tabel 'pemesanan' adalah 'user_id'
        return $this->hasMany(Pemesanan::class, 'user_id');
    }

    // Jika Anda mengimplementasikan fitur di mana pengguna bisa menyimpan data rekeningnya
    // (misalnya untuk refund), Anda bisa menambahkan relasi ini.
    // Berdasarkan diskusi kita, ini belum menjadi prioritas.
    // public function dataRekenings()
    // {
    //     return $this->hasMany(DataRekening::class, 'user_id');
    // }
}
