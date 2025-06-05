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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id(); // Membuat 'id' BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('jenis_laporan'); // Default VARCHAR(255) NOT NULL
            $table->text('deskripsi'); // Kolom TEXT NOT NULL
            $table->date('tanggal_laporan'); // Kolom DATE NOT NULL
            $table->timestamps(); // Membuat 'created_at' dan 'updated_at' TIMESTAMP NULLABLE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
