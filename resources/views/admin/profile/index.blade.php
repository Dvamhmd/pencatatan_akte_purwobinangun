@extends('layouts.admin')

@section('title', 'Pengaturan Profil Admin')
@section('page_title', 'Pengaturan Profil & Email Petugas')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Card -->
    <div class="rounded-2xl shadow-sm relative overflow-hidden text-white p-6 sm:p-7" style="background: linear-gradient(135deg, #065b65 0%, #0b7c89 100%); background-color: #065b65;">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-inner shrink-0" style="background-color: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25);">
                    <i class="fa-solid fa-user-shield text-3xl text-teal-200"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-md" style="background-color: rgba(255, 255, 255, 0.2); color: #ffffff;">
                            Petugas Pelayanan Kalurahan
                        </span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold" style="background-color: rgba(94, 234, 212, 0.25); color: #ccfbf1; border: 1px solid rgba(94, 234, 212, 0.4);">
                            Administrator
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $admin->name }}</h2>
                    <div class="text-xs sm:text-sm text-teal-50/90 mt-2 flex flex-wrap items-center gap-x-4 gap-y-1.5">
                        <span class="inline-flex items-center gap-2 font-medium text-white">
                            <i class="fa-solid fa-envelope text-teal-300"></i> {{ $admin->email }} <span class="text-[10px] opacity-75 font-normal">(Login Admin)</span>
                        </span>
                        <span class="text-teal-200/70 hidden sm:inline px-1.5 font-bold">•</span>
                        <span class="inline-flex items-center gap-2 font-medium text-white">
                            <i class="fa-solid fa-phone text-teal-300"></i> {{ $admin->phone ?: 'Belum diatur' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="px-4 py-3.5 rounded-xl text-xs max-w-sm shrink-0 w-full md:w-auto" style="background-color: rgba(0, 0, 0, 0.22); border: 1px solid rgba(255, 255, 255, 0.18);">
                <p class="font-bold flex items-center gap-1.5 text-white mb-1">
                    <i class="fa-solid fa-envelope-circle-check text-amber-300"></i> Status Integrasi Notifikasi
                </p>
                <div class="text-[11px] leading-relaxed text-teal-50/90 space-y-1 font-normal">
                    <p><strong class="text-white">Pengirim ke Warga:</strong> {{ $mailSenderAddress }}</p>
                    <p><strong class="text-white">Penerima Pengajuan:</strong> {{ $adminNotificationEmail }}</p>
                </div>
            </div>
        </div>

        <!-- Decorative background elements -->
        <div class="absolute -right-8 -bottom-12 w-48 h-48 rounded-full blur-2xl pointer-events-none" style="background-color: rgba(255, 255, 255, 0.08);"></div>
    </div>

    <!-- Forms Grid: Profil & Kata Sandi -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Form Edit Profil Akun Admin -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <div class="pb-4 mb-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <i class="fa-solid fa-user-pen text-[#0b7c89]"></i> Data Akun Petugas
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui nama lengkap, email login admin, dan nomor kontak</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                        Nama Lengkap Petugas / Administrator <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative" style="position: relative;">
                        <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                            class="w-full text-xs rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                        <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                            <i class="fa-regular fa-user text-xs"></i>
                        </span>
                    </div>
                    @error('name')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                        Alamat Email Login Admin <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative" style="position: relative;">
                        <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                            class="w-full text-xs rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                        <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-circle-info text-[#0b7c89]"></i> Email ini digunakan untuk otentikasi/masuk ke panel admin.
                    </p>
                    @error('email')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">
                        Nomor HP / WhatsApp Petugas <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="relative" style="position: relative;">
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $admin->phone) }}" placeholder="Contoh: 081234567890"
                            class="w-full text-xs rounded-xl border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                        <span class="text-emerald-600 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                        </span>
                    </div>
                    @error('phone')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full sm:w-auto bg-[#0b7c89] hover:bg-[#08636e] active:bg-[#065059] text-white font-bold text-xs py-2.5 px-6 rounded-xl shadow-sm hover:shadow transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Perubahan Akun</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Form Ubah Password -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <div class="pb-4 mb-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i class="fa-solid fa-key text-amber-500"></i> Perbarui Kata Sandi
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Ubah kata sandi akun admin untuk keamanan login</p>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">
                        Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative" style="position: relative;">
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full text-xs rounded-xl border {{ $errors->has('current_password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                        <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </span>
                    </div>
                    @error('current_password')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold text-slate-700 mb-1">
                        Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative" style="position: relative;">
                        <input type="password" name="password" id="new_password" required minlength="6" placeholder="Minimal 6 karakter"
                            class="w-full text-xs rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                        <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                            <i class="fa-solid fa-key text-xs"></i>
                        </span>
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                        Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative" style="position: relative;">
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6" placeholder="Ketik ulang kata sandi baru"
                            class="w-full text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                        <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                            <i class="fa-solid fa-shield-check text-xs"></i>
                        </span>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 active:bg-black text-white font-bold text-xs py-2.5 px-6 rounded-xl shadow-sm hover:shadow transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-shield-check"></i>
                        <span>Perbarui Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Section Khusus: Konfigurasi Email Notifikasi (Pengirim & Penerima) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-7">
        <div class="pb-5 mb-6 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 text-[#0b7c89] flex items-center justify-center font-bold">
                        <i class="fa-solid fa-sliders text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-base">
                        Pengaturan Email Notifikasi Sistem & Pengajuan Masuk
                    </h3>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Atur alamat email pengirim status ke warga dan email admin yang menerima pemberitahuan permohonan baru secara fleksibel.
                </p>
            </div>

            <!-- Tombol Buka Panduan Konfigurasi -->
            <button type="button" onclick="openEmailGuideModal()" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-[#0b7c89] bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-xl transition cursor-pointer shrink-0">
                <i class="fa-solid fa-book-open text-xs"></i>
                <span>Panduan Konfigurasi SMTP</span>
            </button>
        </div>

        <form action="{{ route('admin.profile.email_settings') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- 1. Email Penerima Notifikasi Pengajuan Masuk -->
                <div class="p-5 rounded-xl border border-amber-200/80 bg-amber-50/20 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-amber-200/60">
                        <span class="text-xs font-black text-amber-900 uppercase tracking-wide flex items-center gap-1.5">
                            <i class="fa-solid fa-bell text-amber-600"></i> 1. Email Penerima Pengajuan Baru
                        </span>
                        <span class="text-[10px] px-2 py-0.5 font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300/60">
                            Masuk ke Admin
                        </span>
                    </div>

                    <div>
                        <label for="admin_notification_email" class="block text-xs font-bold text-slate-700 mb-1">
                            Alamat Email Admin Penerima <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="admin_notification_email" id="admin_notification_email"
                            value="{{ old('admin_notification_email', $adminNotificationEmail) }}" required
                            placeholder="Contoh: admin@purwobinangun.desa.id"
                            class="w-full text-xs rounded-xl border {{ $errors->has('admin_notification_email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition px-3.5 py-2.5">
                        @error('admin_notification_email')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="text-[11px] leading-relaxed text-slate-600 space-y-1 bg-white p-3 rounded-lg border border-amber-100">
                        <p class="font-semibold text-slate-800 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info text-amber-600"></i> Fungsi Alamat Email ini:
                        </p>
                        <p>
                            Menerima notifikasi pesan instan setiap kali ada permohonan baru yang berstatus <strong>Menunggu Verifikasi</strong> (Akte Kelahiran, Akte Kematian, Pendaftaran Warga Baru, dan Permohonan Perubahan Data).
                        </p>
                        <p class="text-slate-500 italic text-[10.5px]">
                            * Dapat diisi lebih dari satu email dipisahkan tanda koma (contoh: <code>petugas1@gmail.com, admin@purwobinangun.desa.id</code>).
                        </p>
                    </div>
                </div>

                <!-- 2. Email Pengirim Notifikasi ke Warga (SMTP) -->
                <div class="p-5 rounded-xl border border-teal-200/80 bg-teal-50/20 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-teal-200/60">
                        <span class="text-xs font-black text-teal-900 uppercase tracking-wide flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane text-teal-600"></i> 2. Email Pengirim Notifikasi ke Warga
                        </span>
                        <span class="text-[10px] px-2 py-0.5 font-bold rounded-full bg-teal-100 text-teal-800 border border-teal-300/60">
                            Keluar ke Warga
                        </span>
                    </div>

                    <div>
                        <label for="mail_from_address" class="block text-xs font-bold text-slate-700 mb-1">
                            Alamat Email Pengirim Resmi <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="mail_from_address" id="mail_from_address"
                            value="{{ old('mail_from_address', $mailSenderAddress) }}" required
                            placeholder="Contoh: ahmadtaupik580@gmail.com"
                            class="w-full text-xs rounded-xl border {{ $errors->has('mail_from_address') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition px-3.5 py-2.5">
                        @error('mail_from_address')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mail_from_name" class="block text-xs font-bold text-slate-700 mb-1">
                            Nama Pengirim Resmi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="mail_from_name" id="mail_from_name"
                            value="{{ old('mail_from_name', $mailSenderName) }}" required
                            placeholder="Contoh: Pelayanan Akte Purwobinangun"
                            class="w-full text-xs rounded-xl border {{ $errors->has('mail_from_name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition px-3.5 py-2.5">
                        @error('mail_from_name')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mail_password" class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>Sandi Aplikasi SMTP (App Password)</span>
                            <span class="text-slate-400 font-normal text-[10px]">Opsional / jika ganti akun</span>
                        </label>
                        <input type="password" name="mail_password" id="mail_password"
                            placeholder="Ketik sandi aplikasi 16 karakter (jika akun pengirim diubah)"
                            class="w-full text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition px-3.5 py-2.5">
                        <p class="text-[10.5px] text-slate-500 mt-1">
                            Kosongkan jika Anda tidak mengubah akun pengirim email saat ini.
                        </p>
                    </div>
                </div>

            </div>

            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[11px] text-slate-500 flex items-center gap-1.5">
                    <i class="fa-solid fa-lock text-slate-400"></i> Pengaturan yang disimpan akan langsung aktif tanpa perlu membuka file konfigurasi (.env) secara manual.
                </p>
                <button type="submit" class="w-full sm:w-auto bg-[#0b7c89] hover:bg-[#08636e] active:bg-[#065059] text-white font-bold text-xs py-2.5 px-6 rounded-xl shadow-sm hover:shadow transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-check"></i>
                    <span>Simpan Pengaturan Email</span>
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Modal Panduan Konfigurasi Email Pengirim (SMTP Google) -->
<div id="emailGuideModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between" style="background: linear-gradient(135deg, #065b65 0%, #0b7c89 100%);">
            <div class="flex items-center gap-3 text-white">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                    <i class="fa-solid fa-book-open text-base text-teal-200"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-white">Panduan Konfigurasi Email Pengirim (SMTP)</h3>
                    <p class="text-[11px] text-teal-100">Langkah mudah menghubungkan akun Gmail untuk mengirim notifikasi</p>
                </div>
            </div>
            <button type="button" onclick="closeEmailGuideModal()" class="text-white/80 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 leading-relaxed">
            
            <div class="p-3.5 rounded-xl bg-teal-50/70 border border-teal-200 flex items-start gap-3">
                <i class="fa-solid fa-lightbulb text-teal-700 text-sm mt-0.5 shrink-0"></i>
                <p class="text-[11.5px] text-teal-900">
                    Sistem menggunakan protokol SMTP resmi Google. Anda memerlukan <strong>Sandi Aplikasi (App Password) 16 karakter</strong> dari Akun Google pengirim agar email dapat terkirim tanpa terblokir sistem keamanan Google.
                </p>
            </div>

            <div class="space-y-3.5 pt-1">
                
                <!-- Langkah 1 -->
                <div class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="w-6 h-6 rounded-full bg-[#0b7c89] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        1
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs mb-0.5">Buka Keamanan Akun Google</h4>
                        <p class="text-slate-600 text-[11px]">
                            Buka browser dan kunjungi <a href="https://myaccount.google.com/security" target="_blank" class="text-[#0b7c89] font-bold underline">myaccount.google.com/security</a> pada akun Gmail yang ingin digunakan sebagai pengirim.
                        </p>
                    </div>
                </div>

                <!-- Langkah 2 -->
                <div class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="w-6 h-6 rounded-full bg-[#0b7c89] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        2
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs mb-0.5">Pastikan "Verifikasi 2 Langkah" Aktif</h4>
                        <p class="text-slate-600 text-[11px]">
                            Pastikan fitur <strong>Verifikasi 2 Langkah (2-Step Verification)</strong> dalam status <strong>Aktif</strong>. Fitur Sandi Aplikasi hanya tersedia jika Verifikasi 2 Langkah sudah aktif.
                        </p>
                    </div>
                </div>

                <!-- Langkah 3 -->
                <div class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="w-6 h-6 rounded-full bg-[#0b7c89] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        3
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs mb-0.5">Cari Menu "Sandi Aplikasi" (App Passwords)</h4>
                        <p class="text-slate-600 text-[11px]">
                            Pada kolom pencarian di bagian paling atas halaman Akun Google, ketik kata kunci <strong>"Sandi Aplikasi"</strong> atau <strong>"App Passwords"</strong>, lalu klik hasil pencarian tersebut.
                        </p>
                    </div>
                </div>

                <!-- Langkah 4 -->
                <div class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="w-6 h-6 rounded-full bg-[#0b7c89] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        4
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs mb-0.5">Buat Sandi Aplikasi Baru</h4>
                        <p class="text-slate-600 text-[11px]">
                            Masukkan nama aplikasi pemanggil, misalnya: <code class="bg-slate-200 px-1.5 py-0.5 rounded text-slate-800 font-mono text-[10.5px]">Web Pelayanan Purwobinangun</code>, lalu klik tombol <strong>Buat (Generate)</strong>.
                        </p>
                    </div>
                </div>

                <!-- Langkah 5 -->
                <div class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="w-6 h-6 rounded-full bg-[#0b7c89] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        5
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs mb-0.5">Salin 16 Karakter Sandi</h4>
                        <p class="text-slate-600 text-[11px]">
                            Google akan menampilkan popup berupa 16 karakter huruf (contoh: <code class="bg-amber-100 px-1.5 py-0.5 rounded text-amber-900 font-mono text-[10.5px]">abcd efgh ijkl mnop</code>). Salin seluruh kode tersebut.
                        </p>
                    </div>
                </div>

                <!-- Langkah 6 -->
                <div class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="w-6 h-6 rounded-full bg-[#0b7c89] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        6
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs mb-0.5">Masukkan ke Formulir Pengaturan</h4>
                        <p class="text-slate-600 text-[11px]">
                            Kembali ke halaman profil ini:
                            <br>• Masukkan alamat email di <strong>Alamat Email Pengirim Resmi</strong>.
                            <br>• Tempelkan 16 karakter sandi tadi ke kolom <strong>Sandi Aplikasi SMTP</strong> (tanpa tanda spasi).
                            <br>• Klik tombol <strong>Simpan Pengaturan Email</strong>. Selesai!
                        </p>
                    </div>
                </div>

            </div>

        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
            <button type="button" onclick="closeEmailGuideModal()" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition cursor-pointer">
                Tutup Panduan
            </button>
        </div>

    </div>
</div>

<script>
    function openEmailGuideModal() {
        const modal = document.getElementById('emailGuideModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeEmailGuideModal() {
        const modal = document.getElementById('emailGuideModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Tutup modal jika klik di luar area dialog
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('emailGuideModal');
        if (modal && e.target === modal) {
            closeEmailGuideModal();
        }
    });

    // Tutup modal jika menekan tombol Escape (ESC)
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmailGuideModal();
        }
    });
</script>
@endsection
