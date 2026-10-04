<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Outlet;
use App\Models\Member;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $dibayar = $request->query('dibayar');
        $outletId = $request->query('outlet_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $user = Auth::user();

        $query = Transaksi::with(['outlet', 'member', 'user', 'detailTransaksi.paket']);

        // Jika kasir memiliki outlet, filter ke outletnya
        if ($user->role === 'kasir' && $user->id_outlet) {
            $query->where('id_outlet', $user->id_outlet);
        } elseif ($outletId) {
            $query->where('id_outlet', $outletId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_invoice', 'like', "%{$search}%")
                  ->orWhereHas('member', function ($mq) use ($search) {
                      $mq->where('nama', 'like', "%{$search}%")
                         ->orWhere('tlp', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dibayar) {
            $query->where('dibayar', $dibayar);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tgl', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $transaksis = $query->latest('tgl')->paginate(10)->withQueryString();
        $outlets = Outlet::all();

        return view('transaksi.index', compact(
            'transaksis', 'outlets', 'search', 'status', 'dibayar', 'outletId', 'startDate', 'endDate'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        $outlets = Outlet::all();
        $members = Member::orderBy('nama', 'asc')->get();
        
        // Default outlet
        $selectedOutletId = $user->id_outlet ?? ($outlets->first()->id ?? null);
        $pakets = Paket::where('id_outlet', $selectedOutletId)->get();

        // Generate invoice code
        $todayStr = Carbon::now()->format('Ymd');
        $countToday = Transaksi::whereDate('created_at', Carbon::today())->count() + 1;
        $suggestedInvoice = 'INV/' . $todayStr . '/' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

        return view('transaksi.create', compact('outlets', 'members', 'pakets', 'selectedOutletId', 'suggestedInvoice'));
    }

    public function getPaketByOutlet($outletId)
    {
        $pakets = Paket::where('id_outlet', $outletId)->get();
        return response()->json($pakets);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_outlet' => 'required|exists:tb_outlet,id',
            'id_member' => 'required_without:new_member_nama|nullable|exists:tb_member,id',
            'new_member_nama' => 'nullable|string|max:100',
            'new_member_tlp' => 'nullable|string|max:15',
            'new_member_alamat' => 'nullable|string',
            'new_member_gender' => 'nullable|in:L,P',
            'batas_waktu' => 'required|date|after_or_equal:now',
            'biaya_tambahan' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'pajak' => 'nullable|numeric|min:0',
            'dibayar' => 'required|in:dibayar,belum_dibayar',
            'items' => 'required|array|min:1',
            'items.*.id_paket' => 'required|exists:tb_paket,id',
            'items.*.qty' => 'required|numeric|min:0.1',
            'items.*.keterangan' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            // Handle new member creation if provided
            $memberId = $validated['id_member'];
            if (empty($memberId) && !empty($request->new_member_nama)) {
                $newMember = Member::create([
                    'nama' => $request->new_member_nama,
                    'tlp' => $request->new_member_tlp ?? '-',
                    'alamat' => $request->new_member_alamat ?? '-',
                    'jenis_kelamin' => $request->new_member_gender ?? 'L',
                ]);
                $memberId = $newMember->id;
            }

            // Generate Kode Invoice yang unik
            $todayStr = Carbon::now()->format('Ymd');
            $countToday = Transaksi::whereDate('created_at', Carbon::today())->count() + 1;
            $kodeInvoice = 'INV/' . $todayStr . '/' . str_pad($countToday, 4, '0', STR_PAD_LEFT);
            while (Transaksi::where('kode_invoice', $kodeInvoice)->exists()) {
                $countToday++;
                $kodeInvoice = 'INV/' . $todayStr . '/' . str_pad($countToday, 4, '0', STR_PAD_LEFT);
            }

            $now = Carbon::now();
            $tglBayar = ($validated['dibayar'] === 'dibayar') ? $now : null;

            $transaksi = Transaksi::create([
                'id_outlet' => $validated['id_outlet'],
                'kode_invoice' => $kodeInvoice,
                'id_member' => $memberId,
                'tgl' => $now,
                'batas_waktu' => Carbon::parse($validated['batas_waktu']),
                'tgl_bayar' => $tglBayar,
                'biaya_tambahan' => $validated['biaya_tambahan'] ?? 0,
                'diskon' => $validated['diskon'] ?? 0,
                'pajak' => $validated['pajak'] ?? 0,
                'status' => 'baru',
                'dibayar' => $validated['dibayar'],
                'id_user' => Auth::id(),
            ]);

            foreach ($validated['items'] as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_paket' => $item['id_paket'],
                    'qty' => $item['qty'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }

            return redirect()->route('transaksi.show', $transaksi->id)
                ->with('success', 'Transaksi ' . $transaksi->kode_invoice . ' berhasil dibuat!');
        });
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['outlet', 'member', 'user', 'detailTransaksi.paket']);
        return view('transaksi.show', compact('transaksi'));
    }

    public function updateStatus(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,proses,selesai,diambil',
        ]);

        $transaksi->status = $validated['status'];
        $transaksi->save();

        return back()->with('success', 'Status cucian berhasil diubah menjadi: ' . ucfirst($validated['status']));
    }

    public function updateBayar(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'dibayar' => 'required|in:dibayar,belum_dibayar',
            'tgl_bayar' => 'nullable|date',
        ]);

        $transaksi->dibayar = $validated['dibayar'];
        if ($validated['dibayar'] === 'dibayar') {
            $transaksi->tgl_bayar = $validated['tgl_bayar'] ? Carbon::parse($validated['tgl_bayar']) : Carbon::now();
        } else {
            $transaksi->tgl_bayar = null;
        }

        $transaksi->save();

        return back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    public function cetakInvoice(Transaksi $transaksi)
    {
        $transaksi->load(['outlet', 'member', 'user', 'detailTransaksi.paket']);
        return view('transaksi.cetak_invoice', compact('transaksi'));
    }

    public function cetakStruk(Transaksi $transaksi)
    {
        $transaksi->load(['outlet', 'member', 'user', 'detailTransaksi.paket']);
        return view('transaksi.cetak_struk', compact('transaksi'));
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
