<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// Halaman Etalase Depan & Kasir
Route::get('/', function () {
  $products = Product::where('is_active', true)->with('category')->get();
  $categories = Category::all();
  return view('home', compact('products', 'categories'));
})->name('home');

// Checkout Transaksi
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

// Webhook Otomatis Midtrans
Route::post('/midtrans/notification', [PaymentController::class, 'notificationHandler'])->name('midtrans.notification');

// Autentikasi Pengguna
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Area Dashboard (Harus Login: Admin & Karyawan)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

  // Modul Pesanan (Akses: Admin & Karyawan)
  Route::get('/orders', [OrderController::class, 'index'])->name('orders');
  Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
  Route::get('/orders/{id}/print', [OrderController::class, 'printReceipt'])->name('orders.print');

  // Modul Khusus Pemilik Toko (Role: Admin Saja)
  Route::middleware(['admin'])->group(function () {

    // Hapus Pesanan
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Katalog Produk & Kategori
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::post('/categories', [ProductController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{id}', [ProductController::class, 'destroyCategory'])->name('categories.destroy');

    // Modul Keuangan & Laba Rugi (Sesuai pemanggilan view Blade)
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance');
    Route::post('/finance/capital', [FinanceController::class, 'storeCapital'])->name('finance.capital.store');
    Route::post('/finance/expense', [FinanceController::class, 'storeExpense'])->name('finance.expense.store');

    // Cadangan alias jika Blade memanggil tanpa suffix .store
    Route::post('/finance/capital-alt', [FinanceController::class, 'storeCapital'])->name('finance.capital');
    Route::post('/finance/expense-alt', [FinanceController::class, 'storeExpense'])->name('finance.expense');

    // Pengaturan Toko & QRIS
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
  });
});
