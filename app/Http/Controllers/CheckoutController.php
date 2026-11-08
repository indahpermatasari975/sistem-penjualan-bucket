<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    // Tampil halaman checkout
    public function index(): View|RedirectResponse
    {
        $checkoutItem = session('checkout_item');
        // Ambil data keranjang dari database milik user yang login
        $cart = Cart::where('user_id', Auth::id())->with('product')->get();

        // Kalau kosong dua-duanya, balikin ke home
        if (!$checkoutItem && count($cart) == 0) {
            return redirect()->route('home')->with('error', 'Keranjang kamu masih kosong nih.');
        }

        return view('checkout.index', compact('checkoutItem', 'cart'));
    }

    // Kalau dari halaman detail produk klik "Pesan Sekarang"
    public function directCheckout(Request $request): RedirectResponse
    {
        $product = Product::findOrFail($request->product_id);

        $item = [
            'type'        => 'ready',
            'product_id'  => $product->id,
            'name'        => $product->name,
            'price'       => $product->price,
            'image'       => $product->image,
            'custom_text' => 'Produk Ready Stock',
            'quantity'    => 1,
        ];

        session(['checkout_item' => $item]);

        return redirect()->route('checkout.index');
    }

    // Proses simpan pesanan ke database (Mendukung AJAX untuk pop-up Snap JS maupun Redirect biasa)
    public function processPayment(Request $request): JsonResponse|RedirectResponse
    {
        // Validasi form (menerima Non Tunai dan COD)
        $request->validate([
            'nama_penerima'     => 'required|string',
            'telepon'           => ['required', 'regex:/^[0-9]{10,15}$/'],
            'alamat'            => 'required|string|min:20',
            'metode_pembayaran' => 'required|in:Non Tunai,COD,MIDTRANS,QRIS',
        ], [
            'telepon.regex'               => 'Nomor telepon harus terdiri dari 10 sampai 15 angka.',
            'metode_pembayaran.required'  => 'Silakan pilih metode pembayaran terlebih dahulu.',
        ]);

        // Ambil data langsung dari session (Pesan Sekarang) atau dari Database Model Cart
        $checkoutItem = session('checkout_item');
        $cart = Cart::where('user_id', Auth::id())->with('product')->get();

        // Tentukan mau proses dari mana
        if ($checkoutItem) {
            $dataPesanan = [$checkoutItem];
        } elseif (count($cart) > 0) {
            $dataPesanan = $cart;
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada barang di keranjang yang diproses.'], 400);
            }
            return redirect()->back()->with('error', 'Tidak ada barang di keranjang yang diproses.');
        }

        // Hitung subtotal produk
        $subtotalProduk = 0;
        foreach ($dataPesanan as $item) {
            $isModel = is_object($item);
            $qty = $isModel ? ($item->quantity ?? 1) : (isset($item['quantity']) ? $item['quantity'] : ($item['qty'] ?? 1));
            $price = $isModel ? ($item->product->price ?? 0) : ($item['price'] ?? 0);
            $subtotalProduk += ($price * $qty);
        }

        // Ongkir gratis untuk area tertentu, selain itu Rp 15.000
        $ongkir = 15000;
        $wilayahGratis = ['Bandung', 'Cimahi', 'Antapani'];
        foreach ($wilayahGratis as $wilayah) {
            if (stripos($request->alamat, $wilayah) !== false) {
                $ongkir = 0;
                break;
            }
        }
        $totalHarga = $subtotalProduk + $ongkir;

        // Bikin nomor invoice random
        $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // Simpan ke tabel orders dengan status awal "Belum Dibayar"
        $order = Order::create([
            'user_id'        => Auth::id(),
            'invoice_number' => $invoice,
            'recipient_name' => $request->nama_penerima,
            'phone'          => $request->telepon,
            'address'        => $request->alamat,
            'note'           => $request->catatan_kurir,
            'total_price'    => $totalHarga,
            'payment_method' => $request->metode_pembayaran,
            'status'         => 'Belum Dibayar', // Status awal sesuai alur pemesanan
        ]);

        // Simpan ke tabel order_items
        foreach ($dataPesanan as $item) {
            $isModel = is_object($item);
            $qty = $isModel ? ($item->quantity ?? 1) : (isset($item['quantity']) ? $item['quantity'] : ($item['qty'] ?? 1));
            $price = $isModel ? ($item->product->price ?? 0) : ($item['price'] ?? 0);
            $name = $isModel ? ($item->product->name ?? 'Buket Bunga') : ($item['name'] ?? 'Buket Bunga');
            $customText = $isModel ? ($item->custom_text ?? '-') : ($item['custom_text'] ?? '-');
            $productId = $isModel ? $item->product_id : (isset($item['product_id']) && $item['product_id'] != 'custom' ? $item['product_id'] : null);

            OrderItem::create([
                'order_id'       => $order->id,
                'product_id'     => $productId,
                'product_name'   => $name,
                'price'          => $price,
                'quantity'       => $qty,
                'subtotal'       => $price * $qty,
                'custom_details' => $customText,
            ]);
        }

        // Minta Snap Token dari Midtrans jika metode pembayaran digital / Non Tunai (bukan COD)
        $snapToken = null;
        if ($request->metode_pembayaran != 'COD') {
            // 1. Konfigurasi kredensial Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
            // Penanganan opsi cURL untuk lingkungan Windows/Laragon (menghindari error SSL certificate)
            \Midtrans\Config::$curlOptions = [
                CURLOPT_HTTPHEADER => [],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ];

            // 2. Siapkan parameter transaksi
            $midtransParams = [
                'transaction_details' => [
                    'order_id'     => $order->invoice_number,
                    'gross_amount' => (int) $order->total_price,
                ],
                'customer_details' => [
                    'first_name' => $order->recipient_name,
                    'phone'      => $order->phone,
                    'address'    => $order->address,
                ],
            ];

            // 3. Minta token ke Midtrans dan simpan token-nya ke database
            try {
                $snapToken = \Midtrans\Snap::getSnapToken($midtransParams);
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                Log::error('Midtrans Snap Error: ' . $e->getMessage());

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Gagal menghubungi server Midtrans: ' . $e->getMessage(),
                    ], 500);
                }
            }
        }

        // 1. Ambil daftar product_id dari pesanan yang baru saja dibuat
        $daftar_id_produk_yang_dibeli = [];
        foreach ($dataPesanan as $item) {
            if (is_object($item) && !empty($item->product_id)) {
                $daftar_id_produk_yang_dibeli[] = $item->product_id;
            } elseif (is_array($item) && !empty($item['product_id']) && $item['product_id'] != 'custom') {
                $daftar_id_produk_yang_dibeli[] = $item['product_id'];
            }
        }

        // 2. Hanya hapus produk dari tabel carts jika produk tersebut memang ada di dalam pesanan yang baru dibuat
        if (!empty($daftar_id_produk_yang_dibeli)) {
            Cart::where('user_id', Auth::id())
                ->whereIn('product_id', $daftar_id_produk_yang_dibeli)
                ->delete();
        }

        // 3. Bersihkan session checkout langsung dan session cart
        session()->forget(['checkout_item', 'cart']);

        // Jika request datang dari AJAX (JavaScript checkout untuk memunculkan modal snap.pay)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'     => 'success',
                'is_cod'     => ($request->metode_pembayaran == 'COD'),
                'snap_token' => $order->snap_token,
                'order_id'   => $order->id,
                'redirect'   => route('checkout.sukses', $order->id),
            ]);
        }

        // Fallback jika dikirimkan form submit konvensional
        if ($request->metode_pembayaran != 'COD' && $order->snap_token) {
            return redirect()->route('pembayaran.show', $order->id);
        }

        return redirect()->route('checkout.sukses', $order->id);
    }

    // Menampilkan halaman pembayaran Midtrans Snap
    public function pembayaran(int $id): View
    {
        $order = Order::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pembayaran', compact('order'));
    }

    // Menampilkan halaman sukses milik pembeli yang sedang login
    public function sukses(int $id): View
    {
        $order = Order::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Jika metode pembayaran online dan user masuk ke halaman sukses (setelah callback onSuccess Snap),
        // tandai status pesanan menjadi Lunas (sangat membantu demo lokal di localhost)
        if ($order->payment_method != 'COD' && $order->status == 'Belum Dibayar') {
            $order->update(['status' => 'Lunas']);
        }

        // Ambil daftar product_id dari pesanan ini dan pastikan hanya produk yang dibeli yang dihapus dari keranjang
        $daftar_id_produk_yang_dibeli = $order->items->pluck('product_id')->filter()->toArray();
        if (!empty($daftar_id_produk_yang_dibeli)) {
            Cart::where('user_id', Auth::id())
                ->whereIn('product_id', $daftar_id_produk_yang_dibeli)
                ->delete();
        }

        return view('checkout.sukses', compact('order'));
    }
}
