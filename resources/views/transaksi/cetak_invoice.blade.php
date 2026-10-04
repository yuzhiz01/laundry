<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaksi->kode_invoice }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-shadow-none { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 flex flex-col items-center min-h-screen" onload="window.print()">

    <!-- Print Control Bar -->
    <div class="w-full max-w-3xl mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-2">
            <a href="{{ route('transaksi.show', $transaksi->id) }}" class="text-xs font-semibold px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all">
                ← Kembali
            </a>
            <span class="text-xs font-bold text-slate-500">Preview Invoice Cetak</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="text-xs font-bold px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/20 transition-all flex items-center gap-1.5">
                <span>Cetak Invoice (A4)</span>
            </button>
        </div>
    </div>

    <!-- Invoice Sheet A4 -->
    <div class="w-full max-w-3xl bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-slate-200 print-shadow-none text-slate-800 space-y-8">
        
        <!-- Header: Logo & Invoice Details -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-slate-200 pb-8">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black tracking-tight text-indigo-700">LAUNDRY</span>
                </div>
                <h3 class="font-bold text-slate-800 text-sm">{{ $transaksi->outlet->nama ?? 'Laundry Outlet' }}</h3>
                <p class="text-xs text-slate-500 max-w-xs">{{ $transaksi->outlet->alamat ?? '-' }}</p>
                <p class="text-xs text-slate-500">No. Telp/WA: {{ $transaksi->outlet->tlp ?? '-' }}</p>
            </div>

            <div class="text-left sm:text-right space-y-1">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ $transaksi->dibayar === 'dibayar' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $transaksi->dibayar === 'dibayar' ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
                <h2 class="text-xl font-extrabold font-mono text-slate-900 mt-2">{{ $transaksi->kode_invoice }}</h2>
                <p class="text-xs text-slate-500">Tanggal Masuk: {{ $transaksi->tgl->format('d F Y, H:i') }}</p>
                <p class="text-xs text-slate-500">Batas Estimasi: <span class="font-bold text-slate-700">{{ $transaksi->batas_waktu->format('d F Y, H:i') }}</span></p>
            </div>
        </div>

        <!-- Bill To Info -->
        <div class="grid grid-cols-2 gap-6 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Ditagihkan Kepada:</span>
                <h4 class="font-bold text-sm text-slate-900">{{ $transaksi->member->nama ?? 'Pelanggan Umum' }}</h4>
                <p class="text-slate-600 mt-0.5">{{ $transaksi->member->alamat ?? '-' }}</p>
                <p class="text-slate-600">No. HP: {{ $transaksi->member->tlp ?? '-' }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Petugas Kasir:</span>
                <h4 class="font-bold text-sm text-slate-900">{{ $transaksi->user->nama ?? '-' }}</h4>
                <p class="text-slate-600 mt-0.5">Status Pengerjaan: <strong class="uppercase text-indigo-600">{{ $transaksi->status }}</strong></p>
                @if($transaksi->tgl_bayar)
                    <p class="text-emerald-700 font-semibold">Tgl Pelunasan: {{ $transaksi->tgl_bayar->format('d F Y, H:i') }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 font-bold uppercase text-slate-700 border-b border-slate-200">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">Rincian Paket</th>
                        <th class="p-3 text-center">Jumlah</th>
                        <th class="p-3 text-right">Tarif Satuan</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($transaksi->detailTransaksi as $index => $item)
                        <tr>
                            <td class="p-3 font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-3">
                                <span class="font-bold text-slate-900 text-sm block">{{ $item->paket->nama_paket ?? 'Paket' }}</span>
                                @if($item->keterangan)
                                    <span class="text-slate-500 italic text-[11px]">* {{ $item->keterangan }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-center font-bold text-slate-800">
                                {{ $item->qty }} {{ ($item->paket && $item->paket->jenis === 'kiloan') ? 'Kg' : 'Pcs' }}
                            </td>
                            <td class="p-3 text-right font-mono">
                                Rp {{ number_format($item->paket->harga ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-slate-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Calculations & Totals -->
        <div class="flex flex-col sm:flex-row justify-between gap-6 pt-2">
            <div class="text-xs text-slate-500 max-w-sm space-y-1">
                <p class="font-bold text-slate-700 uppercase tracking-wider text-[11px]">Catatan Pengambilan:</p>
                <p>1. Harap menunjukkan nota/invoice ini saat mengambil cucian.</p>
                <p>2. Periksa kembali cucian saat pengambilan.</p>
            </div>

            <div class="w-full sm:w-64 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-mono font-bold text-slate-900">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($transaksi->diskon > 0)
                    <div class="flex justify-between text-rose-600">
                        <span>Diskon ({{ $transaksi->diskon }}%):</span>
                        <span class="font-mono font-bold">- Rp {{ number_format(($transaksi->diskon/100)*$transaksi->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($transaksi->pajak > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>Pajak:</span>
                        <span class="font-mono font-bold text-slate-900">+ Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($transaksi->biaya_tambahan > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Tambahan:</span>
                        <span class="font-mono font-bold text-slate-900">+ Rp {{ number_format($transaksi->biaya_tambahan, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="border-t-2 border-slate-900 pt-2 flex justify-between text-sm">
                    <span class="font-extrabold text-slate-900">Total Tagihan:</span>
                    <span class="font-black text-indigo-700 font-mono text-base">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-8 pt-10 text-center text-xs text-slate-700">
            <div>
                <p class="font-semibold">Pelanggan,</p>
                <div class="h-16"></div>
                <p class="font-bold border-t border-slate-300 inline-block px-8 pt-1">{{ $transaksi->member->nama ?? 'Pelanggan' }}</p>
            </div>
            <div>
                <p class="font-semibold">Petugas Kasir,</p>
                <div class="h-16"></div>
                <p class="font-bold border-t border-slate-300 inline-block px-8 pt-1">{{ $transaksi->user->nama ?? 'Kasir' }}</p>
            </div>
        </div>

    </div>

</body>
</html>
