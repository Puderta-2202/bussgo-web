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
        Schema::create('keberangkatan', function (Blueprint $table) {
            $table->id(); // Membuat 'id' BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            // Foreign key ke tabel 'bus'
            $table->foreignId('bus_id')
                ->constrained('bus') // Merujuk ke tabel 'bus' kolom 'id'
                ->onUpdate('RESTRICT') // Sesuai dengan SQL Anda
                ->onDelete('RESTRICT'); // Sesuai dengan SQL Anda
            // JANGAN tambahkan ->unique() di sini untuk bus_id

            $table->string('asal');
            $table->string('tujuan');
            $table->date('tanggal_berangkat');
            $table->time('jam_berangkat');
            $table->time('jam_sampai');
            $table->string('durasi_perjalanan', 50); // varchar(50)
            $table->decimal('harga', 10, 0); // Sesuai dengan decimal(10,0) di SQL Anda
            $table->integer('jumlah_kursi_tersedia');
            $table->enum('status_jadwal', ['aktif', 'dibatalkan', 'selesai']);
            $table->timestamps(); // Membuat 'created_at' dan 'updated_at' TIMESTAMP NULLABLE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keberangkatan');
    }
};
