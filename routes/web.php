<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomOptionController;
use App\Http\Controllers\CustomBuketController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Admin\OrderController; // <-- Sudah pas dengan struktur folder
use App\Models\Product;
use App\Models\Category;

// ==========================================
// 1. AREA PUBLIC
// ==========================================
Route::get('/', function (Request $request) {
    $query = Product::with('category')->latest();
    if ($request->has('category')) {
        $query->where('category_id', $request->category);
    }
    $products = $query->paginate(12);
    $categories = Category::all();
    return view('home', compact('products', 'categories'));
})->name('home');

// Webhook Midtrans (Publik - dipanggil langsung oleh server Midtrans)
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');
Route::post('/webhook_midtrans.php', [MidtransWebhookController::class, 'handle']);

// ==========================================
// 2. LOGIN & REGISTER
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// 3. AREA USER & ADMIN
// ==========================================
Route::middleware(['auth.manual'])->group(function () {

    // --- USER ---
    Route::get('/produk/{id}', function ($id) {
        $product = Product::with('category')->find($id);
        if (!$product) return redirect('/');
        return view('products.detail', compact('product'));
    })->name('products.detail');

    Route::get('/rakit-buket/{product?}', [CustomBuketController::class, 'index'])->whereNumber('product')->name('custom.index');
    Route::post('/rakit-buket/proses', [CustomBuketController::class, 'process'])->name('custom.process');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/direct', [CheckoutController::class, 'directCheckout'])->name('checkout.direct');
    Route::post('/checkout/proses', [CheckoutController::class, 'processPayment'])->name('checkout.process');
    Route::get('/checkout/sukses/{id}', [CheckoutController::class, 'sukses'])->name('checkout.sukses');
    Route::get('/pembayaran/{id}', [CheckoutController::class, 'pembayaran'])->name('pembayaran.show');

    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/pesanan-saya', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan-saya/{id}', [PesananController::class, 'show'])->name('pesanan.show');

    Route::get('/akun', function () {
        return view('profile.index');
    })->name('profile.index');


    // --- ADMIN ---
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::resource('categories', CategoryController::class)->except(['index']);
        Route::resource('custom-options', CustomOptionController::class);

        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // RUTE PESANAN (Memanggil yang ada di folder Admin)
        Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/admin/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::put('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    });

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->whereNumber('product');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
});
