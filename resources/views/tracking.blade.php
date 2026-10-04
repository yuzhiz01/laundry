<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Status Pesanan Laundry</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-full flex flex-col text-slate-800 antialiased selection:bg-indigo-600 selection:text-white relative bg-slate-50">
    
    <!-- Fixed Background Image with Soft Bright Overlay -->
    <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('{{ asset('images/bg-laundry.jpg') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-white/85 via-slate-50/80 to-white/95 backdrop-blur-[1.5px]"></div>
    </div>

    <div class="relative z-10 min-h-full flex flex-col flex-1">
        <!-- Navbar Header -->
        <header class="border-b border-slate-200/80 bg-white/85 backdrop-blur-xl sticky top-0 z-50 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-500 flex items-center justify-center text-white text-lg shadow-lg shadow-indigo-500/25">
                        <i class="fa-solid fa-soap"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-black text-slate-900 tracking-tight leading-none">Laundry</h1>
                        <span class="text-[11px] text-slate-500 font-medium">Lacak Status Cucian</span>
                    </div>
                </a>

                <!-- Navigation Actions -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 sm:px-4 py-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-white/80 transition-all">
                        <i class="fa-solid fa-house text-slate-400"></i>
                        <span>Beranda</span>
                    </a>

                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold px-3 sm:px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/25 transition-all">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Login Petugas</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Container -->
        <main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 py-10 w-full space-y-8">
            
            <!-- Search Box -->
            <div class="bg-white/92 backdrop-blur-xl border border-white/80 rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-300/50 text-center space-y-5">
                <div class="max-w-xl mx-auto space-y-2">
                    <span class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass"></i> Cek Status Pesanan
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Lacak Cucian Anda
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Masukkan nomor nota / kode invoice atau nomor WhatsApp yang terdaftar
                    </p>
                </div>

                <form action="{{ route('tracking') }}" method="GET" class="max-w-xl mx-auto flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="fa-solid fa-barcode text-sm"></i>
                        </span>
                        <input type="text" name="invoice" value="{{ $keyword }}" placeholder="Contoh: INV/20260907/0001 atau 08123456789" required
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 placeholder-slate-400 transition-all">
                    </div>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-indigo-600/25 transition-all flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-search"></i>
                        <span>Cari</span>
                    </button>
                </form>
            </div>

            @if(!empty($keyword))
                @if($transaksi)
                    <!-- Result Box -->
                    <div class="bg-white/92 backdrop-blur-xl border border-white/80 rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-300/50 space-y-6">
                        
                        <!-- Header Nota -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                            <div>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kode Invoice</span>
                                <h3 class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $transaksi->kode_invoice }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <i class="fa-regular fa-calendar mr-1"></i> Tanggal Masuk: {{ \Carbon\Carbon::parse($transaksi->tgl)->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <!-- Status Transaksi -->
                                @php
                                    $statusStyles = [
                                        'baru' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'proses' => 'bg-cyan-50 text-cyan-800 border-cyan-200',
                                        'selesai' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                        'diambil' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    ];
                                    $statusIcons = [
                                        'baru' => 'fa-clock',
                                        'proses' => 'fa-spinner fa-spin',
                                        'selesai' => 'fa-circle-check',
                                        'diambil' => 'fa-handshake',
                                    ];
                                @endphp
                                <div class="px-4 py-2 rounded-xl border text-xs font-bold uppercase tracking-wider flex items-center gap-2 {{ $statusStyles[$transaksi->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    <i class="fa-solid {{ $statusIcons[$transaksi->status] ?? 'fa-circle-info' }}"></i>
                                    <span>Status: {{ ucfirst($transaksi->status) }}</span>
                                </div>

                                <!-- Status Bayar -->
                                @if($transaksi->dibayar === 'dibayar')
                                    <div class="px-4 py-2 rounded-xl border bg-emerald-50 text-emerald-800 border-emerald-200 text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Lunas</span>
                                    </div>
                                @else
                                    <div class="px-4 py-2 rounded-xl border bg-rose-50 text-rose-800 border-rose-200 text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                        <span>Belum Dibayar</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Visual Progress Timeline -->
                        <div class="py-4">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-6">Tahapan Proses Pengerjaan</h4>
                            @php
                                $steps = ['baru' => 1, 'proses' => 2, 'selesai' => 3, 'diambil' => 4];
                                $currentStep = $steps[$transaksi->status] ?? 1;
                            @endphp
                            <div class="grid grid-cols-4 gap-2 relative">
                                <div class="text-center space-y-2">
                                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 1 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 ring-4 ring-indigo-100' : 'bg-slate-100 text-slate-400' }}">
                                        1
                                    </div>
                                    <span class="block text-xs font-semibold {{ $currentStep >= 1 ? 'text-slate-900' : 'text-slate-400' }}">Pesanan Baru</span>
                                </div>

                                <div class="text-center space-y-2">
                                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 2 ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-600/30 ring-4 ring-cyan-100' : 'bg-slate-100 text-slate-400' }}">
                                        2
                                    </div>
                                    <span class="block text-xs font-semibold {{ $currentStep >= 2 ? 'text-slate-900' : 'text-slate-400' }}">Sedang Diproses</span>
                                </div>

                                <div class="text-center space-y-2">
                                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 3 ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 ring-4 ring-emerald-100' : 'bg-slate-100 text-slate-400' }}">
                                        3
                                    </div>
                                    <span class="block text-xs font-semibold {{ $currentStep >= 3 ? 'text-slate-900' : 'text-slate-400' }}">Selesai Dicuci</span>
                                </div>

                                <div class="text-center space-y-2">
                                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 4 ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 ring-4 ring-emerald-100' : 'bg-slate-100 text-slate-400' }}">
                                        4
                                    </div>
                                    <span class="block text-xs font-semibold {{ $currentStep >= 4 ? 'text-slate-900' : 'text-slate-400' }}">Sudah Diambil</span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer & Outlet Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-700">Data Pelanggan</span>
                                <h5 class="font-bold text-slate-900 text-sm">{{ $transaksi->member->nama }}</h5>
                                <p class="text-xs text-slate-500"><i class="fa-solid fa-phone text-slate-400 mr-1"></i> {{ $transaksi->member->tlp }}</p>
                                <p class="text-xs text-slate-500"><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $transaksi->member->alamat }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700">Outlet Pelayanan</span>
                                <h5 class="font-bold text-slate-900 text-sm">{{ $transaksi->outlet->nama }}</h5>
                                <p class="text-xs text-slate-500"><i class="fa-solid fa-phone text-slate-400 mr-1"></i> {{ $transaksi->outlet->tlp }}</p>
                                <p class="text-xs text-slate-500"><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $transaksi->outlet->alamat }}</p>
                            </div>
                        </div>

                        <!-- Items Detail -->
                        <div class="space-y-3 pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Rincian Paket & Cucian</h4>
                            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                <table class="w-full text-left text-xs text-slate-600">
                                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                                        <tr>
                                            <th class="p-3">Paket Laundry</th>
                                            <th class="p-3 text-center">Jumlah / Berat</th>
                                            <th class="p-3 text-right">Harga Satuan</th>
                                            <th class="p-3 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($transaksi->detailTransaksi as $detail)
                                            <tr>
                                                <td class="p-3 font-semibold text-slate-900">
                                                    {{ $detail->paket->nama_paket ?? 'Paket' }}
                                                    @if($detail->keterangan)
                                                        <span class="block text-[11px] text-slate-500 font-normal">Catatan: {{ $detail->keterangan }}</span>
                                                    @endif
                                                </td>
                                                <td class="p-3 text-center font-mono font-medium">{{ $detail->qty }} {{ ($detail->paket->jenis ?? '') === 'kiloan' ? 'Kg' : 'Pcs' }}</td>
                                                <td class="p-3 text-right font-mono">Rp {{ number_format($detail->paket->harga ?? 0, 0, ',', '.') }}</td>
                                                <td class="p-3 text-right font-mono font-bold text-slate-900">
                                                    Rp {{ number_format(($detail->paket->harga ?? 0) * $detail->qty, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-slate-50/80 font-semibold border-t border-slate-200">
                                        @if($transaksi->biaya_tambahan > 0)
                                            <tr>
                                                <td colspan="3" class="p-2.5 text-right text-slate-500">Biaya Tambahan:</td>
                                                <td class="p-2.5 text-right font-mono text-slate-800">Rp {{ number_format($transaksi->biaya_tambahan, 0, ',', '.') }}</td>
                                            </tr>
                                        @endif
                                        @if($transaksi->diskon > 0)
                                            <tr>
                                                <td colspan="3" class="p-2.5 text-right text-emerald-600">Diskon ({{ $transaksi->diskon }}%):</td>
                                                <td class="p-2.5 text-right font-mono text-emerald-600">-</td>
                                            </tr>
                                        @endif
                                        @if($transaksi->pajak > 0)
                                            <tr>
                                                <td colspan="3" class="p-2.5 text-right text-slate-500">Pajak:</td>
                                                <td class="p-2.5 text-right font-mono text-slate-800">Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="3" class="p-3 text-right text-slate-900 font-extrabold text-sm">TOTAL PEMBAYARAN:</td>
                                            <td class="p-3 text-right font-mono font-black text-emerald-600 text-base">
                                                Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Batas Waktu -->
                        <div class="p-3.5 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-between text-xs text-indigo-800">
                            <span><i class="fa-solid fa-calendar-check mr-1.5 text-indigo-600"></i> Estimasi Selesai / Batas Waktu:</span>
                            <span class="font-bold font-mono">{{ \Carbon\Carbon::parse($transaksi->batas_waktu)->translatedFormat('d F Y, H:i') }}</span>
                        </div>

                    </div>
                @else
                    <!-- Not Found Box -->
                    <div class="bg-white/92 backdrop-blur-xl border border-white/80 rounded-3xl p-8 sm:p-12 text-center space-y-4 shadow-xl shadow-slate-300/50">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-magnifying-glass-chart"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-xl font-bold text-slate-900">Data Cucian Tidak Ditemukan</h3>
                            <p class="text-xs text-slate-500 max-w-md mx-auto">
                                Tidak ditemukan data pesanan dengan kata kunci "<span class="text-rose-600 font-bold">{{ $keyword }}</span>". Pastikan nomor nota atau nomor telepon sudah sesuai.
                            </p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('tracking') }}" class="inline-flex items-center gap-2 text-xs font-bold px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 transition-all">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Cari Ulang</span>
                            </a>
                        </div>
                    </div>
                @endif
            @endif

        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200/80 bg-white/85 backdrop-blur-md py-6 text-center text-xs text-slate-500 shadow-sm">
            <p>&copy; {{ date('Y') }} Laundry Management System. Seluruh hak cipta dilindungi.</p>
        </footer>
    </div>

</body>
</html>
