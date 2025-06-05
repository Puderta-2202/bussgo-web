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
        Schema::create('bus', function (Blueprint $table) {
            $table->id(); // Membuat 'id' BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('nama_bus'); // Default VARCHAR(255) NOT NULL
            $table->string('jenis_bus'); // Default VARCHAR(255) NOT NULL
            $table->string('plat_nomor'); // Default VARCHAR(255) NOT NULL
            // Jika Anda ingin plat_nomor unik, tambahkan ->unique()
            // $table->string('plat_nomor')->unique();
            $table->timestamps(); // Membuat 'created_at' dan 'updated_at' TIMESTAMP NULLABLE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus');
    }
};
