<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'subtotal',
        'custom_details',
    ];

    // Relasi kembali ke Order utama
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke Produk (opsional jika produk ready)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

