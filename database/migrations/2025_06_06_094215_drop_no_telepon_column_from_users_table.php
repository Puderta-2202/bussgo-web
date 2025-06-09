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
            // Perintah untuk menghapus kolom 'no_telepon'
            $table->dropColumn('no_telepon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ini akan dijalankan jika Anda melakukan rollback,
            // yaitu membuat kembali kolomnya.
            $table->string('no_telepon')->nullable()->after('no_handphone');
        });
    }
};
