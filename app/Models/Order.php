<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_price',
        'recipient_name',
        'phone',
        'address',
        'note',
        'payment_method',
        'status',
        'snap_token',
    ];

    // Relasi ke User yang memesan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Rincian Item Pesanan
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

