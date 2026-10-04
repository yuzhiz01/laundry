@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page_title', 'Manajemen Pengguna & Pegawai')
@section('page_subtitle', 'Kelola akun Admin, Kasir, dan Owner sistem')

@section('content')
<div class="space-y-6">
    
    <!-- Header Action & Filters -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('user.index') }}" class="flex items-center gap-3 flex-1 flex-wrap">
            <div class="relative min-w-[200px] flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau username..."
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
            </div>

            <select name="role" class="bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                <option value="">-- Semua Role --</option>
                <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="kasir" {{ $role === 'kasir' ? 'selected' : '' }}>Kasir</option>
                <option value="owner" {{ $role === 'owner' ? 'selected' : '' }}>Owner</option>
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
            @if($search || $role || $outletId)
                <a href="{{ route('user.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('user.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    <!-- User Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Peran (Role)</th>
                        <th class="px-6 py-4">Outlet Penugasan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $index => $u)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-xs font-semibold text-slate-400">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($u->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900">{{ $u->nama }}</h4>
                                        @if(auth()->id() === $u->id)
                                            <span class="inline-flex items-center text-[10px] text-indigo-600 font-bold bg-indigo-50 px-1.5 py-0.5 rounded">Akun Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono font-medium text-slate-700">
                                {{ $u->username }}
                            </td>
                            <td class="px-6 py-4">
                                @if($u->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                        <i class="fa-solid fa-user-shield text-[10px] mr-1.5"></i> Admin
                                    </span>
                                @elseif($u->role === 'kasir')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-cash-register text-[10px] mr-1.5"></i> Kasir
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        <i class="fa-solid fa-crown text-[10px] mr-1.5"></i> Owner
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($u->outlet)
                                    <span class="font-medium text-slate-800 flex items-center gap-1.5">
                                        <i class="fa-solid fa-store text-indigo-500 text-[11px]"></i>
                                        {{ $u->outlet->nama }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Semua Outlet (Pusat)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('user.edit', $u->id) }}" class="p-2 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all text-xs font-semibold" title="Edit Pengguna">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    @if(auth()->id() !== $u->id)
                                        <form action="{{ route('user.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $u->nama }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-xs font-semibold" title="Hapus Pengguna">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-users-slash text-4xl mb-3 block"></i>
                                <span>Tidak ada pengguna yang ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
