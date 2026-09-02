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
                <span class="text-teal-400/60 hidden sm:inline">|</span>
                
                @if(Auth::check() && Auth::user()->isWarga())
                    <span class="inline-flex items-center gap-1.5 bg-black/25 px-2.5 py-1 rounded text-teal-100 font-semibold border border-white/10">
                        <i class="fa-solid fa-circle-user text-amber-300"></i>
                        <span class="truncate max-w-[120px] sm:max-w-[160px]">{{ Auth::user()->name }}</span>
                        <span class="hidden md:inline-block bg-teal-900/60 text-teal-200 text-[10px] px-1.5 py-0.5 rounded font-mono font-normal">
                            KK: {{ Auth::user()->family_card_no }}
                        </span>
                    </span>
                    <form action="{{ route('warga.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-rose-600/80 hover:bg-rose-700 text-white px-2 py-1 rounded transition inline-flex items-center gap-1" title="Keluar dari Akun Warga">
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
                    <a href="{{ route('birth.list') }}" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ request()->routeIs('birth.list') || request()->routeIs('submissions.index') ? 'text-[#095b8c] bg-teal-50 border border-teal-200 font-bold' : 'hover:text-[#095b8c] hover:bg-slate-50' }}">
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
            <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-start justify-between">
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
            <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex items-start justify-between">
                <div class="flex items-center">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mr-3"></i>
                    <div>
                        <p class="font-bold text-sm text-rose-900">Terjadi Kesalahan</p>
                        <p class="text-xs text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Sidebar Navigation Grid (Sesuai Referensi Gambar Kelurahan) -->
            <aside class="lg:col-span-3 space-y-5">
                
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

                        <a href="{{ route('birth.list') }}" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-[#059cb8] transition text-center group">
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
