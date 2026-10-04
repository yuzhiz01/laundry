# 🧺 Laundry - Sistem Manajemen & Pengelolaan Laundry (Laravel)

Sistem Informasi Pengelolaan Laundry berbasis **Laravel** yang siap pakai (*production ready*), lengkap dengan antarmuka modern, interaktif, dan responsif. Cocok digunakan untuk operasional bisnis laundry nyata maupun Uji Kompetensi Keahlian (UKK).

---

## 🌟 Fitur Utama

### 1. 🔐 Multi-Role Authentication & Hak Akses
* **Admin**: Akses penuh (Manajemen Outlet, User/Pegawai, Paket Laundry, Data Pelanggan, Entri Transaksi POS, Pelunasan, Cetak Struk/Invoice, Laporan Keuangan).
* **Kasir**: Operasional transaksi harian (Pendaftaran Pelanggan, Entri Transaksi Baru POS, Pelunasan/Bayar, Update Status Cucian, Cetak Struk & Invoice, Notifikasi WhatsApp).
* **Owner**: Monitoring bisnis (Dashboard Statistik & Grafik Omset 7 Hari Terakhir, Distribusi Status Cucian, Rekap & Cetak Laporan Keuangan).

### 2. 🏬 Manajemen Data Master (CRUD Lengkap)
* **Manajemen Outlet**: Tambah, edit, dan pantau outlet/cabang laundry.
* **Manajemen Pengguna**: Kelola akun pegawai dengan penetapan outlet dan role.
* **Manajemen Paket Cucian**: Pilihan jenis kiloan, selimut, bed cover, kaos, dan lain-lain dengan tarif harga fleksibel.
* **Manajemen Pelanggan (Member)**: Buku data pelanggan dengan tautan langsung kirim pesan WhatsApp.

### 3. 💳 Modul Transaksi & Kasir (Point of Sale)
* **Input Multi-Item**: Pemilihan beberapa paket cucian sekaligus dengan input kuantitas dan catatan khusus per item.
* **Kalkulasi Otomatis Realtime**: Subtotal, diskon (%), pajak, dan biaya tambahan (antar-jemput/ekspres).
* **Generator Kode Invoice**: Format otomatis `INV/YYYYMMDD/XXXX`.
* **Pelacakan Status Cucian**: `Baru` ➔ `Proses` ➔ `Selesai` ➔ `Diambil`.
* **Status Pembayaran**: `Dibayar (Lunas)` & `Belum Dibayar` beserta pencatatan tanggal pelunasan.
* **Cetak Struk Kasir Thermal (58mm/80mm)** dan **Invoice Formal (A4)**.
* **Kirim Notifikasi WhatsApp Otomatis** ke nomor HP pelanggan dengan pesan dinamis.

### 4. 📊 Dashboard Analitik & Laporan
* **Widget Statistik**: Total Pendapatan Lunas, Antrean Cucian Proses, Cucian Selesai, dan Total Member.
* **Grafik Interaktif Chart.js**: Tren omset 7 hari terakhir dan persentase pengerjaan order.
* **Laporan Keuangan & Transaksi**: Filter rentang tanggal, outlet, status cucian, dan status pembayaran dengan format cetak dokumen bertanda tangan.

---

## 🔑 Akun Demo Siap Pakai

Tersedia tombol **Autofill Demo** di halaman login untuk masuk secara instan:

| Peran (Role) | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `password` | Akses penuh ke seluruh fitur & master |
| **Kasir** | `kasir` | `password` | Khusus transaksi, pelanggan & struk |
| **Owner** | `owner` | `password` | Khusus dashboard omset & cetak laporan |

---

## 🚀 Cara Menjalankan Aplikasi

1. **Buka Terminal / Command Prompt** di direktori proyek:
   ```bash
   cd d:\xamppp\htdocs\pengelolaan_laundry
   ```

2. **Nyalakan Server Laravel**:
   ```bash
   php artisan serve
   ```

3. **Buka di Browser**:
   Akses [http://127.0.0.1:8000](http://127.0.0.1:8000) atau [http://localhost:8000](http://localhost:8000).

---

## 📁 Struktur Database (`db_laundry`)
* `tb_outlet` (id, nama, alamat, tlp, timestamps)
* `tb_user` (id, nama, username, password, id_outlet, role, timestamps)
* `tb_paket` (id, id_outlet, jenis, nama_paket, harga, timestamps)
* `tb_member` (id, nama, alamat, jenis_kelamin, tlp, timestamps)
* `tb_transaksi` (id, id_outlet, kode_invoice, id_member, tgl, batas_waktu, tgl_bayar, biaya_tambahan, diskon, pajak, status, dibayar, id_user, timestamps)
* `tb_detail_transaksi` (id, id_transaksi, id_paket, qty, keterangan, timestamps)
