<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori Contoh
        $sembako = Category::create([
            'name' => 'Sembako & Beras',
            'slug' => 'sembako-dan-beras',
            'icon' => 'shopping-basket'
        ]);

        $minuman = Category::create([
            'name' => 'Minuman Segar',
            'slug' => 'minuman-segar',
            'icon' => 'cup-soda'
        ]);

        $snack = Category::create([
            'name' => 'Camilan & Roti',
            'slug' => 'camilan-dan-roti',
            'icon' => 'cookie'
        ]);

        // 2. Buat Produk Contoh
        Product::create([
            'category_id' => $sembako->id,
            'name' => 'Beras Pandan Wangi 5kg',
            'slug' => 'beras-pandan-wangi-5kg',
            'description' => 'Beras pulen wangi kualitas premium langsung dari petani lokal.',
            'cost_price' => 68000,
            'selling_price' => 75000,
            'stock' => 20,
            'unit' => 'sak',
            'image_url' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=500&q=80',
        ]);

        Product::create([
            'category_id' => $sembako->id,
            'name' => 'Minyak Goreng Pouch 2L',
            'slug' => 'minyak-goreng-pouch-2l',
            'description' => 'Minyak kelapa sawit murni bening 2x penyaringan.',
            'cost_price' => 31000,
            'selling_price' => 35000,
            'stock' => 15,
            'unit' => 'pouch',
            'image_url' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500&q=80',
        ]);

        Product::create([
            'category_id' => $minuman->id,
            'name' => 'Kopi Susu Gula Aren 250ml',
            'slug' => 'kopi-susu-gula-aren',
            'description' => 'Kopi robusta fresh brew dipadu susu evaporasi dan aren asli.',
            'cost_price' => 10000,
            'selling_price' => 15000,
            'stock' => 30,
            'unit' => 'botol',
            'image_url' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=500&q=80',
        ]);

        Product::create([
            'category_id' => $snack->id,
            'name' => 'Keripik Singkong Balado 200g',
            'slug' => 'keripik-singkong-balado',
            'description' => 'Camilan singkong renyah dengan taburan bumbu balado pedas manis.',
            'cost_price' => 8000,
            'selling_price' => 12000,
            'stock' => 25,
            'unit' => 'bungkus',
            'image_url' => 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=500&q=80',
        ]);
    }
}
