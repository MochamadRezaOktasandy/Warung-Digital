<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: Alpine.$persist(false).as('wd_dark_mode') }" :class="{ 'dark': darkMode }">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ setting('shop_name', 'WarungDigital') }} - {{ setting('shop_tagline', 'POS & Etalase UMKM') }}</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          },
          colors: {
            brand: {
              50: '#ecfdf5',
              500: '#10b981',
              600: '#059669',
              700: '#047857',
            }
          }
        }
      }
    }
  </script>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  @php
  $clientKey = setting('midtrans_client_key', env('MIDTRANS_CLIENT_KEY'));$isProd = setting('midtrans_is_production',
  '0') == '1';
  $snapUrl =$isProd ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
  @endphp

  @if(setting('qris_mode', 'manual') === 'midtrans' && !empty($clientKey))
  <script type="text/javascript" src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
  @endif

  <style>
    [x-cloak] {
      display: none !important;
    }

    body {
      transition: background-color 0.2s ease, color 0.2s ease;
    }
  </style>
</head>

<body
  class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased selection:bg-emerald-500 selection:text-white min-h-screen flex flex-col"
  x-data="posEngine()" x-init="$watch('cart', () => { setTimeout(() => lucide.createIcons(), 50); })">

  <!-- Top Navbar -->
  <header
    class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 flex-shrink-0">
    <div class="max-w-[96rem] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

      <!-- Brand & Custom Logo (Kiri Atas) -->
      <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="group flex items-center justify-center">
          @if(setting('shop_logo_type') === 'upload' && setting('shop_logo_image'))
          <img src="{{ setting('shop_logo_image') }}" alt="{{ setting('shop_name') }}"
            class="w-10 h-10 rounded-xl object-contain bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm p-0.5 group-hover:scale-105 transition-transform">
          @else
          <div
            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform">
            <i data-lucide="{{ setting('shop_logo_icon', 'store') }}" class="w-5 h-5"></i>
          </div>
          @endif
        </a>
        <div>
          <a href="{{ route('home') }}"
            class="font-extrabold text-lg tracking-tight text-slate-900 dark:text-white leading-none block hover:text-emerald-600 transition-colors">
            {{ setting('shop_name', 'WarungDigital') }}
          </a>

          @auth
          <span class="text-xs font-semibold inline-flex items-center gap-1.5 mt-0.5">
            @if(setting('enable_online_order', '1') == '1')
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-emerald-600 dark:text-emerald-400">Online Store Aktif</span>
            @else
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            <span class="text-amber-600 dark:text-amber-400">Mode Kasir Offline (POS Internal)</span>
            @endif
          </span>
          @else
          <span class="text-xs text-slate-400 dark:text-slate-500 block mt-0.5 font-medium">
            {{ setting('shop_tagline', 'Belanja Cepat & Mudah') }}
          </span>
          @endauth
        </div>
      </div>

      <!-- Navigasi Kanan -->
      <div class="flex items-center gap-2 sm:gap-3">
        @auth
        <nav class="hidden md:flex items-center gap-1">
          <a href="{{ route('admin.orders') }}"
            class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            Pesanan
          </a>

          @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.products') }}"
            class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            Katalog Barang
          </a>
          <a href="{{ route('admin.finance') }}"
            class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            Keuangan & Laba Rugi
          </a>
          <a href="{{ route('admin.settings') }}"
            class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            Pengaturan
          </a>
          @endif
        </nav>

        <!-- Toggle Dark Mode -->
        <button type="button" @click="darkMode = !darkMode; setTimeout(() => lucide.createIcons(), 50)"
          class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
          title="Beralih Tema">
          <i x-show="!darkMode" data-lucide="moon" class="w-4 h-4"></i>
          <i x-show="darkMode" x-cloak data-lucide="sun" class="w-4 h-4 text-amber-400"></i>
        </button>

        <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-800">
          <span
            class="hidden sm:inline-block px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase {{ auth()->user()->role === 'admin' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-950/70 dark:text-blue-300' }}">
            {{ auth()->user()->role }}
          </span>
          <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit"
              class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors"
              title="Keluar">
              <i data-lucide="log-out" class="w-4 h-4"></i>
            </button>
          </form>
        </div>
        @else
        <button type="button" @click="darkMode = !darkMode; setTimeout(() => lucide.createIcons(), 50)"
          class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
          title="Beralih Tema">
          <i x-show="!darkMode" data-lucide="moon" class="w-4 h-4"></i>
          <i x-show="darkMode" x-cloak data-lucide="sun" class="w-4 h-4 text-amber-400"></i>
        </button>
        @endauth

        <!-- Tombol Keranjang -->
        <button @click="isDrawerOpen = true"
          class="relative p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors flex items-center gap-2">
          <i data-lucide="shopping-cart" class="w-5 h-5 text-slate-700 dark:text-slate-300"></i>
          <span class="hidden sm:inline font-bold text-sm text-slate-700 dark:text-slate-200"
            x-text="'Rp ' + formatRupiah(cartTotal)"></span>
          <span x-show="cartCount > 0" x-cloak
            class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-600 text-white text-[11px] font-bold flex items-center justify-center"
            x-text="cartCount"></span>
        </button>
      </div>
    </div>
  </header>

  <!-- Konten Utama -->
  <main class="flex-1 w-full flex flex-col">
    @yield('content')
  </main>

  <!-- Drawer Keranjang Pesanan -->
  <div x-show="isDrawerOpen" x-cloak class="fixed inset-0 z-50 overflow-hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isDrawerOpen = false"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
      <div
        class="w-screen max-w-md bg-white dark:bg-slate-900 shadow-2xl flex flex-col border-l border-slate-100 dark:border-slate-800">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i data-lucide="receipt" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">
              {{ setting('enable_online_order', '1') == '1' ? 'Pesanan Saya' : 'Transaksi Kasir POS' }}
            </h2>
          </div>
          <button @click="isDrawerOpen = false"
            class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-3">
          <template x-if="cart.length === 0">
            <div class="text-center py-16">
              <i data-lucide="shopping-basket" class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-3"></i>
              <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Belum ada item yang dipilih.</p>
            </div>
          </template>

          <template x-for="(item, index) in cart" :key="item.id">
            <div
              class="flex gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 items-center justify-between">
              <div class="flex-1 min-w-0 pr-2">
                <h4 class="font-bold text-slate-900 dark:text-white text-sm truncate" x-text="item.name"></h4>
                <p class="text-emerald-600 dark:text-emerald-400 font-black text-xs"
                  x-text="'@ Rp ' + formatRupiah(item.price)"></p>
              </div>

              <div class="flex items-center gap-3">
                <div
                  class="flex items-center gap-2 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 px-2 py-1">
                  <button @click="decreaseQty(item.id)"
                    class="text-slate-500 hover:text-emerald-600 dark:text-slate-400 font-bold px-1 text-sm">-</button>
                  <span class="text-xs font-bold w-4 text-center dark:text-slate-200" x-text="item.qty"></span>
                  <button @click="increaseQty(item.id)"
                    class="text-slate-500 hover:text-emerald-600 dark:text-slate-400 font-bold px-1 text-sm">+</button>
                </div>
                <button @click="removeItem(item.id)" class="text-rose-500 hover:text-rose-700">
                  <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
          </template>
        </div>

        <div x-show="cart.length > 0"
          class="p-6 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/60 space-y-4">
          <div class="flex justify-between items-center text-slate-600 dark:text-slate-400 font-semibold">
            <span class="text-sm">Total Tagihan:</span>
            <span class="text-2xl font-black text-slate-900 dark:text-white"
              x-text="'Rp ' + formatRupiah(cartTotal)"></span>
          </div>

          @if(setting('enable_online_order', '1') == '1')
          <!-- Form Checkout Online -->
          <div class="space-y-3">
            <div>
              <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">Nama Pemesan</label>
              <input type="text" x-model="customer.name" placeholder="Misal: Andi Prasetyo"
                class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 focus:outline-emerald-500 bg-white dark:bg-slate-800 dark:text-white">
            </div>

            <div>
              <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">Nomor WhatsApp</label>
              <input type="tel" x-model="customer.phone" placeholder="Misal: 081234567890"
                class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 focus:outline-emerald-500 bg-white dark:bg-slate-800 dark:text-white">
            </div>

            @if(setting('enable_home_delivery', '1') == '1')
            <div>
              <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">Layanan
                Pengambilan</label>
              <select x-model="customer.fulfillment"
                class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white">
                <option value="pickup">Ambil di Toko</option>
                <option value="delivery">Antar ke Rumah (Kurir Warung)</option>
              </select>
            </div>
            <div x-show="customer.fulfillment === 'delivery'">
              <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">Alamat Antar
                Lengkap</label>
              <textarea x-model="customer.address" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, dan patokan..."
                rows="2"
                class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white"></textarea>
            </div>
            @endif

            <div
              class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 text-xs space-y-1">
              <div class="font-extrabold flex items-center gap-1.5">
                <i data-lucide="shield-alert" class="w-4 h-4 text-emerald-600"></i>
                <span>Metode Bayar: QRIS Resmi (Wajib Transfer)</span>
              </div>
              <p class="text-[11px] leading-relaxed text-amber-700 dark:text-amber-400">
                Pesanan online akan langsung disiapkan setelah Anda mengirimkan bukti transfer QRIS ke WhatsApp warung.
              </p>
            </div>
          </div>

          <button @click="processOnlineOrder()"
            class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition-all">
            <i data-lucide="qr-code" class="w-4 h-4"></i>
            <span>Bayar QRIS & Kirim Bukti ke WhatsApp</span>
          </button>
          @else
          <!-- Mode Internal Kasir POS Offline -->
          <div class="space-y-3">
            <input type="text" x-model="customer.name" placeholder="Nama Pelanggan (Default: Pelanggan Kasir)"
              class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 focus:outline-emerald-500 bg-white dark:bg-slate-800 dark:text-white">

            <div>
              <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Metode Bayar Kasir:</label>
              <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="paymentMethod = 'cash'"
                  :class="paymentMethod === 'cash' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700'"
                  class="py-2 px-3 rounded-xl border text-xs font-bold text-center transition-all">
                  Tunai (Cash)
                </button>
                <button type="button" @click="paymentMethod = 'qris'"
                  :class="paymentMethod === 'qris' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700'"
                  class="py-2 px-3 rounded-xl border text-xs font-bold text-center transition-all">
                  QRIS Warung
                </button>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-2">
              <button type="button" @click="processPosOrder(false)"
                class="py-3 px-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all">
                <i data-lucide="save" class="w-4 h-4 text-slate-500"></i> Simpan Saja
              </button>
              <button type="button" @click="processPosOrder(true)"
                class="py-3 px-3 bg-slate-900 hover:bg-black dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md transition-all">
                <i data-lucide="printer" class="w-4 h-4 text-emerald-400 dark:text-white"></i> Simpan & Cetak
              </button>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Engine Script Alpine -->
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('posEngine', () => ({
        isDrawerOpen: false,
        cart: Alpine.$persist([]).as('wd_cart'),
        storeWhatsApp: '{{ setting("shop_phone", "6281234567890") }}',
        shopName: '{{ setting("shop_name", "Ini Cokelat") }}',
        isOnlineMode: {{ setting('enable_online_order', '1') == '1' ? 'true' : 'false' }},
        enableWhatsApp: {{ setting('enable_whatsapp_order', '1') == '1' ? 'true' : 'false' }},
        paymentMethod: 'cash',
        qrisImageSetting: '{{ setting("shop_qris_image", "") }}',
        qrisModeSetting: '{{ setting("qris_mode", "manual") }}',
        customer: {
          name: '',
          phone: '',
          fulfillment: 'pickup',
          address: ''
        },
        get cartCount() {
          return this.cart.reduce((total, item) => total + item.qty, 0);
        },
        get cartTotal() {
          return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
        },
        addToCart(product) {
          const existing = this.cart.find(i => i.id === product.id);
          if (existing) {
            existing.qty += 1;
          } else {
            this.cart.push({
              id: product.id,
              name: product.name,
              price: product.selling_price,
              image: product.image_url,
              qty: 1
            });
          }
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Item dimasukkan',
            showConfirmButton: false,
            timer: 1000
          });
        },
        increaseQty(id) {
          const item = this.cart.find(i => i.id === id);
          if (item) item.qty++;
        },
        decreaseQty(id) {
          const item = this.cart.find(i => i.id === id);
          if (item && item.qty > 1) {
            item.qty--;
          } else {
            this.removeItem(id);
          }
        },
        removeItem(id) {
          this.cart = this.cart.filter(i => i.id !== id);
        },
        formatRupiah(num) {
          return new Intl.NumberFormat('id-ID').format(num);
        },

        async processOnlineOrder() {
          let clientName = this.customer.name.trim();
          let clientPhone = this.customer.phone.trim();

          if (!clientName) {
            Swal.fire('Data Belum Lengkap', 'Nama pemesan wajib diisi.', 'warning');
            return;
          }
          if (!clientPhone) {
            Swal.fire('Data Belum Lengkap', 'Nomor WhatsApp wajib diisi.', 'warning');
            return;
          }
          if (this.customer.fulfillment === 'delivery' && !this.customer.address.trim()) {
            Swal.fire('Data Belum Lengkap', 'Alamat antar wajib diisi.', 'warning');
            return;
          }

          if (this.qrisModeSetting === 'manual' && !this.qrisImageSetting) {
            Swal.fire('QRIS Belum Tersedia', 'Toko belum mengunggah stiker QRIS resmi di Pengaturan.', 'error');
            return;
          }

          Swal.fire({
            title: 'Membuat Pesanan...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
          });

          try {
            const response = await fetch('/checkout', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                customer_name: clientName,
                customer_phone: clientPhone,
                fulfillment_type: this.customer.fulfillment,
                delivery_address: this.customer.fulfillment === 'delivery' ? this.customer.address : null,
                payment_method: 'qris',
                is_pos: false,
                cart: this.cart
              })
            });

            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Gagal memproses pesanan.');

            Swal.close();

            await Swal.fire({
              title: 'Pindai & Bayar QRIS',
              html: `
                <div class="text-center space-y-3">
                  <p class="text-xs text-slate-500">Silakan scan dan transfer tepat sesuai nominal berikut:</p>
                  <p class="text-2xl font-black text-emerald-600">Rp ${this.formatRupiah(result.total_amount)}</p>
                  <img src="${this.qrisImageSetting}" class="w-56 h-56 object-contain mx-auto border-2 border-emerald-500/20 rounded-2xl p-2 bg-white shadow-md">
                  <div class="p-2.5 bg-amber-50 rounded-xl text-left border border-amber-200">
                    <p class="text-[11px] font-bold text-amber-900">⚠️ LANGKAH SELANJUTNYA:</p>
                    <p class="text-[10px] text-amber-800">Setelah transfer berhasil, klik tombol hijau di bawah untuk menuju ke WhatsApp toko dan <b>KIRIM FOTO / SCREENSHOT BUKTI TRANSFER</b>.</p>
                  </div>
                </div>
              `,
              confirmButtonText: 'Kirim Bukti Transfer ke WhatsApp 📲',
              confirmButtonColor: '#10b981',
              allowOutsideClick: false
            });

            let itemsText = '';
            this.cart.forEach((i, idx) => {
              itemsText += `${idx + 1}. ${i.name} (${i.qty}x) - Rp ${this.formatRupiah(i.price * i.qty)}\n`;
            });

            const deliveryLabel = this.customer.fulfillment === 'delivery' ? 'Antar ke Rumah' : 'Ambil di Toko';
            const addressText = this.customer.fulfillment === 'delivery' ? `• Alamat: ${this.customer.address}\n` : '';

            const rawMessage = 
`Halo Admin *${this.shopName}*, saya mau konfirmasi pesanan:

📋 *DETAIL PESANAN*
• Kode Order: *${result.order_code}*
• Nama: ${clientName}
• No. WhatsApp: ${clientPhone}
• Pengambilan: ${deliveryLabel}
${addressText}
🛒 *ITEM BELANJA:*
${itemsText}
💰 *TOTAL TAGIHAN: Rp ${this.formatRupiah(result.total_amount)}*
💳 Metode: Transfer QRIS

-------------------------------------------
⚠ *PENTING:*
Berikut saya lampirkan foto/screenshot bukti transfer QRIS di bawah ini agar pesanan segera diproses. Terima kasih!`;

            const encodedText = encodeURIComponent(rawMessage);
            window.open(`https://api.whatsapp.com/send?phone=${this.storeWhatsApp}&text=${encodedText}`, '_blank');

            this.cart = [];
            this.isDrawerOpen = false;

            Swal.fire({
              icon: 'success',
              title: 'Pesanan Telah Dibuat!',
              text: 'Silakan lampirkan tangkapan layar bukti transfer di WhatsApp agar pesanan segera disiapkan.',
              confirmButtonText: 'Selesai',
              confirmButtonColor: '#10b981'
            });

          } catch (err) {
            Swal.fire('Gagal', err.message, 'error');
          }
        },

        async processPosOrder(autoPrint = false) {
          let clientName = this.customer.name.trim() || 'Pelanggan Kasir';

          if (this.paymentMethod === 'qris' && this.qrisModeSetting === 'manual' && !this.qrisImageSetting) {
            Swal.fire('QRIS Belum Siap', 'Stiker QRIS toko belum diunggah di menu Pengaturan.', 'warning');
            return;
          }

          Swal.fire({
            title: 'Menyimpan Transaksi...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
          });

          try {
            const response = await fetch('/checkout', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                customer_name: clientName,
                customer_phone: '-',
                fulfillment_type: 'pickup',
                payment_method: this.paymentMethod,
                is_pos: true,
                cart: this.cart
              })
            });

            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Gagal menyimpan transaksi.');

            Swal.close();

            if (this.paymentMethod === 'qris' && result.qris_mode === 'manual') {
              await Swal.fire({
                title: 'Scan QRIS Warung',
                html: `
                  <div class="text-center">
                    <p class="text-xs text-slate-500 mb-1">Pindai kode QRIS dan transfer nominal berikut:</p>
                    <p class="text-2xl font-black text-emerald-600 mb-3">Rp ${this.formatRupiah(result.total_amount)}</p>
                    <img src="${this.qrisImageSetting}" class="w-56 h-56 object-contain mx-auto border rounded-xl p-2 bg-white shadow-sm mb-2">
                    <p class="text-[11px] text-slate-400">Kode Transaksi: <b>${result.order_code}</b></p>
                  </div>
                `,
                confirmButtonText: 'Selesai Bayar & Siapkan Pesanan',
                confirmButtonColor: '#10b981',
                allowOutsideClick: false
              });
            }

            if (autoPrint && result.order_id) {
              window.open(`/admin/orders/${result.order_id}/print`, '_blank');
            }

            this.cart = [];
            this.isDrawerOpen = false;

            Swal.fire({
              icon: 'success',
              title: 'Transaksi Kasir Berhasil!',
              text: 'Pesanan masuk ke status Diproses (Lunas & Sedang Dibuatkan).',
              timer: 2000,
              showConfirmButton: false
            });

          } catch (err) {
            Swal.fire('Gagal', err.message, 'error');
          }
        }
      }));
    });

    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });
  </script>
</body>

</html>