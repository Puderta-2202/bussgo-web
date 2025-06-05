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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Membuat 'id' BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('nama_lengkap');
            $table->string('username')->unique(); // Sesuai dengan UNIQUE KEY `users_username_unique`
            $table->string('email'); // Anda mungkin ingin ->unique() di sini juga
            $table->string('no_telepon', 20); // varchar(20)
            $table->string('password');
            $table->text('alamat');
            $table->decimal('saldo_buspay', 15, 2)->default(0.00); // Sesuai dengan decimal(15,2), tambahkan default jika perlu
            $table->timestamp('email_verified_at')->nullable(); // Mengubah menjadi NULLABLE agar sesuai dengan praktik umum Laravel
            $table->rememberToken(); // Membuat 'remember_token' VARCHAR(100) NULLABLE
            $table->timestamps(); // Membuat 'created_at' dan 'updated_at' TIMESTAMP NULLABLE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
