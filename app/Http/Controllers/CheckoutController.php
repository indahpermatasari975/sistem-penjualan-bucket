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
    // 1. Tampil halaman checkout
    public function index(): View|RedirectResponse
    {
        $checkoutItem = session('checkout_item') ?? session('custom_buket');
        $selectedItems = session('selected_items', []);

        $cart = empty($selectedItems)
            ? Cart::where('user_id', Auth::id())->with('product')->get()
            : Cart::where('user_id', Auth::id())
                ->whereIn('id', $selectedItems)
                ->with('product')
                ->get();

        if (!$checkoutItem && count($cart) == 0) {
            return redirect()->route('home')
                ->with('error', 'Keranjang kamu masih kosong nih.');
        }

        return view('checkout.index', compact('checkoutItem', 'cart'));
    }

    // 2. Checkout Langsung
    public function directCheckout(Request $request): RedirectResponse
    {
        $product = Product::findOrFail($request->product_id);

        session(['checkout_item' => [
            'type'        => 'ready',
            'product_id'  => $product->id,
            'name'        => $product->name,
            'price'       => $product->price,
            'image'       => $product->image,
            'custom_text' => 'Produk Ready Stock',
            'quantity'    => 1,
        ]]);

        session()->forget('selected_items');

        return redirect()->route('checkout.index');
    }

    // 3. Proses Pembayaran
    public function processPayment(Request $request): JsonResponse|RedirectResponse
    {
        // Menentukan batas tanggal pengiriman
        $tanggalMinimal = now('Asia/Jakarta')->addDays(2)->format('Y-m-d');
        $tanggalMaksimal = now('Asia/Jakarta')->addDays(30)->format('Y-m-d');

        // Validasi data checkout
        $request->validate([
            'nama_penerima' => 'required|string',
            'telepon' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'alamat' => 'required|string|min:20',

            'delivery_method' => 'required|in:Diantar,Ambil Sendiri',

            'delivery_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:' . $tanggalMinimal,
                'before_or_equal:' . $tanggalMaksimal,
            ],

            'delivery_time' => [
                'required',
                'in:09:00,10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00',
            ],

            'catatan_kurir' => 'nullable|string|max:1000',

            'metode_pembayaran' => 'required|in:Non Tunai,COD,MIDTRANS,QRIS',

        ], [
            'telepon.regex' => 'Nomor telepon harus terdiri dari 10 sampai 15 angka.',
            'delivery_method.required' => 'Silakan pilih metode pengiriman.',
            'delivery_date.required' => 'Silakan pilih tanggal pengiriman.',
            'delivery_date.after_or_equal' => 'Tanggal pengiriman minimal dua hari setelah pemesanan.',
            'delivery_date.before_or_equal' => 'Tanggal pengiriman maksimal 30 hari ke depan.',
            'delivery_time.required' => 'Silakan pilih jam pengiriman.',
            'delivery_time.in' => 'Jam pengiriman yang dipilih tidak tersedia.',
        ]);

        $checkoutItem = session('checkout_item') ?? session('custom_buket');
        $selectedItems = session('selected_items', []);

        if ($checkoutItem) {

            $dataPesanan = [$checkoutItem];

        } elseif (!empty($selectedItems)) {

            $dataPesanan = Cart::where('user_id', Auth::id())
                ->whereIn('id', $selectedItems)
                ->with('product')
                ->get();

        } else {

            $dataPesanan = Cart::where('user_id', Auth::id())
                ->with('product')
                ->get();

        }

        if (count($dataPesanan) == 0) {
            return $request->ajax()
                ? response()->json([
                    'status' => 'error',
                    'message' => 'Keranjang kosong.'
                ], 400)
                : redirect()->back()->with('error', 'Keranjang kosong.');
        }

        // Menghitung total harga pesanan
        $totalHarga = 0;

        foreach ($dataPesanan as $item) {

            $qty = is_object($item)
                ? ($item->quantity ?? 1)
                : ($item['quantity'] ?? $item['qty'] ?? 1);

            if (is_array($item)) {
                $price = $item['price'] ?? 0;
            } else {
                $price = $item->product->price ?? 0;
            }

            $totalHarga += ($price * $qty);
        }

        // Menyimpan data pesanan
        $order = Order::create([
            'user_id' => Auth::id(),
            'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4)),

            'recipient_name' => $request->nama_penerima,
            'phone' => $request->telepon,
            'address' => $request->alamat,

            // Catatan kurir disimpan ke kolom note
            'note' => $request->catatan_kurir,

            // Jadwal pengiriman
            'delivery_method' => $request->delivery_method,
            'delivery_date' => $request->delivery_date,
            'delivery_time' => $request->delivery_time,

            'total_price' => $totalHarga,
            'payment_method' => $request->metode_pembayaran,
            'status' => 'Belum Dibayar',
        ]);

        // Menyimpan rincian barang pesanan
        $cartIdsToDelete = [];

        foreach ($dataPesanan as $item) {

            $isModel = is_object($item);

            $qty = $isModel
                ? ($item->quantity ?? 1)
                : ($item['quantity'] ?? $item['qty'] ?? 1);

            if (is_array($item)) {
                $price = $item['price'] ?? 0;
            } else {
                $price = $item->product->price ?? 0;
            }

            OrderItem::create([
                'order_id' => $order->id,

                'product_id' => $isModel
                    ? ($item->product_id ?? null)
                    : ($item['product_id'] ?? null),

                'product_name' => $isModel
                    ? ($item->product->name ?? 'Buket')
                    : ($item['name'] ?? 'Buket'),

                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $price * $qty,

                'custom_details' => $isModel
                    ? ($item->custom_text ?? '-')
                    : ($item['custom_text'] ?? '-'),
            ]);

            if ($isModel && isset($item->id)) {
                $cartIdsToDelete[] = $item->id;
            }
        }

        // Integrasi Midtrans
        $snapToken = null;

        if ($request->metode_pembayaran != 'COD') {

            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            \Midtrans\Config::$curlOptions = [
                CURLOPT_HTTPHEADER => [],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ];

            try {

                $snapToken = \Midtrans\Snap::getSnapToken([
                    'transaction_details' => [
                        'order_id' => $order->invoice_number,
                        'gross_amount' => (int) $order->total_price,
                    ],

                    'customer_details' => [
                        'first_name' => $order->recipient_name,
                        'phone' => $order->phone,
                        'address' => $order->address,
                    ],
                ]);

                $order->update([
                    'snap_token' => $snapToken
                ]);

            } catch (\Exception $e) {

                Log::error('Midtrans Snap Error: ' . $e->getMessage());

                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gagal ke Midtrans: ' . $e->getMessage()
                    ], 500);
                }
            }
        }

        // Menghapus keranjang yang sudah dibeli
        if (!empty($cartIdsToDelete)) {

            Cart::where('user_id', Auth::id())
                ->whereIn('id', $cartIdsToDelete)
                ->delete();

        }

        // Menghapus data checkout dari session
        session()->forget([
            'checkout_item',
            'custom_buket',
            'selected_items'
        ]);

        // Mengirim hasil ke halaman checkout
        if ($request->ajax()) {

            return response()->json([
                'status' => 'success',
                'is_cod' => ($request->metode_pembayaran == 'COD'),
                'snap_token' => $order->snap_token,
                'redirect' => route('checkout.sukses', $order->id),
            ]);

        }

        return redirect()->route(
            ($request->metode_pembayaran != 'COD' && $order->snap_token)
                ? 'pembayaran.show'
                : 'checkout.sukses',
            $order->id
        );
    }

    // 4. Halaman Pembayaran
    public function pembayaran(int $id): View
    {
        $order = Order::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pembayaran', compact('order'));
    }

    // 5. Halaman Pesanan Berhasil
    public function sukses(int $id): View
    {
        $order = Order::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->payment_method != 'COD' && $order->status == 'Belum Dibayar') {
            $order->update([
                'status' => 'Lunas'
            ]);
        }

        return view('checkout.sukses', compact('order'));
    }
}
