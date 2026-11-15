<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Menghitung jumlah pesanan aktif (belum selesai/batal) untuk notifikasi navbar
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // Hitung pesanan yang statusnya BUKAN 'Selesai' dan BUKAN 'Dibatalkan'
                $jumlahPesananAktif = Order::where('user_id', Auth::id())
                    ->whereNotIn('status', ['Selesai', 'Dibatalkan', 'selesai', 'Dibatalkan'])
                    ->count();

                $view->with('jumlahPesananAktif', $jumlahPesananAktif);
            }
        });
    }
}
