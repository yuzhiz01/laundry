<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;
use App\Models\User;
use App\Models\Paket;
use App\Models\Member;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Outlets
        $outletPusat = Outlet::create([
            'nama' => 'Laundry Cabang Utama',
            'alamat' => 'Jl. Merdeka Raya No. 45, Jakarta Selatan',
            'tlp' => '081234567890',
        ]);

        $outletCabang = Outlet::create([
            'nama' => 'Laundry Cabang Melati',
            'alamat' => 'Jl. Melati Indah No. 12, Jakarta Barat',
            'tlp' => '081298765432',
        ]);

        // 2. Seed Users
        $admin = User::create([
            'nama' => 'Administrator Laundry',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'id_outlet' => $outletPusat->id,
            'role' => 'admin',
        ]);

        $kasir = User::create([
            'nama' => 'Siti Nurhaliza (Kasir)',
            'username' => 'kasir',
            'password' => Hash::make('kasir123'),
            'id_outlet' => $outletPusat->id,
            'role' => 'kasir',
        ]);

        $owner = User::create([
            'nama' => 'Bapak Hendra (Owner)',
            'username' => 'owner',
            'password' => Hash::make('owner123'),
            'id_outlet' => $outletPusat->id,
            'role' => 'owner',
        ]);

        // 3. Seed Paket Laundry untuk Outlet Pusat
        $paket1 = Paket::create([
            'id_outlet' => $outletPusat->id,
            'jenis' => 'kiloan',
            'nama_paket' => 'Cuci Kering Lipat Reguler (2 Hari)',
            'harga' => 7000,
        ]);

        $paket2 = Paket::create([
            'id_outlet' => $outletPusat->id,
            'jenis' => 'kiloan',
            'nama_paket' => 'Cuci Komplit Setrika Wangi (1 Hari)',
            'harga' => 10000,
        ]);

        $paket3 = Paket::create([
            'id_outlet' => $outletPusat->id,
            'jenis' => 'bed_cover',
            'nama_paket' => 'Cuci Bed Cover King Size',
            'harga' => 35000,
        ]);

        $paket4 = Paket::create([
            'id_outlet' => $outletPusat->id,
            'jenis' => 'selimut',
            'nama_paket' => 'Cuci Selimut Tebal / Blanket',
            'harga' => 25000,
        ]);

        $paket5 = Paket::create([
            'id_outlet' => $outletPusat->id,
            'jenis' => 'kaos',
            'nama_paket' => 'Cuci Satuan Kaos / Kemeja Formal',
            'harga' => 15000,
        ]);

        $paket6 = Paket::create([
            'id_outlet' => $outletPusat->id,
            'jenis' => 'lain',
            'nama_paket' => 'Cuci Boneka / Karpet Sedang',
            'harga' => 30000,
        ]);

        // Seed Paket Laundry untuk Cabang Melati
        Paket::create([
            'id_outlet' => $outletCabang->id,
            'jenis' => 'kiloan',
            'nama_paket' => 'Cuci Komplit Express Cabang',
            'harga' => 9000,
        ]);

        Paket::create([
            'id_outlet' => $outletCabang->id,
            'jenis' => 'bed_cover',
            'nama_paket' => 'Cuci Bed Cover Cabang',
            'harga' => 30000,
        ]);

        // 4. Seed Members (Pelanggan)
        $member1 = Member::create([
            'nama' => 'Budi Santoso',
            'alamat' => 'Jl. Mawar No. 10, RT 02/05, Tebet',
            'jenis_kelamin' => 'L',
            'tlp' => '081311223344',
        ]);

        $member2 = Member::create([
            'nama' => 'Rina Wijaya',
            'alamat' => 'Apartemen Casablanca Tower B Lt. 8',
            'jenis_kelamin' => 'P',
            'tlp' => '085788990011',
        ]);

        $member3 = Member::create([
            'nama' => 'Ahmad Fauzi',
            'alamat' => 'Komplek Permata Hijau Blok C-4',
            'jenis_kelamin' => 'L',
            'tlp' => '087812345678',
        ]);

        $member4 = Member::create([
            'nama' => 'Dewi Lestari',
            'alamat' => 'Jl. Pejaten Barat No. 88, Pasar Minggu',
            'jenis_kelamin' => 'P',
            'tlp' => '082199887766',
        ]);

        // 5. Seed Transaksi Contoh
        // Transaksi 1: Selesai & Dibayar
        $trx1 = Transaksi::create([
            'id_outlet' => $outletPusat->id,
            'kode_invoice' => 'INV/' . Carbon::now()->subDays(3)->format('Ymd') . '/0001',
            'id_member' => $member1->id,
            'tgl' => Carbon::now()->subDays(3)->setTime(9, 30),
            'batas_waktu' => Carbon::now()->subDays(1)->setTime(17, 00),
            'tgl_bayar' => Carbon::now()->subDays(3)->setTime(9, 35),
            'biaya_tambahan' => 0,
            'diskon' => 0,
            'pajak' => 0,
            'status' => 'selesai',
            'dibayar' => 'dibayar',
            'id_user' => $kasir->id,
        ]);

        DetailTransaksi::create([
            'id_transaksi' => $trx1->id,
            'id_paket' => $paket2->id,
            'qty' => 4.5,
            'keterangan' => 'Pewangi Lavender, pisahkan baju putih',
        ]);

        DetailTransaksi::create([
            'id_transaksi' => $trx1->id,
            'id_paket' => $paket3->id,
            'qty' => 1,
            'keterangan' => 'Bed cover warna biru motif kotak',
        ]);

        // Transaksi 2: Proses & Belum Dibayar
        $trx2 = Transaksi::create([
            'id_outlet' => $outletPusat->id,
            'kode_invoice' => 'INV/' . Carbon::now()->subDays(1)->format('Ymd') . '/0002',
            'id_member' => $member2->id,
            'tgl' => Carbon::now()->subDays(1)->setTime(14, 15),
            'batas_waktu' => Carbon::now()->addDays(1)->setTime(16, 00),
            'tgl_bayar' => null,
            'biaya_tambahan' => 5000,
            'diskon' => 10,
            'pajak' => 2000,
            'status' => 'proses',
            'dibayar' => 'belum_dibayar',
            'id_user' => $kasir->id,
        ]);

        DetailTransaksi::create([
            'id_transaksi' => $trx2->id,
            'id_paket' => $paket1->id,
            'qty' => 6.0,
            'keterangan' => 'Cucian harian campur',
        ]);

        // Transaksi 3: Baru & Dibayar
        $trx3 = Transaksi::create([
            'id_outlet' => $outletPusat->id,
            'kode_invoice' => 'INV/' . Carbon::now()->format('Ymd') . '/0003',
            'id_member' => $member3->id,
            'tgl' => Carbon::now()->setTime(8, 45),
            'batas_waktu' => Carbon::now()->addDays(2)->setTime(17, 00),
            'tgl_bayar' => Carbon::now()->setTime(8, 50),
            'biaya_tambahan' => 0,
            'diskon' => 0,
            'pajak' => 0,
            'status' => 'baru',
            'dibayar' => 'dibayar',
            'id_user' => $admin->id,
        ]);

        DetailTransaksi::create([
            'id_transaksi' => $trx3->id,
            'id_paket' => $paket4->id,
            'qty' => 2,
            'keterangan' => 'Selimut bulu tebal 2 pcs',
        ]);

        DetailTransaksi::create([
            'id_transaksi' => $trx3->id,
            'id_paket' => $paket5->id,
            'qty' => 3,
            'keterangan' => 'Kemeja kerja putih dan batik',
        ]);
    }
}
