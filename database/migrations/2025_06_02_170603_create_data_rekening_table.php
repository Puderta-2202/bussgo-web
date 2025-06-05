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
        Schema::create('data_rekening', function (Blueprint $table) {
            $table->id(); // Membuat 'id' BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('nama_bank'); // Default VARCHAR(255) NOT NULL
            $table->string('nomor_rekening'); // Default VARCHAR(255) NOT NULL
            // Jika Anda ingin nomor_rekening unik, tambahkan ->unique()
            // $table->string('nomor_rekening')->unique();
            $table->string('pemilik_rekening'); // Default VARCHAR(255) NOT NULL
            $table->timestamps(); // Membuat 'created_at' dan 'updated_at' TIMESTAMP NULLABLE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rekening');
    }
};
