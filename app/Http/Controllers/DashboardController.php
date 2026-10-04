<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\Member;
use App\Models\Outlet;
use App\Models\User;
use App\Models\Paket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $queryTransaksi = Transaksi::with(['member', 'outlet', 'user', 'detailTransaksi.paket']);

        // Jika kasir, filter transaksi outletnya (opsional atau tampilkan seluruh/outlet)
        if ($user->role === 'kasir' && $user->id_outlet) {
            $queryTransaksi->where('id_outlet', $user->id_outlet);
        }

        $totalTransaksi = (clone $queryTransaksi)->count();
        $transaksiBaru = (clone $queryTransaksi)->where('status', 'baru')->count();
        $transaksiProses = (clone $queryTransaksi)->where('status', 'proses')->count();
        $transaksiSelesai = (clone $queryTransaksi)->where('status', 'selesai')->count();
        $transaksiDiambil = (clone $queryTransaksi)->where('status', 'diambil')->count();

        // Hitung total pendapatan dari transaksi yang sudah dibayar
        $semuaTransaksiDibayar = (clone $queryTransaksi)->where('dibayar', 'dibayar')->get();
        $totalPendapatan = $semuaTransaksiDibayar->sum(function ($t) {
            return $t->total_bayar;
        });

        $totalMember = Member::count();
        $totalOutlet = Outlet::count();
        $totalPaket = Paket::count();
        $totalUser = User::count();

        // Data Grafik Pendapatan 7 Hari Terakhir
        $chartDates = [];
        $chartIncomes = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->translatedFormat('d M');

            $incomeDay = Transaksi::with('detailTransaksi.paket')
                ->whereDate('tgl_bayar', $date)
                ->where('dibayar', 'dibayar')
                ->when($user->role === 'kasir' && $user->id_outlet, function ($q) use ($user) {
                    return $q->where('id_outlet', $user->id_outlet);
                })
                ->get()
                ->sum(function ($t) {
                    return $t->total_bayar;
                });

            $chartIncomes[] = $incomeDay;
        }

        // Transaksi Terbaru (5 transaksi terakhir)
        $recentTransaksi = (clone $queryTransaksi)->latest('tgl')->take(6)->get();

        return view('dashboard.index', compact(
            'totalTransaksi',
            'transaksiBaru',
            'transaksiProses',
            'transaksiSelesai',
            'transaksiDiambil',
            'totalPendapatan',
            'totalMember',
            'totalOutlet',
            'totalPaket',
            'totalUser',
            'chartDates',
            'chartIncomes',
            'recentTransaksi'
        ));
    }
}
