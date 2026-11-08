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
        // Tabel orders untuk menyimpan data transaksi pemesanan
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('invoice_number')->unique();     // Contoh: INV-20261025-ABCDE
            $table->integer('total_price');                 // Total biaya yang harus dibayar
            $table->string('recipient_name');               // Nama penerima buket
            $table->string('phone');                        // Nomor HP/WhatsApp
            $table->text('address');                        // Alamat lengkap pengiriman
            $table->text('note')->nullable();               // Catatan kurir/pengiriman
            $table->string('payment_method');               // Metode pembayaran (BCA, QRIS, COD)
            $table->string('status')->default('menunggu');  // Status: 'menunggu', 'diproses', 'dikirim', 'selesai'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

