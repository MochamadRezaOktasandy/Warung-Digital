# 🏪 WarungDigital - Hybrid POS & Online Store UMKM

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=black)](https://alpinejs.dev)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Midtrans](https://img.shields.io/badge/Payment-Midtrans_QRIS-002855?style=for-the-badge)](https://midtrans.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-emerald.svg?style=for-the-badge)](LICENSE)

**WarungDigital** adalah platform Point of Sales (POS) dan Etalase Digital modern siap pakai yang dirancang khusus untuk memenuhi kebutuhan operasional dan transaksi UMKM (F&B, Retail, Kelontong, dan Usaha Menengah). Platform ini menggabungkan fleksibilitas kasir fisik (*Offline POS*) dan kenyamanan toko daring (*Online Store*) dalam satu aplikasi terintegrasi.

---

## 🌟 Fitur Utama

### 1. Dual Operational Mode (Hybrid Engine)
* **Mode Kasir Offline (POS Internal):** Dioptimalkan untuk efisiensi input kasir toko fisik. Mendukung metode pembayaran Tunai (*Cash*) dan QRIS, status transaksi langsung masuk antrean pemrosesan (`processing`), pencetakan struk termal instan, dan bebas form pembeli yang rumit.
* **Mode Online Store (Etalase Publik):** Antarmuka publik yang bersih tanpa login pembeli. Mengunci pembayaran **Wajib QRIS** (mencegah order fiktif / *fraud* COD) dengan pengalihan otomatis ke WhatsApp beserta draf nota transaksi rapi dan instruksi wajib melampirkan bukti transfer.

### 2. Branding & CMS Customizer
* **Custom Logo:** Fleksibilitas memilih preset ikon toko modern (Warung, Kopi, Minuman, Sembako, F&B) atau mengunggah logo kustom berformat PNG transparan (1:1).
* **Hero Banner Engine:** Kustomisasi latar banner depan menggunakan **Interactive Dual Color Picker (Hex Gradient)** dengan *Live Preview* seketika atau unggah banner grafis kustom (16:5 / 16:6). Kolom teks bersifat opsional sehingga poster yang sudah memiliki desain tulisan dapat tampil bersih tanpa tertimpa teks sistem.

### 3. Manajemen Transaksi & Alur Kerja (Order Lifecycle)
* **Status Terstandarisasi:**
  * `pending`: Menunggu konfirmasi pembayaran / validasi bukti transfer.
  * `processing`: Pembayaran terkonfirmasi (Kasir Tunai, QRIS Manual, atau Midtrans Settlement), staf mulai menyiapkan barang.
  * `completed`: Barang fisik telah diserahkan ke pelanggan atau selesai dikirim.
  * `cancelled`: Transaksi dibatalkan; stok barang dikembalikan otomatis ke inventaris (*rollback*).

### 4. Integrasi Pembayaran QRIS Fleksibel
* **QRIS Statis (Manual):** Cukup unggah stiker barcode rekening toko (BCA, Mandiri, BRI, GoPay, OVO, DANA, ShopeePay, BSI).
* **QRIS Dinamis (Midtrans API):** Terintegrasi langsung dengan Snap API dan Webhook Notification untuk otomasi status pembayaran tanpa verifikasi mutasi manual.

### 5. UI/UX Responsif & Tema Adaptif
* **Full Responsive:** Tabel pesanan pas layar (*Fit-Screen*) tanpa *horizontal scroll* pada resolusi desktop/laptop.
* **Dark Mode Native:** Terintegrasi penuh dengan persistensi preferensi tema via Alpine.js Persist.
* **Smart Settings Hierarchy:** Pilihan opsi kurir dan pesan WhatsApp otomatis terkunci (*disabled state*) ketika mode online dinonaktifkan.

---

## 🛠️ Tech Stack

* **Backend Framework:** Laravel 13.x (PHP 8.5+)
* **Database:** MySQL / MariaDB / SQLite
* **Frontend State Management:** Alpine.js 3.x (with `@alpinejs/persist`)
* **Styling & Icons:** Tailwind CSS 3.x, Lucide Icons
* **UI Components & Alerts:** SweetAlert2
* **Payment Gateway:** Midtrans Snap & Notification Engine

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan pengembangan lokal:

### 1. Kloning Repositori
```bash
git clone [https://github.com/MochamadRezaOktasandy/warungdigital.git](https://github.com/MochamadRezaOktasandy/warungdigital.git)
cd warungdigital

2. Instalasi Dependensi PHP & Node

composer install
npm install

3. Konfigurasi Environment (.env)

cp .env.example .env

Sesuaikan konfigurasi basis data Anda di file .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warungdigital
DB_USERNAME=root
DB_PASSWORD=

 4. Generate Application Key & Symlink Storage

php artisan key:generate
php artisan storage:link

5. Eksekusi Migrasi & Data Awal

php artisan migrate --seed

6. Menjalankan Server Lokal

php artisan serve

Peran (Role),Akses Fitur:

Admin (Pemilik Usaha),"Akses penuh: Transaksi Kasir, Update Status Pesanan, Hapus Transaksi, Cetak Struk, Manajemen Produk & Kategori, Pembukuan Kas & Laba Rugi, serta Pengaturan Toko, Branding, dan QRIS."

Karyawan / Kasir,"Akses operasional: Layar POS Kasir, Akses Daftar Antrean Pesanan, Pembaruan Status Kerja (processing → completed), dan Cetak Struk Belanja."

📄 Lisensi
Proyek ini dilisensikan di bawah MIT License. Bebas digunakan, dimodifikasi, dan didistribusikan untuk mendukung digitalisasi UMKM.