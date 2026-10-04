<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', Carbon::now()->format('Y-m-d'));
        $outletId = $request->query('outlet_id');
        $status = $request->query('status');
        $dibayar = $request->query('dibayar');

        $user = Auth::user();

        $query = Transaksi::with(['outlet', 'member', 'user', 'detailTransaksi.paket'])
            ->whereBetween('tgl', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        if ($user->role === 'kasir' && $user->id_outlet) {
            $query->where('id_outlet', $user->id_outlet);
        } elseif ($outletId) {
            $query->where('id_outlet', $outletId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dibayar) {
            $query->where('dibayar', $dibayar);
        }

        $transaksis = $query->latest('tgl')->get();

        // Ringkasan Laporan
        $totalTransaksi = $transaksis->count();
        $totalPendapatan = $transaksis->where('dibayar', 'dibayar')->sum(function ($t) {
            return $t->total_bayar;
        });
        $totalPiutang = $transaksis->where('dibayar', 'belum_dibayar')->sum(function ($t) {
            return $t->total_bayar;
        });

        $outlets = Outlet::all();

        return view('laporan.index', compact(
            'transaksis', 'outlets', 'startDate', 'endDate', 'outletId', 'status', 'dibayar',
            'totalTransaksi', 'totalPendapatan', 'totalPiutang'
        ));
    }

    public function cetak(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', Carbon::now()->format('Y-m-d'));
        $outletId = $request->query('outlet_id');
        $status = $request->query('status');
        $dibayar = $request->query('dibayar');

        $user = Auth::user();

        $query = Transaksi::with(['outlet', 'member', 'user', 'detailTransaksi.paket'])
            ->whereBetween('tgl', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        if ($user->role === 'kasir' && $user->id_outlet) {
            $query->where('id_outlet', $user->id_outlet);
        } elseif ($outletId) {
            $query->where('id_outlet', $outletId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dibayar) {
            $query->where('dibayar', $dibayar);
        }

        $transaksis = $query->oldest('tgl')->get();

        $totalTransaksi = $transaksis->count();
        $totalPendapatan = $transaksis->where('dibayar', 'dibayar')->sum(function ($t) {
            return $t->total_bayar;
        });
        $totalPiutang = $transaksis->where('dibayar', 'belum_dibayar')->sum(function ($t) {
            return $t->total_bayar;
        });

        $selectedOutlet = $outletId ? Outlet::find($outletId) : null;

        return view('laporan.cetak', compact(
            'transaksis', 'startDate', 'endDate', 'selectedOutlet', 'status', 'dibayar',
            'totalTransaksi', 'totalPendapatan', 'totalPiutang'
        ));
    }
}
