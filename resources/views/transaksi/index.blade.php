@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi Laundry')
@section('page_subtitle', 'Pantau status cucian dan status pembayaran seluruh transaksi')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <form method="GET" action="{{ route('transaksi.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cari Invoice / Pelanggan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Kode Invoice / Nama / No WA..."
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-9 pr-3 py-2 text-xs focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Cucian</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ $status === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="proses" {{ $status === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="diambil" {{ $status === 'diambil' ? 'selected' : '' }}>Diambil</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Pembayaran</label>
                <select name="dibayar" class="w-full bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Bayar</option>
                    <option value="dibayar" {{ $dibayar === 'dibayar' ? 'selected' : '' }}>Lunas (Dibayar)</option>
                    <option value="belum_dibayar" {{ $dibayar === 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                </select>
            </div>

            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner')
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Outlet</label>
                <select name="outlet_id" class="w-full bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Outlet</option>
                    @foreach($outlets as $out)
                        <option value="{{ $out->id }}" {{ $outletId == $out->id ? 'selected' : '' }}>{{ $out->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="flex items-end gap-2 {{ (Auth::user()->role === 'kasir') ? 'lg:col-span-2' : '' }}">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-3 rounded-xl text-xs shadow-sm transition-all flex items-center justify-center gap-1">
                    <i class="fa-solid fa-filter"></i>
                    <span>Terapkan</span>
                </button>
                @if($search || $status || $dibayar || $outletId || $startDate)
                    <a href="{{ route('transaksi.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Transaksi Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Kode Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Tgl Masuk / Batas Waktu</th>
                        <th class="px-6 py-4">Status Cucian</th>
                        <th class="px-6 py-4">Pembayaran</th>
                        <th class="px-6 py-4">Total Biaya</th>
                        <th class="px-6 py-4 text-center">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksis as $trx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('transaksi.show', $trx->id) }}" class="font-mono font-bold text-indigo-600 hover:text-indigo-800 text-xs flex items-center gap-1.5">
                                    <i class="fa-solid fa-barcode text-slate-400 text-sm"></i>
                                    <span>{{ $trx->kode_invoice }}</span>
                                </a>
                                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $trx->outlet->nama ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <h5 class="font-bold text-slate-900 text-sm">{{ $trx->member->nama ?? 'Pelanggan Umum' }}</h5>
                                <p class="text-xs text-slate-500 font-mono">{{ $trx->member->tlp ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="space-y-0.5">
                                    <p class="text-slate-700 font-medium">Masuk: {{ $trx->tgl->format('d/m/Y H:i') }}</p>
                                    <p class="text-slate-400">Batas: <span class="text-rose-600 font-semibold">{{ $trx->batas_waktu->format('d/m/Y H:i') }}</span></p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($trx->status === 'baru')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fa-solid fa-circle-dot text-[8px] mr-1.5 text-blue-500"></i> Baru
                                    </span>
                                @elseif($trx->status === 'proses')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-spinner animate-spin text-[8px] mr-1.5 text-amber-500"></i> Proses
                                    </span>
                                @elseif($trx->status === 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check text-[8px] mr-1.5 text-emerald-500"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                        <i class="fa-solid fa-hand-holding-box text-[8px] mr-1.5"></i> Diambil
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($trx->dibayar === 'dibayar')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-check-double text-[10px] mr-1"></i> Lunas
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">{{ $trx->tgl_bayar ? $trx->tgl_bayar->format('d/m/Y') : '' }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                        <i class="fa-solid fa-hourglass-half text-[10px] mr-1"></i> Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 font-mono">
                                Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('transaksi.show', $trx->id) }}" class="p-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all text-xs font-semibold" title="Detail & Kelola">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('transaksi.cetak_struk', $trx->id) }}" target="_blank" class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-700 hover:text-white transition-all text-xs font-semibold" title="Cetak Struk Kasir">
                                        <i class="fa-solid fa-receipt"></i>
                                    </a>
                                    <a href="{{ route('transaksi.cetak_invoice', $trx->id) }}" target="_blank" class="p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-700 hover:text-white transition-all text-xs font-semibold" title="Cetak Invoice Formal">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-receipt text-4xl mb-3 block"></i>
                                <span>Tidak ada transaksi yang cocok dengan filter.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksis->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
