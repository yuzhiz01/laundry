@extends('layouts.app')

@section('title', 'Tambah Paket')
@section('page_title', 'Tambah Paket Laundry Baru')
@section('page_subtitle', 'Tentukan nama paket, jenis cucian, dan tarif per kilogram atau satuan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('paket.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="id_outlet" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Outlet Cabang <span class="text-rose-500">*</span></label>
                <select id="id_outlet" name="id_outlet" required
                        class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    <option value="">-- Pilih Outlet --</option>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ old('id_outlet') == $outlet->id ? 'selected' : '' }}>{{ $outlet->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="jenis" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jenis Cucian <span class="text-rose-500">*</span></label>
                    <select id="jenis" name="jenis" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                        <option value="kiloan" {{ old('jenis') === 'kiloan' ? 'selected' : '' }}>Kiloan (Kg)</option>
                        <option value="selimut" {{ old('jenis') === 'selimut' ? 'selected' : '' }}>Selimut</option>
                        <option value="bed_cover" {{ old('jenis') === 'bed_cover' ? 'selected' : '' }}>Bed Cover</option>
                        <option value="kaos" {{ old('jenis') === 'kaos' ? 'selected' : '' }}>Kaos / Satuan</option>
                        <option value="lain" {{ old('jenis') === 'lain' ? 'selected' : '' }}>Lain-lain</option>
                    </select>
                </div>

                <div>
                    <label for="harga" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tarif Harga (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" id="harga" name="harga" value="{{ old('harga') }}" required min="0" step="500" placeholder="Contoh: 10000"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            <div>
                <label for="nama_paket" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Paket <span class="text-rose-500">*</span></label>
                <input type="text" id="nama_paket" name="nama_paket" value="{{ old('nama_paket') }}" required placeholder="Contoh: Cuci Komplit Wangi 1 Hari"
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('paket.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Paket</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
