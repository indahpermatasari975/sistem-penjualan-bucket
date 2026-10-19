<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use App\Models\Category;

// ==========================================
// 1. AREA PUBLIC (Bisa diakses tanpa login)
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
// 3. AREA USER (Wajib Login)
// ==========================================
Route::middleware(['auth.manual'])->group(function () {

    // Halaman Detail Produk (Minggu 3 & 4)
    Route::get('/produk/{id}', function ($id) {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return redirect('/');
        }
        return view('products.detail', compact('product'));
    })->name('products.detail');

    // Halaman Keranjang (Minggu 5)
    Route::get('/keranjang', function () {
        return view('cart.index');
    })->name('cart.index');

    // Halaman Akun (Minggu 5)
    Route::get('/akun', function () {
        return view('profile.index');
    })->name('profile.index');


    // ==========================================
    // 4. AREA ADMIN (Wajib Login & Role Admin)
    // ==========================================
    Route::middleware(['role:admin'])->group(function () {
        // CRUD Produk & Kategori
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::resource('categories', CategoryController::class)->except(['index']);

        // FITUR BARU: DAFTAR USER
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Halaman Tabel Admin (Index Produk & Kategori)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->whereNumber('product');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
});
