<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil semua kategori untuk filter
        $categories = Category::all();

        // Mengambil produk aktif beserta relasi kategorinya
        $products = Product::where('is_active', true)
            ->with('category')
            ->latest()
            ->get();

        // Mengirim data ke tampilan Blade
        return view('home', compact('categories', 'products'));
    }
}
