<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKeberangkatan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Laravel akan mencari 'jadwal_keberangkatans' jika ini tidak ditentukan.
     * Karena nama tabel Anda 'keberangkatan', kita perlu menentukannya.
     *
     * @var string
     */
    protected $table = 'keberangkatan'; // Sesuaikan dengan nama tabel Anda

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bus_id',
        'asal',
        'tujuan',
        'tanggal_berangkat',
        'jam_berangkat',
        'jam_sampai',
        'durasi_perjalanan',
        'harga',
        'jumlah_kursi_tersedia',
        'status_jadwal',
    ];

    /**
     * Atribut yang seharusnya di-cast ke tipe data tertentu.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_berangkat' => 'date', // Otomatis cast ke objek Carbon/DateTime
        'harga' => 'decimal:0', // Sesuai dengan decimal(10,0) di DB. Jika Anda ubah DB ke decimal(10,2), ganti ini jadi 'decimal:2'
        'jumlah_kursi_tersedia' => 'integer',
        'jam_berangkat' => 'datetime:H:i:s', // Atau cukup 'string' jika Anda handle manual
        'jam_sampai' => 'datetime:H:i:s',    // Atau cukup 'string'
    ];

    /**
     * Relasi Many-to-One: Satu JadwalKeberangkatan dimiliki oleh satu Bus.
     */
    public function bus()
    {
        // Asumsi Model untuk tabel 'bus' Anda adalah 'Bus'
        // dan foreign key di tabel ini ('keberangkatan') adalah 'bus_id'
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    /**
     * Relasi One-to-Many: Satu JadwalKeberangkatan bisa memiliki banyak Pemesanan.
     */
    public function pemesanans() // atau nama relasi yang Anda inginkan
    {
        // Asumsi Model untuk tabel 'pemesanan' Anda adalah 'Pemesanan'
        // dan foreign key di tabel 'pemesanan' yang merujuk ke jadwal ini adalah 'keberangkatan_id'
        return $this->hasMany(Pemesanan::class, 'keberangkatan_id');
    }

    /**
     * Kolom 'id' adalah primary key dan auto-increment (default Laravel).
     * Kolom 'created_at' dan 'updated_at' juga dikelola otomatis (default Laravel).
     */
}
