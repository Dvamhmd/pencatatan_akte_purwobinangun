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
                            <i class="fa-solid fa-envelope text-teal-300"></i> {{ $admin->email }}
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
                    <i class="fa-solid fa-paper-plane text-amber-300"></i> Identitas Notifikasi Email
                </p>
                <p class="text-[11px] leading-relaxed text-teal-50/90 font-normal">
                    Email di atas akan digunakan sebagai alamat pengirim/balasan pada saat sistem mengirimkan notifikasi otomatis status pengajuan ke warga.
                </p>
            </div>
        </div>

        <!-- Decorative background elements -->
        <div class="absolute -right-8 -bottom-12 w-48 h-48 rounded-full blur-2xl pointer-events-none" style="background-color: rgba(255, 255, 255, 0.08);"></div>
    </div>

    <!-- Forms Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Form Edit Profil & Email -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <div class="pb-4 mb-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <i class="fa-solid fa-user-pen text-[#0b7c89]"></i> Informasi Profil & Email
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui nama lengkap, email login, dan no. kontak admin</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                        Nama Lengkap Petugas / Administrator <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                            class="w-full text-xs pl-4 pr-10 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.5rem;">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user text-xs"></i>
                        </div>
                    </div>
                    @error('name')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                        Alamat Email Admin & Pengirim Notifikasi <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                            class="w-full text-xs pl-4 pr-10 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.5rem;">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-circle-info text-[#0b7c89]"></i> Email ini digunakan untuk login ke panel admin dan sebagai pengirim email notifikasi berkas.
                    </p>
                    @error('email')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">
                        Nomor HP / WhatsApp Petugas <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $admin->phone) }}" placeholder="Contoh: 081234567890"
                            class="w-full text-xs pl-4 pr-10 py-2.5 rounded-xl border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.5rem;">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                        </div>
                    </div>
                    @error('phone')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full sm:w-auto bg-[#0b7c89] hover:bg-[#08636e] active:bg-[#065059] text-white font-bold text-xs py-2.5 px-6 rounded-xl shadow-sm hover:shadow transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Perubahan Profil</span>
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
                <p class="text-xs text-slate-500 mt-0.5">Ubah kata sandi akun admin untuk keamanan</p>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">
                        Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full text-xs pl-4 pr-10 py-2.5 rounded-xl border {{ $errors->has('current_password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.5rem;">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </div>
                    </div>
                    @error('current_password')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold text-slate-700 mb-1">
                        Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="new_password" required minlength="6" placeholder="Minimal 6 karakter"
                            class="w-full text-xs pl-4 pr-10 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.5rem;">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key text-xs"></i>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                        Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6" placeholder="Ketik ulang kata sandi baru"
                            class="w-full text-xs pl-4 pr-10 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition"
                            style="padding-left: 1rem; padding-right: 2.5rem;">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-shield-check text-xs"></i>
                        </div>
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

</div>
@endsection
