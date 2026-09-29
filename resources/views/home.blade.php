@extends('layouts.app')

@section('content')
<div class="max-w-[96rem] mx-auto px-4 sm:px-6 lg:px-8 py-5 space-y-6" x-data="{ 
        activeCategory: 'all', 
        searchQuery: '',
        matches(product) {
            const matchesCat = this.activeCategory === 'all' || product.category_id == this.activeCategory;
            const matchesSearch = product.name.toLowerCase().includes(this.searchQuery.toLowerCase());
            return matchesCat && matchesSearch;
        }
     }">

  <!-- Banner Hero Depan -->
  @php
  $heroBgType = setting('hero_bg_type', 'color');
  $colorStart = setting('hero_color_start', '#059669');
  $colorEnd = setting('hero_color_end', '#0f766e');
  $heroBgImage = setting('hero_bg_image');
  $heroTitle = setting('hero_title');
  $heroSubtitle = setting('hero_subtitle');
  $hasText = !empty(trim($heroTitle)) || !empty(trim($heroSubtitle));
  @endphp

  <div class="relative overflow-hidden rounded-3xl text-white shadow-xl shadow-emerald-950/10 min-h-[180px] sm:min-h-[240px] md:min-h-[280px] flex items-center
                {{ $hasText ? 'p-6 sm:p-10' : 'p-0' }}" @if($heroBgType==='image' && $heroBgImage)
    style="background-image: url('{{ $heroBgImage }}'); background-size: cover; background-position: center;" @else
    style="background: linear-gradient(135deg, {{ $colorStart }}, {{ $colorEnd }});" @endif>

    <!-- Overlay Gelap HANYA muncul jika ada teks judul agar tulisan terbaca jelas -->
    @if($heroBgType === 'image' && $heroBgImage && $hasText)
    <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-[1px]"></div>
    @endif

    @if($hasText)
    <div class="max-w-2xl relative z-10 space-y-3">
      @if(setting('shop_tagline'))
      <span
        class="inline-block px-3 py-1 rounded-full bg-white/20 text-white font-extrabold text-[11px] uppercase tracking-wider backdrop-blur-sm">
        {{ setting('shop_tagline') }}
      </span>
      @endif

      @if($heroTitle)
      <h1 class="text-2xl sm:text-4xl md:text-5xl font-black tracking-tight leading-tight drop-shadow-sm">
        {{ $heroTitle }}
      </h1>
      @endif

      @if($heroSubtitle)
      <p class="text-xs sm:text-sm text-white/90 max-w-lg leading-relaxed drop-shadow-sm">
        {{ $heroSubtitle }}
      </p>
      @endif
    </div>
    @endif

    <!-- Ornamen Halus jika menggunakan Mode Warna -->
    @if($heroBgType === 'color' && $hasText)
    <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
    @endif
  </div>

  <!-- Filter Bar: Search Box & Kategori Navigasi -->
  <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">

    <!-- Input Pencarian -->
    <div class="relative flex-1 max-w-md">
      <i data-lucide="search"
        class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
      <input type="text" x-model="searchQuery" placeholder="Cari produk seperti beras, kopi, snack..."
        class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-emerald-500 shadow-sm transition-all">
    </div>

    <!-- Filter Tab Kategori -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 max-w-full no-scrollbar">
      <button type="button" @click="activeCategory = 'all'"
        :class="activeCategory === 'all' 
                        ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20 font-extrabold' 
                        : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800'"
        class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5">
        <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> SEMUA
      </button>

      @foreach($categories as $category)
      <button type="button" @click="activeCategory = '{{ $category->id }}'"
        :class="activeCategory === '{{ $category->id }}' 
                        ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20 font-extrabold' 
                        : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800'"
        class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5">
        <i data-lucide="{{ $category->icon ?? 'tag' }}" class="w-3.5 h-3.5"></i> {{ strtoupper($category->name) }}
      </button>
      @endforeach
    </div>
  </div>

  <!-- Grid Katalog Produk -->
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    @forelse($products as $product)
    <div x-show="matches({{ json_encode($product) }})" x-cloak
      class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-emerald-500/40 transition-all flex flex-col overflow-hidden">

      <div class="relative aspect-square w-full overflow-hidden bg-slate-100 dark:bg-slate-800">
        <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&q=80' }}"
          alt="{{ $product->name }}"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

        <span
          class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md bg-white/90 dark:bg-slate-950/80 backdrop-blur-md text-[10px] font-extrabold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-100/50 dark:border-slate-800">
          {{ $product->category->name ?? 'Produk' }}
        </span>

        @if($product->stock <= 0) <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center">
          <span
            class="px-2.5 py-1 rounded-full bg-rose-600 text-white text-[10px] font-extrabold uppercase tracking-wide">
            Stok Habis
          </span>
      </div>
      @endif
    </div>

    <div class="p-3.5 flex-1 flex flex-col justify-between space-y-2.5">
      <div>
        <h3
          class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white line-clamp-2 leading-snug group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
          {{ $product->name }}
        </h3>
        <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-0.5">
          Tersedia: <span class="font-bold text-slate-600 dark:text-slate-300">{{ $product->stock }} {{ $product->unit
            }}</span>
        </span>
      </div>

      <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
        <div>
          <span class="text-[10px] text-slate-400 dark:text-slate-500 block uppercase font-bold">Harga</span>
          <span class="text-xs sm:text-sm font-black text-emerald-600 dark:text-emerald-400">
            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
          </span>
        </div>

        @if($product->stock > 0)
        <button type="button" @click="addToCart({{ json_encode($product) }})"
          class="p-2 sm:px-3 sm:py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white dark:bg-emerald-950/60 dark:text-emerald-300 dark:hover:bg-emerald-600 dark:hover:text-white text-xs font-bold transition-all flex items-center gap-1">
          <i data-lucide="plus" class="w-3.5 h-3.5"></i>
          <span class="hidden sm:inline">Tambah</span>
        </button>
        @else
        <button disabled
          class="p-2 sm:px-3 sm:py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 text-xs font-bold cursor-not-allowed">
          Habis
        </button>
        @endif
      </div>
    </div>
  </div>
  @empty
  <div class="col-span-full py-16 text-center text-slate-400 dark:text-slate-500">
    <i data-lucide="package-open" class="w-12 h-12 mx-auto mb-2 text-slate-300 dark:text-slate-700"></i>
    <p class="text-sm font-semibold">Belum ada barang di etalase toko.</p>
  </div>
  @endforelse
</div>
</div>
@endsection