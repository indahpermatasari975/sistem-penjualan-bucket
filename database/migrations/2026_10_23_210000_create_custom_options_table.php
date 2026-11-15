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
        Schema::create('custom_options', function (Blueprint $table) {
            $table->id();

            // Kategori: misal 'Bunga', 'Boneka', 'Kertas', 'Pita', 'Uang'
            $table->string('category');

            // Nama pilihan: misal 'Mawar Merah', 'Teddy Bear'
            $table->string('name');

            // Harga tambahan (0 jika gratis)
            $table->integer('price')->default(0);

            // Kolom BARU: Untuk menyimpan link/path gambar pratinjau
            $table->string('image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_options');
    }
};
