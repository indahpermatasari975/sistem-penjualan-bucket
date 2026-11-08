<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
    // Menampilkan halaman keranjang belanja mengambil data dari database
    public function index(): View
    {
        // Ambil data keranjang dari database berdasarkan user yang sedang login
        $cart = Cart::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return view('cart.index', compact('cart'));
    }

    // Memproses penambahan produk buket ke tabel carts database
    public function add(Request $request): RedirectResponse
    {
        // 1. Ambil data produk dari database berdasarkan ID yang dikirim
        $product = Product::findOrFail($request->product_id);

        // 2. Ambil nilai custom jika ada (misal dari form detail buket)
        $customText = null;
        if ($request->filled('custom_uang') || $request->filled('custom_boneka')) {
            $uang    = $request->input('custom_uang', 'Tanpa Uang');
            $boneka  = $request->input('custom_boneka', 'Tanpa Boneka');
            $kertas  = $request->input('custom_kertas', 'Pink');
            $pita    = $request->input('custom_pita', 'Satin Pink');
            $pesan   = $request->input('custom_pesan') ?: '-';
            $requestKhusus = $request->input('custom_request') ?: '-';
            $customText = "Uang: {$uang}, Boneka: {$boneka}, Kertas: {$kertas}, Pita: {$pita}, Pesan: {$pesan}, Request: {$requestKhusus}";
        }

        // 3. Cek apakah produk yang sama sudah ada di keranjang database milik user ini
        $itemKeranjang = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('custom_text', $customText)
            ->first();

        if ($itemKeranjang) {
            // Jika sudah ada, cukup tambahkan jumlahnya (quantity + 1)
            $itemKeranjang->increment('quantity');
        } else {
            // Jika belum ada, simpan data baru ke database tabel carts
            Cart::create([
                'user_id'     => Auth::id(),
                'product_id'  => $product->id,
                'quantity'    => 1,
                'custom_text' => $customText,
            ]);
        }

        // 4. Redirect ke halaman keranjang dengan notifikasi sukses
        return redirect()->route('cart.index')->with('success', 'Buket berhasil ditambahkan ke keranjang!');
    }

    // Menghapus satu item dari keranjang berdasarkan ID cart di database
    public function remove(int $id): RedirectResponse
    {
        // Cari item keranjang milik user yang login berdasarkan ID
        $item = Cart::where('user_id', Auth::id())->where('id', $id)->first();

        if ($item) {
            $item->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    // Mengosongkan seluruh item di keranjang milik user di database
    public function clear(): RedirectResponse
    {
        // Hapus semua data keranjang milik user yang sedang login
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
