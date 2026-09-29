<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = setting('midtrans_server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = setting('midtrans_is_production', '0') == '1';
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function notificationHandler(Request $request)
    {
        try {
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $paymentType       = $notif->payment_type;
            $orderCode         = $notif->order_id;
            $fraudStatus       = $notif->fraud_status;

            $order = Order::where('order_code', $orderCode)->firstOrFail();

            // Uang sukses masuk -> Status beralih ke 'processing' (Bukan completed)
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $order->update([
                        'status'         => 'processing',
                        'payment_method' => $paymentType
                    ]);
                }
            } else if ($transactionStatus == 'settlement') {
                $order->update([
                    'status'         => 'processing',
                    'payment_method' => $paymentType
                ]);
            } else if ($transactionStatus == 'pending') {
                $order->update(['status' => 'pending']);
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->update(['status' => 'cancelled']);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Status pesanan berhasil diperbarui ke tahap diproses.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
