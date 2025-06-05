<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Laravel akan mencari 'pemesanans' jika ini tidak ditentukan.
     * Karena nama tabel Anda 'pemesanan' (singular), kita perlu menentukannya.
     *
     * @var string
     */
    protected $table = 'pemesanan';

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bus_id', // Jika Anda tetap menggunakan ini secara langsung di tabel pemesanan
        'keberangkatan_id', // Ini akan menjadi jadwal_id jika Anda mengganti nama tabel/model keberangkatan
        'kode_booking', // Kolom baru yang disarankan
        'nama_pemesan',
        'email_pemesan', // Kolom baru yang disarankan
        'telepon_pemesan', // Kolom baru yang disarankan
        'jumlah_tiket',
        'total_harga', // Kolom baru yang disarankan
        'status_pembayaran', // Kolom baru yang disarankan
        'metode_pembayaran', // Kolom baru yang disarankan
    ];

    /**
     * Atribut yang seharusnya di-cast ke tipe data tertentu.
     *
     * @var array
     */
    protected $casts = [
        'total_harga' => 'decimal:2', // Memastikan presisi untuk harga
        'jumlah_tiket' => 'integer',
        // Jika Anda menyimpan tanggal spesifik pemesanan (bukan hanya dari jadwal)
        // 'tanggal_pemesanan' => 'datetime',
    ];

    /**
     * Relasi Many-to-One: Satu Pemesanan dimiliki oleh satu User.
     */
    public function user()
    {
        // Asumsi Model untuk tabel 'users' Anda adalah 'User'
        // dan foreign key di tabel ini ('pemesanan') adalah 'user_id'
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi Many-to-One: Satu Pemesanan terkait dengan satu Bus secara langsung.
     * Catatan: Ini mungkin redundan jika bus sudah terkait melalui JadwalKeberangkatan.
     */
    public function bus()
    {
        // Asumsi Model untuk tabel 'bus' Anda adalah 'Bus'
        // dan foreign key di tabel ini ('pemesanan') adalah 'bus_id'
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    /**
     * Relasi Many-to-One: Satu Pemesanan terkait dengan satu JadwalKeberangkatan.
     */
    public function jadwalKeberangkatan() // Atau 'keberangkatan' jika nama modelnya Keberangkatan
    {
        // Asumsi Model untuk tabel 'keberangkatan' Anda adalah 'JadwalKeberangkatan'
        // dan foreign key di tabel ini ('pemesanan') adalah 'keberangkatan_id'
        return $this->belongsTo(JadwalKeberangkatan::class, 'keberangkatan_id');
    }

    /**
     * Jika Anda memiliki tabel detail_pemesanan untuk setiap kursi yang dipesan.
     * Relasi One-to-Many: Satu Pemesanan bisa memiliki banyak DetailPemesanan.
     */
    // public function detailPemesanans()
    // {
    //     // Asumsi Model untuk tabel 'detail_pemesanan' Anda adalah 'DetailPemesanan'
    //     // dan foreign key di tabel 'detail_pemesanan' adalah 'pemesanan_id'
    //     return $this->hasMany(DetailPemesanan::class, 'pemesanan_id');
    // }


    /**
     * Kolom 'id' adalah primary key dan auto-increment (default Laravel).
     * Kolom 'created_at' dan 'updated_at' juga dikelola otomatis (default Laravel).
     */
}
