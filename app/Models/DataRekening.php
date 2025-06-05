<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataRekening extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Laravel akan mencari 'data_rekenings' jika ini tidak ditentukan.
     * Karena nama tabel Anda 'data_rekening' (singular), kita perlu menentukannya.
     *
     * @var string
     */
    protected $table = 'data_rekening';

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'pemilik_rekening',
    ];

    /**
     * Atribut yang seharusnya di-cast ke tipe data tertentu.
     *
     * @var array
     */
    // protected $casts = [
    //     // Tidak ada casting khusus yang diperlukan untuk kolom saat ini,
    //     // kecuali jika Anda memiliki kebutuhan spesifik.
    // ];

    /**
     * Kolom 'id' adalah primary key dan auto-increment (default Laravel).
     * Kolom 'created_at' dan 'updated_at' juga dikelola otomatis (default Laravel).
     */

    // Tidak ada relasi Eloquent yang didefinisikan di sini untuk saat ini,
    // karena tabel ini berdiri sendiri dan tidak memiliki foreign key ke tabel lain,
    // dan juga tidak ada tabel lain yang secara langsung merujuk ke 'data_rekening.id'
    // sebagai foreign key dalam skema yang kita diskusikan untuk fungsionalitas inti.
}
