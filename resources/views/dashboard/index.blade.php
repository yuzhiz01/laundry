@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Ringkasan')
@section('page_subtitle', 'Ringkasan operasional dan statistik bisnis laundry Anda')

@section('content')
<div class="space-y-6">
    
    <!-- Hero Banner Greeting -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/30 border border-indigo-400/30 text-xs font-semibold text-indigo-200">
                    <i class="fa-solid fa-sparkles text-amber-400"></i>
                    <span>Sistem Siap Operasi</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Halo, {{ Auth::user()->nama }}! 👋
                </h2>
                <p class="text-indigo-200 text-sm max-w-xl">
                    Anda masuk sebagai <strong class="text-white uppercase font-bold">{{ Auth::user()->role }}</strong>. 
                    @if(Auth::user()->outlet)
                        Outlet yang aktif: <strong class="text-white">{{ Auth::user()->outlet->nama }}</strong>.
                    @endif
                </p>
            </div>
            
            <div class="flex items-center gap-3 flex-wrap">
                @if(in_array(Auth::user()->role, ['admin', 'kasir']))
                    <a href="{{ route('transaksi.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-400 hover:to-indigo-400 text-white font-bold px-5 py-3 rounded-2xl shadow-lg shadow-cyan-500/20 transition-all transform hover:-translate-y-0.5 text-sm">
                        <i class="fa-solid fa-plus-circle text-base"></i>
                        <span>Entri Transaksi Baru</span>
                    </a>
                @endif
                <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-4 py-3 rounded-2xl border border-white/15 transition-all text-sm">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Lihat Laporan</span>
                </a>
            </div>
        </div>

        <!-- Decorative background elements -->
        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-48 h-48 bg-cyan-500/20 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Stat Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Pendapatan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                        <span>Dari transaksi lunas</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>

        <!-- Perlu Dikerjakan (Baru & Proses) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sedang Diproses</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $transaksiBaru + $transaksiProses }} <span class="text-xs font-normal text-slate-500">Order</span></h3>
                    <p class="text-[11px] text-amber-600 font-semibold mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>{{ $transaksiBaru }} Baru, {{ $transaksiProses }} Proses</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold border border-amber-100">
                    <i class="fa-solid fa-spinner"></i>
                </div>
            </div>
        </div>

        <!-- Siap Diambil / Selesai -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Siap Diambil</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $transaksiSelesai }} <span class="text-xs font-normal text-slate-500">Order</span></h3>
                    <p class="text-[11px] text-indigo-600 font-semibold mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-box"></i>
                        <span>Cucian sudah bersih & wangi</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold border border-indigo-100">
                    <i class="fa-solid fa-shirt"></i>
                </div>
            </div>
        </div>

        <!-- Total Pelanggan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pelanggan</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalMember }} <span class="text-xs font-normal text-slate-500">Member</span></h3>
                    <p class="text-[11px] text-cyan-600 font-semibold mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-users"></i>
                        <span>Pelanggan terdaftar</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl font-bold border border-cyan-100">
                    <i class="fa-solid fa-id-card"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Pendapatan 7 Hari Terakhir (Line Chart) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Tren Omset 7 Hari Terakhir</h3>
                    <p class="text-xs text-slate-500">Grafik pemasukan harian yang tercatat lunas</p>
                </div>
                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600 text-sm font-bold">
                    <i class="fa-solid fa-chart-line"></i>
                </span>
            </div>
            <div class="h-64 relative">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <!-- Distribusi Status Cucian (Donut Chart) -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Status Laundry</h3>
                        <p class="text-xs text-slate-500">Proporsi pengerjaan cucian</p>
                    </div>
                    <span class="p-2 rounded-xl bg-cyan-50 text-cyan-600 text-sm font-bold">
                        <i class="fa-solid fa-chart-pie"></i>
                    </span>
                </div>
                <div class="h-52 relative flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-slate-100 text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <span class="text-slate-600">Baru ({{ $transaksiBaru }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <span class="text-slate-600">Proses ({{ $transaksiProses }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-slate-600">Selesai ({{ $transaksiSelesai }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-slate-500"></span>
                    <span class="text-slate-600">Diambil ({{ $transaksiDiambil }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Transaksi Terbaru</h3>
                <p class="text-xs text-slate-500">Daftar transaksi cucian yang baru dimasukkan ke sistem</p>
            </div>
            <a href="{{ route('transaksi.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                <span>Lihat Semua Riwayat</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Kode Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Tgl Masuk</th>
                        <th class="px-6 py-4">Status Cucian</th>
                        <th class="px-6 py-4">Pembayaran</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTransaksi as $trx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600 text-xs">
                                <a href="{{ route('transaksi.show', $trx->id) }}" class="hover:underline">
                                    {{ $trx->kode_invoice }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-800">{{ $trx->member->nama ?? 'Umum' }}</p>
                                <p class="text-[11px] text-slate-400">{{ $trx->member->tlp ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $trx->tgl->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($trx->status === 'baru')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fa-solid fa-circle-dot text-[8px] mr-1.5 text-blue-500"></i> Baru
                                    </span>
                                @elseif($trx->status === 'proses')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-spinner animate-spin text-[8px] mr-1.5 text-amber-500"></i> Proses
                                    </span>
                                @elseif($trx->status === 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check text-[8px] mr-1.5 text-emerald-500"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                        <i class="fa-solid fa-hand-holding-box text-[8px] mr-1.5"></i> Diambil
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($trx->dibayar === 'dibayar')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-check-double text-[10px] mr-1"></i> Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                        <i class="fa-solid fa-hourglass text-[10px] mr-1"></i> Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('transaksi.show', $trx->id) }}" class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all text-xs font-semibold" title="Detail Transaksi">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('transaksi.cetak_struk', $trx->id) }}" target="_blank" class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-700 hover:text-white transition-all text-xs font-semibold" title="Cetak Struk">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-receipt text-3xl mb-2 block"></i>
                                <span>Belum ada transaksi tercatat.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Income Trend Chart
    const ctxIncome = document.getElementById('incomeChart').getContext('2d');
    new Chart(ctxIncome, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: {!! json_encode($chartIncomes) !!},
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#4f46e5',
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' Pemasukan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value >= 1000 ? (value / 1000) + 'k' : value);
                        }
                    },
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Status Donut Chart
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Baru', 'Proses', 'Selesai', 'Diambil'],
            datasets: [{
                data: [{{ $transaksiBaru }}, {{ $transaksiProses }}, {{ $transaksiSelesai }}, {{ $transaksiDiambil }}],
                backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#64748b'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
</script>
@endpush
