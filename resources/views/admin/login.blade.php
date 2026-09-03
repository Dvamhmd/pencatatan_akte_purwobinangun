<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Kalurahan Purwobinangun</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-h-screen flex flex-col items-center justify-start px-4 overflow-y-auto" style="padding-top: 100px; padding-bottom: 30px;">

    <div class="max-w-md w-full mx-auto">
        
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <div class="w-16 h-20 sm:w-20 sm:h-24 bg-white rounded-2xl p-2 flex items-center justify-center mx-auto shadow-md mb-2.5 border border-slate-200">
                <img src="{{ asset('images/logo-sleman.png') }}" alt="Logo Kabupaten Sleman" class="max-h-full max-w-full object-contain">
            </div>
            <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">PANEL PETUGAS PELAYANAN</h1>
            <p class="text-xs text-slate-600">Pemerintah Kalurahan Purwobinangun, Pakem, Sleman</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-7">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-[#0b7c89]"></i> Masuk ke Sistem
            </h2>

            @if(session('info'))
                <div class="popup-notification mb-4 bg-blue-50 border-l-4 border-blue-500 p-3 rounded-r text-xs text-blue-800 flex items-start justify-between">
                    <span>{{ session('info') }}</span>
                    <button type="button" data-dismiss="notification" class="close-notification-btn text-blue-500 hover:text-blue-700 ml-2 cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('success'))
                <div class="popup-notification mb-4 bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-r text-xs text-emerald-800 flex items-start justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" data-dismiss="notification" class="close-notification-btn text-emerald-500 hover:text-emerald-700 ml-2 cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="popup-notification mb-4 bg-rose-50 border-l-4 border-rose-500 p-3 rounded-r text-xs text-rose-800 flex items-start justify-between">
                    <span>{{ $errors->first() }}</span>
                    <button type="button" data-dismiss="notification" class="close-notification-btn text-rose-500 hover:text-rose-700 ml-2 cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4" autocomplete="off">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Petugas</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="off" placeholder="Masukkan email petugas" class="w-full text-xs pl-9 pr-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" required autocomplete="new-password" placeholder="Masukkan kata sandi" class="w-full text-xs pl-9 pr-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded text-[#0b7c89] focus:ring-[#0b7c89]">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs md:text-sm py-2.5 rounded-lg shadow-sm transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </button>
            </form>

            <div class="mt-5 pt-4 border-t border-slate-100 text-center">
                <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-[#0b7c89] font-medium transition flex items-center justify-center gap-1">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Website Warga
                </a>
            </div>
        </div>

        <p class="text-center text-[11px] text-slate-400 mt-4 mb-2">
            &copy; 2026 Pemerintah Kalurahan Purwobinangun, Sleman
        </p>

    </div>

</body>
</html>
