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
    // 1. Menampilkan halaman keranjang
    public function index(): View
    {
        $cart = Cart::where('user_id', Auth::id())->with('product')->latest()->get();
        return view('cart.index', compact('cart'));
    }

    // 2. Menambahkan produk ke keranjang
    public function add(Request $request): RedirectResponse
    {
        $product = Product::findOrFail($request->product_id);
        $customText = null;

        // Cek jika ada input custom
        if ($request->filled('custom_uang') || $request->filled('custom_boneka')) {
            $customText = "Uang: " . ($request->custom_uang ?? 'Tanpa Uang') .
                          ", Boneka: " . ($request->custom_boneka ?? 'Tanpa Boneka') .
                          ", Kertas: " . ($request->custom_kertas ?? 'Pink') .
                          ", Pita: " . ($request->custom_pita ?? 'Satin Pink') .
                          ", Pesan: " . ($request->custom_pesan ?: '-') .
                          ", Request: " . ($request->custom_request ?: '-');
        }

        // Cek apakah produk + custom yang sama sudah ada di keranjang
        $itemKeranjang = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('custom_text', $customText)
            ->first();

        if ($itemKeranjang) {
            $itemKeranjang->increment('quantity'); // Jika ada, tambah qty
        } else {
            Cart::create([ // Jika belum ada, buat baru
                'user_id'     => Auth::id(),
                'product_id'  => $product->id,
                'quantity'    => 1,
                'custom_text' => $customText,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Buket berhasil ditambahkan ke keranjang!');
    }

    // 3. Menghapus satu item dari keranjang
    public function remove(int $id): RedirectResponse
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    // 4. Mengosongkan seluruh keranjang
    public function clear(): RedirectResponse
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }

    // 5. Menyimpan produk yang dicentang di halaman keranjang ke session untuk checkout
    public function updateSelection(Request $request)
    {
        $request->session()->put('selected_items', $request->input('selected_items', []));
        return redirect()->back();
    }
}
