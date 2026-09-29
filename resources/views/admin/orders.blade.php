@extends('layouts.app')

@section('content')
<div class="max-w-[96rem] w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 flex-1 flex flex-col justify-start">

    <!-- Header Halaman (Kompak & Bersih) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Manajemen Pesanan Masuk</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Kelola konfirmasi belanjaan dan pantau arus kas harian warung.</p>
        </div>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 shadow-sm w-fit transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke Etalase
        </a>
    </div>

    @if(session('success'))
    <div class="mb-3 p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2">
        <i data-lucide="check" class="w-3.5 h-3.5"></i> {{ session('success') }}
    </div>
    @endif

    <!-- Kartu Statistik Omzet Ringkas (Compact Layout) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
        <div class="bg-white dark:bg-slate-900 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                <i data-lucide="wallet" class="w-4 h-4"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Omzet Masuk</span>
                <span class="text-base sm:text-lg font-black text-slate-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                <i data-lucide="clock" class="w-4 h-4"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Sedang Disiapkan (Lunas)</span>
                <span class="text-base sm:text-lg font-black text-blue-600 dark:text-blue-400">{{ $processingCount }} Pesanan</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                <i data-lucide="badge-check" class="w-4 h-4"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Selesai & Diserahkan</span>
                <span class="text-base sm:text-lg font-black text-emerald-600 dark:text-emerald-400">{{ $completedCount }} Pesanan</span>
            </div>
        </div>
    </div>

    <!-- Tabel Pas Layar (No Vertical Scroll) -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden flex-1">
        <div class="w-full">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50/75 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-[10px] uppercase font-extrabold text-slate-400 dark:text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5 w-[18%]">Kode & Waktu</th>
                        <th class="px-4 py-2.5 w-[20%]">Pelanggan & Layanan</th>
                        <th class="px-4 py-2.5 w-[24%]">Rincian Belanja</th>
                        <th class="px-4 py-2.5 w-[14%]">Total Tagihan</th>
                        <th class="px-4 py-2.5 w-[12%]">Status</th>
                        <th class="px-4 py-2.5 w-[12%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <!-- Kolom 1: Kode & Waktu -->
                        <td class="px-4 py-2.5 align-middle">
                            <span class="font-extrabold text-slate-900 dark:text-white block text-xs tracking-tight">{{ $order->order_code }}</span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 block">{{ $order->created_at->diffForHumans() }}</span>
                        </td>

                        <!-- Kolom 2: Pelanggan & Layanan -->
                        <td class="px-4 py-2.5 align-middle">
                            <div class="font-bold text-slate-900 dark:text-white leading-tight">{{ $order->customer_name }}</div>
                            @if($order->customer_phone && $order->customer_phone !== '-')
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 block">{{ $order->customer_phone }}</span>
                            @endif
                            <div class="mt-0.5">
                                <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-bold {{ $order->fulfillment_type === 'delivery' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/70 dark:text-indigo-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }}">
                                    {{ $order->fulfillment_type === 'delivery' ? 'Kirim ke Rumah' : 'Ambil di Toko' }}
                                </span>
                            </div>
                        </td>

                        <!-- Kolom 3: Rincian Belanja -->
                        <td class="px-4 py-2.5 align-middle">
                            <div class="space-y-0.5">
                                @foreach($order->items as $item)
                                <div class="text-xs flex items-baseline gap-1.5 leading-snug">
                                    <span class="font-extrabold text-slate-800 dark:text-slate-200">{{ $item->quantity }}x</span>
                                    <span class="text-slate-600 dark:text-slate-400">{{ $item->product_name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        <!-- Kolom 4: Total & Metode Pembayaran -->
                        <td class="px-4 py-2.5 align-middle">
                            <div class="font-black text-slate-900 dark:text-white text-xs">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                            <span class="inline-block mt-0.5 px-1 py-0.2 rounded text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800">
                                {{ $order->payment_method }}
                            </span>
                        </td>

                        <!-- Kolom 5: Status -->
                        <td class="px-4 py-2.5 align-middle">
                            @if($order->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu
                            </span>
                            @elseif($order->status === 'processing')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/80 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/60 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Diproses
                            </span>
                            @elseif($order->status === 'completed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Selesai
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Batal
                            </span>
                            @endif
                        </td>

                        <!-- Kolom 6: Aksi -->
                        <td class="px-4 py-2.5 align-middle text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-[11px] px-2 py-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-semibold text-slate-700 dark:text-slate-200 focus:outline-emerald-500">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                </form>

                                <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="p-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors" title="Cetak Struk">
                                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                </a>

                                @if(auth()->user()->role === 'admin')
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus pesanan {{ $order->order_code }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 border border-rose-200 dark:border-rose-900/60 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-colors" title="Hapus Pesanan">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">Belum ada catatan pesanan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-2.5 border-t border-slate-100 dark:border-slate-800">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection