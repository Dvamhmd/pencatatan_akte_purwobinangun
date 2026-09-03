@extends('layouts.app')

@section('title', 'Pendaftaran Akun Warga Baru')

@section('content')
<div class="max-w-3xl mx-auto py-4">

    <!-- Card Registrasi Warga -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Header Banner Toska -->
        <div class="bg-gradient-to-r from-[#095b8c] to-[#059cb8] text-white p-6 sm:p-7 text-center relative overflow-hidden">
            <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur border border-white/30 flex items-center justify-center mx-auto mb-3 shadow-inner">
                <i class="fa-solid fa-user-plus text-2xl text-amber-300"></i>
            </div>
            <span class="text-[11px] font-bold uppercase tracking-widest text-teal-200 bg-black/20 px-3 py-1 rounded-full">
                Formulir Pendaftaran Akun Warga
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold mt-2 tracking-tight">DAFTAR AKUN WARGA</h2>
            <p class="text-xs text-teal-100 max-w-lg mx-auto mt-1 leading-relaxed">
                Daftarkan akun warga untuk mengajukan Akte Kelahiran & Kematian secara online. Data NIK dan KK akan diverifikasi oleh petugas kelurahan.
            </p>
        </div>

        <div class="p-6 sm:p-8 space-y-6">

            <!-- Banner Panduan Verifikasi -->
            <div class="p-4 bg-teal-50 border border-teal-200 rounded-xl flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-[#095b8c] text-lg mt-0.5 shrink-0"></i>
                <div class="text-xs text-slate-700 leading-relaxed">
                    <p class="font-bold text-[#095b8c] mb-1">Ketentuan Pendaftaran & Verifikasi:</p>
                    <ul class="list-disc pl-4 space-y-1 text-slate-600">
                        <li>Pastikan <strong>NIK</strong> dan <strong>Nomor KK</strong> terdiri dari 16 digit angka yang valid sesuai dokumen resmi.</li>
                        <li>Nomor Kartu Keluarga (KK) akan digunakan untuk mengelompokkan dan mengakses seluruh data pengajuan akte keluarga Anda.</li>
                        <li>Setelah pendaftaran berhasil, akun akan berstatus <strong>Menunggu Verifikasi</strong> dan akan diperiksa oleh petugas kelurahan sebelum dapat digunakan.</li>
                    </ul>
                </div>
            </div>

            @if(isset($prefill) && $prefill)
                <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl">
                    <p class="text-xs font-bold text-amber-900">Mode Perbaikan Data (Pendaftaran Ulang)</p>
                    <p class="text-xs text-amber-800 mt-1">
                        Silakan perbaiki data yang sebelumnya belum sesuai. Catatan penolakan sebelumnya: <em>"{{ $prefill->rejection_reason }}"</em>
                    </p>
                </div>
            @endif

            <!-- Form Registrasi -->
            <form action="{{ route('warga.register.submit') }}" method="POST" class="space-y-6" autocomplete="off">
                @csrf

                <!-- BAGIAN 1: IDENTITAS KEPENDUDUKAN -->
                <div class="space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#095b8c] pb-2 border-b border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-id-card"></i> 1. Identitas Kependudukan
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block text-xs font-bold text-slate-700 mb-1">
                                Nomor Induk Kependudukan (NIK) <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik', $prefill->nik ?? '') }}" maxlength="16" required placeholder="16 digit NIK sesuai KTP" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('nik') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                            @error('nik')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor KK -->
                        <div>
                            <label for="family_card_no" class="block text-xs font-bold text-slate-700 mb-1">
                                Nomor Kartu Keluarga (KK) <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="family_card_no" id="family_card_no" value="{{ old('family_card_no', $prefill->family_card_no ?? '') }}" maxlength="16" required placeholder="16 digit Nomor KK" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('family_card_no') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                            @error('family_card_no')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                            Nama Lengkap Sesuai KTP / KK <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $prefill->name ?? '') }}" required placeholder="Nama lengkap tanpa singkatan" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                        @error('name')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat & Tanggal Lahir & Jenis Kelamin -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="birth_place" class="block text-xs font-bold text-slate-700 mb-1">
                                Tempat Lahir <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $prefill->birth_place ?? '') }}" required placeholder="Kota/Kabupaten Lahir" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('birth_place') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                            @error('birth_place')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birth_date" class="block text-xs font-bold text-slate-700 mb-1">
                                Tanggal Lahir <span class="text-rose-600">*</span>
                            </label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', isset($prefill->birth_date) ? $prefill->birth_date->format('Y-m-d') : '') }}" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('birth_date') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20">
                            @error('birth_date')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="block text-xs font-bold text-slate-700 mb-1">
                                Jenis Kelamin <span class="text-rose-600">*</span>
                            </label>
                            <select name="gender" id="gender" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('gender') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('gender', $prefill->gender ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $prefill->gender ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 2: ALAMAT & KONTAK -->
                <div class="space-y-4 pt-2">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#095b8c] pb-2 border-b border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot"></i> 2. Alamat & Kontak
                    </h3>

                    <div>
                        <label for="address" class="block text-xs font-bold text-slate-700 mb-1">
                            Alamat Lengkap / Padukuhan <span class="text-rose-600">*</span>
                        </label>
                        <textarea name="address" id="address" rows="2" required placeholder="Contoh: Kadilobo, Purwobinangun, Pakem, Sleman" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('address') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">{{ old('address', $prefill->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <label for="rt" class="block text-xs font-bold text-slate-700 mb-1">
                                RT <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="rt" id="rt" value="{{ old('rt', $prefill->rt ?? '') }}" maxlength="5" required placeholder="01" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('rt') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 text-center">
                            @error('rt')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rw" class="block text-xs font-bold text-slate-700 mb-1">
                                RW <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="rw" id="rw" value="{{ old('rw', $prefill->rw ?? '') }}" maxlength="5" required placeholder="02" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('rw') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 text-center">
                            @error('rw')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">
                                No. HP / WhatsApp <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $prefill->phone ?? '') }}" required placeholder="081234567890" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                            @error('phone')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                            Email <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $prefill->email ?? '') }}" autocomplete="off" placeholder="email@contoh.com" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                        @error('email')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- BAGIAN 3: DATA AKUN & KATA SANDI -->
                <div class="space-y-4 pt-2">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#095b8c] pb-2 border-b border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i> 3. Kata Sandi Akun
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                                Kata Sandi <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative" style="position: relative;">
                                <input type="password" name="password" id="reg_password" required minlength="6" autocomplete="new-password" placeholder="Minimal 6 karakter" class="w-full text-xs rounded-lg border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs" style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.65rem; padding-bottom: 0.65rem;">
                                <button type="button" onclick="toggleRegPassword('reg_password', 'reg-pwd-icon-1')" class="flex items-center justify-center text-slate-400 hover:text-slate-700 cursor-pointer focus:outline-none" style="position: absolute; right: 0.65rem; top: 50%; transform: translateY(-50%); width: 2rem; height: 2rem;">
                                    <i id="reg-pwd-icon-1" class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                                Konfirmasi Kata Sandi <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative" style="position: relative;">
                                <input type="password" name="password_confirmation" id="reg_password_confirmation" required minlength="6" autocomplete="new-password" placeholder="Ketik ulang kata sandi" class="w-full text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs" style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.65rem; padding-bottom: 0.65rem;">
                                <button type="button" onclick="toggleRegPassword('reg_password_confirmation', 'reg-pwd-icon-2')" class="flex items-center justify-center text-slate-400 hover:text-slate-700 cursor-pointer focus:outline-none" style="position: absolute; right: 0.65rem; top: 50%; transform: translateY(-50%); width: 2rem; height: 2rem;">
                                    <i id="reg-pwd-icon-2" class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#095b8c] hover:bg-[#074a73] text-white font-extrabold text-sm py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center">
                        Kirim Pendaftaran Akun Warga
                    </button>
                </div>

            </form>

            <!-- Link to Login -->
            <div class="pt-4 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-600">
                    Sudah pernah mendaftar?
                    <a href="{{ route('warga.login') }}" class="font-bold text-[#095b8c] hover:underline ml-1">
                        Masuk ke Akun Warga &rarr;
                    </a>
                </p>
            </div>

        </div>

    </div>

</div>

<script>
function toggleRegPassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// LocalStorage Draft Management untuk Form Pendaftaran Warga
document.addEventListener('DOMContentLoaded', function() {
    const REGISTER_DRAFT_KEY = 'purwobinangun_warga_register_draft';
    const draftFields = [
        'nik',
        'family_card_no',
        'name',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'rt',
        'rw',
        'phone',
        'email'
    ];

    function loadRegisterDraft() {
        try {
            const rawDraft = localStorage.getItem(REGISTER_DRAFT_KEY);
            if (!rawDraft) return;

            const draft = JSON.parse(rawDraft);
            if (!draft || typeof draft !== 'object') return;

            draftFields.forEach(function(fieldId) {
                const el = document.getElementById(fieldId);
                if (el && (!el.value || el.value.trim() === '')) {
                    if (draft[fieldId] !== undefined && draft[fieldId] !== null) {
                        el.value = draft[fieldId];
                    }
                }
            });
        } catch (e) {
            console.warn('Gagal memulihkan draft pendaftaran dari localStorage:', e);
        }
    }

    function saveRegisterDraft() {
        try {
            const draft = {};
            let hasContent = false;

            draftFields.forEach(function(fieldId) {
                const el = document.getElementById(fieldId);
                if (el) {
                    draft[fieldId] = el.value || '';
                    if (el.value && el.value.trim() !== '') {
                        hasContent = true;
                    }
                }
            });

            if (hasContent) {
                localStorage.setItem(REGISTER_DRAFT_KEY, JSON.stringify(draft));
            } else {
                localStorage.removeItem(REGISTER_DRAFT_KEY);
            }
        } catch (e) {
            console.warn('Gagal menyimpan draft pendaftaran ke localStorage:', e);
        }
    }

    // Pasang event listener input & change pada semua input form pendaftaran
    draftFields.forEach(function(fieldId) {
        const el = document.getElementById(fieldId);
        if (el) {
            el.addEventListener('input', saveRegisterDraft);
            el.addEventListener('change', saveRegisterDraft);
        }
    });

    // Pulihkan data draft jika halaman direfresh
    loadRegisterDraft();

    // Pastikan penyimpanan lokal tetap tersinkronisasi dengan nilai yang ada
    saveRegisterDraft();
});
</script>
@endsection
