@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
      <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pembukuan & Laba Rugi UMKM</h1>
      <p class="text-sm text-slate-500">Pantau arus kas, alokasi modal, dan laba bersih usaha secara real-time.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.orders') }}"
        class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 shadow-sm">
        Lihat Pesanan
      </a>
      <a href="{{ route('admin.settings') }}"
        class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 shadow-sm">
        Pengaturan Toko
      </a>
    </div>
  </div>

  @if(session('success'))
  <div
    class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-2">
    <i data-lucide="check" class="w-4 h-4"></i> {{ session('success') }}
  </div>
  @endif

  <!-- 4 Kartu Metrik Keuangan Utama -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Modal Disetor</span>
      <span class="text-xl font-black text-slate-900">Rp {{ number_format($totalCapital, 0, ',', '.') }}</span>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Omzet Penjualan</span>
      <span class="text-xl font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Beban Operasional</span>
      <span class="text-xl font-black text-rose-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Laba Bersih Usaha</span>
      <span class="text-xl font-black {{ $netProfit >= 0 ? 'text-blue-600' : 'text-rose-600' }}">
        Rp {{ number_format($netProfit, 0, ',', '.') }}
      </span>
    </div>
  </div>

  <!-- Grafik Performa Penjualan & Rincian Laba Rugi -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Grafik Penjualan Bulanan (Chart.js) -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
      <h3 class="font-bold text-slate-900 text-base mb-4 flex items-center gap-2">
        <i data-lucide="trending-up" class="w-5 h-5 text-emerald-600"></i> Tren Penjualan Bulanan ({{ $currentYear }})
      </h3>
      <div class="relative h-64">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>

    <!-- Kartu Rincian Kalkulasi Laba Rugi Praktis -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
      <div>
        <h3 class="font-bold text-slate-900 text-base mb-4 flex items-center gap-2">
          <i data-lucide="calculator" class="w-5 h-5 text-emerald-600"></i> Ringkasan Laba Rugi
        </h3>
        <div class="space-y-3 text-sm">
          <div class="flex justify-between text-slate-600">
            <span>(+) Penjualan Bersih</span>
            <span class="font-semibold text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between text-slate-600">
            <span>(-) HPP Modal Barang</span>
            <span class="font-semibold text-rose-500">Rp {{ number_format($totalCogs, 0, ',', '.') }}</span>
          </div>
          <div class="pt-2 border-t border-dashed border-slate-200 flex justify-between font-bold text-slate-800">
            <span>(=) Laba Kotor</span>
            <span>Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between text-slate-600">
            <span>(-) Beban Operasional</span>
            <span class="font-semibold text-rose-500">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>

      <div class="pt-4 border-t border-slate-100 mt-6">
        <div
          class="p-4 rounded-xl {{ $netProfit >= 0 ? 'bg-emerald-50 text-emerald-900' : 'bg-rose-50 text-rose-900' }}">
          <span class="text-xs uppercase font-extrabold tracking-wider block">Estimasi Laba Bersih</span>
          <span class="text-2xl font-black block">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Formulir & Riwayat Transaksi Modal dan Pengeluaran -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Seksi Pencatatan Modal -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
      <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
        <i data-lucide="coins" class="w-5 h-5 text-emerald-600"></i> Catat Modal Pemilik / Tambahan
      </h3>

      <form action="{{ route('admin.finance.capital.store') }}" method="POST" class="space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-3">
          <input type="text" name="source" placeholder="Sumber (Misal: Modal Awal)" required
            class="text-sm px-3.5 py-2 rounded-xl border border-slate-200 bg-white">
          <input type="number" name="amount" placeholder="Nominal (Rp)" required
            class="text-sm px-3.5 py-2 rounded-xl border border-slate-200 bg-white">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <input type="date" name="capital_date" value="{{ date('Y-m-d') }}" required
            class="text-sm px-3.5 py-2 rounded-xl border border-slate-200 bg-white">
          <input type="text" name="notes" placeholder="Catatan tambahan..."
            class="text-sm px-3.5 py-2 rounded-xl border border-slate-200 bg-white">
        </div>
        <button type="submit"
          class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
          + Simpan Setoran Modal
        </button>
      </form>

      <div class="pt-4 border-t border-slate-100">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Riwayat Modal Terbaru</span>
        <ul class="text-xs space-y-2">
          @forelse($capitals as $c)
          <li class="flex justify-between items-center py-1 border-b border-slate-50">
            <div>
              <span class="font-bold text-slate-800">{{ $c->source }}</span>
              <span class="text-slate-400 block text-[10px]">{{ $c->capital_date }}</span>
            </div>
            <span class="font-black text-slate-900">Rp {{ number_format($c->amount, 0, ',', '.') }}</span>
          </li>
          @empty
          <li class="text-slate-400 text-center py-2">Belum ada catatan modal.</li>
          @endforelse
        </ul>
      </div>
    </div>

    <!-- Seksi Pencatatan Pengeluaran Operasional -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
      <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
        <i data-lucide="receipt-text" class="w-5 h-5 text-rose-600"></i> Catat Biaya Operasional Warung
      </h3>

      <form action="{{ route('admin.finance.expense.store') }}" method="POST" class="space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-3">
          <input type="text" name="title" placeholder="Nama Biaya (Misal: Listrik Toko)" required
            class="text-sm px-3.5 py-2 rounded-xl border border-slate-200 bg-white">
          <select name="category" class="text-sm px-3.5 py-2 rounded-xl border border-slate-200 bg-white">
            <option value="operational">Operasional Toko</option>
            <option value="utilities">Listrik / Air / Internet</option>
            <option value="salary">Gaji Karyawan</option>
            <option value="stock_purchase">Belanja Perlengkapan</option>
            <option value="other">Lain-lain</option>
          </select>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <input type="number" name="amount" placeholder="Nominal (Rp)" required
            class="text-sm px-3.5 py-2 rounded-xl border border-slate-200 bg-white">
          <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" required
            class="text-sm px-3.5 py-2 rounded-xl border border-slate-200 bg-white">
        </div>
        <button type="submit"
          class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
          + Simpan Pengeluaran
        </button>
      </form>

      <div class="pt-4 border-t border-slate-100">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Riwayat Biaya Terbaru</span>
        <ul class="text-xs space-y-2">
          @forelse($expenses as $e)
          <li class="flex justify-between items-center py-1 border-b border-slate-50">
            <div>
              <span class="font-bold text-slate-800">{{ $e->title }}</span>
              <span class="text-slate-400 block text-[10px]">{{ $e->expense_date }} &bull; {{ strtoupper($e->category)
                }}</span>
            </div>
            <span class="font-black text-rose-600">- Rp {{ number_format($e->amount, 0, ',', '.') }}</span>
          </li>
          @empty
          <li class="text-slate-400 text-center py-2">Belum ada catatan biaya operasional.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Omzet Penjualan (Rp)',
                    data: chartData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#059669',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection