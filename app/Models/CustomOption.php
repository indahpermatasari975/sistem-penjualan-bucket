<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOption extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database
    protected $table = 'custom_options';

    // Kolom-kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'category',
        'name',
        'price',
        'image', // <-- TAMBAHKAN INI
    ];
}
