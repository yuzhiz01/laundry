<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\Outlet;
use App\Models\Paket;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $outlets = Outlet::all();
        $pakets = Paket::with('outlet')->take(6)->get();

        return view('welcome', compact('outlets', 'pakets'));
    }

    public function track(Request $request)
    {
        $keyword = trim($request->query('invoice'));
        $transaksi = null;

        if (!empty($keyword)) {
            $transaksi = Transaksi::with(['outlet', 'member', 'detailTransaksi.paket'])
                ->where('kode_invoice', $keyword)
                ->orWhereHas('member', function ($q) use ($keyword) {
                    $q->where('tlp', $keyword);
                })
                ->latest('tgl')
                ->first();
        }

        return view('tracking', compact('transaksi', 'keyword'));
    }
}
