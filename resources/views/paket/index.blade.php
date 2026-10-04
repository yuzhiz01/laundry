@extends('layouts.app')

@section('title', 'Paket Laundry')
@section('page_title', 'Daftar Paket Laundry')
@section('page_subtitle', 'Kelola daftar jenis paket dan tarif harga per outlet')

@section('content')
<div class="space-y-6">
    
    <!-- Header Action & Filters -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('paket.index') }}" class="flex items-center gap-3 flex-1 flex-wrap">
            <div class="relative min-w-[200px] flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama paket laundry..."
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
            </div>

            <select name="jenis" class="bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                <option value="">-- Semua Jenis --</option>
                <option value="kiloan" {{ $jenis === 'kiloan' ? 'selected' : '' }}>Kiloan</option>
                <option value="selimut" {{ $jenis === 'selimut' ? 'selected' : '' }}>Selimut</option>
                <option value="bed_cover" {{ $jenis === 'bed_cover' ? 'selected' : '' }}>Bed Cover</option>
                <option value="kaos" {{ $jenis === 'kaos' ? 'selected' : '' }}>Kaos</option>
                <option value="lain" {{ $jenis === 'lain' ? 'selected' : '' }}>Lain-lain</option>
            </select>

            <select name="outlet_id" class="bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                <option value="">-- Semua Outlet --</option>
                @foreach($outlets as $out)
                    <option value="{{ $out->id }}" {{ $outletId == $out->id ? 'selected' : '' }}>{{ $out->nama }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-all">
                Filter
            </button>
            @if($search || $jenis || $outletId)
                <a href="{{ route('paket.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('paket.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Paket Baru</span>
        </a>
    </div>

    <!-- Paket Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Nama Paket</th>
                        <th class="px-6 py-4">Jenis Cucian</th>
                        <th class="px-6 py-4">Tarif Harga</th>
                        <th class="px-6 py-4">Outlet</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pakets as $index => $pkt)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-xs font-semibold text-slate-400">
                                {{ $pakets->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $pkt->nama_paket }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $pkt->jenis === 'kiloan' ? 'bg-cyan-50 text-cyan-700 border border-cyan-200' : '' }}
                                    {{ $pkt->jenis === 'bed_cover' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                    {{ $pkt->jenis === 'selimut' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : '' }}
                                    {{ $pkt->jenis === 'kaos' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                    {{ $pkt->jenis === 'lain' ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}">
                                    <i class="fa-solid fa-tag text-[9px] mr-1.5 opacity-70"></i>
                                    {{ ucfirst(str_replace('_', ' ', $pkt->jenis)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-indigo-600 font-mono">
                                Rp {{ number_format($pkt->harga, 0, ',', '.') }}
                                <span class="text-[11px] text-slate-400 font-normal">/ {{ $pkt->jenis === 'kiloan' ? 'Kg' : 'Pcs' }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-700">
                                <i class="fa-solid fa-store text-slate-400 mr-1 text-[11px]"></i>
                                {{ $pkt->outlet->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('paket.edit', $pkt->id) }}" class="p-2 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all text-xs font-semibold" title="Edit Paket">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('paket.destroy', $pkt->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus paket {{ $pkt->nama_paket }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-xs font-semibold" title="Hapus Paket">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                <span>Tidak ada data paket yang ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pakets->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $pakets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
