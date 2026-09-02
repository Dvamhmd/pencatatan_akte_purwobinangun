<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin') - Pelayanan Kalurahan Purwobinangun</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo-sleman.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex">

    <!-- Admin Sidebar -->
    <aside class="w-64 bg-[#065b65] text-white flex-shrink-0 flex flex-col min-h-screen border-r border-[#054850]">
        <!-- Brand Header -->
        <div class="p-4 border-b border-teal-800/60 flex items-center gap-3">
            <div class="w-10 h-12 bg-white/10 rounded-lg p-1 flex items-center justify-center border border-teal-400/30 shrink-0">
                <img src="{{ asset('images/logo-sleman.png') }}" alt="Logo Sleman" class="max-h-full max-w-full object-contain">
            </div>
            <div>
                <h2 class="font-bold text-sm leading-tight text-white">ADMIN KALURAHAN</h2>
                <p class="text-[11px] text-teal-200">Purwobinangun, Sleman</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-3 space-y-1 text-xs font-semibold">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#0b7c89] text-white shadow-sm' : 'text-teal-100 hover:bg-teal-800/50' }}">
                <i class="fa-solid fa-gauge-high w-4"></i>
                <span>Dashboard</span>
            </a>

            <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-teal-300">
                Layanan Kependudukan
            </div>

            <a href="{{ route('admin.birth.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.birth.*') ? 'bg-[#0b7c89] text-white shadow-sm' : 'text-teal-100 hover:bg-teal-800/50' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-baby w-4 text-teal-300"></i>
                    <span>Akte Kelahiran</span>
                </div>
            </a>

            <a href="{{ route('admin.death.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.death.*') ? 'bg-[#0b7c89] text-white shadow-sm' : 'text-teal-100 hover:bg-teal-800/50' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-book-skull w-4 text-rose-300"></i>
                    <span>Akte Kematian</span>
                </div>
            </a>

            <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-teal-300">
                Akses Publik
            </div>

            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-teal-100 hover:bg-teal-800/50 transition">
                <i class="fa-solid fa-arrow-up-right-from-square w-4"></i>
                <span>Buka Website Warga</span>
            </a>
        </nav>

        <!-- User Info & Logout Footer -->
        <div class="p-3 border-t border-teal-800/60 bg-[#054b53]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-amber-400 text-slate-900 font-bold text-xs flex items-center justify-center">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="truncate text-xs">
                        <p class="font-bold text-white truncate">{{ Auth::user()->name ?? 'Petugas' }}</p>
                        <p class="text-[10px] text-teal-300 truncate">Administrator</p>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" class="w-8 h-8 rounded-lg bg-teal-800/80 hover:bg-rose-600 text-white flex items-center justify-center transition">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Admin Topbar -->
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6">
            <div>
                <h1 class="text-base font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                <p class="text-xs text-slate-500">Sistem Pelayanan Administrasi Kependudukan Kalurahan Purwobinangun</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs text-slate-500 hidden sm:inline">
                    <i class="fa-regular fa-calendar-days text-[#0b7c89] mr-1"></i>
                    {{ date('d F Y') }}
                </span>
                <a href="{{ route('home') }}" target="_blank" class="text-xs font-semibold text-[#0b7c89] bg-teal-50 border border-teal-200 px-3 py-1.5 rounded-lg hover:bg-teal-100 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-globe"></i> Lihat Web
                </a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 p-6 overflow-y-auto">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg mr-3"></i>
                        <div>
                            <p class="font-bold text-sm text-emerald-900">Sukses</p>
                            <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mr-3"></i>
                        <div>
                            <p class="font-bold text-sm text-rose-900">Peringatan</p>
                            <p class="text-xs text-rose-700">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
