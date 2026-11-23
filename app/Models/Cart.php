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
        'price',
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

    // Accessor untuk mendapatkan harga item keranjang
    // Jika item adalah buket custom dengan harga tersimpan atau tag [HARGA:...], ambil harga custom tersebut
    public function getPriceAttribute()
    {
        if (isset($this->attributes['price']) && (int) $this->attributes['price'] > 0) {
            return (int) $this->attributes['price'];
        }

        if (!empty($this->custom_text) && preg_match('/\[HARGA:(\d+)\]/', $this->custom_text, $matches)) {
            return (int) $matches[1];
        }

        return $this->product ? (int) $this->product->price : 0;
    }

    // Accessor untuk menampilkan teks kustomisasi tanpa tag internal [HARGA:...]
    public function getCleanCustomTextAttribute()
    {
        if (empty($this->custom_text)) {
            return null;
        }

        return trim(preg_replace('/\[HARGA:\d+\]\s*/', '', $this->custom_text));
    }
}

