<?php

namespace App\Http\Controllers;

use App\Models\Capital;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        // 1. Total Modal Disetor
        $totalCapital = Capital::sum('amount');

        // 2. Total Omzet dari Transaksi Selesai (Completed)
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // 3. Hitung HPP (Harga Pokok Penjualan) dari barang yang sudah terjual pada transaksi completed
        $totalCogs = OrderItem::whereHas('order', function ($query) {
            $query->where('status', 'completed');
        })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(DB::raw('products.cost_price * order_items.quantity'));

        // 4. Total Beban Pengeluaran Operasional
        $totalExpense = Expense::sum('amount');

        // 5. Kalkulasi Laba Kotor dan Laba Bersih
        $grossProfit = $totalRevenue - $totalCogs;
        $netProfit = $grossProfit - $totalExpense;

        // 6. Data Riwayat Modal & Pengeluaran Terbaru
        $capitals = Capital::latest('capital_date')->take(5)->get();
        $expenses = Expense::latest('expense_date')->take(5)->get();

        // 7. Agregasi Tren Omzet Bulanan untuk Chart.js (Januari - Desember Tahun Berjalan)
        $currentYear = date('Y');
        $monthlyRevenue = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = (float) ($monthlyRevenue[$m] ?? 0);
        }

        return view('admin.finance', compact(
            'totalCapital',
            'totalRevenue',
            'totalCogs',
            'totalExpense',
            'grossProfit',
            'netProfit',
            'capitals',
            'expenses',
            'chartData',
            'currentYear'
        ));
    }

    public function storeCapital(Request $request)
    {
        $validated = $request->validate([
            'source'       => 'required|string|max:100',
            'amount'       => 'required|numeric|min:1000',
            'capital_date' => 'required|date',
            'notes'        => 'nullable|string',
        ]);

        Capital::create($validated);

        return back()->with('success', 'Modal berhasil dicatat ke sistem!');
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:150',
            'category'     => 'required|in:operational,utilities,salary,stock_purchase,other',
            'amount'       => 'required|numeric|min:1000',
            'expense_date' => 'required|date',
            'notes'        => 'nullable|string',
        ]);

        Expense::create($validated);

        return back()->with('success', 'Pengeluaran operasional berhasil disimpan!');
    }
}
