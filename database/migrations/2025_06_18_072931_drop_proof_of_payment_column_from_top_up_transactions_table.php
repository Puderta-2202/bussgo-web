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
        Schema::table('top_up_transactions', function (Blueprint $table) {
            // Periksa dulu apakah kolomnya ada sebelum menghapus untuk menghindari error
            if (Schema::hasColumn('top_up_transactions', 'proof_of_payment')) {
                $table->dropColumn('proof_of_payment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('top_up_transactions', function (Blueprint $table) {
            // Ini akan membuat kembali kolomnya jika Anda melakukan rollback
            $table->string('proof_of_payment')->nullable();
        });
    }
};
