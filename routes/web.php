<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Models\Product;

// Landing Page (Public)
Route::get('/', function () {
    $products = Product::with('category')->latest()->paginate(12);
    return view('home', compact('products'));
})->name('home');

// Auth (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route yang harus login
Route::middleware(['auth.manual'])->group(function () {
    // Bisa diakses semua role
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    // Hanya admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('products', ProductController::class)->except(['index']);
        Route::resource('categories', CategoryController::class)->except(['index']);
    });
});
