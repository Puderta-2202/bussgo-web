<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Laravel akan mencari 'laporans' jika ini tidak ditentukan.
     * Karena nama tabel Anda 'laporan' (singular), kita perlu menentukannya.
     *
     * @var string
     */
    protected $table = 'laporan';

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jenis_laporan',
        'deskripsi',
        'tanggal_laporan',
    ];

    /**
     * Atribut yang seharusnya di-cast ke tipe data tertentu.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_laporan' => 'date', // Otomatis cast ke objek Carbon/DateTime
    ];

    /**
     * Kolom 'id' adalah primary key dan auto-increment (default Laravel).
     * Kolom 'created_at' dan 'updated_at' juga dikelola otomatis (default Laravel).
     */

    // Tidak ada relasi Eloquent yang umum didefinisikan di sini untuk saat ini
    // berdasarkan skema database Anda.
    // Jika di masa depan laporan ini dibuat oleh admin atau user tertentu,
    // Anda bisa menambahkan relasi belongsTo di sini.
    // Contoh:
    // public function adminPembuat()
    // {
    //     return $this->belongsTo(AdminSistem::class, 'admin_id'); // Jika ada kolom admin_id
    // }
    // public function userPelapor()
    // {
    //     return $this->belongsTo(User::class, 'user_id'); // Jika ada kolom user_id
    // }
}
