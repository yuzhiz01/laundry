<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Laundry - {{ date('d/m/Y') }}</title>
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
    <div class="w-full max-w-4xl mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-2">
            <button onclick="window.history.back()" class="text-xs font-semibold px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all">
                ← Kembali
            </button>
            <span class="text-xs font-bold text-slate-500">Preview Laporan Cetak</span>
        </div>
        <div>
            <button onclick="window.print()" class="text-xs font-bold px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/20 transition-all flex items-center gap-1.5">
                <span>🖨️ Cetak Dokumen Laporan</span>
            </button>
        </div>
    </div>

    <!-- Official Report Document -->
    <div class="w-full max-w-4xl bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-slate-200 print-shadow-none text-slate-800 space-y-8">
        
        <!-- Kop Surat / Header Laporan -->
        <div class="text-center border-b-2 border-slate-900 pb-6">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">LAUNDRY MANAGEMENT</h1>
            <p class="text-sm font-bold text-slate-700 mt-1">
                {{ $selectedOutlet ? $selectedOutlet->nama : 'SEMUA OUTLET & CABANG' }}
            </p>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $selectedOutlet ? $selectedOutlet->alamat . ' | Telp: ' . $selectedOutlet->tlp : 'Sistem Informasi Manajemen Laundry Terintegrasi' }}
            </p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">REKAP LAPORAN TRANSAKSI</h2>
                <p class="text-xs text-slate-600">
                    Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong>
                </p>
            </div>
            <div class="text-left sm:text-right text-xs text-slate-500">
                <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>
                <p>Oleh Petugas: <strong>{{ Auth::user()->nama }} ({{ strtoupper(Auth::user()->role) }})</strong></p>
            </div>
        </div>

        <!-- Summary Stat Table -->
        <div class="grid grid-cols-3 gap-4 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-200">
            <div>
                <span class="text-slate-500 block">Total Order Transaksi:</span>
                <span class="font-extrabold text-base text-slate-900">{{ $totalTransaksi }} Order</span>
            </div>
            <div>
                <span class="text-slate-500 block">Total Pendapatan (Lunas):</span>
                <span class="font-bold text-base text-emerald-700 font-mono">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-slate-500 block">Total Piutang (Belum Lunas):</span>
                <span class="font-bold text-base text-rose-700 font-mono">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Table Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 border border-slate-300">
                <thead class="bg-slate-100 uppercase font-bold text-slate-800 border-b border-slate-300">
                    <tr>
                        <th class="p-2.5 border-r border-slate-300">No</th>
                        <th class="p-2.5 border-r border-slate-300">Kode Invoice</th>
                        <th class="p-2.5 border-r border-slate-300">Pelanggan</th>
                        <th class="p-2.5 border-r border-slate-300">Outlet</th>
                        <th class="p-2.5 border-r border-slate-300">Tgl Masuk</th>
                        <th class="p-2.5 border-r border-slate-300">Status</th>
                        <th class="p-2.5 border-r border-slate-300">Bayar</th>
                        <th class="p-2.5 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($transaksis as $index => $trx)
                        <tr>
                            <td class="p-2.5 border-r border-slate-200 text-center">{{ $index + 1 }}</td>
                            <td class="p-2.5 border-r border-slate-200 font-mono font-bold">{{ $trx->kode_invoice }}</td>
                            <td class="p-2.5 border-r border-slate-200">{{ $trx->member->nama ?? 'Umum' }}</td>
                            <td class="p-2.5 border-r border-slate-200">{{ $trx->outlet->nama ?? '-' }}</td>
                            <td class="p-2.5 border-r border-slate-200">{{ $trx->tgl->format('d/m/Y') }}</td>
                            <td class="p-2.5 border-r border-slate-200 uppercase font-semibold text-[11px]">{{ $trx->status }}</td>
                            <td class="p-2.5 border-r border-slate-200 font-bold {{ $trx->dibayar === 'dibayar' ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $trx->dibayar === 'dibayar' ? 'Lunas' : 'Belum' }}
                            </td>
                            <td class="p-2.5 font-mono font-bold text-right">
                                Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-4 text-center text-slate-400">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-100 font-bold border-t-2 border-slate-400">
                    <tr>
                        <td colspan="7" class="p-2.5 text-right uppercase">Total Omset Pendapatan Lunas:</td>
                        <td class="p-2.5 text-right font-mono text-emerald-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-8 pt-10 text-center text-xs text-slate-700">
            <div>
                <p>Mengetahui,</p>
                <p class="font-semibold">Pimpinan / Owner</p>
                <div class="h-20"></div>
                <p class="font-bold border-t border-slate-400 inline-block px-10 pt-1">( ............................................ )</p>
            </div>
            <div>
                <p>{{ $selectedOutlet ? $selectedOutlet->nama : 'Jakarta' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="font-semibold">Petugas Kasir / Admin,</p>
                <div class="h-20"></div>
                <p class="font-bold border-t border-slate-400 inline-block px-10 pt-1">( {{ Auth::user()->nama }} )</p>
            </div>
        </div>

    </div>

</body>
</html>
