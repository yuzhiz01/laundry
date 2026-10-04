<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pengelolaan Laundry') - Laundry</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full flex flex-col antialiased text-slate-800">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 transition-all duration-300 z-30 shadow-xl">
            <!-- Brand Logo -->
            <div class="h-16 flex items-center px-6 bg-slate-950/60 border-b border-slate-800/80 gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-cyan-400 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30">
                    <i class="fa-solid fa-soap"></i>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-base tracking-tight leading-none">Laundry</h1>
                    <span class="text-xs text-slate-400 font-medium">Management System</span>
                </div>
            </div>

            <!-- Current User Badge -->
            <div class="px-5 py-4 border-b border-slate-800/60 flex items-center gap-3 bg-slate-900/40">
                <div class="w-10 h-10 rounded-full bg-slate-700 border-2 border-indigo-500/50 flex items-center justify-center text-white font-semibold text-sm">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->nama }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold 
                            {{ Auth::user()->role === 'admin' ? 'bg-purple-900/80 text-purple-300 border border-purple-700' : '' }}
                            {{ Auth::user()->role === 'kasir' ? 'bg-emerald-900/80 text-emerald-300 border border-emerald-700' : '' }}
                            {{ Auth::user()->role === 'owner' ? 'bg-amber-900/80 text-amber-300 border border-amber-700' : '' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-1"></i>
                            {{ strtoupper(Auth::user()->role) }}
                        </span>
                        @if(Auth::user()->outlet)
                            <span class="text-[10px] text-slate-400 truncate" title="{{ Auth::user()->outlet->nama }}">
                                • {{ Str::limit(Auth::user()->outlet->nama, 12) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Menu Navigation -->
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-6">
                <!-- Main Nav -->
                <div>
                    <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Utama</p>
                    <nav class="space-y-1">
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-chart-pie w-5 text-center text-base"></i>
                            <span>Dashboard</span>
                        </a>
                    </nav>
                </div>

                <!-- Modul Transaksi & Kasir (Admin & Kasir) -->
                @if(in_array(Auth::user()->role, ['admin', 'kasir']))
                <div>
                    <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Operasional Kasir</p>
                    <nav class="space-y-1">
                        <a href="{{ route('transaksi.create') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-colors {{ request()->routeIs('transaksi.create') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-cash-register w-5 text-center text-base text-cyan-400"></i>
                            <span>Transaksi Baru</span>
                            <span class="ml-auto bg-cyan-500/20 text-cyan-300 text-[10px] font-bold px-1.5 py-0.5 rounded-md">POS</span>
                        </a>

                        <a href="{{ route('transaksi.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-colors {{ request()->routeIs('transaksi.index') || request()->routeIs('transaksi.show') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-receipt w-5 text-center text-base"></i>
                            <span>Riwayat Transaksi</span>
                        </a>

                        <a href="{{ route('member.create') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-colors {{ request()->routeIs('member.create') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-user-plus w-5 text-center text-base text-emerald-400"></i>
                            <span>Registrasi Pelanggan</span>
                        </a>

                        <a href="{{ route('member.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-colors {{ request()->routeIs('member.index') || request()->routeIs('member.edit') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-users w-5 text-center text-base"></i>
                            <span>Data Pelanggan</span>
                        </a>
                    </nav>
                </div>
                @endif

                <!-- Data Master (Hanya Admin) -->
                @if(Auth::user()->role === 'admin')
                <div>
                    <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Data Master</p>
                    <nav class="space-y-1">
                        <a href="{{ route('outlet.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-colors {{ request()->routeIs('outlet.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-store w-5 text-center text-base"></i>
                            <span>Kelola Outlet</span>
                        </a>

                        <a href="{{ route('paket.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-colors {{ request()->routeIs('paket.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-box-open w-5 text-center text-base"></i>
                            <span>Paket Laundry</span>
                        </a>

                        <a href="{{ route('user.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-colors {{ request()->routeIs('user.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-user-shield w-5 text-center text-base"></i>
                            <span>Kelola Pengguna</span>
                        </a>
                    </nav>
                </div>
                @endif

                <!-- Laporan & Rekap (Admin, Kasir, Owner) -->
                <div>
                    <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Laporan</p>
                    <nav class="space-y-1">
                        <a href="{{ route('laporan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-colors {{ request()->routeIs('laporan.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-base"></i>
                            <span>Laporan Keuangan</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Footer / Logout -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/60 flex items-center justify-between">
                <a href="{{ route('profile') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-gear"></i>
                    <span>Pengaturan Akun</span>
                </a>
                <a href="{{ route('logout') }}" 
                   onclick="return confirm('Apakah Anda yakin ingin logout dari sistem?')"
                   class="text-xs text-rose-400 hover:text-rose-300 flex items-center gap-1.5 transition-colors font-medium">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-100">
            <!-- Top Header Navbar -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-10 flex-shrink-0 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="text-slate-500 hover:text-slate-700 p-2 rounded-lg hover:bg-slate-100 md:hidden">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">@yield('page_subtitle', 'Selamat datang di Sistem Pengelolaan Laundry')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-600 gap-2">
                        <i class="fa-regular fa-calendar text-indigo-500"></i>
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                    </div>

                    @if(in_array(Auth::user()->role, ['admin', 'kasir']))
                        <a href="{{ route('transaksi.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl shadow-md shadow-indigo-600/20 transition-all hover:scale-[1.02]">
                            <i class="fa-solid fa-plus"></i>
                            <span>Transaksi Baru</span>
                        </a>
                    @endif
                </div>
            </header>

            <!-- Main Body View Container -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 flex items-center p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm animate-fade-in">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-xl mr-3"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                        <div class="flex items-center mb-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-xl mr-3"></i>
                            <span class="text-sm font-bold">Terjadi beberapa kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 ml-8">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Toggle Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
