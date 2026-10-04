<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $outlets = Outlet::withCount(['users', 'paket', 'transaksi'])
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                             ->orWhere('alamat', 'like', "%{$search}%")
                             ->orWhere('tlp', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('outlet.index', compact('outlets', 'search'));
    }

    public function create()
    {
        return view('outlet.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'tlp' => 'required|string|max:15',
        ]);

        Outlet::create($validated);

        return redirect()->route('outlet.index')->with('success', 'Outlet baru berhasil ditambahkan!');
    }

    public function edit(Outlet $outlet)
    {
        return view('outlet.edit', compact('outlet'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'tlp' => 'required|string|max:15',
        ]);

        $outlet->update($validated);

        return redirect()->route('outlet.index')->with('success', 'Data outlet berhasil diperbarui!');
    }

    public function destroy(Outlet $outlet)
    {
        if ($outlet->transaksi()->exists() || $outlet->users()->exists() || $outlet->paket()->exists()) {
            return back()->withErrors(['error' => 'Outlet "' . $outlet->nama . '" tidak dapat dihapus karena masih memiliki relasi data pengguna, paket, atau riwayat transaksi.']);
        }

        try {
            $outlet->delete();
            return redirect()->route('outlet.index')->with('success', 'Data outlet "' . $outlet->nama . '" berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus outlet karena masih terhubung dengan data lain.']);
        }
    }
}
