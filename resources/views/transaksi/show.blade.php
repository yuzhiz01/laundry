@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $transaksi->kode_invoice)
@section('page_title', 'Detail & Manajemen Transaksi')
@section('page_subtitle', 'Kelola status cucian, pembayaran, dan cetak invoice pelanggan')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action Toolbar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('transaksi.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all text-xs font-semibold" title="Kembali">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-mono font-bold text-indigo-600 uppercase tracking-wider">{{ $transaksi->kode_invoice }}</span>
                <h3 class="font-extrabold text-slate-900 text-lg">Pelanggan: {{ $transaksi->member->nama ?? 'Umum' }}</h3>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <!-- WhatsApp Notification Button -->
            @if($transaksi->member && $transaksi->member->tlp)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $transaksi->member->tlp);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                    $statusText = ucfirst($transaksi->status);
                    $msgText = "Halo Kak {$transaksi->member->nama}, pesanan laundry Anda di {$transaksi->outlet->nama} dengan nomor invoice *{$transaksi->kode_invoice}* saat ini berstatus: *{$statusText}*.\nTotal Biaya: Rp " . number_format($transaksi->total_bayar, 0, ',', '.') . " (" . ($transaksi->dibayar === 'dibayar' ? 'LUNAS' : 'BELUM LUNAS') . ").\nTerima kasih!";
                    $waLink = "https://wa.me/{$cleanPhone}?text=" . urlencode($msgText);
                @endphp
                <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3.5 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition-all">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Kirim WA Pelanggan</span>
                </a>
            @endif

            <!-- Print Thermal Receipt -->
            <a href="{{ route('transaksi.cetak_struk', $transaksi->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-3.5 py-2.5 rounded-xl shadow-sm transition-all">
                <i class="fa-solid fa-receipt text-sm"></i>
                <span>Cetak Struk Kasir</span>
            </a>

            <!-- Print Formal Invoice -->
            <a href="{{ route('transaksi.cetak_invoice', $transaksi->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-3.5 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all">
                <i class="fa-solid fa-file-invoice text-sm"></i>
                <span>Cetak Invoice A4</span>
            </a>
        </div>
    </div>

    <!-- Status Stepper Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Progress Status Pengerjaan Cucian</h4>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            
            <!-- Step 1: Baru -->
            <div class="p-4 rounded-2xl border {{ $transaksi->status === 'baru' ? 'bg-blue-50 border-blue-400 ring-2 ring-blue-400/20' : 'bg-slate-50 border-slate-200' }} transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-7 h-7 rounded-full {{ in_array($transaksi->status, ['baru', 'proses', 'selesai', 'diambil']) ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-xs font-bold">1</span>
                    <i class="fa-solid fa-circle-dot {{ $transaksi->status === 'baru' ? 'text-blue-600' : 'text-slate-300' }}"></i>
                </div>
                <h5 class="font-bold text-sm text-slate-800">Order Baru</h5>
                <p class="text-[11px] text-slate-500 mt-0.5">Cucian baru diterima kasir</p>
                @if($transaksi->status !== 'baru')
                    <form action="{{ route('transaksi.update_status', $transaksi->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="baru">
                        <button type="submit" class="w-full text-center text-[10px] font-bold py-1 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-100">Set Baru</button>
                    </form>
                @endif
            </div>

            <!-- Step 2: Proses -->
            <div class="p-4 rounded-2xl border {{ $transaksi->status === 'proses' ? 'bg-amber-50 border-amber-400 ring-2 ring-amber-400/20' : 'bg-slate-50 border-slate-200' }} transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-7 h-7 rounded-full {{ in_array($transaksi->status, ['proses', 'selesai', 'diambil']) ? 'bg-amber-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-xs font-bold">2</span>
                    <i class="fa-solid fa-spinner {{ $transaksi->status === 'proses' ? 'text-amber-500 animate-spin' : 'text-slate-300' }}"></i>
                </div>
                <h5 class="font-bold text-sm text-slate-800">Sedang Proses</h5>
                <p class="text-[11px] text-slate-500 mt-0.5">Cucian sedang dicuci / disetrika</p>
                @if($transaksi->status !== 'proses')
                    <form action="{{ route('transaksi.update_status', $transaksi->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="proses">
                        <button type="submit" class="w-full text-center text-[10px] font-bold py-1 rounded-lg bg-amber-500 text-white hover:bg-amber-600 shadow-sm">Set Proses</button>
                    </form>
                @endif
            </div>

            <!-- Step 3: Selesai -->
            <div class="p-4 rounded-2xl border {{ $transaksi->status === 'selesai' ? 'bg-emerald-50 border-emerald-400 ring-2 ring-emerald-400/20' : 'bg-slate-50 border-slate-200' }} transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-7 h-7 rounded-full {{ in_array($transaksi->status, ['selesai', 'diambil']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-xs font-bold">3</span>
                    <i class="fa-solid fa-check {{ $transaksi->status === 'selesai' ? 'text-emerald-600' : 'text-slate-300' }}"></i>
                </div>
                <h5 class="font-bold text-sm text-slate-800">Selesai Dicuci</h5>
                <p class="text-[11px] text-slate-500 mt-0.5">Siap dikemas & diambil</p>
                @if($transaksi->status !== 'selesai')
                    <form action="{{ route('transaksi.update_status', $transaksi->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="w-full text-center text-[10px] font-bold py-1 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm">Set Selesai</button>
                    </form>
                @endif
            </div>

            <!-- Step 4: Diambil -->
            <div class="p-4 rounded-2xl border {{ $transaksi->status === 'diambil' ? 'bg-slate-900 text-white border-slate-800' : 'bg-slate-50 border-slate-200' }} transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-7 h-7 rounded-full {{ $transaksi->status === 'diambil' ? 'bg-indigo-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-xs font-bold">4</span>
                    <i class="fa-solid fa-hand-holding-box {{ $transaksi->status === 'diambil' ? 'text-cyan-400' : 'text-slate-300' }}"></i>
                </div>
                <h5 class="font-bold text-sm {{ $transaksi->status === 'diambil' ? 'text-white' : 'text-slate-800' }}">Sudah Diambil</h5>
                <p class="text-[11px] {{ $transaksi->status === 'diambil' ? 'text-slate-400' : 'text-slate-500' }} mt-0.5">Order selesai diserahkan</p>
                @if($transaksi->status !== 'diambil')
                    <form action="{{ route('transaksi.update_status', $transaksi->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="diambil">
                        <button type="submit" class="w-full text-center text-[10px] font-bold py-1 rounded-lg bg-slate-800 text-white hover:bg-slate-900 shadow-sm">Set Diambil</button>
                    </form>
                @endif
            </div>

        </div>
    </div>

    <!-- Main Content Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Item Breakdown -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h4 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <i class="fa-solid fa-basket-shopping text-indigo-500"></i>
                        <span>Rincian Item Cucian</span>
                    </h4>
                    <span class="text-xs text-slate-500">{{ $transaksi->detailTransaksi->count() }} Paket</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">Nama Paket</th>
                                <th class="px-6 py-3">Tarif Satuan</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Keterangan</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($transaksi->detailTransaksi as $item)
                                <tr>
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $item->paket->nama_paket ?? 'Paket Tidak Ditemukan' }}
                                        <span class="block text-[10px] text-slate-400 font-normal uppercase">{{ $item->paket->jenis ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs">
                                        Rp {{ number_format($item->paket->harga ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-900">
                                        {{ $item->qty }} {{ ($item->paket && $item->paket->jenis === 'kiloan') ? 'Kg' : 'Pcs' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono font-bold text-slate-900 text-right">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Informasi Transaksi & Petugas -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="space-y-2">
                    <h5 class="font-bold text-slate-700 uppercase tracking-wider text-[11px]">Informasi Pelanggan</h5>
                    <p><strong class="text-slate-900">Nama:</strong> {{ $transaksi->member->nama ?? 'Umum' }}</p>
                    <p><strong class="text-slate-900">Telepon/WA:</strong> {{ $transaksi->member->tlp ?? '-' }}</p>
                    <p><strong class="text-slate-900">Alamat:</strong> {{ $transaksi->member->alamat ?? '-' }}</p>
                </div>
                <div class="space-y-2">
                    <h5 class="font-bold text-slate-700 uppercase tracking-wider text-[11px]">Informasi Outlet & Kasir</h5>
                    <p><strong class="text-slate-900">Outlet:</strong> {{ $transaksi->outlet->nama ?? '-' }}</p>
                    <p><strong class="text-slate-900">Kasir / Petugas:</strong> {{ $transaksi->user->nama ?? '-' }}</p>
                    <p><strong class="text-slate-900">Waktu Masuk:</strong> {{ $transaksi->tgl->format('d F Y, H:i') }}</p>
                    <p><strong class="text-slate-900">Batas Estimasi:</strong> <span class="text-rose-600 font-bold">{{ $transaksi->batas_waktu->format('d F Y, H:i') }}</span></p>
                </div>
            </div>
        </div>

        <!-- Right: Payment Status & Summary -->
        <div class="space-y-6">
            
            <!-- Payment Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h4 class="font-bold text-slate-900 text-base">Status Pembayaran</h4>
                    @if($transaksi->dibayar === 'dibayar')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                            <i class="fa-solid fa-check-double mr-1.5"></i> LUNAS
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                            <i class="fa-solid fa-hourglass-half mr-1.5"></i> BELUM LUNAS
                        </span>
                    @endif
                </div>

                <div class="space-y-2.5 text-xs text-slate-600">
                    <div class="flex items-center justify-between">
                        <span>Subtotal Item:</span>
                        <span class="font-mono font-bold text-slate-900">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                    </div>

                    @if($transaksi->diskon > 0)
                        <div class="flex items-center justify-between text-rose-600">
                            <span>Diskon ({{ $transaksi->diskon }}%):</span>
                            <span class="font-mono font-bold">- Rp {{ number_format(($transaksi->diskon/100) * $transaksi->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if($transaksi->pajak > 0)
                        <div class="flex items-center justify-between">
                            <span>Pajak:</span>
                            <span class="font-mono font-bold text-slate-900">+ Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if($transaksi->biaya_tambahan > 0)
                        <div class="flex items-center justify-between">
                            <span>Biaya Tambahan:</span>
                            <span class="font-mono font-bold text-slate-900">+ Rp {{ number_format($transaksi->biaya_tambahan, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                        <span class="text-sm font-extrabold text-slate-900">Total Tagihan:</span>
                        <span class="text-xl font-black text-indigo-600 font-mono">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                    </div>

                    @if($transaksi->tgl_bayar)
                        <p class="text-[11px] text-emerald-600 font-medium pt-1">
                            <i class="fa-solid fa-calendar-check mr-1"></i> Dilunasi pada: {{ $transaksi->tgl_bayar->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>

                <!-- Update Payment Form -->
                <div class="pt-4 border-t border-slate-100">
                    <form action="{{ route('transaksi.update_bayar', $transaksi->id) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="dibayar" value="{{ $transaksi->dibayar === 'dibayar' ? 'belum_dibayar' : 'dibayar' }}">
                        
                        @if($transaksi->dibayar !== 'dibayar')
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-3 px-4 rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                <span>Tandai Lunas Sekarang</span>
                            </button>
                        @else
                            <button type="submit" onclick="return confirm('Ubah status menjadi Belum Dibayar?')" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold py-2.5 px-4 rounded-xl transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Batalkan Pelunasan</span>
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Danger Zone: Delete Transaction (Admin Only) -->
            @if(Auth::user()->role === 'admin')
                <div class="bg-rose-50/50 rounded-3xl p-5 border border-rose-200 space-y-3">
                    <h5 class="text-xs font-bold text-rose-800 uppercase tracking-wider">Hapus Transaksi</h5>
                    <p class="text-[11px] text-rose-600">Aksi ini akan menghapus permanen invoice dan seluruh rincian cucian ini.</p>
                    <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" onsubmit="return confirm('Hapus permanen transaksi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold py-2 rounded-xl transition-all">
                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus Transaksi Ini
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
