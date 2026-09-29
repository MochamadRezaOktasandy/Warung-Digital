<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'shop_name'              => 'required|string|max:100',
            'shop_tagline'           => 'nullable|string|max:200',
            'shop_phone'             => 'required|string|max:25',
            'shop_address'           => 'nullable|string',
            'shop_logo_type'         => 'required|in:preset,upload',
            'shop_logo_icon'         => 'nullable|string|max:50',
            'shop_logo_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'hero_title'             => 'nullable|string|max:150',
            'hero_subtitle'          => 'nullable|string|max:255',
            'hero_bg_type'           => 'required|in:color,image',
            'hero_color_start'       => 'nullable|string|max:20',
            'hero_color_end'         => 'nullable|string|max:20',
            'hero_bg_image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'enable_online_order'    => 'nullable|boolean',
            'enable_whatsapp_order'  => 'nullable|boolean',
            'enable_home_delivery'   => 'nullable|boolean',
            'qris_mode'              => 'required|in:manual,midtrans',
            'shop_qris_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'midtrans_server_key'    => 'nullable|string|max:255',
            'midtrans_client_key'    => 'nullable|string|max:255',
            'midtrans_is_production' => 'nullable|boolean',
        ]);

        $settingsData = [
            'shop_name'              => $validated['shop_name'],
            'shop_tagline'           => $validated['shop_tagline'] ?? '',
            'shop_phone'             => $validated['shop_phone'],
            'shop_address'           => $validated['shop_address'] ?? '',
            'shop_logo_type'         => $validated['shop_logo_type'],
            'shop_logo_icon'         => $validated['shop_logo_icon'] ?? 'store',
            'hero_title'             => $validated['hero_title'] ?? '',
            'hero_subtitle'          => $validated['hero_subtitle'] ?? '',
            'hero_bg_type'           => $validated['hero_bg_type'],
            'hero_color_start'       => $validated['hero_color_start'] ?? '#059669',
            'hero_color_end'         => $validated['hero_color_end'] ?? '#0f766e',
            'enable_online_order'    => $request->has('enable_online_order') ? '1' : '0',
            'enable_whatsapp_order'  => $request->has('enable_whatsapp_order') ? '1' : '0',
            'enable_home_delivery'   => $request->has('enable_home_delivery') ? '1' : '0',
            'qris_mode'              => $validated['qris_mode'],
            'midtrans_server_key'    => $validated['midtrans_server_key'] ?? '',
            'midtrans_client_key'    => $validated['midtrans_client_key'] ?? '',
            'midtrans_is_production' => $request->has('midtrans_is_production') ? '1' : '0',
        ];

        // 1. Upload Logo Toko Kustom
        if ($request->hasFile('shop_logo_image')) {
            $oldLogo = setting('shop_logo_image');
            if ($oldLogo && str_starts_with($oldLogo, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $oldLogo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $logoPath = $request->file('shop_logo_image')->store('branding', 'public');
            $settingsData['shop_logo_image'] = '/storage/' . $logoPath;
        }

        // 2. Upload Gambar Latar Belakang Hero
        if ($request->hasFile('hero_bg_image')) {
            $oldHero = setting('hero_bg_image');
            if ($oldHero && str_starts_with($oldHero, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $oldHero);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $heroPath = $request->file('hero_bg_image')->store('branding', 'public');
            $settingsData['hero_bg_image'] = '/storage/' . $heroPath;
        }

        // 3. Upload Stiker QRIS
        if ($request->hasFile('shop_qris_image')) {
            $oldQris = setting('shop_qris_image');
            if ($oldQris && str_starts_with($oldQris, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $oldQris);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $qrisPath = $request->file('shop_qris_image')->store('settings', 'public');
            $settingsData['shop_qris_image'] = '/storage/' . $qrisPath;
        }

        foreach ($settingsData as $key => $val) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $val]
            );
        }

        return back()->with('success', 'Pengaturan toko, logo, dan tampilan hero berhasil disimpan!');
    }
}
