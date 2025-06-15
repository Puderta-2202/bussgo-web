<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- Tambahkan ini
use Carbon\Carbon; // <-- Tambahkan ini

class JadwalKeberangkatan extends Model
{
    use HasFactory;

    /**
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
        'jam_berangkat' => 'string', // Atau cukup 'string' jika Anda handle manual
        'jam_sampai' => 'string',    // Atau cukup 'string'
    ];

    /**
     * Relasi Many-to-One: Satu JadwalKeberangkatan dimiliki oleh satu Bus.
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    public function pemesanans() // atau nama relasi yang Anda inginkan
    {
        return $this->hasMany(Pemesanan::class, 'keberangkatan_id');
    }

    // public function getStatusPerjalananAttribute(): string
    // {
    //     // Gabungkan tanggal dan jam berangkat menjadi satu objek waktu
    //     $waktuBerangkat = Carbon::parse($this->tanggal_berangkat . ' ' . $this->jam_berangkat);
    //     $waktuSampai = Carbon::parse($this->tanggal_berangkat . ' ' . $this->jam_sampai);

    //     // Jika jam sampai lebih awal dari jam berangkat (berarti tiba di hari berikutnya)
    //     if ($waktuSampai->isBefore($waktuBerangkat)) {
    //         $waktuSampai->addDay();
    //     }

    //     // Cek status berdasarkan waktu sekarang
    //     if ($this->status_jadwal == 'dibatalkan') {
    //         return 'Dibatalkan';
    //     } elseif ($this->status_jadwal == 'selesai') {
    //         return 'Selesai';
    //     } elseif (Carbon::now()->isBefore($waktuBerangkat)) {
    //         return 'Belum Berangkat';
    //     } elseif (Carbon::now()->between($waktuBerangkat, $waktuSampai)) {
    //         return 'Telah Berangkat / Sedang Berjalan';
    //     } else {
    //         return 'Perjalanan Selesai';
    //     }
    // }
}
