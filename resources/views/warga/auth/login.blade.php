@extends('layouts.app')

@section('title', 'Masuk Akun Warga')

@section('content')
<div class="max-w-md mx-auto py-6">

    <!-- Card Login Warga -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Header Banner Toska -->
        <div class="bg-[#095b8c] text-white p-6 text-center relative">
            <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-xs border border-white/20 flex items-center justify-center mx-auto mb-3 shadow-xs">
                <i class="fa-solid fa-id-card text-2xl text-amber-300"></i>
            </div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-[#b8ede6] bg-black/20 px-3 py-0.5 rounded-full inline-block">
                Layanan Mandiri Warga
            </span>
            <h2 class="text-xl font-extrabold mt-2 tracking-tight text-white">MASUK AKUN WARGA</h2>
            <p class="text-xs text-teal-100 max-w-xs mx-auto mt-1 leading-relaxed font-normal">
                Gunakan NIK dan kata sandi Anda untuk mengakses pengajuan akte sesuai Kartu Keluarga (KK).
            </p>
        </div>

        <div class="p-6 sm:p-7 space-y-5">

            <!-- Notifikasi Menunggu Verifikasi (Pending) -->
            @if(session('pending_notice'))
                <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl shadow-2xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-clock text-base"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-amber-900 uppercase tracking-wide">Status: Menunggu Verifikasi</h4>
                            <p class="text-xs text-amber-800 mt-1 leading-relaxed font-medium">
                                {{ session('pending_notice')['message'] }}
                            </p>
                            <div class="mt-2 text-[11px] text-amber-700 bg-white/70 p-2 rounded border border-amber-200">
                                <strong>Nama:</strong> {{ session('pending_notice')['name'] }} | <strong>NIK:</strong> {{ session('pending_notice')['nik'] }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notifikasi Akun Ditolak (Rejected) -->
            @if(session('rejected_notice'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl shadow-2xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation text-base"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-xs text-rose-900 uppercase tracking-wide">Status: Pendaftaran Ditolak</h4>
                            <p class="text-xs text-rose-800 mt-1 font-medium leading-relaxed">
                                Pendaftaran akun Anda belum dapat disetujui oleh petugas kelurahan.
                            </p>
                            <div class="mt-2 p-2.5 bg-white rounded border border-rose-200 text-xs">
                                <span class="font-bold text-rose-950 block mb-0.5"><i class="fa-solid fa-note-sticky text-rose-600"></i> Alasan / Catatan Petugas:</span>
                                <p class="text-rose-800 italic leading-relaxed">{{ session('rejected_notice')['reason'] }}</p>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('warga.register', ['reapply_nik' => session('rejected_notice')['nik']]) }}" class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition shadow-2xs">
                                    <i class="fa-solid fa-pen-to-square"></i> Perbaiki Data & Ajukan Ulang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notifikasi Sukses Pendaftaran Baru -->
            @if(session('registration_success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl shadow-2xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-circle-check text-base"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-emerald-900 uppercase tracking-wide">Pendaftaran Berhasil Terkirim!</h4>
                            <p class="text-xs text-emerald-800 mt-1 leading-relaxed">
                                {{ session('registration_success')['message'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('warga.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input NIK -->
                <div>
                    <label for="nik" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Induk Kependudukan (NIK) <span class="text-rose-600">*</span>
                    </label>
                    <div class="relative" style="position: relative;">
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" maxlength="16" required autofocus placeholder="Masukkan 16 digit NIK Anda" class="w-full text-sm rounded-lg border {{ $errors->has('nik') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-[#095b8c]' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 text-slate-800 placeholder:text-slate-400 placeholder:text-xs bg-white transition" style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.65rem; padding-bottom: 0.65rem;">
                        <span class="text-slate-400 pointer-events-none flex items-center justify-center" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%);">
                            <i class="fa-solid fa-id-card text-sm"></i>
                        </span>
                    </div>
                    @error('nik')
                        <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1 font-medium">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi <span class="text-rose-600">*</span>
                    </label>
                    <div class="relative" style="position: relative;">
                        <input type="password" name="password" id="password" required placeholder="Masukkan kata sandi akun warga" class="w-full text-sm rounded-lg border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300 focus:border-[#095b8c]' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 text-slate-800 placeholder:text-slate-400 placeholder:text-xs bg-white transition" style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.65rem; padding-bottom: 0.65rem;">
                        <button type="button" onclick="togglePasswordVisibility()" title="Tampilkan / Sembunyikan Kata Sandi" class="flex items-center justify-center text-slate-400 hover:text-slate-700 transition cursor-pointer z-10 focus:outline-none rounded-md" style="position: absolute; right: 0.65rem; top: 50%; transform: translateY(-50%); width: 2rem; height: 2rem;">
                            <i id="password-toggle-icon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1 font-medium">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-[#095b8c] border-slate-300 rounded focus:ring-[#095b8c]">
                        <span class="text-xs text-slate-600 select-none">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#095b8c] hover:bg-[#074a73] text-white font-bold text-sm py-2.5 px-4 rounded-lg shadow-sm hover:shadow transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Akun Warga
                    </button>
                </div>

            </form>

            <!-- Divider & Registration Link -->
            <div class="pt-4 border-t border-slate-200 text-center space-y-2.5">
                <p class="text-xs text-slate-600">
                    Belum memiliki akun warga?
                </p>
                <a href="{{ route('warga.register') }}" class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-lg border-2 border-[#095b8c] text-[#095b8c] hover:bg-teal-50 font-bold text-xs transition">
                    <i class="fa-solid fa-user-plus"></i> Daftar Akun Warga Baru
                </a>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    *Pendaftaran akun gratis dan diverifikasi langsung oleh petugas Kalurahan.
                </p>
            </div>

        </div>

    </div>

</div>

<script>
function togglePasswordVisibility() {
    const input = document.getElementById('password');
    const icon = document.getElementById('password-toggle-icon');
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
</script>
@endsection
