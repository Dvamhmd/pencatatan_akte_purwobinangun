@extends('layouts.admin')

@section('title', 'Detail Akun Warga: ' . $citizen->name)
@section('page_title', 'Pemeriksaan & Verifikasi Akun Warga')

@section('content')
<div class="space-y-6">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.citizens.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#0b7c89] hover:underline bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Akun Warga
        </a>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500">Status Akun:</span>
            <span class="inline-block text-xs font-bold px-3 py-1 rounded-full border {{ $citizen->status_badge_class }}">
                {{ $citizen->status_label }}
            </span>
        </div>
    </div>

    <!-- Grid 2 Kolom: Detail Akun & Form Verifikasi -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Kolom Kiri: Data Lengkap Warga (8 col) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Card Data Pribadi -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-[#0b7c89]"></i> Data Identitas Warga
                    </h3>
                    <span class="text-[11px] text-slate-400">ID #{{ $citizen->id }}</span>
                </div>

                <div class="p-5 space-y-4 text-xs">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-slate-500 font-medium block">Nomor Induk Kependudukan (NIK):</span>
                            <span class="text-sm font-extrabold font-mono text-slate-900">{{ $citizen->nik }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 font-medium block">Nomor Kartu Keluarga (KK):</span>
                            <span class="text-sm font-extrabold font-mono text-[#0b7c89] bg-teal-50 px-2 py-0.5 rounded border border-teal-200 inline-block mt-0.5">
                                {{ $citizen->family_card_no }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-slate-500 font-medium block">Nama Lengkap:</span>
                            <span class="text-sm font-bold text-slate-900">{{ $citizen->name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 font-medium block">Jenis Kelamin:</span>
                            <span class="font-semibold text-slate-800">
                                {{ $citizen->gender === 'L' ? 'Laki-laki (L)' : ($citizen->gender === 'P' ? 'Perempuan (P)' : '-') }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-slate-500 font-medium block">Tempat & Tanggal Lahir:</span>
                            <span class="font-semibold text-slate-800">
                                {{ $citizen->birth_place }}, {{ $citizen->birth_date ? $citizen->birth_date->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 font-medium block">Nomor Telepon / WhatsApp:</span>
                            <span class="font-bold text-slate-900 flex items-center gap-1.5 mt-0.5">
                                <i class="fa-brands fa-whatsapp text-emerald-600 text-sm"></i>
                                <a href="https://api.whatsapp.com/send/?phone={{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $citizen->phone)) }}" target="_blank" class="hover:underline text-emerald-700">
                                    {{ $citizen->phone }}
                                </a>
                            </span>
                        </div>
                    </div>

                    <div class="pb-3 border-b border-slate-100">
                        <span class="text-slate-500 font-medium block">Alamat Lengkap KTP/KK:</span>
                        <span class="font-medium text-slate-800 leading-relaxed block mt-0.5">
                            {{ $citizen->address }}
                        </span>
                        <span class="inline-block mt-1 text-[11px] text-slate-600 bg-slate-100 px-2 py-0.5 rounded font-semibold">
                            RT {{ $citizen->rt }} / RW {{ $citizen->rw }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-500 font-medium block">Email:</span>
                            <span class="font-medium text-slate-800">{{ $citizen->email ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 font-medium block">Waktu Mendaftar:</span>
                            <span class="font-medium text-slate-800">
                                {{ $citizen->created_at->translatedFormat('d F Y, H:i') }} WIB
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Card Anggota Keluarga Satu KK & Riwayat Pengajuan -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-people-roof text-[#0b7c89]"></i> Data & Pengajuan KK Ini (KK: {{ $citizen->family_card_no }})
                    </h3>
                </div>

                <div class="p-5 space-y-4 text-xs">
                    
                    <!-- Anggota Keluarga Lain Terdaftar -->
                    <div>
                        <h4 class="font-bold text-slate-800 mb-2">Anggota Keluarga Terdaftar Lainnya:</h4>
                        @if($familyMembers->count() > 0)
                            <div class="space-y-1.5">
                                @foreach($familyMembers as $member)
                                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-lg border border-slate-200/80">
                                        <div>
                                            <span class="font-bold text-slate-900">{{ $member->name }}</span>
                                            <span class="text-[10px] text-slate-500 font-mono block">NIK: {{ $member->nik }}</span>
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $member->status_badge_class }}">
                                            {{ $member->status_label }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-slate-400 italic">Belum ada anggota keluarga lain yang mendaftar dengan Nomor KK ini.</p>
                        @endif
                    </div>

                    <!-- Pengajuan Akte Kelahiran dari KK Ini -->
                    <div class="pt-3 border-t border-slate-100">
                        <h4 class="font-bold text-slate-800 mb-2">Pengajuan Akte Kelahiran (KK Ini):</h4>
                        @if($birthSubmissions->count() > 0)
                            <div class="space-y-2">
                                @foreach($birthSubmissions as $b)
                                    <div class="flex items-center justify-between p-2.5 bg-teal-50/50 rounded-lg border border-teal-200/60">
                                        <div>
                                            <span class="font-extrabold text-[#0b7c89] font-mono">{{ $b->registration_no }}</span>
                                            <p class="font-bold text-slate-800">{{ $b->child_name }}</p>
                                            <span class="text-[10px] text-slate-500">Pemohon: {{ $b->applicant_name }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $b->status_badge_class }} mb-1">
                                                {{ $b->status_label }}
                                            </span>
                                            <a href="{{ route('admin.birth.show', $b) }}" class="block text-[11px] text-[#0b7c89] font-bold hover:underline">
                                                Buka Berkas &rarr;
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-slate-400 italic">Belum ada pengajuan akte kelahiran dari KK ini.</p>
                        @endif
                    </div>

                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Panel Tindakan Verifikasi (5 col) -->
        <div class="lg:col-span-5 space-y-6">
            
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden sticky top-6">
                <div class="bg-[#065b65] text-white px-5 py-3.5 flex items-center justify-between">
                    <h3 class="font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-user-shield text-amber-300"></i> Verifikasi Petugas
                    </h3>
                </div>

                <div class="p-5 space-y-5 text-xs">
                    
                    <!-- Informasi Status Terkini -->
                    <div class="p-3.5 rounded-xl border {{ $citizen->isPending() ? 'bg-amber-50 border-amber-300 text-amber-900' : ($citizen->isActive() ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : ($citizen->isArchived() ? 'bg-slate-100 border-slate-300 text-slate-800' : 'bg-rose-50 border-rose-300 text-rose-900')) }}">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid {{ $citizen->isPending() ? 'fa-clock text-amber-600' : ($citizen->isActive() ? 'fa-circle-check text-emerald-600' : ($citizen->isArchived() ? 'fa-box-archive text-slate-600' : 'fa-circle-xmark text-rose-600')) }} text-base"></i>
                            <span class="font-bold">Status: {{ $citizen->status_label }}</span>
                        </div>
                        @if($citizen->verified_at)
                            <p class="text-[11px] mt-1.5 opacity-80">
                                Diverifikasi oleh <strong>{{ $citizen->verified_by }}</strong> pada {{ $citizen->verified_at->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        @endif
                        @if($citizen->rejection_reason)
                            <div class="mt-2 p-2 bg-white/80 rounded border {{ $citizen->isArchived() ? 'border-slate-300 text-slate-800' : 'border-rose-200 text-rose-900' }} text-[11px]">
                                <strong>Catatan Penolakan / Arsip:</strong>
                                <p class="italic mt-0.5">{{ $citizen->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Formulir Tindakan Verifikasi -->
                    <form action="{{ route('admin.citizens.verify', $citizen) }}" method="POST" id="verify-form" class="space-y-4">
                        @csrf

                        @if(!$citizen->isArchived())
                            <div>
                                <label for="rejection_reason" class="block font-bold text-slate-700 mb-1">
                                    Catatan / Alasan {{ $citizen->isActive() ? 'Penonaktifan' : 'Penolakan' }} <span class="text-slate-400 font-normal">(Wajib jika {{ $citizen->isActive() ? 'menonaktifkan' : 'menolak' }})</span>:
                                </label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" placeholder="{{ $citizen->isActive() ? 'Tuliskan catatan mengapa akun warga ini dinonaktifkan...' : 'Tuliskan catatan mengapa data pendaftaran warga belum dapat disetujui (misal: Nomor KK tidak ditemukan, NIK tidak sesuai KTP, dsb)...' }}" class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]">{{ old('rejection_reason', $citizen->rejection_reason) }}</textarea>
                                @error('rejection_reason')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-2">
                            
                            <!-- Tombol Setujui / Aktifkan -->
                            <button type="submit" name="action" value="approve" onclick="return confirm('Apakah Anda yakin ingin {{ $citizen->isActive() ? 'MEMPERBARUI dan TETAP MENGAKTIFKAN' : ($citizen->isArchived() ? 'MEMULIHKAN dan MENGAKTIFKAN' : 'MENYETUJUI dan MENGAKTIFKAN') }} akun warga ini?');" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check-circle"></i> {{ $citizen->isArchived() ? 'Pulihkan & Aktifkan Akun Warga' : ($citizen->isActive() ? 'Simpan & Tetap Aktifkan Akun' : 'Setujui & Aktifkan Akun Warga') }}
                            </button>

                            @if($citizen->isRejected())
                                <!-- Saat status ditolak / dinonaktifkan: Tombol Tolak/Nonaktifkan hilang diganti dengan tombol Arsipkan -->
                                <button type="submit" name="action" value="archive" onclick="return confirm('Apakah Anda yakin ingin MENGARSIPKAN data akun warga yang telah ditolak / dinonaktifkan ini?');" class="btn-archive w-full bg-slate-700 hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2" style="background-color: #334155; color: #ffffff;">
                                    <i class="fa-solid fa-box-archive text-amber-300"></i> Arsipkan Akun Warga
                                </button>
                            @elseif($citizen->isArchived())
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-slate-500 text-[11px]">
                                    <i class="fa-solid fa-circle-info text-[#0b7c89] mr-1"></i> Akun ini sudah berada di dalam daftar arsip.
                                </div>
                            @elseif($citizen->isActive())
                                <!-- Tombol Nonaktifkan untuk akun yang sudah aktif -->
                                <button type="submit" name="action" value="reject" onclick="return validateRejection('active');" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-ban"></i> Nonaktifkan Akun
                                </button>
                            @else
                                <!-- Tombol Tolak untuk status pendaftaran yang masih menunggu verifikasi (pending) -->
                                <button type="submit" name="action" value="reject" onclick="return validateRejection('pending');" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-xmark-circle"></i> Tolak Pendaftaran Akun
                                </button>
                            @endif

                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>

<script>
function validateRejection(status) {
    const reason = document.getElementById('rejection_reason').value.trim();
    if (status === 'active') {
        if (!reason) {
            alert('Harap isi kolom Catatan / Alasan Penonaktifan sebelum menonaktifkan akun warga.');
            document.getElementById('rejection_reason').focus();
            return false;
        }
        return confirm('Apakah Anda yakin ingin MENONAKTIFKAN akun warga ini?');
    } else {
        if (!reason) {
            alert('Harap isi kolom Catatan / Alasan Penolakan sebelum menolak pendaftaran akun.');
            document.getElementById('rejection_reason').focus();
            return false;
        }
        return confirm('Apakah Anda yakin ingin MENOLAK pendaftaran akun warga ini?');
    }
}
</script>
@endsection
