<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambahkan kolom snap_token ke tabel orders.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom snap_token untuk menyimpan token transaksi dari Midtrans
            $table->string('snap_token')->nullable()->after('status');
        });
    }

    /**
     * Kembalikan perubahan migrasi jika di-rollback.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('snap_token');
        });
    }
};

