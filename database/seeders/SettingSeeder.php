<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaultSettings = [
            // Identitas Toko
            'shop_name' => 'Warung Berkah Jaya',
            'shop_tagline' => 'Belanja Cepat, Hemat, & Terpercaya',
            'shop_phone' => '6281234567890',
            'shop_address' => 'Jl. Mawar No. 12, Jakarta Pusat',
            'hero_title' => 'Kebutuhan Harian Warung, Lengkap & Terjangkau.',
            'hero_subtitle' => 'Pesan langsung kebutuhan pokok, camilan, dan minuman segar dengan harga grosir lokal.',

            // Kontrol Fitur & Mode
            'enable_online_order' => '1',       // '1' = Online/Publik Aktif, '0' = Mode Kasir Internal Offline
            'enable_whatsapp_order' => '1',     // '1' = Pesan via WhatsApp
            'enable_home_delivery' => '1',      // '1' = Antar ke Rumah
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
