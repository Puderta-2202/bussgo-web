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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom no_handphone setelah kolom email
            $table->string('no_handphone')->nullable()->unique()->after('email');

            // Menambahkan kolom saldo dengan tipe data decimal untuk presisi
            // Default nilai saldo adalah 0 saat user pertama kali mendaftar
            $table->decimal('saldo', 15, 2)->default(0)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_handphone');
            $table->dropColumn('saldo');
        });
    }
};
