@extends('layouts.app')

@section('title', 'Data Pelanggan')
@section('page_title', 'Manajemen Data Pelanggan (Member)')
@section('page_subtitle', 'Kelola buku data pelanggan setia laundry Anda')

@section('content')
<div class="space-y-6">
    
    <!-- Header Action & Filters -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('member.index') }}" class="flex items-center gap-3 flex-1 flex-wrap">
            <div class="relative min-w-[200px] flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, no tlp/wa, atau alamat..."
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
            </div>

            <select name="gender" class="bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                <option value="">-- Semua Gender --</option>
                <option value="L" {{ $gender === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $gender === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>

            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-all">
                Cari
            </button>
            @if($search || $gender)
                <a href="{{ route('member.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('member.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pelanggan Baru</span>
        </a>
    </div>

    <!-- Member Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">Gender</th>
                        <th class="px-6 py-4">No. Telepon / WA</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4 text-center">Total Order</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($members as $index => $m)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-xs font-semibold text-slate-400">
                                {{ $members->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $m->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }} flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($m->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900">{{ $m->nama }}</h4>
                                        <span class="text-xs text-slate-400">ID: #MBR-{{ str_pad($m->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($m->jenis_kelamin === 'L')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fa-solid fa-mars mr-1"></i> Laki-laki
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-pink-50 text-pink-700 border border-pink-200">
                                        <i class="fa-solid fa-venus mr-1"></i> Perempuan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-mono font-medium text-slate-800">
                                <div class="flex items-center gap-2">
                                    <span>{{ $m->tlp }}</span>
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $m->tlp);
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 text-sm" title="Chat WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600 max-w-xs truncate">
                                {{ $m->alamat }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $m->transaksi_count }} Trx
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('member.edit', $m->id) }}" class="p-2 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all text-xs font-semibold" title="Edit Pelanggan">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('member.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelanggan {{ $m->nama }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-xs font-semibold" title="Hapus Pelanggan">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-users-slash text-4xl mb-3 block"></i>
                                <span>Tidak ada data pelanggan yang ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
