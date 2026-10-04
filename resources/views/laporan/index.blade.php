@extends('layouts.app')

@section('title', 'Laporan Keuangan & Transaksi')
@section('page_title', 'Laporan Transaksi Laundry')
@section('page_subtitle', 'Rekapitulasi keuangan, omset, dan seluruh transaksi dalam rentang periode')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
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
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pembayaran</label>
                <select name="dibayar" class="w-full bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                    <option value="">Semua</option>
                    <option value="dibayar" {{ $dibayar === 'dibayar' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum_dibayar" {{ $dibayar === 'belum_dibayar' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2 px-3 rounded-xl text-xs shadow-sm transition-all flex items-center justify-center gap-1">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('laporan.cetak', request()->all()) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-3 rounded-xl text-xs shadow-md shadow-indigo-600/20 transition-all flex items-center justify-center gap-1">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Financial Recap Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Transaksi</p>
            <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalTransaksi }} <span class="text-xs font-normal text-slate-500">Transaksi</span></h3>
            <p class="text-[11px] text-slate-400 mt-1">Periode {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Pemasukan (Lunas)</p>
            <h3 class="text-2xl font-extrabold text-emerald-600 font-mono mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Uang tunai / transfer masuk</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Piutang (Belum Lunas)</p>
            <h3 class="text-2xl font-extrabold text-rose-600 font-mono mt-1">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Menunggu pembayaran saat ambil</p>
        </div>
    </div>

    <!-- Detailed Transactions Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-base">Rincian Data Laporan</h4>
            <span class="text-xs font-semibold text-slate-500">{{ $transaksis->count() }} Data Ditemukan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Kode Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Outlet</th>
                        <th class="px-6 py-4">Tgl Masuk</th>
                        <th class="px-6 py-4">Status Cucian</th>
                        <th class="px-6 py-4">Status Bayar</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksis as $index => $trx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-xs font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600 text-xs">
                                <a href="{{ route('transaksi.show', $trx->id) }}" class="hover:underline">
                                    {{ $trx->kode_invoice }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $trx->member->nama ?? 'Umum' }}</p>
                                <p class="text-[11px] text-slate-400">{{ $trx->member->tlp ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $trx->outlet->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $trx->tgl->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="capitalize text-xs font-semibold px-2 py-0.5 rounded-full
                                    {{ $trx->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $trx->status === 'proses' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $trx->status === 'baru' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $trx->status === 'diambil' ? 'bg-slate-100 text-slate-800' : '' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($trx->dibayar === 'dibayar')
                                    <span class="inline-flex items-center text-xs font-bold text-emerald-600">
                                        <i class="fa-solid fa-circle-check mr-1"></i> Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs font-bold text-rose-600">
                                        <i class="fa-solid fa-clock mr-1"></i> Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-900 text-right">
                                Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-file-excel text-4xl mb-3 block"></i>
                                <span>Tidak ada transaksi pada filter periode ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
