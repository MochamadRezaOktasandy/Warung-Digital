@extends('layouts.app')

@section('content')
<div class="max-w-[96rem] w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 flex-1 flex flex-col justify-start" x-data="{ 
         isModalOpen: false, 
         editMode: false, 
         currentProduct: {},
         selectedCategoryIcon: 'shopping-bag'
     }">

  <!-- Header Halaman -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
    <div>
      <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Manajemen Katalog &
        Produk</h1>
      <p class="text-xs text-slate-500 dark:text-slate-400">Kelola harga modal (HPP), harga jual, stok gudang, dan foto
        barang.</p>
    </div>
    <button @click="isModalOpen = true; editMode = false; currentProduct = {}"
      class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center gap-2 transition-all w-fit">
      <i data-lucide="plus" class="w-4 h-4"></i> Tambah Produk Baru
    </button>
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

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

    <!-- Panel Kiri: Form & Daftar Kategori (4 Kolom) -->
    <div class="lg:col-span-4 space-y-4">
      <div
        class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm space-y-4">
        <h3 class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm flex items-center gap-2">
          <i data-lucide="folder-plus" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i> Buat Kategori Baru
        </h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-3">
          @csrf
          <div>
            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Nama Kategori</label>
            <input type="text" name="name" placeholder="Misal: Minuman Dingin, Bumbu Dapur" required
              class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white focus:outline-emerald-500">
          </div>

          <!-- Pilihan Icon Visual (User Friendly Click & Choose) -->
          <div>
            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1.5">Pilih Simbol
              Kategori</label>
            <input type="hidden" name="icon" :value="selectedCategoryIcon">

            <div class="grid grid-cols-6 gap-2">
              @php
              $presetIcons = [
              ['name' => 'shopping-bag', 'label' => 'Belanja'],
              ['name' => 'utensils', 'label' => 'Makanan'],
              ['name' => 'coffee', 'label' => 'Kopi/Teh'],
              ['name' => 'cup-soda', 'label' => 'Minuman'],
              ['name' => 'cookie', 'label' => 'Snack'],
              ['name' => 'apple', 'label' => 'Buah'],
              ['name' => 'wheat', 'label' => 'Sembako/Beras'],
              ['name' => 'package', 'label' => 'Paket'],
              ['name' => 'sparkles', 'label' => 'Kebersihan'],
              ['name' => 'flame', 'label' => 'Pedas/Hangat'],
              ['name' => 'tag', 'label' => 'Umum'],
              ['name' => 'star', 'label' => 'Favorit'],
              ];
              @endphp

              @foreach($presetIcons as $pIcon)
              <button type="button" @click="selectedCategoryIcon = '{{ $pIcon['name'] }}'"
                :class="selectedCategoryIcon === '{{ $pIcon['name'] }}' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                class="p-2 rounded-xl border flex flex-col items-center justify-center transition-all"
                title="{{ $pIcon['label'] }}">
                <i data-lucide="{{ $pIcon['name'] }}" class="w-4 h-4"></i>
              </button>
              @endforeach
            </div>
          </div>

          <button type="submit"
            class="w-full py-2.5 bg-slate-900 hover:bg-black dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
            + Simpan Kategori
          </button>
        </form>

        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
          <span
            class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-2">Daftar
            Kategori Aktif</span>
          <div class="space-y-1.5 max-h-[320px] overflow-y-auto pr-1">
            @forelse($categories as $cat)
            <div
              class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
              <div class="flex items-center gap-2 min-w-0 pr-1">
                <span
                  class="w-6 h-6 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center flex-shrink-0 text-slate-600 dark:text-slate-300">
                  <i data-lucide="{{ $cat->icon ?? 'tag' }}" class="w-3.5 h-3.5"></i>
                </span>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate">{{ $cat->name }}</span>
                <span
                  class="text-[10px] bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-1.5 py-0.2 rounded-full font-semibold">{{
                  $cat->products_count }}</span>
              </div>
              <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="p-1 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 rounded transition-colors"
                  title="Hapus Kategori">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
              </form>
            </div>
            @empty
            <p class="text-xs text-slate-400 dark:text-slate-500 py-2">Belum ada kategori terdaftar.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <!-- Panel Kanan: Tabel Produk Rapi & Pas Layar (8 Kolom) -->
    <div
      class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
      <div class="w-full overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
          <thead
            class="bg-slate-50/75 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-[10px] uppercase font-extrabold text-slate-400 dark:text-slate-400 tracking-wider">
            <tr>
              <th class="px-4 py-3 w-[36%]">Produk</th>
              <th class="px-4 py-3 w-[20%]">Kategori</th>
              <th class="px-4 py-3 w-[14%]">HPP (Modal)</th>
              <th class="px-4 py-3 w-[14%]">Harga Jual</th>
              <th class="px-4 py-3 w-[8%] text-center">Stok</th>
              <th class="px-4 py-3 w-[8%] text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($products as $product)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
              <!-- Kolom Produk -->
              <td class="px-4 py-3 align-middle">
                <div class="flex items-center gap-3">
                  <img
                    src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=100&q=80' }}"
                    alt="{{ $product->name }}"
                    class="w-10 h-10 rounded-xl object-cover bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex-shrink-0">
                  <div class="min-w-0 pr-2">
                    <span class="font-extrabold text-slate-900 dark:text-white block text-xs truncate leading-snug">{{
                      $product->name }}</span>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Satuan: {{ $product->unit
                      }}</span>
                  </div>
                </div>
              </td>

              <!-- Kolom Kategori -->
              <td class="px-4 py-3 align-middle">
                <span
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[11px]">
                  <i data-lucide="{{ $product->category->icon ?? 'tag' }}" class="w-3 h-3 text-slate-500"></i>
                  {{ $product->category->name ?? '-' }}
                </span>
              </td>

              <!-- Kolom HPP / Modal -->
              <td class="px-4 py-3 align-middle font-medium text-slate-500 dark:text-slate-400">
                Rp {{ number_format($product->cost_price, 0, ',', '.') }}
              </td>

              <!-- Kolom Harga Jual -->
              <td class="px-4 py-3 align-middle font-black text-emerald-600 dark:text-emerald-400">
                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
              </td>

              <!-- Kolom Stok -->
              <td class="px-4 py-3 align-middle text-center">
                <span
                  class="inline-block px-2 py-0.5 rounded-full text-[10px] font-extrabold whitespace-nowrap {{ $product->stock > 5 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/70 dark:text-rose-300' }}">
                  {{ $product->stock }} {{ $product->unit }}
                </span>
              </td>

              <!-- Kolom Aksi -->
              <td class="px-4 py-3 align-middle text-center">
                <div class="inline-flex items-center gap-1.5">
                  <button @click="editMode = true; currentProduct = {{ $product->toJson() }}; isModalOpen = true"
                    class="p-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
                    title="Edit Produk">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                  </button>
                  <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                    onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="p-1.5 text-rose-500 hover:text-rose-700 border border-rose-200 dark:border-rose-900/60 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors"
                      title="Hapus Produk">
                      <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">Belum ada barang di
                katalog toko.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($products->hasPages())
      <div class="p-3 border-t border-slate-100 dark:border-slate-800 mt-auto">
        {{ $products->links() }}
      </div>
      @endif
    </div>
  </div>

  <!-- Modal Form Tambah / Edit Produk (Dark Mode Ready) -->
  <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isModalOpen = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div
        class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-2xl border border-slate-100 dark:border-slate-800 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
          <h3 class="font-extrabold text-base sm:text-lg text-slate-900 dark:text-white"
            x-text="editMode ? 'Edit Informasi Produk' : 'Tambah Produk Baru'"></h3>
          <button @click="isModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>

        <form :action="editMode ? '/admin/products/' + currentProduct.id : '{{ route('admin.products.store') }}'"
          method="POST" enctype="multipart/form-data" class="space-y-3">
          @csrf
          <template x-if="editMode">
            <input type="hidden" name="_method" value="PUT">
          </template>

          <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Nama Produk</label>
            <input type="text" name="name" :value="currentProduct.name || ''" required
              class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white focus:outline-emerald-500">
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Kategori</label>
              <select name="category_id" required
                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white focus:outline-emerald-500">
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" :selected="currentProduct.category_id == {{ $cat->id }}">{{ $cat->name }}
                </option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Satuan (Unit)</label>
              <input type="text" name="unit" :value="currentProduct.unit || 'pcs'" placeholder="pcs, sak, botol"
                required
                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white focus:outline-emerald-500">
            </div>
          </div>

          <div class="grid grid-cols-3 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">HPP (Modal)</label>
              <input type="number" name="cost_price" :value="currentProduct.cost_price || 0" required
                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white focus:outline-emerald-500">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Harga Jual</label>
              <input type="number" name="selling_price" :value="currentProduct.selling_price || 0" required
                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white focus:outline-emerald-500">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Stok Gudang</label>
              <input type="number" name="stock" :value="currentProduct.stock || 0" required
                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white focus:outline-emerald-500">
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Foto Produk
              (Opsional)</label>
            <input type="file" name="image"
              class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 dark:file:bg-emerald-950/70 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 border border-slate-200 dark:border-slate-700 rounded-xl">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Deskripsi Singkat</label>
            <textarea name="description" rows="2" :value="currentProduct.description || ''"
              class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white focus:outline-emerald-500"></textarea>
          </div>

          <button type="submit"
            class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/20 transition-all">
            Simpan Informasi Produk
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection