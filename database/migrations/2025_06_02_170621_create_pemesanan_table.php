<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id(); // 'id' BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // Foreign key ke tabel 'users'
            $table->foreignId('user_id')
                ->nullable() // Jika tamu boleh memesan atau jika user bisa dihapus
                ->constrained('users') // Merujuk ke tabel 'users' kolom 'id'
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL'); // Jika user dihapus, user_id jadi NULL

            // Foreign key ke tabel 'bus' (jika Anda tetap ingin menyimpannya di sini)
            $table->foreignId('bus_id')
                ->constrained('bus') // Merujuk ke tabel 'bus' kolom 'id'
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT'); // Mencegah penghapusan bus jika ada pemesanan terkait

            // Foreign key ke tabel 'keberangkatan' (atau 'jadwal_keberangkatan')
            // Pastikan nama tabel 'keberangkatan' sudah benar
            $table->foreignId('keberangkatan_id')
                ->constrained('keberangkatan') // Merujuk ke tabel 'keberangkatan' kolom 'id'
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->string('kode_booking')->unique()->nullable(); // Kode unik untuk pemesanan, bisa generate nanti
            $table->string('nama_pemesan');
            $table->string('email_pemesan')->nullable();
            $table->string('telepon_pemesan')->nullable();
            $table->integer('jumlah_tiket');
            $table->decimal('total_harga', 12, 2); // Misal total 12 digit, 2 desimal
            $table->enum('status_pembayaran', ['pending', 'berhasil', 'gagal', 'kadaluarsa', 'dibatalkan'])->default('pending');
            $table->string('metode_pembayaran')->nullable();
            $table->timestamps(); // 'created_at' dan 'updated_at' TIMESTAMP NULLABLE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
