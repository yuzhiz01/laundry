@extends('layouts.app')

@section('title', 'Kelola Outlet')
@section('page_title', 'Manajemen Outlet Cabang')
@section('page_subtitle', 'Daftar seluruh outlet dan cabang operasional laundry')

@section('content')
<div class="space-y-6">
    
    <!-- Header Action & Search Filter -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('outlet.index') }}" class="flex items-center gap-3 flex-1 max-w-md">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama outlet, alamat, atau no tlp..."
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-all">
                Cari
            </button>
            @if($search)
                <a href="{{ route('outlet.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('outlet.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Outlet Baru</span>
        </a>
    </div>

    <!-- Outlet Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Nama Outlet</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4">No. Telepon</th>
                        <th class="px-6 py-4 text-center">Statistik</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($outlets as $index => $outlet)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-xs font-semibold text-slate-400">
                                {{ $outlets->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-base">
                                        <i class="fa-solid fa-store"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900">{{ $outlet->nama }}</h4>
                                        <span class="text-xs text-slate-400">ID: #{{ $outlet->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600 max-w-xs truncate">
                                {{ $outlet->alamat }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono font-medium text-slate-700">
                                <i class="fa-solid fa-phone text-slate-400 mr-1 text-[11px]"></i>
                                {{ $outlet->tlp }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-2 text-xs">
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-semibold border border-purple-200" title="Jumlah Pegawai">
                                        <i class="fa-solid fa-user-tie mr-1"></i>{{ $outlet->users_count }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-semibold border border-blue-200" title="Jumlah Paket">
                                        <i class="fa-solid fa-box mr-1"></i>{{ $outlet->paket_count }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200" title="Jumlah Transaksi">
                                        <i class="fa-solid fa-receipt mr-1"></i>{{ $outlet->transaksi_count }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('outlet.edit', $outlet->id) }}" class="p-2 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all text-xs font-semibold" title="Edit Outlet">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('outlet.destroy', $outlet->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus outlet {{ $outlet->nama }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-xs font-semibold" title="Hapus Outlet">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-store-slash text-4xl mb-3 block"></i>
                                <span>Tidak ada data outlet yang ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($outlets->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $outlets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
