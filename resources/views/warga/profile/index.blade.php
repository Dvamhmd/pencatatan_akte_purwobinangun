@extends('layouts.app')

@section('title', 'Profil & Data Warga - ' . $warga->name)

@section('content')
<div class="space-y-6">

    <!-- Header & Hero Profile Card -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden dissolve-card">
        <div class="bg-gradient-to-r from-[#095b8c] via-[#0b7c89] to-[#059cb8] p-6 text-white relative">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-5 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 backdrop-blur border border-white/30 flex items-center justify-center text-white text-3xl font-extrabold shadow-inner shrink-0">
                        <i class="fa-solid fa-user text-amber-300"></i>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="bg-black/25 text-teal-100 text-[11px] font-bold px-2.5 py-0.5 rounded border border-white/10 uppercase tracking-wider">
                                Akun Warga Terdaftar
                            </span>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $warga->status_badge_class }}">
                                {{ $warga->status_label }}
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">{{ $warga->name }}</h1>
                        <div class="text-xs text-teal-100 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                            <span class="inline-flex items-center gap-1.5 font-mono">
                                <i class="fa-solid fa-id-card text-amber-300"></i> NIK: {{ $warga->nik }}
                            </span>
                            <span class="text-teal-300/60 hidden sm:inline">•</span>
                            <span class="inline-flex items-center gap-1.5 font-mono">
                                <i class="fa-solid fa-people-roof text-teal-300"></i> No. KK: {{ $warga->family_card_no }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-black/20 backdrop-blur rounded-xl p-3 border border-white/15 text-xs text-teal-50 max-w-xs shrink-0 w-full md:w-auto">
                    <p class="font-bold text-amber-300 flex items-center gap-1.5 mb-1">
                        <i class="fa-solid fa-shield-halved"></i> Keamanan Data
                    </p>
                    <p class="text-[11px] leading-relaxed text-teal-100">
                        Pastikan data kependudukan dan kontak Anda selalu mutakhir untuk kelancaran verifikasi permohonan akta.
                    </p>
                </div>
            </div>

            <!-- Background subtle pattern -->
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        </div>
    </div>

    <!-- Grid Konten: Form Ubah Profil (Kiri) & Form Password + Info KK (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri: Form Ubah Profil & Data Kependudukan Warga (7 Col) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-user-pen text-[#059cb8]"></i> Ubah Data Profil Warga
                    </h2>
                    <span class="text-[11px] text-slate-400"><i class="fa-solid fa-asterisk text-rose-500 text-[9px]"></i> Wajib diisi</span>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="p-5 space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <!-- Readonly NIK & No KK -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-3.5 bg-slate-50 rounded-xl border border-slate-200/80">
                        <div>
                            <label class="block text-slate-500 font-bold mb-1">
                                Nomor Induk Kependudukan (NIK)
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="text" value="{{ $warga->nik }}" readonly
                                    class="w-full text-xs font-mono font-bold bg-white text-slate-700 px-3 py-2 rounded-lg border border-slate-300 cursor-not-allowed">
                                <span class="text-emerald-600 text-base" title="Terkunci & Terverifikasi"><i class="fa-solid fa-circle-check"></i></span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">NIK terdaftar permanen</p>
                        </div>

                        <div>
                            <label class="block text-slate-500 font-bold mb-1">
                                Nomor Kartu Keluarga (KK)
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="text" value="{{ $warga->family_card_no }}" readonly
                                    class="w-full text-xs font-mono font-bold bg-teal-50/50 text-[#095b8c] px-3 py-2 rounded-lg border border-teal-200 cursor-not-allowed">
                                <span class="text-teal-600 text-base" title="Nomor KK Terdaftar"><i class="fa-solid fa-lock"></i></span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Terhubung dengan KK keluarga</p>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block font-bold text-slate-700 mb-1">
                            Nama Lengkap Sesuai KTP <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative" style="position: relative;">
                            <input type="text" name="name" id="name" value="{{ old('name', $warga->name) }}" required
                                class="w-full text-xs rounded-lg border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                                <i class="fa-regular fa-user text-xs"></i>
                            </span>
                        </div>
                        @error('name')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat & Tanggal Lahir -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="birth_place" class="block font-bold text-slate-700 mb-1">
                                Tempat Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $warga->birth_place) }}" required placeholder="Contoh: Sleman"
                                class="w-full text-xs rounded-lg border {{ $errors->has('birth_place') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 0.875rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            @error('birth_place')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birth_date" class="block font-bold text-slate-700 mb-1">
                                Tanggal Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $warga->birth_date ? $warga->birth_date->format('Y-m-d') : '') }}" required
                                class="w-full text-xs rounded-lg border {{ $errors->has('birth_date') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 0.875rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            @error('birth_date')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition {{ old('gender', $warga->gender) === 'L' ? 'bg-teal-50/80 border-[#059cb8] text-[#095b8c] font-bold' : 'border-slate-200 hover:bg-slate-50' }}">
                                <input type="radio" name="gender" value="L" {{ old('gender', $warga->gender) === 'L' ? 'checked' : '' }} required class="text-[#095b8c] focus:ring-[#059cb8]">
                                <span><i class="fa-solid fa-mars text-blue-500 mr-1"></i> Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition {{ old('gender', $warga->gender) === 'P' ? 'bg-pink-50/80 border-pink-400 text-pink-700 font-bold' : 'border-slate-200 hover:bg-slate-50' }}">
                                <input type="radio" name="gender" value="P" {{ old('gender', $warga->gender) === 'P' ? 'checked' : '' }} required class="text-pink-600 focus:ring-pink-500">
                                <span><i class="fa-solid fa-venus text-pink-500 mr-1"></i> Perempuan</span>
                            </label>
                        </div>
                        @error('gender')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kontak: No. HP & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block font-bold text-slate-700 mb-1">
                                Nomor HP / WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative" style="position: relative;">
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $warga->phone) }}" required placeholder="Contoh: 081234567890"
                                    class="w-full text-xs rounded-lg border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                    style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                                <span class="text-emerald-600 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                </span>
                            </div>
                            @error('phone')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block font-bold text-slate-700 mb-1">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative" style="position: relative;">
                                <input type="email" name="email" id="email" value="{{ old('email', $warga->email) }}" required placeholder="nama@email.com"
                                    class="w-full text-xs rounded-lg border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                    style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                                <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                                    <i class="fa-regular fa-envelope text-xs"></i>
                                </span>
                            </div>
                            @error('email')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat Lengkap KTP/KK -->
                    <div>
                        <label for="address" class="block font-bold text-slate-700 mb-1">
                            Alamat Lengkap (Padukuhan / Jalan / Dusun) <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="address" id="address" rows="2" required placeholder="Contoh: Watuadeg, Purwobinangun, Pakem, Sleman"
                            class="w-full text-xs rounded-lg border {{ $errors->has('address') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                            style="padding: 0.625rem 0.875rem;">{{ old('address', $warga->address) }}</textarea>
                        @error('address')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RT & RW -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="rt" class="block font-bold text-slate-700 mb-1">
                                RT <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="rt" id="rt" value="{{ old('rt', $warga->rt) }}" required placeholder="01" maxlength="5"
                                class="w-full text-xs rounded-lg border {{ $errors->has('rt') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 0.875rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            @error('rt')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rw" class="block font-bold text-slate-700 mb-1">
                                RW <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="rw" id="rw" value="{{ old('rw', $warga->rw) }}" required placeholder="05" maxlength="5"
                                class="w-full text-xs rounded-lg border {{ $errors->has('rw') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 0.875rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            @error('rw')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit" class="bg-[#095b8c] hover:bg-[#059cb8] active:bg-[#074a73] text-white font-bold text-xs py-2.5 px-6 rounded-lg shadow-xs hover:shadow transition flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Ubah Kata Sandi & Anggota Keluarga Satu KK (5 Col) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Card Ganti Password -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200">
                    <h2 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-key text-amber-500"></i> Ganti Kata Sandi Akun
                    </h2>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="p-5 space-y-3.5 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block font-bold text-slate-700 mb-1">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative" style="position: relative;">
                            <input type="password" name="current_password" id="current_password" required
                                class="w-full text-xs rounded-lg border {{ $errors->has('current_password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                        </div>
                        @error('current_password')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password" class="block font-bold text-slate-700 mb-1">
                            Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative" style="position: relative;">
                            <input type="password" name="password" id="new_password" required minlength="6" placeholder="Minimal 6 karakter"
                                class="w-full text-xs rounded-lg border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                                <i class="fa-solid fa-key text-xs"></i>
                            </span>
                        </div>
                        @error('password')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-bold text-slate-700 mb-1">
                            Ulangi Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative" style="position: relative;">
                            <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6" placeholder="Ketik ulang kata sandi baru"
                                class="w-full text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                                <i class="fa-solid fa-shield-check text-xs"></i>
                            </span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 active:bg-black text-white font-bold text-xs py-2.5 px-4 rounded-lg shadow-xs hover:shadow transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Anggota Keluarga Satu KK -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-people-roof text-[#095b8c]"></i> Anggota Keluarga Satu KK
                    </h2>
                    <span class="text-[10px] font-mono bg-teal-100/70 text-[#095b8c] font-bold px-2 py-0.5 rounded">
                        KK: {{ $warga->family_card_no }}
                    </span>
                </div>

                <div class="p-5 space-y-3 text-xs">
                    <p class="text-slate-500 text-[11px] leading-relaxed">
                        Akun warga lain yang terdaftar dalam satu Nomor Kartu Keluarga (KK):
                    </p>

                    @if($familyMembers->count() > 0)
                        <div class="space-y-2">
                            @foreach($familyMembers as $member)
                                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/80 flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $member->name }}</p>
                                        <p class="text-[10px] text-slate-500 font-mono mt-0.5">NIK: {{ $member->nik }}</p>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $member->status_badge_class }}">
                                        {{ $member->status_label }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 bg-slate-50 rounded-lg border border-dashed border-slate-200 text-center text-slate-400 italic text-[11px]">
                            Belum ada anggota keluarga lain yang terdaftar di akun ini.
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
