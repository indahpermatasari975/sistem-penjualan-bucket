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
        // Tabel order_items untuk menyimpan rincian barang per transaksi
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name');                 // Nama produk / buket
            $table->integer('price');                       // Harga per satuan
            $table->integer('quantity')->default(1);        // Jumlah item
            $table->integer('subtotal');                    // Subtotal = harga x jumlah
            $table->text('custom_details')->nullable();     // Rincian kustomisasi jika buket custom
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

