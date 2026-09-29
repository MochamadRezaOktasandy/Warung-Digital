<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')->latest()->paginate(15);

        // Total Omzet dihitung dari pesanan yang sudah lunas dibayar (processing & completed)
        $totalRevenue = Order::whereIn('status', ['processing', 'completed'])->sum('total_amount');

        $pendingCount = Order::where('status', 'pending')->count();
        $processingCount = Order::where('status', 'processing')->count();
        $completedCount = Order::where('status', 'completed')->count();

        return view('admin.orders', compact('orders', 'totalRevenue', 'pendingCount', 'processingCount', 'completedCount'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:100',
            'customer_phone'   => 'required|string|max:25',
            'fulfillment_type' => 'required|in:delivery,pickup',
            'delivery_address' => 'nullable|string',
            'payment_method'   => 'nullable|string|max:30',
            'is_pos'           => 'nullable|boolean',
            'notes'            => 'nullable|string',
            'cart'             => 'required|array|min:1',
            'cart.*.id'        => 'required|exists:products,id',
            'cart.*.qty'       => 'required|integer|min:1',
        ]);

        $qrisMode = setting('qris_mode', 'manual');
        $qrisImage = setting('shop_qris_image');

        if (($validated['payment_method'] ?? '') === 'qris') {
            if ($qrisMode === 'manual' && empty($qrisImage)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stiker QRIS toko belum diunggah di menu Pengaturan.'
                ], 422);
            }
        }

        try {
            $order = DB::transaction(function () use ($validated, $qrisMode) {
                $totalAmount = 0;
                $orderItemsData = [];

                foreach ($validated['cart'] as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['id']);

                    if ($product->stock < $item['qty']) {
                        throw new \Exception("Stok untuk '{$product->name}' tidak mencukupi.");
                    }

                    $product->decrement('stock', $item['qty']);

                    $subtotal = $product->selling_price * $item['qty'];
                    $totalAmount += $subtotal;

                    $orderItemsData[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'unit_price'   => $product->selling_price,
                        'quantity'     => $item['qty'],
                        'subtotal'     => $subtotal,
                    ];
                }

                $orderCode = 'WD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

                // Alur Fix: Transaksi Kasir POS langsung masuk 'processing' (Lunas & Siap Dibuatkan)
                // Jika order online publik via WA, status awal tetap 'pending'
                $isPos = $validated['is_pos'] ?? false;
                $initialStatus = $isPos ? 'processing' : 'pending';

                $order = Order::create([
                    'order_code'       => $orderCode,
                    'customer_name'    => $validated['customer_name'],
                    'customer_phone'   => $validated['customer_phone'],
                    'fulfillment_type' => $validated['fulfillment_type'],
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'total_amount'     => $totalAmount,
                    'payment_method'   => $validated['payment_method'] ?? 'cash',
                    'status'           => $initialStatus,
                    'notes'            => $validated['notes'] ?? null,
                ]);

                foreach ($orderItemsData as $itemData) {
                    $order->items()->create($itemData);
                }

                // Midtrans Snap Token jika mode otomatis aktif
                $serverKey = setting('midtrans_server_key', env('MIDTRANS_SERVER_KEY'));
                if (($validated['payment_method'] ?? '') === 'qris' && $qrisMode === 'midtrans' && !empty($serverKey)) {
                    Config::$serverKey    = $serverKey;
                    Config::$isProduction = setting('midtrans_is_production', '0') == '1';
                    Config::$isSanitized  = true;
                    Config::$is3ds        = true;

                    $params = [
                        'transaction_details' => [
                            'order_id'     => $orderCode,
                            'gross_amount' => (int) $totalAmount,
                        ],
                        'customer_details' => [
                            'first_name' => $validated['customer_name'],
                            'phone'      => $validated['customer_phone'],
                        ],
                        'enabled_payments' => ['gopay', 'qris', 'shopeepay'],
                    ];

                    $snapToken = Snap::getSnapToken($params);
                    $order->update(['snap_token' => $snapToken]);
                }

                return $order;
            });

            return response()->json([
                'success'      => true,
                'order_id'     => $order->id,
                'order_code'   => $order->order_code,
                'total_amount' => $order->total_amount,
                'status'       => $order->status,
                'snap_token'   => $order->snap_token,
                'qris_mode'    => $qrisMode,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
                }
            }

            $order->status = $newStatus;
            $order->save();
        });

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status'  => $newStatus,
                'message' => "Status pesanan {$order->order_code} berhasil diubah ke {$newStatus}."
            ]);
        }

        return back()->with('success', "Status pesanan {$order->order_code} berhasil diubah ke {$newStatus}.");
    }

    public function destroy($id)
    {
        $order = Order::with('items')->findOrFail($id);

        DB::transaction(function () use ($order) {
            if ($order->status !== 'completed' && $order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }
            $order->items()->delete();
            $order->delete();
        });

        return back()->with('success', "Pesanan {$order->order_code} berhasil dihapus permanen.");
    }

    public function printReceipt($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.receipt', compact('order'));
    }
}
