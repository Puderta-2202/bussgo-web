<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Laravel akan mencari 'buses' jika ini tidak ditentukan.
     * Karena nama tabel Anda 'bus' (singular), kita perlu menentukannya.
     *
     * @var string
     */
    protected $table = 'bus';

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_bus',
        'jenis_bus',
        'plat_nomor',
        // 'jumlah_kursi', // Jika Anda menambahkan kolom ini ke tabel bus di migrasi/database
    ];

    /**
     * Atribut yang seharusnya di-cast ke tipe data tertentu.
     *
     * @var array
     */
    // protected $casts = [
    //     'jumlah_kursi' => 'integer', // Contoh jika ada kolom jumlah_kursi
    // ];

    /**
     * Relasi One-to-Many: Satu Bus bisa memiliki banyak JadwalKeberangkatan.
     */
    public function jadwalKeberangkatans() // atau jadwalKeberangkatan jika Anda lebih suka singular untuk relasi hasMany
    {
        // Asumsi Model untuk tabel 'keberangkatan' Anda adalah 'JadwalKeberangkatan'
        // dan foreign key di tabel 'keberangkatan' adalah 'bus_id'
        return $this->hasMany(JadwalKeberangkatan::class, 'bus_id');
    }

    /**
     * Relasi One-to-Many: Satu Bus bisa terkait dengan banyak Pemesanan secara langsung.
     * Ini jika Anda mempertahankan kolom 'bus_id' di tabel 'pemesanan'.
     * Jika tidak, Anda akan mengakses pemesanan melalui JadwalKeberangkatan.
     */
    public function pemesanans()
    {
        // Asumsi Model untuk tabel 'pemesanan' Anda adalah 'Pemesanan'
        // dan foreign key di tabel 'pemesanan' adalah 'bus_id'
        return $this->hasMany(Pemesanan::class, 'bus_id');
    }

    /**
     * Kolom 'id' adalah primary key dan auto-increment (default Laravel).
     * Kolom 'created_at' dan 'updated_at' juga dikelola otomatis (default Laravel).
     */
}
