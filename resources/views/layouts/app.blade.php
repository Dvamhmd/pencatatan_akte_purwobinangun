<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pencatatan Akte Kelahiran & Kematian') - Kalurahan Purwobinangun</title>
    
    <!-- Favicon -->
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
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Topbar Toska Resmi (Sesuai Warna --clr2 #095b8c & #059cb8) -->
    <header class="bg-[#095b8c] text-white text-xs font-medium border-b border-[#074a73] shadow-xs">
        <div class="max-w-7xl mx-auto px-4 py-2 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center gap-1.5 bg-black/20 px-2.5 py-1 rounded text-teal-100">
                    <i class="fa-regular fa-calendar-days text-[#b8ede6]"></i>
                    <span id="live-date">Selasa, 1 September 2026</span>
                </span>
                <span class="hidden md:inline-flex items-center gap-1 text-teal-200">
                    <i class="fa-regular fa-clock"></i>
                    <span id="live-clock">15:00:00 WIB</span>
                </span>
            </div>
            <div class="flex items-center space-x-2.5 text-[11px]">
                <a href="https://www.purwobinangun.desa.id/pengaduan" target="_blank" class="hover:text-[#b8ede6] transition hidden sm:inline-flex items-center gap-1">
                    <i class="fa-solid fa-bullhorn"></i> Pengaduan
                </a>

                @if(Auth::check() && Auth::user()->isWarga())
                    @php
                        $wargaNotifications = \App\Services\CitizenNotificationService::getNotificationsForUser(Auth::user());
                        $totalNotifCount = $wargaNotifications->count();
                    @endphp
                    
                    <!-- Notification Bell Component Warga -->
                    <div class="relative inline-block text-left" id="warga-notif-container">
                        <button type="button" 
                                id="warga-notif-btn" 
                                class="inline-flex items-center gap-1.5 bg-black/25 hover:bg-black/35 px-2.5 py-1 rounded text-teal-100 hover:text-white font-semibold border border-white/10 transition cursor-pointer relative"
                                title="Daftar Notifikasi Warga" 
                                aria-label="Daftar Notifikasi Warga" 
                                aria-expanded="false">
                            <span class="relative inline-flex items-center">
                                <i class="fa-solid fa-bell text-amber-300 text-[13px]"></i>
                                <span id="warga-notif-ping" class="absolute -top-1 -right-1 flex h-2 w-2 {{ $totalNotifCount > 0 ? '' : 'hidden' }}">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                </span>
                            </span>
                            <span class="hidden md:inline">Notifikasi</span>
                            <span id="warga-notif-badge" class="bg-rose-600 text-white text-[10px] font-extrabold px-1.5 py-0.2 rounded-full min-w-[18px] text-center border border-white/20 {{ $totalNotifCount > 0 ? '' : 'hidden' }}">
                                {{ $totalNotifCount }}
                            </span>
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div id="warga-notif-dropdown" 
                             class="hidden fixed sm:absolute inset-x-2 sm:inset-x-auto sm:right-0 top-12 sm:top-full mt-2 w-auto sm:w-[420px] max-w-[calc(100vw-16px)] sm:max-w-[440px] bg-white rounded-2xl shadow-2xl border border-slate-200/90 text-slate-800 z-50 overflow-hidden transform transition-all duration-200 origin-top-right">
                            
                            <!-- Header Panel -->
                            <div class="p-3.5 bg-gradient-to-r from-[#095b8c] to-[#059cb8] text-white flex items-center justify-between shadow-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center text-amber-300">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-sm text-white leading-tight">Notifikasi Masuk</h3>
                                        <p class="text-[10px] text-teal-100 font-normal">Informasi permohonan, akun, & data warga</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <button type="button" 
                                            id="btn-mark-all-read" 
                                            class="text-[10px] bg-white/20 hover:bg-white/30 text-white px-2 py-1 rounded-md transition font-medium cursor-pointer inline-flex items-center gap-1"
                                            title="Tandai semua sudah dibaca">
                                        <i class="fa-solid fa-check-double text-[9px]"></i> Dibaca
                                    </button>
                                    <button type="button" 
                                            id="btn-clear-all-notifs" 
                                            class="text-[10px] bg-rose-500/30 hover:bg-rose-600 text-white px-2 py-1 rounded-md transition font-medium cursor-pointer border border-white/20 inline-flex items-center gap-1"
                                            title="Hapus semua riwayat notifikasi">
                                        <i class="fa-solid fa-trash-can text-[9px]"></i> Hapus Semua
                                    </button>
                                    <button type="button" 
                                            id="btn-close-notif-dropdown" 
                                            class="w-7 h-7 rounded-lg hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer text-xs"
                                            title="Tutup">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Filter Pills -->
                            <div class="flex items-center gap-1 px-3 py-2 bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 overflow-x-auto">
                                <button type="button" data-filter="all" class="notif-filter-btn px-2.5 py-1 rounded-full bg-[#095b8c] text-white cursor-pointer transition">
                                    Semua (<span class="notif-filter-count" data-filter-type="all">{{ $totalNotifCount }}</span>)
                                </button>
                                <button type="button" data-filter="akte" class="notif-filter-btn px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 hover:bg-slate-300 cursor-pointer transition">
                                    Akte (<span class="notif-filter-count" data-filter-type="akte">{{ $wargaNotifications->whereIn('type', ['birth', 'death'])->count() }}</span>)
                                </button>
                                <button type="button" data-filter="profile" class="notif-filter-btn px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 hover:bg-slate-300 cursor-pointer transition">
                                    Profil & KK (<span class="notif-filter-count" data-filter-type="profile">{{ $wargaNotifications->where('type', 'profile')->count() }}</span>)
                                </button>
                                <button type="button" data-filter="account" class="notif-filter-btn px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 hover:bg-slate-300 cursor-pointer transition">
                                    Akun (<span class="notif-filter-count" data-filter-type="account">{{ $wargaNotifications->where('type', 'account')->count() }}</span>)
                                </button>
                            </div>

                            <!-- Notifications Scrollable List -->
                            <div id="warga-notif-list" class="max-h-[380px] overflow-y-auto divide-y divide-slate-100 bg-white" data-user-id="{{ Auth::id() }}">
                                @forelse($wargaNotifications as $item)
                                    <div class="notif-item notif-unread p-3.5 transition flex items-start gap-3 relative cursor-pointer group"
                                         data-notif-id="{{ $item['id'] }}"
                                         data-notif-type="{{ in_array($item['type'], ['birth', 'death']) ? 'akte' : $item['type'] }}"
                                         onclick="handleNotificationClick('{{ $item['id'] }}', '{{ $item['url'] }}')">
                                        
                                        <!-- Unread Dot Indicator -->
                                        <div class="notif-unread-dot w-2.5 h-2.5 rounded-full bg-rose-500 mt-1 shrink-0 transition"></div>

                                        <!-- Status Icon -->
                                        <div class="status-icon-container w-9 h-9 rounded-xl {{ $item['icon_bg'] }} flex items-center justify-center shrink-0 text-sm shadow-2xs group-hover:scale-105 transition">
                                            <i class="{{ $item['icon_class'] }}"></i>
                                        </div>

                                        <!-- Notification Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1.5 mb-1">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="text-[9px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                                                        <i class="{{ $item['category_icon'] }} text-[9px] mr-0.5"></i> {{ $item['category'] }}
                                                    </span>
                                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded border {{ $item['status_badge_class'] }}">
                                                        {{ $item['status_label'] }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    <span class="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                                                        <i class="fa-regular fa-clock text-[9px]"></i> {{ $item['formatted_time'] }}
                                                    </span>
                                                    <button type="button" 
                                                            class="btn-delete-notif w-5 h-5 rounded hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center transition cursor-pointer text-[10px]" 
                                                            title="Hapus notifikasi ini" 
                                                            aria-label="Hapus notifikasi ini"
                                                            onclick="event.stopPropagation(); window.deleteNotification('{{ $item['id'] }}', event);">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-[#095b8c] transition leading-snug">
                                                {{ $item['title'] }}
                                            </h4>

                                            <p class="text-[11px] text-slate-600 mt-1 leading-relaxed">
                                                {!! $item['message'] !!}
                                            </p>

                                            @if(!empty($item['admin_note']))
                                                <div class="mt-2 text-[10px] bg-rose-50 border-l-2 border-rose-400 p-2 rounded-r text-rose-800">
                                                    <span class="font-bold block text-rose-900"><i class="fa-solid fa-circle-info"></i> Catatan Admin:</span>
                                                    {{ $item['admin_note'] }}
                                                </div>
                                            @endif

                                            <div class="mt-2.5 flex items-center justify-between">
                                                <span class="notif-action-link text-[10px] font-bold text-[#095b8c] group-hover:text-[#059cb8] inline-flex items-center gap-1">
                                                    <span>{{ $item['url_label'] }}</span>
                                                    <i class="fa-solid fa-arrow-right text-[9px] transition-transform group-hover:translate-x-0.5"></i>
                                                </span>
                                                @if(!empty($item['reference_no']))
                                                    <span class="text-[9px] font-mono text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">
                                                        {{ $item['reference_no'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center" id="warga-notif-empty">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2.5 text-lg">
                                            <i class="fa-regular fa-bell-slash"></i>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-700">Belum Ada Notifikasi</h4>
                                        <p class="text-[11px] text-slate-500 mt-1 max-w-xs mx-auto">
                                            Status pengajuan akta, verifikasi akun, dan perubahan data kependudukan akan tampil di sini.
                                        </p>
                                    </div>
                                @endforelse

                                <div class="p-8 text-center hidden" id="warga-notif-empty">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2.5 text-lg">
                                        <i class="fa-regular fa-bell-slash"></i>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-700">Belum Ada Notifikasi</h4>
                                    <p class="text-[11px] text-slate-500 mt-1 max-w-xs mx-auto">
                                        Status pengajuan akta, verifikasi akun, dan perubahan data kependudukan akan tampil di sini.
                                    </p>
                                </div>
                            </div>

                            <!-- Footer Links -->
                            <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs font-semibold">
                                <a href="{{ route('submissions.index') }}" class="text-[#095b8c] hover:text-[#059cb8] inline-flex items-center gap-1.5 transition text-[11px]">
                                    <i class="fa-solid fa-list-check"></i> Semua Pengajuan
                                </a>
                                <a href="{{ route('profile.index') }}" class="text-slate-600 hover:text-[#095b8c] inline-flex items-center gap-1.5 transition text-[11px]">
                                    <i class="fa-solid fa-user-gear"></i> Profil Saya
                                </a>
                            </div>

                        </div>
                    </div>
                @endif

                <span class="text-teal-400/60 hidden sm:inline">|</span>
                
                @if(Auth::check() && Auth::user()->isWarga())
                    <form action="{{ route('warga.logout') }}" method="POST" class="inline" onsubmit="try { localStorage.removeItem('purwobinangun_birth_form_draft'); localStorage.removeItem('purwobinangun_birth_form_draft_{{ Auth::id() }}'); localStorage.removeItem('purwobinangun_death_form_draft'); localStorage.removeItem('purwobinangun_death_form_draft_{{ Auth::id() }}'); localStorage.removeItem('purwobinangun_warga_register_draft'); if(window.indexedDB){ indexedDB.deleteDatabase('PurwobinangunBirthDB'); indexedDB.deleteDatabase('PurwobinangunBirthDB_{{ Auth::id() }}'); indexedDB.deleteDatabase('PurwobinangunFormDB'); } } catch(e){}">
                        @csrf
                        <button type="submit" class="bg-rose-600/80 hover:bg-rose-700 text-white px-2 py-1 rounded transition inline-flex items-center gap-1 cursor-pointer" title="Keluar dari Akun Warga">
                            <i class="fa-solid fa-right-from-bracket"></i> <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                @elseif(Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 bg-black/25 hover:bg-black/35 px-2.5 py-1 rounded text-teal-100 hover:text-white font-semibold border border-white/10 transition" title="Buka Dashboard Petugas">
                        <i class="fa-solid fa-user-shield text-amber-300"></i>
                        <span class="truncate max-w-[120px] sm:max-w-[160px]">{{ Auth::user()->name }}</span>
                        <span class="bg-amber-500/30 text-amber-200 text-[9px] px-1.5 py-0.5 rounded ml-0.5 border border-amber-400/30">
                            Admin
                        </span>
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-rose-600/80 hover:bg-rose-700 text-white px-2 py-1 rounded transition inline-flex items-center gap-1 cursor-pointer" title="Keluar dari Akun Petugas">
                            <i class="fa-solid fa-right-from-bracket"></i> <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('warga.login') }}" class="bg-teal-50 hover:bg-white text-[#095b8c] font-extrabold px-2.5 py-1 rounded transition inline-flex items-center gap-1 shadow-xs">
                        <i class="fa-solid fa-user-lock text-amber-600"></i> Masuk / Daftar Warga
                    </a>
                @endif

                <span class="text-teal-400/60">|</span>
                <a href="{{ Auth::check() && Auth::user()->isAdmin() ? route('admin.dashboard') : route('admin.login') }}" class="bg-amber-400 hover:bg-amber-500 text-slate-950 font-bold px-2.5 py-1 rounded transition inline-flex items-center gap-1 shadow-xs">
                    <i class="fa-solid fa-user-shield"></i> Portal Petugas
                </a>
            </div>
        </div>
    </header>

    <!-- Main Civic Header & Banner dengan HIGHLIGHT MENU AKTE KELAHIRAN & KEMATIAN -->
    <section class="hero-purwobinangun text-white py-6 shadow-md border-b-4 border-amber-400">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-5">
            
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="w-16 h-20 md:w-20 md:h-24 bg-white/15 backdrop-blur rounded-2xl p-2 flex items-center justify-center border border-white/30 shadow-lg shrink-0 hover:scale-105 transition">
                    <!-- Logo Resmi Sleman -->
                    <img src="{{ asset('images/logo-sleman.png') }}" alt="Logo Kabupaten Sleman" class="max-h-full max-w-full object-contain drop-shadow-md">
                </a>
                <div>
                    <div class="mb-1">
                        <span class="text-[11px] md:text-xs uppercase tracking-widest text-[#b8ede6] font-bold">
                            PEMERINTAH KALURAHAN PURWOBINANGUN
                        </span>
                    </div>

                    <!-- Highlight Judul Menu Layanan Akte -->
                    <h1 class="text-xl md:text-3xl font-extrabold tracking-tight text-white drop-shadow-md flex items-center gap-2">
                        PENCATATAN AKTE KELAHIRAN & KEMATIAN
                    </h1>
                    
                    <p class="text-xs md:text-sm text-teal-100 flex items-center gap-1.5 mt-1">
                        <i class="fa-solid fa-location-dot text-amber-300"></i> Kapanewon Pakem, Kabupaten Sleman, D.I. Yogyakarta 55582
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- Main Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-13">
                <div class="flex space-x-1 md:space-x-4 overflow-x-auto py-2 text-xs md:text-sm font-semibold text-slate-700">
                    <a href="https://www.purwobinangun.desa.id/" target="_blank" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-[#095b8c] hover:bg-slate-100 transition flex items-center gap-1 border border-slate-200">
                        <i class="fa-solid fa-globe text-[#059cb8]"></i> Web Kelurahan
                    </a>
                    <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ request()->routeIs('home') ? 'text-[#095b8c] bg-teal-50 border border-teal-200 font-bold' : 'hover:text-[#095b8c] hover:bg-slate-50' }}">
                        <i class="fa-solid fa-house"></i> Beranda Menu
                    </a>
                    <a href="{{ route('birth.create') }}" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ request()->routeIs('birth.create') ? 'text-[#095b8c] bg-teal-50 border border-teal-200 font-bold' : 'hover:text-[#095b8c] hover:bg-slate-50' }}">
                        <i class="fa-solid fa-baby text-[#059cb8]"></i> Akte Kelahiran
                    </a>
                    <a href="{{ route('death.create') }}" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ request()->routeIs('death.*') ? 'text-rose-700 bg-rose-50 border border-rose-200 font-bold' : 'hover:text-rose-700 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-book-skull text-rose-600"></i> Akte Kematian
                    </a>
                    <a href="{{ route('submissions.index') }}" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ request()->routeIs('submissions.index') || request()->routeIs('birth.list') ? 'text-[#095b8c] bg-teal-50 border border-teal-200 font-bold' : 'hover:text-[#095b8c] hover:bg-slate-50' }}">
                        <i class="fa-solid fa-list-check text-[#059cb8]"></i> Daftar Pengajuan
                    </a>

                    <a href="{{ route('tracking.index') }}" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ request()->routeIs('tracking.*') ? 'text-amber-800 bg-amber-50 border border-amber-200 font-bold' : 'hover:text-amber-700 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-magnifying-glass text-amber-600"></i> Cek Status
                    </a>
                    <a href="{{ route('guidelines') }}" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ request()->routeIs('guidelines') ? 'text-[#095b8c] bg-teal-50 border border-teal-200 font-bold' : 'hover:text-[#095b8c] hover:bg-slate-50' }}">
                        <i class="fa-solid fa-file-circle-check text-[#059cb8]"></i> Syarat Berkas
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content Container with Sidebar Layout -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-6">
        
        <!-- Flash Message -->
        @if(session('success'))
            <div class="popup-notification mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-start justify-between">
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
            <div class="popup-notification mb-5 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex items-start justify-between">
                <div class="flex items-center">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mr-3"></i>
                    <div>
                        <p class="font-bold text-sm text-rose-900">Terjadi Kesalahan</p>
                        <p class="text-xs text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" data-dismiss="notification" class="close-notification-btn text-rose-500 hover:text-rose-700 transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="popup-notification mb-5 bg-sky-50 border-l-4 border-sky-500 p-4 rounded-r-xl shadow-sm flex items-start justify-between">
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Sidebar Navigation Grid (Sesuai Referensi Gambar Kelurahan) -->
            <aside class="lg:col-span-3 space-y-5">
                
                @if(Auth::check() && Auth::user()->isWarga())
                    <!-- Card Akun Warga Mini di Sidebar -->
                    <div class="bg-gradient-to-br from-[#095b8c] to-[#059cb8] text-white rounded-xl shadow-xs p-3.5 border border-[#074a73]">
                        <div class="flex items-center gap-3 mb-2.5">
                            <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center text-white text-base font-bold shrink-0 border border-white/30">
                                <i class="fa-solid fa-user-check text-amber-300"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="font-bold text-xs truncate text-white">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-teal-100 font-mono truncate">NIK: {{ Auth::user()->nik }}</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.index') }}" class="w-full bg-white/20 hover:bg-white text-teal-100 hover:text-[#095b8c] font-bold text-xs py-1.5 px-2 rounded-lg transition flex items-center justify-center gap-1.5 border border-white/25 shadow-2xs">
                            <i class="fa-solid fa-user-pen text-amber-300"></i>
                            <span>Ubah Profil & Data Warga</span>
                        </a>
                    </div>
                @endif
                
                <!-- Quick Icon Grid (Mirip Kotak Menu di Referensi) -->
                <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 px-2 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                        <span>Navigasi Menu</span>
                        <i class="fa-solid fa-shapes text-[#059cb8]"></i>
                    </h3>
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-[#059cb8] transition text-center group">
                            <div class="w-8 h-8 rounded-lg bg-teal-100 text-[#059cb8] flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                <i class="fa-solid fa-house-chimney text-xs"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-700 leading-tight">Beranda</span>
                        </a>

                        <a href="{{ route('birth.create') }}" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-[#059cb8] transition text-center group">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-[#095b8c] flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                <i class="fa-solid fa-baby text-xs"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-700 leading-tight">Akte Lahir</span>
                        </a>

                        <a href="{{ route('submissions.index') }}" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-[#059cb8] transition text-center group">
                            <div class="w-8 h-8 rounded-lg bg-teal-100 text-[#095b8c] flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                <i class="fa-solid fa-list-check text-xs"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-700 leading-tight">Daftar Ajuan</span>
                        </a>

                        <a href="{{ route('death.create') }}" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-500 transition text-center group">
                            <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                <i class="fa-solid fa-book-skull text-xs"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-700 leading-tight">Akte Mati</span>
                        </a>

                        <a href="{{ route('tracking.index') }}" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-500 transition text-center group">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-700 leading-tight">Lacak Berkas</span>
                        </a>

                        <a href="{{ route('guidelines') }}" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-[#059cb8] transition text-center group">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                <i class="fa-solid fa-file-contract text-xs"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-700 leading-tight">Syarat Berkas</span>
                        </a>
                    </div>
                </div>

                <!-- Info Kontak Resmi Kalurahan (Data dari web purwobinangun.desa.id) -->
                <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 pb-1 border-b border-slate-100 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-[#059cb8]"></i> Informasi Kalurahan
                    </h3>
                    <ul class="text-xs space-y-2.5 text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-location-dot text-[#059cb8] mt-0.5 shrink-0"></i>
                            <span class="text-[11px] leading-tight">Jl. Pakem-Turi KM 4, Watuadeg, Purwobinangun, Pakem, Sleman, DIY 55582</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-[#059cb8] shrink-0"></i>
                            <span class="text-[11px]">(0274) 896920</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-green-600 shrink-0"></i>
                            <a href="https://api.whatsapp.com/send/?phone=6289514947444" target="_blank" class="text-[11px] hover:text-[#059cb8] font-medium">0895-1494-7444</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-[#059cb8] shrink-0"></i>
                            <span class="text-[11px] truncate">Purwobinangun@slemankab.go.id</span>
                        </li>
                    </ul>
                </div>

                <!-- Tautan Menu Kalurahan Lainnya -->
                <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2 pb-1 border-b border-slate-100">
                        Menu Terkait
                    </h3>
                    <ul class="text-xs space-y-1.5">
                        <li>
                            <a href="https://www.purwobinangun.desa.id/data-wilayah" target="_blank" class="flex items-center justify-between text-slate-600 hover:text-[#095b8c] py-1 border-b border-slate-100">
                                <span>Wilayah Administratif</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.purwobinangun.desa.id/pengaduan" target="_blank" class="flex items-center justify-between text-slate-600 hover:text-[#095b8c] py-1">
                                <span>Layanan Pengaduan</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Banner Maklumat Pelayanan -->
                <div class="bg-gradient-to-br from-[#095b8c] to-[#059cb8] text-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-center gap-2 mb-1.5">
                        <i class="fa-solid fa-stamp text-amber-300 text-base"></i>
                        <h4 class="font-bold text-xs">Pelayanan Gratis & Cepat</h4>
                    </div>
                    <p class="text-[11px] text-teal-100 leading-relaxed">
                        Seluruh pengurusan surat pengantar Akte Kelahiran dan Kematian di Kalurahan Purwobinangun <strong>TIDAK DIPUNGUT BIAYA (GRATIS)</strong>.
                    </p>
                </div>

            </aside>

            <!-- Main Content Area -->
            <section class="lg:col-span-9">
                @yield('content')
            </section>

        </div>

    </main>

    <!-- Civic Footer (Data Resmi Web Purwobinangun) -->
    <footer class="bg-slate-900 text-slate-400 text-xs mt-auto border-t-4 border-[#059cb8]">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <h5 class="text-white font-bold text-sm mb-2 flex items-center gap-2">
                        <img src="{{ asset('images/logo-sleman.png') }}" alt="Logo" class="w-5 h-6 object-contain">
                        Kalurahan Purwobinangun
                    </h5>
                    <p class="text-slate-400 leading-relaxed text-[11px]">
                        Menu Pelayanan Pencatatan Administrasi Kependudukan (Surat Pengantar Akte Kelahiran & Kematian) terintegrasi pada Website Resmi Pemerintah Kalurahan Purwobinangun, Kapanewon Pakem, Kabupaten Sleman.
                    </p>
                    <div class="flex items-center gap-3 mt-3">
                        <a href="https://www.instagram.com/purwobinangun" target="_blank" class="w-7 h-7 rounded-full bg-slate-800 hover:bg-[#059cb8] text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-instagram text-xs"></i>
                        </a>
                        <a href="https://www.youtube.com/@kalurahanpurwobinangun_off1827" target="_blank" class="w-7 h-7 rounded-full bg-slate-800 hover:bg-rose-600 text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-youtube text-xs"></i>
                        </a>
                        <a href="https://www.facebook.com/purwobinangun" target="_blank" class="w-7 h-7 rounded-full bg-slate-800 hover:bg-blue-600 text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send/?phone=6289514947444" target="_blank" class="w-7 h-7 rounded-full bg-slate-800 hover:bg-green-600 text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h5 class="text-white font-bold text-sm mb-2">Layanan Kependudukan</h5>
                    <ul class="space-y-1.5 text-[11px]">
                        <li><a href="{{ route('birth.create') }}" class="hover:text-teal-300 transition">Pengajuan Akte Kelahiran</a></li>
                        <li><a href="{{ route('death.create') }}" class="hover:text-teal-300 transition">Pengajuan Akte Kematian</a></li>
                        <li><a href="{{ route('tracking.index') }}" class="hover:text-teal-300 transition">Cek Status & Resi Permohonan</a></li>
                        <li><a href="{{ route('guidelines') }}" class="hover:text-teal-300 transition">Daftar Syarat Dokumen</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold text-sm mb-2">Kontak Kantor Kalurahan</h5>
                    <ul class="space-y-1.5 text-[11px] text-slate-400">
                        <li>Jl. Pakem-Turi KM 4, Watuadeg, Purwobinangun, Pakem, Sleman</li>
                        <li>Telepon: (0274) 896920</li>
                        <li>Email: Purwobinangun@slemankab.go.id</li>
                        <li>Website: <a href="https://www.purwobinangun.desa.id/" target="_blank" class="text-[#059cb8] hover:underline">www.purwobinangun.desa.id</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-2 text-[11px]">
                <p>&copy; 2026 Pemerintah Kalurahan Purwobinangun. Sistem Informasi Terpadu.</p>
                <p class="text-slate-500">Kapanewon Pakem, Kabupaten Sleman, D.I. Yogyakarta 55582</p>
            </div>
        </div>
    </footer>

    <!-- Simple Live Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const day = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const dateElement = document.getElementById('live-date');
            const clockElement = document.getElementById('live-clock');

            if (dateElement) dateElement.innerText = `${dayName}, ${day} ${month} ${year}`;
            if (clockElement) clockElement.innerText = `${hours}:${minutes}:${seconds} WIB`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
