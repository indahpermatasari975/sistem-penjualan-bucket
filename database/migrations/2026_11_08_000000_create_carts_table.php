<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel carts (keranjang belanja di database).
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->text('custom_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi jika dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};

