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
        Schema::create('admin_sistem', function (Blueprint $table) {
            $table->id(); // Ini membuat `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->string('nama'); // defaultnya VARCHAR(255) NOT NULL
            $table->string('email')->unique(); // defaultnya VARCHAR(255) NOT NULL dan menambahkan UNIQUE constraint
            $table->string('password'); // defaultnya VARCHAR(255) NOT NULL
            $table->timestamp('created_at')->nullable(); // Sesuai dengan `timestamp NULL DEFAULT NULL`
            $table->timestamp('updated_at')->nullable(); // Sesuai dengan `timestamp NULL DEFAULT NULL`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_sistem');
    }
};
