<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Sistem Pengelolaan Laundry</title>
    
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
<body class="min-h-full flex items-center justify-center p-4 py-8 text-slate-800 relative bg-slate-50">
    
    <!-- Fixed Background Image with Soft Bright Overlay -->
    <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('{{ asset('images/bg-laundry.jpg') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-white/85 via-slate-50/80 to-white/95 backdrop-blur-[2px]"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        
        <!-- Back to Home Link -->
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-indigo-600 transition-colors px-3 py-1.5 rounded-lg bg-white/80 hover:bg-white border border-slate-200/60 shadow-sm backdrop-blur-md">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>

            <a href="{{ route('tracking') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition-colors px-3 py-1.5 rounded-lg bg-white/80 hover:bg-white border border-slate-200/60 shadow-sm backdrop-blur-md">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Lacak Cucian</span>
            </a>
        </div>

        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-500 items-center justify-center text-white text-3xl shadow-xl shadow-indigo-500/25 mb-3 ring-8 ring-white/60">
                <i class="fa-solid fa-soap"></i>
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laundry</h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">Portal Masuk Petugas & Pengelola Laundry</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/92 backdrop-blur-xl border border-white/80 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-slate-300/60 space-y-5">
            
            <div class="border-b border-slate-100 pb-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-user-shield text-indigo-600 text-sm"></i>
                        <span>Login Akun Petugas</span>
                    </h2>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700">
                        Admin / Kasir / Owner
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-start gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 text-sm"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="fa-solid fa-user text-xs"></i>
                        </span>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-9 pr-4 py-2.5 text-xs focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 transition-all placeholder-slate-400"
                               placeholder="Masukkan username Anda">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="fa-solid fa-key text-xs"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-9 pr-10 py-2.5 text-xs focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 transition-all placeholder-slate-400"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <i id="eyeIcon" class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-xs text-slate-600 select-none cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded bg-slate-100 border-slate-300 text-indigo-600 focus:ring-0 focus:ring-offset-0 mr-2">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-600/25 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 text-xs">
                    <span>Masuk ke Dashboard</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 space-y-2 text-center">
                <p class="text-xs text-slate-500">
                    Pelanggan ingin cek status pesanan cucian? 
                    <a href="{{ route('tracking') }}" class="text-emerald-600 hover:text-emerald-700 font-bold hover:underline">
                        Lacak Cucian Sekarang
                    </a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">&copy; {{ date('Y') }} Laundry Management System. All rights reserved.</p>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
