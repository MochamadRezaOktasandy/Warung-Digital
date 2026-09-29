@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full" x-data="{ 
         onlineOrderEnabled: {{ setting('enable_online_order', '1') == '1' ? 'true' : 'false' }},
         qrisMode: '{{ setting('qris_mode', 'manual') }}',
         logoType: '{{ setting('shop_logo_type', 'preset') }}',
         selectedLogoIcon: '{{ setting('shop_logo_icon', 'store') }}',
         heroBgType: '{{ setting('hero_bg_type', 'color') }}',
         colorStart: '{{ setting('hero_color_start', '#059669') }}',
         colorEnd: '{{ setting('hero_color_end', '#0f766e') }}'
     }">

  <!-- Header Halaman -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
      <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Pengaturan Toko &
        Tampilan Branding</h1>
      <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Kelola identitas visual, logo warung, banner
        hero, operasional, dan QRIS.</p>
    </div>
    <a href="{{ route('admin.orders') }}"
      class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 shadow-sm w-fit transition-colors">
      <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke Pesanan
    </a>
  </div>

  @if(session('success'))
  <div
    class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2">
    <i data-lucide="check" class="w-4 h-4"></i> {{ session('success') }}
  </div>
  @endif

  @if($errors->any())
  <div
    class="mb-4 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-semibold flex items-center gap-2">
    <i data-lucide="alert-circle" class="w-4 h-4"></i> {{ $errors->first() }}
  </div>
  @endif

  <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- 1. Custom Logo Toko (Header Kiri Atas) -->
    <div
      class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm space-y-4">
      <div>
        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="image" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i> Logo Warung (Header Kiri
          Atas)
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pilih apakah ingin menggunakan simbol ikon siap
          pakai atau mengunggah logo bisnis Anda sendiri.</p>
      </div>

      <!-- Switcher Tipe Logo -->
      <div class="grid grid-cols-2 gap-3 pt-1">
        <label class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all"
          :class="logoType === 'preset' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300'">
          <input type="radio" name="shop_logo_type" value="preset" x-model="logoType"
            class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
          <div>
            <span class="block text-xs sm:text-sm">Pilihan Simbol Logo</span>
            <span class="text-[11px] font-normal text-slate-500 dark:text-slate-400">Gunakan kumpulan ikon toko siap
              pakai.</span>
          </div>
        </label>

        <label class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all"
          :class="logoType === 'upload' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300'">
          <input type="radio" name="shop_logo_type" value="upload" x-model="logoType"
            class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
          <div>
            <span class="block text-xs sm:text-sm">Unggah Logo Sendiri</span>
            <span class="text-[11px] font-normal text-slate-500 dark:text-slate-400">Upload logo warung format PNG
              transparan.</span>
          </div>
        </label>
      </div>

      <!-- Opsi 1: Pilihan Simbol Icon -->
      <div x-show="logoType === 'preset'" class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-2">
        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400">Pilih Simbol yang Paling
          Cocok:</label>
        <input type="hidden" name="shop_logo_icon" :value="selectedLogoIcon">

        <div class="grid grid-cols-4 sm:grid-cols-8 gap-2.5">
          <button type="button" @click="selectedLogoIcon = 'store'"
            :class="selectedLogoIcon === 'store' ? 'border-emerald-500 bg-emerald-600 text-white shadow-md' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
            class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1 transition-all"
            title="Warung/Toko">
            <i data-lucide="store" class="w-5 h-5"></i>
            <span class="text-[9px] font-semibold truncate w-full text-center">Warung</span>
          </button>

          <button type="button" @click="selectedLogoIcon = 'shopping-bag'"
            :class="selectedLogoIcon === 'shopping-bag' ? 'border-emerald-500 bg-emerald-600 text-white shadow-md' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
            class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1 transition-all"
            title="Belanja">
            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            <span class="text-[9px] font-semibold truncate w-full text-center">Belanja</span>
          </button>

          <button type="button" @click="selectedLogoIcon = 'cup-soda'"
            :class="selectedLogoIcon === 'cup-soda' ? 'border-emerald-500 bg-emerald-600 text-white shadow-md' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
            class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1 transition-all"
            title="Minuman">
            <i data-lucide="cup-soda" class="w-5 h-5"></i>
            <span class="text-[9px] font-semibold truncate w-full text-center">Minuman</span>
          </button>

          <button type="button" @click="selectedLogoIcon = 'coffee'"
            :class="selectedLogoIcon === 'coffee' ? 'border-emerald-500 bg-emerald-600 text-white shadow-md' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
            class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1 transition-all"
            title="Kedai Kopi">
            <i data-lucide="coffee" class="w-5 h-5"></i>
            <span class="text-[9px] font-semibold truncate w-full text-center">Kopi</span>
          </button>

          <button type="button" @click="selectedLogoIcon = 'utensils'"
            :class="selectedLogoIcon === 'utensils' ? 'border-emerald-500 bg-emerald-600 text-white shadow-md' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
            class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1 transition-all"
            title="Resto/F&B">
            <i data-lucide="utensils" class="w-5 h-5"></i>
            <span class="text-[9px] font-semibold truncate w-full text-center">Makanan</span>
          </button>

          <button type="button" @click="selectedLogoIcon = 'cookie'"
            :class="selectedLogoIcon === 'cookie' ? 'border-emerald-500 bg-emerald-600 text-white shadow-md' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
            class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1 transition-all"
            title="Kue/Bakery">
            <i data-lucide="cookie" class="w-5 h-5"></i>
            <span class="text-[9px] font-semibold truncate w-full text-center">Snack</span>
          </button>

          <button type="button" @click="selectedLogoIcon = 'wheat'"
            :class="selectedLogoIcon === 'wheat' ? 'border-emerald-500 bg-emerald-600 text-white shadow-md' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
            class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1 transition-all"
            title="Sembako">
            <i data-lucide="wheat" class="w-5 h-5"></i>
            <span class="text-[9px] font-semibold truncate w-full text-center">Sembako</span>
          </button>

          <button type="button" @click="selectedLogoIcon = 'sparkles'"
            :class="selectedLogoIcon === 'sparkles' ? 'border-emerald-500 bg-emerald-600 text-white shadow-md' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
            class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1 transition-all"
            title="Modern">
            <i data-lucide="sparkles" class="w-5 h-5"></i>
            <span class="text-[9px] font-semibold truncate w-full text-center">Modern</span>
          </button>
        </div>
      </div>

      <!-- Opsi 2: Upload File Logo Custom -->
      <div x-show="logoType === 'upload'" class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
          <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Pilih Berkas Logo</label>
            <input type="file" name="shop_logo_image" accept="image/png,image/jpeg,image/webp,image/svg+xml"
              class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 dark:file:bg-emerald-950/70 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 border border-slate-200 dark:border-slate-700 rounded-xl">
            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400 space-y-0.5">
              <p class="font-bold text-slate-700 dark:text-slate-300">💡 Rekomendasi Resolusi:</p>
              <p>• Rasio Kotak <b>1:1</b> (Minimal <b>200 × 200 px</b> s.d. <b>512 × 512 px</b>).</p>
              <p>• Format terbaik: <b>PNG transparan</b> atau <b>WebP</b> (Maks 5MB).</p>
            </div>
          </div>

          @if(setting('shop_logo_image'))
          <div
            class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700">
            <img src="{{ setting('shop_logo_image') }}" alt="Logo Warung Aktif"
              class="w-14 h-14 object-contain rounded-xl border bg-white shadow-sm p-1">
            <div>
              <span class="text-xs text-emerald-700 dark:text-emerald-400 font-bold flex items-center gap-1">
                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Logo Custom Aktif
              </span>
              <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-0.5">Akan tampil di navbar kiri atas
                dan struk belanja.</span>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>

    <!-- 2. Custom Konten & Background Banner Hero (Bisa Dikosongkan Jika Gambar Berisi Teks) -->
    <div
      class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm space-y-4">
      <div>
        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="palette" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i> Konten & Background
          Banner Hero Depan
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kustomisasi teks headline dan warna/gambar latar
          belakang banner besar di etalase depan.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        <div>
          <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">
            Judul Headline (Hero Title) <span class="text-slate-400 font-normal">(Opsional - Kosongkan jika banner sudah
              ada teks)</span>
          </label>
          <input type="text" name="hero_title" value="{{ setting('hero_title') }}"
            placeholder="Contoh: Nikmati Promo Akhir Pekan"
            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">
            Subjudul / Deskripsi Banner <span class="text-slate-400 font-normal">(Opsional)</span>
          </label>
          <textarea name="hero_subtitle" rows="2"
            placeholder="Contoh: Pesan kebutuhan harian Anda dengan mudah dan harga bersahabat."
            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ setting('hero_subtitle') }}</textarea>
        </div>
      </div>

      <!-- Switcher Tipe Background Hero -->
      <div class="pt-2">
        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">Tipe Latar Belakang
          Banner:</label>
        <div class="grid grid-cols-2 gap-3">
          <label class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all"
            :class="heroBgType === 'color' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300'">
            <input type="radio" name="hero_bg_type" value="color" x-model="heroBgType"
              class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
            <div>
              <span class="block text-xs sm:text-sm">Color Picker (Gradien Kustom)</span>
              <span class="text-[11px] font-normal text-slate-500 dark:text-slate-400">Pilih dua warna sesuka hati atau
                gunakan palet rekomendasi.</span>
            </div>
          </label>

          <label class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all"
            :class="heroBgType === 'image' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300'">
            <input type="radio" name="hero_bg_type" value="image" x-model="heroBgType"
              class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
            <div>
              <span class="block text-xs sm:text-sm">Upload Gambar Banner Sendiri</span>
              <span class="text-[11px] font-normal text-slate-500 dark:text-slate-400">Gunakan poster/foto toko sebagai
                latar belakang.</span>
            </div>
          </label>
        </div>
      </div>

      <!-- Fitur Color Picker Interaktif + Live Preview -->
      <div x-show="heroBgType === 'color'" class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-4">

        <div>
          <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">Pratinjau Langsung (Live
            Preview):</label>
          <div
            class="h-20 sm:h-24 rounded-2xl p-4 text-white flex items-center justify-between shadow-md transition-all duration-300"
            :style="`background: linear-gradient(135deg, ${colorStart}, ${colorEnd})`">
            <div>
              <p class="font-extrabold text-sm sm:text-base leading-tight">Ini Tampilan Banner Anda</p>
              <p class="text-xs text-white/80 mt-0.5">Warna akan otomatis menyesuaikan etalase depan.</p>
            </div>
            <span
              class="text-xs font-mono bg-black/20 backdrop-blur-sm px-2.5 py-1 rounded-lg border border-white/20 hidden sm:inline-block"
              x-text="`${colorStart} → ${colorEnd}`"></span>
          </div>
        </div>

        <div
          class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Warna Awal (Sisi
              Kiri)</label>
            <div class="flex items-center gap-2">
              <input type="color" name="hero_color_start" x-model="colorStart"
                class="w-10 h-10 p-0.5 rounded-lg border border-slate-300 dark:border-slate-600 cursor-pointer bg-white dark:bg-slate-800">
              <input type="text" x-model="colorStart"
                class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-mono uppercase focus:outline-emerald-500">
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Warna Akhir (Sisi
              Kanan)</label>
            <div class="flex items-center gap-2">
              <input type="color" name="hero_color_end" x-model="colorEnd"
                class="w-10 h-10 p-0.5 rounded-lg border border-slate-300 dark:border-slate-600 cursor-pointer bg-white dark:bg-slate-800">
              <input type="text" x-model="colorEnd"
                class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-mono uppercase focus:outline-emerald-500">
            </div>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">Atau Pilih Palet Kombinasi
            Cepat:</label>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            <button type="button" @click="colorStart = '#059669'; colorEnd = '#0f766e'"
              class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:scale-[1.02] transition-all flex items-center gap-2 text-left">
              <div class="w-6 h-6 rounded-lg shadow-sm flex-shrink-0"
                style="background: linear-gradient(135deg, #059669, #0f766e)"></div>
              <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">Emerald Green</span>
            </button>

            <button type="button" @click="colorStart = '#451a03'; colorEnd = '#78350f'"
              class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:scale-[1.02] transition-all flex items-center gap-2 text-left">
              <div class="w-6 h-6 rounded-lg shadow-sm flex-shrink-0"
                style="background: linear-gradient(135deg, #451a03, #78350f)"></div>
              <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">Warm Chocolate</span>
            </button>

            <button type="button" @click="colorStart = '#1e3a8a'; colorEnd = '#3b82f6'"
              class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:scale-[1.02] transition-all flex items-center gap-2 text-left">
              <div class="w-6 h-6 rounded-lg shadow-sm flex-shrink-0"
                style="background: linear-gradient(135deg, #1e3a8a, #3b82f6)"></div>
              <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">Royal Navy</span>
            </button>

            <button type="button" @click="colorStart = '#be123c'; colorEnd = '#db2777'"
              class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:scale-[1.02] transition-all flex items-center gap-2 text-left">
              <div class="w-6 h-6 rounded-lg shadow-sm flex-shrink-0"
                style="background: linear-gradient(135deg, #be123c, #db2777)"></div>
              <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">Berry Red</span>
            </button>

            <button type="button" @click="colorStart = '#581c87'; colorEnd = '#4338ca'"
              class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:scale-[1.02] transition-all flex items-center gap-2 text-left">
              <div class="w-6 h-6 rounded-lg shadow-sm flex-shrink-0"
                style="background: linear-gradient(135deg, #581c87, #4338ca)"></div>
              <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">Deep Violet</span>
            </button>

            <button type="button" @click="colorStart = '#0f172a'; colorEnd = '#1e293b'"
              class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:scale-[1.02] transition-all flex items-center gap-2 text-left">
              <div class="w-6 h-6 rounded-lg shadow-sm flex-shrink-0"
                style="background: linear-gradient(135deg, #0f172a, #1e293b)"></div>
              <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">Midnight Dark</span>
            </button>

            <button type="button" @click="colorStart = '#d97706'; colorEnd = '#b45309'"
              class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:scale-[1.02] transition-all flex items-center gap-2 text-left">
              <div class="w-6 h-6 rounded-lg shadow-sm flex-shrink-0"
                style="background: linear-gradient(135deg, #d97706, #b45309)"></div>
              <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">Warm Amber</span>
            </button>

            <button type="button" @click="colorStart = '#0d9488'; colorEnd = '#0891b2'"
              class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:scale-[1.02] transition-all flex items-center gap-2 text-left">
              <div class="w-6 h-6 rounded-lg shadow-sm flex-shrink-0"
                style="background: linear-gradient(135deg, #0d9488, #0891b2)"></div>
              <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">Fresh Ocean</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Upload Gambar Banner Custom -->
      <div x-show="heroBgType === 'image'" class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
          <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Unggah Banner Gambar</label>
            <input type="file" name="hero_bg_image" accept="image/jpeg,image/png,image/jpg,image/webp"
              class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 dark:file:bg-emerald-950/70 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 border border-slate-200 dark:border-slate-700 rounded-xl">
            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400 space-y-0.5">
              <p class="font-bold text-slate-700 dark:text-slate-300">💡 Rekomendasi Resolusi:</p>
              <p>• Rasio Lebar <b>16:5</b> atau <b>16:6</b>.</p>
              <p>• Ukuran Ideal: <b>1600 × 500 px</b> atau <b>1920 × 600 px</b> (Maks 10MB).</p>
              <p>• Jika gambar sudah memiliki tulisan promosi, kosongkan kolom judul di atas.</p>
            </div>
          </div>

          @if(setting('hero_bg_image'))
          <div
            class="space-y-1.5 p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700">
            <span class="text-xs text-emerald-700 dark:text-emerald-400 font-bold flex items-center gap-1">
              <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Banner Gambar Aktif
            </span>
            <img src="{{ setting('hero_bg_image') }}" alt="Banner Hero Aktif"
              class="w-full h-24 object-cover rounded-lg border shadow-sm">
          </div>
          @endif
        </div>
      </div>
    </div>

    <!-- 3. Profil & Kontak Usaha -->
    <div
      class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm space-y-4">
      <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
        <i data-lucide="store" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i> Profil & Kontak Usaha
      </h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        <div>
          <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Nama Toko / Usaha</label>
          <input type="text" name="shop_name" value="{{ setting('shop_name', 'WarungDigital') }}" required
            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Tagline Singkat</label>
          <input type="text" name="shop_tagline" value="{{ setting('shop_tagline', 'POS & Etalase UMKM') }}"
            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Nomor WhatsApp Toko (Awali
            62)</label>
          <input type="text" name="shop_phone" value="{{ setting('shop_phone', '6281234567890') }}" required
            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Alamat Fisik Usaha</label>
          <input type="text" name="shop_address" value="{{ setting('shop_address') }}"
            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
      </div>
    </div>

    <!-- 4. Mode Operasional Toko -->
    <div
      class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm space-y-4">
      <div>
        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="sliders" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i> Mode Operasional & Fitur
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tentukan apakah toko membuka pesanan publik daring
          atau bertindak sebagai kasir toko fisik (POS).</p>
      </div>

      <div class="space-y-2.5 pt-1">
        <label
          class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 cursor-pointer bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-100/60 dark:hover:bg-slate-800 transition-colors">
          <input type="checkbox" name="enable_online_order" value="1" x-model="onlineOrderEnabled" {{
            setting('enable_online_order', '1' )=='1' ? 'checked' : '' }}
            class="mt-1 w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
          <div>
            <span class="block text-xs sm:text-sm font-bold text-slate-900 dark:text-white">Aktifkan Pesanan Online
              (Publik)</span>
            <span class="text-[11px] text-slate-500 dark:text-slate-400">Jika dimatikan, aplikasi beralih menjadi layar
              kasir internal warung (POS Offline murni).</span>
          </div>
        </label>

        <label class="flex items-start gap-3 p-3.5 rounded-xl border transition-all"
          :class="onlineOrderEnabled 
                           ? 'border-slate-200 dark:border-slate-800 cursor-pointer bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60' 
                           : 'border-slate-100 dark:border-slate-800/40 bg-slate-100/50 dark:bg-slate-950/40 opacity-50 cursor-not-allowed'">
          <input type="checkbox" name="enable_whatsapp_order" value="1" :disabled="!onlineOrderEnabled" {{
            setting('enable_whatsapp_order', '1' )=='1' ? 'checked' : '' }}
            class="mt-1 w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 disabled:opacity-50">
          <div>
            <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">Pesan via
              WhatsApp</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">Mengarahkan pembeli online ke WhatsApp toko
              untuk mengirimkan bukti transfer QRIS.</span>
          </div>
        </label>

        <label class="flex items-start gap-3 p-3.5 rounded-xl border transition-all"
          :class="onlineOrderEnabled 
                           ? 'border-slate-200 dark:border-slate-800 cursor-pointer bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60' 
                           : 'border-slate-100 dark:border-slate-800/40 bg-slate-100/50 dark:bg-slate-950/40 opacity-50 cursor-not-allowed'">
          <input type="checkbox" name="enable_home_delivery" value="1" :disabled="!onlineOrderEnabled" {{
            setting('enable_home_delivery', '1' )=='1' ? 'checked' : '' }}
            class="mt-1 w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 disabled:opacity-50">
          <div>
            <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">Layanan Antar ke
              Rumah</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">Menyediakan opsi pengiriman kurir toko serta
              pengisian alamat lengkap pelanggan.</span>
          </div>
        </label>
      </div>
    </div>

    <!-- 5. Integrasi QRIS Usaha -->
    <div
      class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm space-y-4">
      <div>
        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="qr-code" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i> Integrasi Metode QRIS
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pilih tipe QRIS yang digunakan: Manual (Stiker
          Barcode) atau Otomatis (Midtrans API).</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
        <label class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all"
          :class="qrisMode === 'manual' 
                           ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' 
                           : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300'">
          <input type="radio" name="qris_mode" value="manual" x-model="qrisMode"
            class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
          <div>
            <span class="block text-xs sm:text-sm">Mode Manual (Stiker QRIS)</span>
            <span class="text-[11px] font-normal text-slate-500 dark:text-slate-400">Upload stiker barcode rekening toko
              Anda sendiri.</span>
          </div>
        </label>

        <label class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all"
          :class="qrisMode === 'midtrans' 
                           ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' 
                           : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300'">
          <input type="radio" name="qris_mode" value="midtrans" x-model="qrisMode"
            class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
          <div>
            <span class="block text-xs sm:text-sm">Mode Otomatis (Midtrans API)</span>
            <span class="text-[11px] font-normal text-slate-500 dark:text-slate-400">QRIS dinamis instan dengan webhook
              otomatis.</span>
          </div>
        </label>
      </div>

      <!-- Panel Mode Manual -->
      <div x-show="qrisMode === 'manual'" class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-3">
        <p class="text-xs text-slate-500 dark:text-slate-400">Unggah foto stiker barcode QRIS warung (BCA, Mandiri, BRI,
          GoPay, OVO, DANA, BSI, dll):</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 items-center">
          <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Pilih Berkas Gambar (JPG,
              PNG, WebP maks 10MB)</label>
            <input type="file" name="shop_qris_image" accept="image/jpeg,image/png,image/jpg,image/webp"
              class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 dark:file:bg-emerald-950/70 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 border border-slate-200 dark:border-slate-700 rounded-xl">
          </div>

          @if(setting('shop_qris_image'))
          <div
            class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700">
            <img src="{{ setting('shop_qris_image') }}" alt="Stiker QRIS Warung"
              class="w-16 h-16 object-contain rounded-lg border bg-white shadow-sm p-1">
            <div>
              <span class="text-xs text-emerald-700 dark:text-emerald-400 font-bold flex items-center gap-1">
                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> QRIS Aktif Terpasang
              </span>
              <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-0.5">Unggah berkas baru di sebelah
                kiri jika ingin mengganti stiker ini.</span>
            </div>
          </div>
          @else
          <div
            class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs font-medium flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-4 h-4 flex-shrink-0"></i>
            <span>Belum ada foto QRIS. Pelanggan/kasir belum dapat menggunakan QRIS sampai barcode diunggah.</span>
          </div>
          @endif
        </div>
      </div>

      <!-- Panel Mode Midtrans -->
      <div x-show="qrisMode === 'midtrans'" class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-3">
        <p class="text-xs text-slate-500 dark:text-slate-400">Masukkan kunci API Midtrans Anda dari dashboard Merchant:
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Server Key</label>
            <input type="text" name="midtrans_server_key" value="{{ setting('midtrans_server_key') }}"
              placeholder="SB-Mid-server-..."
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Client Key</label>
            <input type="text" name="midtrans_client_key" value="{{ setting('midtrans_client_key') }}"
              placeholder="SB-Mid-client-..."
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>
        </div>
        <label class="flex items-center gap-2 pt-1 cursor-pointer">
          <input type="checkbox" name="midtrans_is_production" value="1" {{ setting('midtrans_is_production', '0' )=='1'
            ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
          <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Mode Production (Biarkan tidak dicentang
            jika masih akun Sandbox/Uji coba)</span>
        </label>
      </div>
    </div>

    <button type="submit"
      class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2 transition-all text-xs sm:text-sm">
      <i data-lucide="save" class="w-4 h-4"></i> Simpan Seluruh Perubahan
    </button>
  </form>
</div>
@endsection