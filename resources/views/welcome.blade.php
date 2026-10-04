<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Sistem Pengelolaan Laundry</title>
    
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

    <!-- Main Content Container with relative z-10 -->
    <div class="relative z-10 min-h-full flex flex-col flex-1">
        
        <!-- Navbar Header -->
        <header class="border-b border-slate-200/80 bg-white/85 backdrop-blur-xl sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-500 flex items-center justify-center text-white text-xl shadow-lg shadow-indigo-500/25">
                        <i class="fa-solid fa-soap"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-900 tracking-tight leading-none">Laundry</h1>
                        <span class="text-[11px] text-slate-500 font-medium">Sistem Pengelolaan Terpadu</span>
                    </div>
                </a>

                <!-- Navigation Actions -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('tracking') }}" class="inline-flex items-center gap-2 text-xs font-semibold px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-white/80 transition-all">
                        <i class="fa-solid fa-magnifying-glass text-indigo-500"></i>
                        <span>Lacak Cucian</span>
                    </a>

                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-bold px-4 sm:px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white shadow-md shadow-indigo-600/25 transition-all transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Login Petugas</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 space-y-16 w-full">
            
            <!-- Welcome Title & Subtitle -->
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/90 border border-indigo-200/90 text-indigo-700 text-xs font-semibold shadow-sm backdrop-blur-md">
                    <i class="fa-solid fa-sparkles text-amber-500"></i>
                    <span>Sistem Informasi Pengelolaan Laundry Terintegrasi</span>
                </div>
                
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Kelola Layanan & Cek Status Laundry dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-600">Mudah & Cepat</span>
                </h2>
                
                <p class="text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    Masuk ke portal operasional petugas (Admin, Kasir, Owner) untuk mengelola data laundry, atau lacak status pesanan cucian secara real-time.
                </p>
            </div>

            <!-- Primary Action Cards (Portal Petugas & Lacak Cucian) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                
                <!-- Card 1: Login Petugas -->
                <div class="group relative bg-white/92 backdrop-blur-xl border border-white/80 hover:border-indigo-400 rounded-3xl p-8 shadow-xl shadow-slate-300/40 hover:shadow-2xl transition-all duration-300 flex flex-col justify-between hover:-translate-y-1">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Portal Akses Khusus</span>
                            <h3 class="text-xl font-extrabold text-slate-900">Login Petugas</h3>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Akses khusus Admin, Kasir POS, dan Owner untuk mencatat transaksi baru, mengelola data pelanggan, tarif paket, dan laporan keuangan.
                        </p>
                    </div>
                    
                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <a href="{{ route('login') }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-md shadow-indigo-600/25 transition-all">
                            <i class="fa-solid fa-right-to-bracket text-xs"></i>
                            <span>Masuk ke Dashboard Petugas</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Cek Status / Tracking Cucian -->
                <div class="group relative bg-white/92 backdrop-blur-xl border border-white/80 hover:border-emerald-400 rounded-3xl p-8 shadow-xl shadow-slate-300/40 hover:shadow-2xl transition-all duration-300 flex flex-col justify-between hover:-translate-y-1">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Pelacakan Realtime</span>
                            <h3 class="text-xl font-extrabold text-slate-900">Lacak Cucian</h3>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Cek status cucian Anda (Baru, Sedang Diproses, atau Sudah Selesai & Siap Diambil) cukup dengan nomor invoice atau nomor HP.
                        </p>
                    </div>
                    
                    <!-- Quick Search Input inside Card -->
                    <form action="{{ route('tracking') }}" method="GET" class="pt-6 mt-6 border-t border-slate-100 space-y-2">
                        <div class="relative">
                            <input type="text" name="invoice" placeholder="Contoh: INV/20260907/0001" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3 px-4 rounded-xl flex items-center justify-center gap-2 shadow-md shadow-emerald-600/20 transition-all">
                            <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                            <span>Cek Status Sekarang</span>
                        </button>
                    </form>
                </div>

            </div>

            <!-- Outlet & Services Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-8 border-t border-slate-200/80">
                
                <!-- Outlets List -->
                <div class="bg-white/92 backdrop-blur-xl border border-white/80 rounded-3xl p-6 sm:p-8 space-y-4 shadow-lg shadow-slate-200/50">
                    <h4 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <i class="fa-solid fa-store text-indigo-600"></i>
                        <span>Cabang Outlet Kami</span>
                    </h4>
                    <div class="space-y-3">
                        @foreach($outlets as $out)
                            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 flex items-start justify-between gap-4">
                                <div>
                                    <h5 class="font-bold text-slate-900 text-sm">{{ $out->nama }}</h5>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $out->alamat }}</p>
                                </div>
                                <span class="text-xs text-indigo-600 font-mono font-semibold whitespace-nowrap bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                                    <i class="fa-solid fa-phone mr-1"></i> {{ $out->tlp }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Popular Packages -->
                <div class="bg-white/92 backdrop-blur-xl border border-white/80 rounded-3xl p-6 sm:p-8 space-y-4 shadow-lg shadow-slate-200/50">
                    <h4 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <i class="fa-solid fa-tags text-cyan-600"></i>
                        <span>Daftar Tarif Layanan Laundry</span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($pakets as $p)
                            <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-1">
                                <span class="text-[10px] font-bold text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded border border-cyan-100 uppercase tracking-wider">{{ $p->jenis }}</span>
                                <h5 class="font-bold text-slate-900 text-xs truncate mt-1">{{ $p->nama_paket }}</h5>
                                <p class="text-sm font-mono font-extrabold text-emerald-600">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                    <span class="text-[10px] font-normal text-slate-500">/ {{ $p->jenis === 'kiloan' ? 'Kg' : 'Pcs' }}</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200/80 bg-white/85 backdrop-blur-md py-6 text-center text-xs text-slate-500 shadow-sm">
            <p>&copy; {{ date('Y') }} Laundry Management System. Seluruh hak cipta dilindungi.</p>
        </footer>

    </div>

</body>
</html>
