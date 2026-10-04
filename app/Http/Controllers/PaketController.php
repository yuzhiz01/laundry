<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Outlet;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $jenis = $request->query('jenis');
        $outletId = $request->query('outlet_id');

        $pakets = Paket::with('outlet')
            ->when($search, function ($query, $search) {
                return $query->where('nama_paket', 'like', "%{$search}%");
            })
            ->when($jenis, function ($query, $jenis) {
                return $query->where('jenis', $jenis);
            })
            ->when($outletId, function ($query, $outletId) {
                return $query->where('id_outlet', $outletId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $outlets = Outlet::all();

        return view('paket.index', compact('pakets', 'outlets', 'search', 'jenis', 'outletId'));
    }

    public function create()
    {
        $outlets = Outlet::all();
        return view('paket.create', compact('outlets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_outlet' => 'required|exists:tb_outlet,id',
            'jenis' => 'required|in:kiloan,selimut,bed_cover,kaos,lain',
            'nama_paket' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
        ]);

        Paket::create($validated);

        return redirect()->route('paket.index')->with('success', 'Paket laundry berhasil ditambahkan!');
    }

    public function edit(Paket $paket)
    {
        $outlets = Outlet::all();
        return view('paket.edit', compact('paket', 'outlets'));
    }

    public function update(Request $request, Paket $paket)
    {
        $validated = $request->validate([
            'id_outlet' => 'required|exists:tb_outlet,id',
            'jenis' => 'required|in:kiloan,selimut,bed_cover,kaos,lain',
            'nama_paket' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
        ]);

        $paket->update($validated);

        return redirect()->route('paket.index')->with('success', 'Paket laundry berhasil diperbarui!');
    }

    public function destroy(Paket $paket)
    {
        if ($paket->detailTransaksi()->exists()) {
            return back()->withErrors(['error' => 'Paket "' . $paket->nama_paket . '" tidak dapat dihapus karena tercatat dalam ' . $paket->detailTransaksi()->count() . ' riwayat transaksi laundry.']);
        }

        try {
            $paket->delete();
            return redirect()->route('paket.index')->with('success', 'Paket laundry "' . $paket->nama_paket . '" berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus paket karena masih terhubung dengan data lain.']);
        }
    }
}
