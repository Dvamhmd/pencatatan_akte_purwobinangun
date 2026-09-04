<!DOCTYPE html>
<html lang="id" class="h-full">
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
<body data-role="admin" class="admin-portal bg-slate-100 text-slate-800 antialiased h-screen h-[100dvh] overflow-hidden flex m-0 p-0">

    <!-- Admin Sidebar -->
    <aside class="w-72 bg-[#065b65] text-white flex-shrink-0 flex flex-col h-screen h-[100dvh] max-h-screen border-r border-[#054850] z-30 overflow-hidden">
        <!-- Brand Header -->
        <div class="p-4 sm:p-5 border-b border-teal-800/60 flex items-center gap-3.5 shrink-0 bg-[#065b65]">
            <div class="w-11 h-13 bg-white/10 rounded-xl p-1.5 flex items-center justify-center border border-teal-400/30 shrink-0">
                <img src="{{ asset('images/logo-sleman.png') }}" alt="Logo Sleman" class="max-h-full max-w-full object-contain">
            </div>
            <div>
                <h2 class="font-extrabold text-sm sm:text-base leading-snug text-white tracking-wide">ADMIN KALURAHAN</h2>
                <p class="text-xs text-teal-200 font-medium">Purwobinangun, Sleman</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 min-h-0 p-3.5 space-y-1 text-sm font-semibold overflow-y-auto custom-admin-scrollbar">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#0b7c89] text-white shadow-sm font-bold' : 'text-teal-100 hover:bg-teal-800/50 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center text-base {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-teal-300' }}"></i>
                <span>Dashboard</span>
            </a>

            <div class="pt-4 pb-1.5 px-3 text-[11px] uppercase font-bold tracking-wider text-teal-300/80">
                Layanan Kependudukan
            </div>

            <a href="{{ route('admin.birth.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.birth.*') ? 'bg-[#0b7c89] text-white shadow-sm font-bold' : 'text-teal-100 hover:bg-teal-800/50 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-baby w-5 text-center text-teal-300 text-base"></i>
                    <span>Akte Kelahiran</span>
                </div>
                @php
                    $pendingBirthCount = \App\Models\BirthCertificate::where('status', 'pending')->count();
                @endphp
                @if($pendingBirthCount > 0)
                    <span class="bg-amber-400 text-slate-950 font-bold text-xs px-2 py-0.5 rounded-full shadow-xs">
                        {{ $pendingBirthCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.death.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.death.*') ? 'bg-[#0b7c89] text-white shadow-sm font-bold' : 'text-teal-100 hover:bg-teal-800/50 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-book-skull w-5 text-center text-rose-300 text-base"></i>
                    <span>Akte Kematian</span>
                </div>
                @php
                    $pendingDeathCount = \App\Models\DeathCertificate::where('status', 'pending')->count();
                @endphp
                @if($pendingDeathCount > 0)
                    <span class="bg-amber-400 text-slate-950 font-bold text-xs px-2 py-0.5 rounded-full shadow-xs">
                        {{ $pendingDeathCount }}
                    </span>
                @endif
            </a>

            <div class="pt-4 pb-1.5 px-3 text-[11px] uppercase font-bold tracking-wider text-teal-300/80">
                Manajemen Pengguna
            </div>

            <a href="{{ route('admin.citizens.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.citizens.*') ? 'bg-[#0b7c89] text-white shadow-sm font-bold' : 'text-teal-100 hover:bg-teal-800/50 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-user-check w-5 text-center text-amber-300 text-base"></i>
                    <span>Verifikasi Warga</span>
                </div>
                @php
                    $pendingWargaCount = \App\Models\User::where('role', 'warga')->where('status', 'pending')->count();
                @endphp
                @if($pendingWargaCount > 0)
                    <span class="bg-amber-400 text-slate-950 font-bold text-xs px-2 py-0.5 rounded-full shadow-xs">
                        {{ $pendingWargaCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.profile_requests.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.profile_requests.*') ? 'bg-[#0b7c89] text-white shadow-sm font-bold' : 'text-teal-100 hover:bg-teal-800/50 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-user-pen w-5 text-center text-teal-300 text-base"></i>
                    <span>Perubahan Data</span>
                </div>
                @php
                    $pendingProfileRequestCount = \App\Models\ProfileUpdateRequest::where('status', 'pending')->count();
                @endphp
                @if($pendingProfileRequestCount > 0)
                    <span class="bg-amber-400 text-slate-950 font-bold text-xs px-2 py-0.5 rounded-full shadow-xs">
                        {{ $pendingProfileRequestCount }}
                    </span>
                @endif
            </a>

            <div class="pt-4 pb-1.5 px-3 text-[11px] uppercase font-bold tracking-wider text-teal-300/80">
                Arsip & Penonaktifan
            </div>

            <a href="{{ route('admin.archive.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.archive.*') ? 'bg-[#0b7c89] text-white shadow-sm font-bold' : 'text-teal-100 hover:bg-teal-800/50 hover:text-white' }}">
                <i class="fa-solid fa-box-archive w-5 text-center text-amber-200 text-base"></i>
                <span>Arsip Pengajuan</span>
            </a>

            <div class="pt-4 pb-1.5 px-3 text-[11px] uppercase font-bold tracking-wider text-teal-300/80">
                Pengaturan
            </div>

            <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.profile.*') ? 'bg-[#0b7c89] text-white shadow-sm font-bold' : 'text-teal-100 hover:bg-teal-800/50 hover:text-white' }}">
                <i class="fa-solid fa-user-gear w-5 text-center text-teal-300 text-base"></i>
                <span>Profil & Email Notifikasi</span>
            </a>

        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 h-screen h-[100dvh] max-h-screen overflow-hidden">
        
        <!-- Admin Topbar -->
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 shrink-0">
            <div>
                <h1 class="text-lg font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                <p class="text-xs text-slate-500">Sistem Pelayanan Administrasi Kependudukan Kalurahan Purwobinangun</p>
            </div>
            <div class="flex items-center gap-3 sm:gap-4">
                <span class="text-sm text-slate-500 hidden sm:inline">
                    <i class="fa-regular fa-calendar-days text-[#0b7c89] mr-1"></i>
                    {{ date('d F Y') }}
                </span>
                <a href="{{ route('home') }}" target="_blank" class="text-sm font-semibold text-[#0b7c89] bg-teal-50 border border-teal-200 px-3 py-1.5 rounded-lg hover:bg-teal-100 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-globe"></i> <span class="hidden md:inline">Lihat Web</span>
                </a>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline m-0">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-rose-600 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-lg hover:bg-rose-100 hover:text-rose-700 transition flex items-center gap-1.5 cursor-pointer" title="Keluar dari Panel Admin">
                        <i class="fa-solid fa-right-from-bracket"></i> <span>Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 min-h-0 p-6 pb-28 overflow-y-auto">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="popup-notification mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg mr-3"></i>
                        <div>
                            <p class="font-bold text-sm text-emerald-900">Sukses</p>
                            <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" data-dismiss="notification" class="close-notification-btn text-emerald-500 hover:text-emerald-700 transition cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="popup-notification mb-5 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mr-3"></i>
                        <div>
                            <p class="font-bold text-sm text-rose-900">Peringatan</p>
                            <p class="text-xs text-rose-700">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" data-dismiss="notification" class="close-notification-btn text-rose-500 hover:text-rose-700 transition cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div class="popup-notification mb-5 bg-sky-50 border-l-4 border-sky-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-info text-sky-600 text-lg mr-3"></i>
                        <div>
                            <p class="font-bold text-sm text-sky-900">Informasi</p>
                            <p class="text-xs text-sky-700">{{ session('info') }}</p>
                        </div>
                    </div>
                    <button type="button" data-dismiss="notification" class="close-notification-btn text-sky-500 hover:text-sky-700 transition cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
