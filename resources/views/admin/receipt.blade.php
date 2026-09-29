<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk - {{ $order->order_code }}</title>
  <style>
    /* Ukuran standar struk kasir thermal 58mm / 80mm */
    @page {
      size: 80mm auto;
      margin: 0;
    }

    body {
      font-family: 'Courier New', Courier, monospace;
      font-size: 12px;
      color: #000;
      background-color: #f1f5f9;
      margin: 0;
      padding: 24px 0;
    }

    /* Container struk menyerupai kertas kasir nyata */
    .receipt-card {
      width: 72mm;
      margin: 0 auto;
      background-color: #ffffff;
      padding: 16px 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      border-radius: 4px;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .bold {
      font-weight: bold;
    }

    .divider {
      border-top: 1px dashed #000;
      margin: 8px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td {
      padding: 2px 0;
      vertical-align: top;
    }

    .no-print {
      margin-bottom: 20px;
      text-align: center;
    }

    .btn {
      padding: 8px 18px;
      background-color: #059669;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      font-family: sans-serif;
      font-size: 13px;
      text-decoration: none;
      display: inline-block;
    }

    .btn-back {
      background-color: #64748b;
      margin-left: 8px;
    }

    /* Penyesuaian saat printer / print preview aktif */
    @media print {
      body {
        background-color: #fff;
        padding: 0;
      }

      .no-print {
        display: none !important;
      }

      .receipt-card {
        box-shadow: none;
        padding: 10px 4px;
        width: 72mm !important;
        margin: 0 auto !important;
      }
    }
  </style>
</head>

<body>

  <!-- Tombol Kontrol (Hanya tampil di layar web, hilang saat diprint) -->
  <div class="no-print">
    <button class="btn" onclick="window.print()">Cetak Ulang</button>
    <a href="{{ route('admin.orders') }}" class="btn btn-back">&larr; Kembali ke Admin</a>
  </div>

  <!-- Area Kertas Struk -->
  <div class="receipt-card">
    <!-- Header Toko Dinamis -->
    <div class="text-center">
      <h2 style="margin: 0; font-size: 16px; text-transform: uppercase;">{{ setting('shop_name', 'WARUNG DIGITAL') }}
      </h2>
      <p style="margin: 2px 0; font-size: 10px;">{{ setting('shop_tagline', 'Solusi Belanja Cepat UMKM') }}</p>
      <p style="margin: 0; font-size: 10px;">WA: {{ setting('shop_phone', '0812-3456-7890') }}</p>
    </div>

    <div class="divider"></div>

    <!-- Info Transaksi -->
    <table style="font-size: 11px;">
      <tr>
        <td>No: {{ $order->order_code }}</td>
        <td class="text-right">{{ $order->created_at->format('d/m/y H:i') }}</td>
      </tr>
      <tr>
        <td>Plg: {{ $order->customer_name }}</td>
        <td class="text-right bold">[{{ $order->fulfillment_type === 'delivery' ? 'KIRIM' : 'AMBIL' }}]</td>
      </tr>
    </table>

    <div class="divider"></div>

    <!-- Daftar Barang Belanjaan -->
    <table>
      @foreach($order->items as $item)
      <tr>
        <td colspan="2" class="bold">{{ $item->product_name }}</td>
      </tr>
      <tr style="font-size: 11px;">
        <td>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</td>
        <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
      </tr>
      @endforeach
    </table>

    <div class="divider"></div>

    <!-- Total Tagihan -->
    <table>
      <tr class="bold" style="font-size: 13px;">
        <td>TOTAL:</td>
        <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
      </tr>
      <tr style="font-size: 10px;">
        <td>Metode: {{ strtoupper($order->payment_method) }}</td>
        <td class="text-right">LUNAS</td>
      </tr>
    </table>

    <div class="divider"></div>

    <!-- Footer Struk -->
    <div class="text-center" style="font-size: 10px; margin-top: 10px;">
      <p style="margin: 2px 0;">Terima kasih atas pesanan Anda!</p>
      <p style="margin: 2px 0;">Barang yang sudah dibeli tidak dapat ditukar kembali.</p>
    </div>
  </div>

  <script>
    window.onload = function() {
            window.print();
        }
  </script>
</body>

</html>