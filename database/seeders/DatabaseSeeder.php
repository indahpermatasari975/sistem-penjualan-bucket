<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Indah',
            'email' => 'indah@gmail.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);


        $categories = ['Bucket Bunga', 'Bucket Uang', 'Bucket Snack', 'Buket Boneka & Merchandise', 'Buket Fashion & Skincare'];
        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }
    }
}
