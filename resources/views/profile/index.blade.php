@extends('layouts.app')

@section('title', 'Profil & Pengaturan Akun')
@section('page_title', 'Profil Pengguna')
@section('page_subtitle', 'Kelola informasi pribadi dan keamanan kata sandi akun Anda')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Profile Card Summary -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm flex items-center gap-5">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-700 text-white flex items-center justify-center font-black text-2xl shadow-lg shadow-indigo-600/30">
            {{ strtoupper(substr($user->nama, 0, 2)) }}
        </div>
        <div>
            <h3 class="text-xl font-extrabold text-slate-900">{{ $user->nama }}</h3>
            <p class="text-xs text-slate-500 font-mono">@<span>{{ $user->username }}</span></p>
            <div class="flex items-center gap-2 mt-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                    {{ $user->role === 'kasir' ? 'bg-emerald-100 text-emerald-800' : '' }}
                    {{ $user->role === 'owner' ? 'bg-amber-100 text-amber-800' : '' }}">
                    Role: {{ $user->role }}
                </span>
                @if($user->outlet)
                    <span class="text-xs text-slate-500 flex items-center gap-1">
                        <i class="fa-solid fa-store text-indigo-500"></i>
                        {{ $user->outlet->nama }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Profile & Password Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <h4 class="font-bold text-slate-800 text-sm border-b border-slate-100 pb-2 mb-4">Informasi Akun</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Username <span class="text-rose-500">*</span></label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-slate-800 text-sm border-b border-slate-100 pb-2 mb-4">
                    Ubah Password <span class="text-xs font-normal text-slate-400">(Biarkan kosong jika tidak ingin mengganti)</span>
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" placeholder="••••••••"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password Baru</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Minimal 6 karakter"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ulangi password baru"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Profil</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
