
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
use App\Http\Controllers\Admin\OrderController;

use App\Models\Product;
use App\Models\Category;


// ==========================================
// 1. AREA PUBLIC / BERANDA
// ==========================================

Route::get('/', function (Request $request) {

    // Mengambil semua kategori
    $allCategories = Category::orderBy('id')->get();

    // Empat kategori utama Buket House
    $categorySettings = [
        [
            'key' => 'bunga',
            'label' => 'Buket Bunga',
            'icon' => 'bi-flower1',
            'keyword' => 'bunga',
        ],
        [
            'key' => 'uang',
            'label' => 'Buket Uang',
            'icon' => 'bi-cash-stack',
            'keyword' => 'uang',
        ],
        [
            'key' => 'snack',
            'label' => 'Buket Snack',
            'icon' => 'bi-gift',
            'keyword' => 'snack',
        ],
        [
            'key' => 'boneka',
            'label' => 'Buket Boneka',
            'icon' => 'bi-emoji-smile',
            'keyword' => 'boneka',
        ],
    ];

    // Menyiapkan empat kategori utama
    $featuredCategories = collect();

    foreach ($categorySettings as $setting) {

        $category = $allCategories->first(function ($item) use ($setting) {

            return Str::contains(
                Str::lower($item->name),
                $setting['keyword']
            );

        });

        if ($category) {

            $category->display_name = $setting['label'];
            $category->icon = $setting['icon'];
            $category->category_key = $setting['key'];

            // Mengambil foto produk dari kategori tersebut
            $category->featured_image = Product::where(
                'category_id',
                $category->id
            )
                ->whereNotNull('image')
                ->latest()
                ->value('image');

            $featuredCategories->push($category);
        }
    }

    // ID kategori yang boleh ditampilkan di beranda
    $categoryIds = $featuredCategories->pluck('id');

    // Mengambil produk dari empat kategori utama
    $query = Product::with('category')
        ->whereIn('category_id', $categoryIds)
        ->latest();

    // Fitur pencarian
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('name', 'like', '%' . $search . '%')

                ->orWhere('description', 'like', '%' . $search . '%')

                ->orWhereHas('category', function ($categoryQuery) use ($search) {

                    $categoryQuery->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );

                });

        });
    }

    // Filter kategori
    if ($request->filled('category')) {

        $query->where('category_id', $request->category);

    }

    // Pagination
    $products = $query->paginate(12)->withQueryString();

    // Data untuk halaman home
    $categories = $featuredCategories;

    return view('home', compact(
        'products',
        'categories',
        'featuredCategories'
    ));

})->name('home');


// ==========================================
// 2. WEBHOOK MIDTRANS
// ==========================================

Route::post(
    '/midtrans/webhook',
    [MidtransWebhookController::class, 'handle']
)->name('midtrans.webhook');

Route::post(
    '/webhook_midtrans.php',
    [MidtransWebhookController::class, 'handle']
);


// ==========================================
// 3. LOGIN & REGISTER
// ==========================================

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [AuthController::class, 'showLoginForm']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class, 'login']
    );

    Route::get(
        '/register',
        [AuthController::class, 'showRegisterForm']
    )->name('register');

    Route::post(
        '/register',
        [AuthController::class, 'register']
    );

});

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout')->middleware('auth');


// ==========================================
// 4. AREA USER & ADMIN
// ==========================================

Route::middleware(['auth.manual'])->group(function () {

    // ======================================
    // DETAIL PRODUK
    // ======================================

    Route::get('/produk/{id}', function ($id) {

        $product = Product::with('category')->find($id);

        if (!$product) {
            return redirect('/');
        }

        return view('products.detail', compact('product'));

    })->name('products.detail');


    // ======================================
    // RAKIT BUKET
    // ======================================

    Route::get(
        '/rakit-buket/{product?}',
        [CustomBuketController::class, 'index']
    )
        ->whereNumber('product')
        ->name('custom.index');

    Route::post(
        '/rakit-buket/proses',
        [CustomBuketController::class, 'process']
    )->name('custom.process');

    Route::post(
        '/checkout/custom',
        [CustomBuketController::class, 'process']
    )->name('checkout.custom');


    // ======================================
    // CHECKOUT
    // ======================================

    Route::get(
        '/checkout',
        [CheckoutController::class, 'index']
    )->name('checkout.index');

    Route::post(
        '/checkout/direct',
        [CheckoutController::class, 'directCheckout']
    )->name('checkout.direct');

    Route::post(
        '/checkout/proses',
        [CheckoutController::class, 'processPayment']
    )->name('checkout.process');

    Route::get(
        '/checkout/sukses/{id}',
        [CheckoutController::class, 'sukses']
    )->name('checkout.sukses');

    Route::get(
        '/pembayaran/{id}',
        [CheckoutController::class, 'pembayaran']
    )->name('pembayaran.show');


    // ======================================
    // KERANJANG
    // ======================================

    Route::get(
        '/keranjang',
        [CartController::class, 'index']
    )->name('cart.index');

    Route::post(
        '/keranjang/tambah',
        [CartController::class, 'add']
    )->name('cart.add');

    Route::delete(
        '/keranjang/hapus/{id}',
        [CartController::class, 'remove']
    )->name('cart.remove');

    Route::delete(
        '/keranjang/kosongkan',
        [CartController::class, 'clear']
    )->name('cart.clear');

    Route::post(
        '/keranjang/pilih-produk',
        [CartController::class, 'updateSelection']
    )->name('cart.update_selection');


    // ======================================
    // PESANAN
    // ======================================

    Route::get(
        '/pesanan-saya',
        [PesananController::class, 'index']
    )->name('pesanan.index');

    Route::get(
        '/pesanan-saya/{id}',
        [PesananController::class, 'show']
    )->name('pesanan.show');


    // ======================================
    // PROFILE
    // ======================================

    Route::get('/akun', function () {

        return view('profile.index');

    })->name('profile.index');


    // ======================================
    // ADMIN
    // ======================================

    Route::middleware(['role:admin'])->group(function () {

        Route::resource('products', ProductController::class)
            ->except(['index', 'show']);

        Route::resource('categories', CategoryController::class)
            ->except(['index']);

        Route::resource(
            'custom-options',
            CustomOptionController::class
        );

        Route::get(
            '/admin/users',
            [UserController::class, 'index']
        )->name('admin.users.index');

        Route::delete(
            '/admin/users/{id}',
            [UserController::class, 'destroy']
        )->name('admin.users.destroy');


        // PESANAN ADMIN

        Route::get(
            '/admin/orders',
            [OrderController::class, 'index']
        )->name('admin.orders.index');

        Route::get(
            '/admin/orders/{id}',
            [OrderController::class, 'show']
        )->name('admin.orders.show');

        Route::get(
            '/admin/orders/{id}/invoice',
            [OrderController::class, 'invoice']
        )->name('admin.orders.invoice');

        Route::put(
            '/admin/orders/{id}/status',
            [OrderController::class, 'updateStatus']
        )->name('admin.orders.updateStatus');

    });


    // ======================================
    // KATALOG PRODUK
    // ======================================

    Route::get(
        '/products',
        [ProductController::class, 'index']
    )->name('products.index');

    Route::get(
        '/products/{product}',
        [ProductController::class, 'show']
    )
        ->name('products.show')
        ->whereNumber('product');

    Route::get(
        '/categories',
        [CategoryController::class, 'index']
    )->name('categories.index');

});
