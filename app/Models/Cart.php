<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'carts';

    // Kolom-kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'custom_text',
    ];

    // Relasi ke User (pemilik keranjang)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Product (data buket)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

