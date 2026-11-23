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
        'delivery_method',
        'delivery_date',
        'delivery_time',
        'payment_method',

        // Status pembayaran
        'status',

        // Status pengerjaan pesanan
        'order_status',

        'snap_token',
    ];

    // Relasi ke user yang melakukan pemesanan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke daftar produk yang dipesan
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
