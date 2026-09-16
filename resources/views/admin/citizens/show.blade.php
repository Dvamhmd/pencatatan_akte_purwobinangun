@extends('layouts.admin')

@section('title', 'Detail Akun Warga: ' . $citizen->name)
@section('page_title', 'Pemeriksaan & Verifikasi Akun Warga')

@section('content')
<div class="space-y-6">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        @if($citizen->isArchived())
            <a href="{{ route('admin.archive.index', ['tab' => 'citizens']) }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#0b7c89] hover:underline bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Arsip Akun Warga
            </a>
        @else
            <a href="{{ route('admin.citizens.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#0b7c89] hover:underline bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Akun Warga
            </a>
        @endif
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.citizens.edit', $citizen) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-300 px-3 py-1.5 rounded-xl shadow-2xs transition">
                <i class="fa-solid fa-pen-to-square text-amber-600"></i> Edit / Koreksi Data
            </a>
            <span class="text-xs text-slate-500">Status Akun:</span>
            <span class="inline-block text-xs font-bold px-3 py-1 rounded-full border {{ $citizen->status_badge_class }}">
                {{ $citizen->status_label }}
            </span>
        </div>
    </div>

    <!-- Alert Permohonan Perubahan Data yang Sedang Menunggu Verifikasi -->
    @if($pendingProfileRequest)
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 p-4 rounded-r-xl shadow-xs flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 border border-amber-300 text-base">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                        Warga Mengajukan Permohonan Perubahan Data Profil & Keluarga
                        <span class="bg-amber-200 text-amber-900 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                            Menunggu Verifikasi
                        </span>
                    </h4>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Diajukan pada {{ $pendingProfileRequest->created_at->translatedFormat('d F Y, H:i') }} WIB. Terdapat perubahan data profil atau anggota keluarga yang memerlukan persetujuan admin.
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.profile_requests.show', $pendingProfileRequest) }}" class="inline-flex items-center gap-1.5 bg-[#0b7c89] hover:bg-[#065b65] text-white text-xs font-bold px-4 py-2.5 rounded-lg transition shrink-0 shadow-2xs">
                <i class="fa-solid fa-code-compare"></i> Periksa & Verifikasi Perubahan
            </a>
        </div>
    @endif

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
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.citizens.edit', $citizen) }}" class="text-[11px] font-bold text-[#0b7c89] hover:text-[#065b65] flex items-center gap-1 bg-teal-50 hover:bg-teal-100 border border-teal-200 px-2 py-0.5 rounded-md transition">
                            <i class="fa-solid fa-pen-to-square"></i> Ubah Data
                        </a>
                        <span class="text-[11px] text-slate-400">ID #{{ $citizen->id }}</span>
                    </div>
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
                            @if($citizen->family_relationship)
                                <span class="inline-block text-[10px] font-bold text-[#0b7c89] bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-full mt-1">
                                    <i class="fa-solid fa-user-tag text-[9px] mr-0.5"></i> {{ $citizen->family_relationship }}
                                </span>
                            @endif
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

                    <!-- Dokumen KK yang Diunggah -->
                    <div class="pt-3 border-t border-slate-100">
                        <span class="text-slate-500 font-medium block mb-1.5">Dokumen Kartu Keluarga (KK) Fisik:</span>
                        @if($citizen->doc_family_card)
                            <div class="p-3 bg-teal-50/60 rounded-xl border border-teal-200 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-lg bg-teal-100 text-[#0b7c89] flex items-center justify-center font-bold">
                                        @if(Str::endsWith(strtolower($citizen->doc_family_card), '.pdf'))
                                            <i class="fa-solid fa-file-pdf text-rose-600 text-lg"></i>
                                        @else
                                            <i class="fa-solid fa-file-image text-teal-700 text-lg"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-xs">Dokumen KK Terlampir</p>
                                        <p class="text-[10px] text-slate-500">{{ basename($citizen->doc_family_card) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <button type="button" onclick="openDocModal();" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
                                        <i class="fa-solid fa-magnifying-glass"></i> Lihat
                                    </button>
                                    <a href="{{ asset('storage/' . $citizen->doc_family_card) }}" target="_blank" class="text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-300 px-2.5 py-1.5 rounded-lg transition flex items-center gap-1 shadow-2xs" title="Buka di Tab Baru">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-slate-400 italic text-[11px] flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-slate-300 text-base"></i>
                                Warga belum melampirkan berkas fisik Kartu Keluarga (KK).
                            </div>
                        @endif
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
                    
                    <!-- Anggota Keluarga Tercatat dalam KK -->
                    @if($citizen->familyMembers->count() > 0)
                        <div>
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center justify-between">
                                <span>Anggota Keluarga Tercatat (KK):</span>
                                <span class="text-[10px] font-bold text-[#0b7c89] bg-teal-50 px-2 py-0.5 rounded border border-teal-200">
                                    {{ $citizen->familyMembers->count() }} Orang
                                </span>
                            </h4>
                            <div class="space-y-1.5">
                                @foreach($citizen->familyMembers as $fm)
                                    <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-200/80 flex items-center justify-between">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900">{{ $fm->name }}</span>
                                                <span class="text-[10px] font-semibold text-[#0b7c89] bg-teal-50 px-1.5 py-0.5 rounded">
                                                    {{ $fm->family_relationship ?: 'Anggota' }}
                                                </span>
                                            </div>
                                            <span class="text-[10px] text-slate-500 font-mono block mt-0.5">
                                                NIK: {{ $fm->nik ?: '-' }} &bull; {{ $fm->gender === 'L' ? 'Laki-laki' : ($fm->gender === 'P' ? 'Perempuan' : '-') }}
                                                @if($fm->birth_place || $fm->birth_date)
                                                    &bull; {{ $fm->birth_place }}, {{ $fm->birth_date ? $fm->birth_date->format('d/m/Y') : '' }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Anggota Keluarga Lain Terdaftar -->
                    <div>
                        <h4 class="font-bold text-slate-800 mb-2">Akun Terdaftar Lain dalam KK:</h4>
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

                        <!-- Hidden Inputs untuk Aksi dan Saluran Notifikasi -->
                        <input type="hidden" name="action" id="formAction" value="approve">
                        <input type="hidden" name="send_email" id="formSendEmail" value="1">
                        <input type="hidden" name="send_whatsapp" id="formSendWhatsApp" value="1">

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-2">
                            
                            <!-- Tombol Setujui / Aktifkan -->
                            <button type="button" id="btnApproveAction" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-check-circle"></i> {{ $citizen->isArchived() ? 'Pulihkan & Aktifkan Akun Warga' : ($citizen->isActive() ? 'Simpan & Tetap Aktifkan Akun' : 'Setujui & Aktifkan Akun Warga') }}
                            </button>

                            @if($citizen->isRejected())
                                <!-- Saat status ditolak / dinonaktifkan: Tombol Tolak/Nonaktifkan hilang diganti dengan tombol Arsipkan -->
                                <button type="submit" name="action" value="archive" onclick="return confirm('Apakah Anda yakin ingin MENGARSIPKAN data akun warga yang telah ditolak / dinonaktifkan ini?');" class="btn-archive w-full bg-slate-700 hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2" style="background-color: #334155; color: #ffffff;">
                                    <i class="fa-solid fa-box-archive text-amber-300"></i> Arsipkan Akun Warga
                                </button>
                            @elseif($citizen->isArchived())
                                <button type="submit" form="delete-citizen-form" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-trash-can"></i> Hapus Akun Warga Permanen
                                </button>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-slate-500 text-[11px]">
                                    <i class="fa-solid fa-circle-info text-[#0b7c89] mr-1"></i> Akun ini sudah berada di dalam daftar arsip.
                                </div>
                            @elseif($citizen->isActive())
                                <!-- Tombol Nonaktifkan untuk akun yang sudah aktif -->
                                <button type="button" id="btnDeactivateAction" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-ban"></i> Nonaktifkan Akun
                                </button>
                            @else
                                <!-- Tombol Tolak untuk status pendaftaran yang masih menunggu verifikasi (pending) -->
                                <button type="button" id="btnRejectAction" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-xmark-circle"></i> Tolak Pendaftaran Akun
                                </button>
                            @endif

                        </div>

                    </form>

                    @if($citizen->isArchived())
                        <form id="delete-citizen-form" action="{{ route('admin.citizens.destroy', $citizen) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENGHAPUS PERMANEN akun warga ({{ $citizen->name }}) ini? Data akun yang dihapus tidak dapat dipulihkan kembali.');">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif

                </div>
            </div>

        </div>

    </div>

</div>

<!-- Pop-up Card Konfirmasi Notifikasi (Dissolve Effect) -->
<div id="notificationConfirmModal" class="no-dissolve fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-300 ease-out" style="transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
    <div id="modalCardContent" class="no-dissolve bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden transform scale-95 transition-all duration-300 ease-out">
        
        <!-- Header Modal Pop-up -->
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-[#0b7c89] border border-teal-200/70">
                    <i class="fa-solid fa-bell text-sm"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Konfirmasi Pengiriman Notifikasi</h4>
                    <p class="text-[11px] text-slate-500">Pilih saluran pemberitahuan warga</p>
                </div>
            </div>
            <button type="button" id="closeModalXBtn" class="text-slate-400 hover:text-slate-600 transition w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Body Modal Pop-up -->
        <div class="p-5 space-y-4">
            <p class="text-xs text-slate-600 leading-relaxed">
                Status akun warga akan diubah menjadi <strong id="modalTargetStatus" class="text-[#0b7c89] font-extrabold">Disetujui & Diaktifkan</strong>. Tentukan saluran notifikasi otomatis yang ingin dikirimkan ke warga:
            </p>

            <div class="grid grid-cols-2 gap-3">
                <!-- Card Pilihan Email -->
                <div id="cardToggleEmail" class="bg-teal-50/30 rounded-xl border-2 border-teal-600 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-teal-700">
                    <div class="flex flex-col items-center space-y-1 mb-3 pointer-events-none w-full">
                        <div id="iconBgEmail" class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-[#0b7c89] border border-teal-200 shadow-2xs transition">
                            <i class="fa-solid fa-envelope text-2xl"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-800 mt-1">Email Warga</span>
                        <span class="block text-[11px] text-slate-600 font-medium truncate max-w-[130px] px-1" title="{{ $citizen->email ?: 'Email tidak tersedia' }}">
                            {{ $citizen->email ?: 'Tidak ada email' }}
                        </span>
                    </div>

                    <div class="toggle-switch-container pointer-events-none">
                        <input type="checkbox" id="modalSendEmail" class="sr-only" checked>
                        <div id="switchBgEmail" class="toggle-switch-track active-email">
                            <div id="switchDotEmail" class="toggle-switch-thumb active"></div>
                        </div>
                        <span id="labelStatusEmail" class="text-xs font-bold text-[#0b7c89]">Aktif</span>
                    </div>
                </div>

                <!-- Card Pilihan WhatsApp -->
                <div id="cardToggleWhatsApp" class="bg-emerald-50/30 rounded-xl border-2 border-emerald-600 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-emerald-700">
                    <div class="flex flex-col items-center space-y-1 mb-3 pointer-events-none w-full">
                        <div id="iconBgWhatsApp" class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-200 shadow-2xs transition">
                            <i class="fa-brands fa-whatsapp text-3xl"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-800 mt-1">WhatsApp</span>
                        <span class="block text-[11px] text-slate-600 font-medium truncate max-w-[130px] px-1" title="{{ $citizen->phone ?: 'No. HP tidak tersedia' }}">
                            {{ $citizen->phone ?: 'Tidak ada No. HP' }}
                        </span>
                    </div>

                    <div class="toggle-switch-container pointer-events-none">
                        <input type="checkbox" id="modalSendWhatsApp" class="sr-only" checked>
                        <div id="switchBgWhatsApp" class="toggle-switch-track active-whatsapp">
                            <div id="switchDotWhatsApp" class="toggle-switch-thumb active"></div>
                        </div>
                        <span id="labelStatusWhatsApp" class="text-xs font-bold text-emerald-600">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Modal Pop-up -->
        <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" id="cancelModalBtn" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg transition cursor-pointer">
                Batal
            </button>
            <button type="button" id="confirmModalSubmitBtn" class="px-4 py-2 text-xs font-bold text-white bg-[#0b7c89] hover:bg-[#065b65] rounded-lg shadow-sm transition cursor-pointer">
                Kirim & Simpan Status
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const verifyForm = document.getElementById('verify-form');
    const rejectionReason = document.getElementById('rejection_reason');
    const formAction = document.getElementById('formAction');
    const formSendEmail = document.getElementById('formSendEmail');
    const formSendWhatsApp = document.getElementById('formSendWhatsApp');

    const btnApproveAction = document.getElementById('btnApproveAction');
    const btnDeactivateAction = document.getElementById('btnDeactivateAction');
    const btnRejectAction = document.getElementById('btnRejectAction');

    const notificationConfirmModal = document.getElementById('notificationConfirmModal');
    const modalCardContent = document.getElementById('modalCardContent');
    const modalTargetStatus = document.getElementById('modalTargetStatus');
    const closeModalXBtn = document.getElementById('closeModalXBtn');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    const confirmModalSubmitBtn = document.getElementById('confirmModalSubmitBtn');

    const cardToggleEmail = document.getElementById('cardToggleEmail');
    const cardToggleWhatsApp = document.getElementById('cardToggleWhatsApp');
    const modalSendEmail = document.getElementById('modalSendEmail');
    const modalSendWhatsApp = document.getElementById('modalSendWhatsApp');
    const switchBgEmail = document.getElementById('switchBgEmail');
    const switchDotEmail = document.getElementById('switchDotEmail');
    const labelStatusEmail = document.getElementById('labelStatusEmail');
    const switchBgWhatsApp = document.getElementById('switchBgWhatsApp');
    const switchDotWhatsApp = document.getElementById('switchDotWhatsApp');
    const labelStatusWhatsApp = document.getElementById('labelStatusWhatsApp');
    const iconBgEmail = document.getElementById('iconBgEmail');
    const iconBgWhatsApp = document.getElementById('iconBgWhatsApp');

    function updateEmailToggleUi() {
        if (!modalSendEmail || !switchBgEmail || !switchDotEmail || !labelStatusEmail || !cardToggleEmail) return;
        const isChecked = modalSendEmail.checked;
        if (isChecked) {
            switchBgEmail.className = 'toggle-switch-track active-email';
            switchDotEmail.className = 'toggle-switch-thumb active';
            labelStatusEmail.textContent = 'Aktif';
            labelStatusEmail.className = 'text-xs font-bold text-[#0b7c89]';
            cardToggleEmail.className = 'bg-teal-50/30 rounded-xl border-2 border-teal-600 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-teal-700';
            if (iconBgEmail) iconBgEmail.className = 'w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-[#0b7c89] border border-teal-200 shadow-2xs transition';
        } else {
            switchBgEmail.className = 'toggle-switch-track';
            switchDotEmail.className = 'toggle-switch-thumb';
            labelStatusEmail.textContent = 'Nonaktif';
            labelStatusEmail.className = 'text-xs font-bold text-slate-500';
            cardToggleEmail.className = 'bg-slate-50 rounded-xl border-2 border-slate-300 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-slate-400';
            if (iconBgEmail) iconBgEmail.className = 'w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200 shadow-2xs transition';
        }
    }

    function updateWhatsAppToggleUi() {
        if (!modalSendWhatsApp || !switchBgWhatsApp || !switchDotWhatsApp || !labelStatusWhatsApp || !cardToggleWhatsApp) return;
        const isChecked = modalSendWhatsApp.checked;
        if (isChecked) {
            switchBgWhatsApp.className = 'toggle-switch-track active-whatsapp';
            switchDotWhatsApp.className = 'toggle-switch-thumb active';
            labelStatusWhatsApp.textContent = 'Aktif';
            labelStatusWhatsApp.className = 'text-xs font-bold text-emerald-600';
            cardToggleWhatsApp.className = 'bg-emerald-50/30 rounded-xl border-2 border-emerald-600 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-emerald-700';
            if (iconBgWhatsApp) iconBgWhatsApp.className = 'w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-200 shadow-2xs transition';
        } else {
            switchBgWhatsApp.className = 'toggle-switch-track';
            switchDotWhatsApp.className = 'toggle-switch-thumb';
            labelStatusWhatsApp.textContent = 'Nonaktif';
            labelStatusWhatsApp.className = 'text-xs font-bold text-slate-500';
            cardToggleWhatsApp.className = 'bg-slate-50 rounded-xl border-2 border-slate-300 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-slate-400';
            if (iconBgWhatsApp) iconBgWhatsApp.className = 'w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200 shadow-2xs transition';
        }
    }

    if (cardToggleEmail) {
        cardToggleEmail.addEventListener('click', function() {
            if (modalSendEmail) {
                modalSendEmail.checked = !modalSendEmail.checked;
                updateEmailToggleUi();
            }
        });
    }

    if (cardToggleWhatsApp) {
        cardToggleWhatsApp.addEventListener('click', function() {
            if (modalSendWhatsApp) {
                modalSendWhatsApp.checked = !modalSendWhatsApp.checked;
                updateWhatsAppToggleUi();
            }
        });
    }

    function openModal(actionName, statusText) {
        if (!notificationConfirmModal || !modalCardContent) return;
        if (formAction) formAction.value = actionName;
        if (modalTargetStatus) modalTargetStatus.textContent = statusText;

        notificationConfirmModal.classList.remove('opacity-0', 'pointer-events-none');
        notificationConfirmModal.classList.add('opacity-100', 'pointer-events-auto');
        modalCardContent.classList.remove('scale-95');
        modalCardContent.classList.add('scale-100');
    }

    function closeModal() {
        if (!notificationConfirmModal || !modalCardContent) return;
        notificationConfirmModal.classList.remove('opacity-100', 'pointer-events-auto');
        notificationConfirmModal.classList.add('opacity-0', 'pointer-events-none');
        modalCardContent.classList.remove('scale-100');
        modalCardContent.classList.add('scale-95');
    }

    if (closeModalXBtn) closeModalXBtn.addEventListener('click', closeModal);
    if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);

    if (notificationConfirmModal) {
        notificationConfirmModal.addEventListener('click', function(e) {
            if (e.target === notificationConfirmModal) {
                closeModal();
            }
        });
    }

    if (btnApproveAction) {
        btnApproveAction.addEventListener('click', function() {
            openModal('approve', 'Disetujui & Diaktifkan');
        });
    }

    if (btnDeactivateAction) {
        btnDeactivateAction.addEventListener('click', function() {
            const reason = rejectionReason ? rejectionReason.value.trim() : '';
            if (!reason) {
                alert('Harap isi kolom Catatan / Alasan Penonaktifan sebelum menonaktifkan akun warga.');
                if (rejectionReason) rejectionReason.focus();
                return;
            }
            openModal('reject', 'Dinonaktifkan');
        });
    }

    if (btnRejectAction) {
        btnRejectAction.addEventListener('click', function() {
            const reason = rejectionReason ? rejectionReason.value.trim() : '';
            if (!reason) {
                alert('Harap isi kolom Catatan / Alasan Penolakan sebelum menolak pendaftaran akun.');
                if (rejectionReason) rejectionReason.focus();
                return;
            }
            openModal('reject', 'Ditolak');
        });
    }

    if (confirmModalSubmitBtn) {
        confirmModalSubmitBtn.addEventListener('click', function() {
            if (formSendEmail && modalSendEmail) {
                formSendEmail.value = modalSendEmail.checked ? '1' : '0';
            }
            if (formSendWhatsApp && modalSendWhatsApp) {
                formSendWhatsApp.value = modalSendWhatsApp.checked ? '1' : '0';
            }

            confirmModalSubmitBtn.disabled = true;
            confirmModalSubmitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...';

            if (verifyForm) {
                verifyForm.submit();
            }
        });
    }

    // Pan & Zoom Preview Modal Logic
    const modalPreviewKk = document.getElementById('modal-preview-kk');
    const modalPreviewImg = document.getElementById('modal-preview-img');
    const modalImgContainer = document.getElementById('modal-img-container');
    const modalZoomControls = document.getElementById('modal-zoom-controls');
    const zoomLevelLabel = document.getElementById('zoom-level-label');
    const btnZoomIn = document.getElementById('btn-zoom-in');
    const btnZoomOut = document.getElementById('btn-zoom-out');
    const btnZoomRotate = document.getElementById('btn-zoom-rotate');
    const btnZoomReset = document.getElementById('btn-zoom-reset');

    const modalPdfContainer = document.getElementById('modal-pdf-container');
    const modalPdfName = document.getElementById('modal-pdf-name');
    const modalPdfLink = document.getElementById('modal-pdf-link');
    const modalFileInfo = document.getElementById('modal-file-info');
    const modalPreviewSubtitle = document.getElementById('modal-preview-subtitle');
    const btnCloseModalKk = document.getElementById('btn-close-modal-kk');
    const btnCloseModalKkFooter = document.getElementById('btn-close-modal-kk-footer');

    @php
        $hasDocCitizen = !empty($citizen->doc_family_card);
        $isPdfCitizen = $hasDocCitizen ? \Illuminate\Support\Str::endsWith(strtolower($citizen->doc_family_card), '.pdf') : false;
        $docCitizenUrl = $hasDocCitizen ? asset('storage/' . $citizen->doc_family_card) : '';
    @endphp

    const currentFileIsPdf = {{ ($hasDocCitizen && $isPdfCitizen) ? 'true' : 'false' }};
    const currentDocUrl = "{{ $hasDocCitizen ? $docCitizenUrl : '' }}";
    const currentFileName = "{{ $hasDocCitizen ? basename($citizen->doc_family_card) : '' }}";

    let zoomScale = 1.0;
    let panX = 0;
    let panY = 0;
    let currentRotation = 0;
    let isDraggingImg = false;
    let dragStartX = 0;
    let dragStartY = 0;

    function applyPanZoomTransform() {
        if (!modalPreviewImg) return;
        modalPreviewImg.style.transform = `translate(${panX}px, ${panY}px) scale(${zoomScale}) rotate(${currentRotation}deg)`;
        if (zoomLevelLabel) {
            zoomLevelLabel.textContent = Math.round(zoomScale * 100) + '%';
        }
    }

    function resetPanZoom() {
        zoomScale = 1.0;
        panX = 0;
        panY = 0;
        currentRotation = 0;
        applyPanZoomTransform();
    }

    function zoomIn() {
        zoomScale = Math.min(5.0, Number((zoomScale + 0.25).toFixed(2)));
        applyPanZoomTransform();
    }

    function zoomOut() {
        zoomScale = Math.max(0.4, Number((zoomScale - 0.25).toFixed(2)));
        applyPanZoomTransform();
    }

    function rotateImage() {
        currentRotation = (currentRotation + 90) % 360;
        applyPanZoomTransform();
    }

    if (btnZoomIn) btnZoomIn.addEventListener('click', zoomIn);
    if (btnZoomOut) btnZoomOut.addEventListener('click', zoomOut);
    if (btnZoomReset) btnZoomReset.addEventListener('click', resetPanZoom);
    if (btnZoomRotate) btnZoomRotate.addEventListener('click', rotateImage);

    if (modalImgContainer) {
        modalImgContainer.addEventListener('mousedown', function(e) {
            if (e.button !== 0) return;
            isDraggingImg = true;
            dragStartX = e.clientX - panX;
            dragStartY = e.clientY - panY;
            modalImgContainer.classList.add('cursor-grabbing');
            modalImgContainer.classList.remove('cursor-grab');
            e.preventDefault();
        });

        window.addEventListener('mousemove', function(e) {
            if (!isDraggingImg) return;
            panX = e.clientX - dragStartX;
            panY = e.clientY - dragStartY;
            applyPanZoomTransform();
        });

        window.addEventListener('mouseup', function() {
            if (isDraggingImg) {
                isDraggingImg = false;
                if (modalImgContainer) {
                    modalImgContainer.classList.remove('cursor-grabbing');
                    modalImgContainer.classList.add('cursor-grab');
                }
            }
        });

        modalImgContainer.addEventListener('wheel', function(e) {
            e.preventDefault();
            const delta = e.deltaY < 0 ? 0.15 : -0.15;
            zoomScale = Math.min(5.0, Math.max(0.4, Number((zoomScale + delta).toFixed(2))));
            applyPanZoomTransform();
        }, { passive: false });

        modalImgContainer.addEventListener('dblclick', function(e) {
            if (zoomScale > 1.2) {
                resetPanZoom();
            } else {
                zoomScale = 2.0;
                applyPanZoomTransform();
            }
        });

        let initialTouchDistance = null;
        modalImgContainer.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                isDraggingImg = true;
                dragStartX = e.touches[0].clientX - panX;
                dragStartY = e.touches[0].clientY - panY;
            } else if (e.touches.length === 2) {
                isDraggingImg = false;
                initialTouchDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
            }
        }, { passive: true });

        modalImgContainer.addEventListener('touchmove', function(e) {
            if (isDraggingImg && e.touches.length === 1) {
                panX = e.touches[0].clientX - dragStartX;
                panY = e.touches[0].clientY - dragStartY;
                applyPanZoomTransform();
            } else if (e.touches.length === 2 && initialTouchDistance) {
                const currentDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                const diff = (currentDist - initialTouchDistance) * 0.005;
                zoomScale = Math.min(5.0, Math.max(0.4, Number((zoomScale + diff).toFixed(2))));
                initialTouchDistance = currentDist;
                applyPanZoomTransform();
            }
        }, { passive: true });

        modalImgContainer.addEventListener('touchend', function(e) {
            if (e.touches.length === 0) {
                isDraggingImg = false;
                initialTouchDistance = null;
            }
        }, { passive: true });
    }

    window.openDocModal = function() {
        if (!modalPreviewKk) return;
        resetPanZoom();
        if (currentFileIsPdf) {
            if (modalImgContainer) modalImgContainer.classList.add('hidden');
            if (modalZoomControls) modalZoomControls.classList.add('hidden');
            if (modalPdfContainer) modalPdfContainer.classList.remove('hidden');
            if (modalPdfName) modalPdfName.textContent = currentFileName || 'Dokumen Kartu Keluarga (PDF)';
            if (modalPreviewSubtitle) modalPreviewSubtitle.textContent = 'Dokumen berformat PDF';
            if (modalFileInfo) modalFileInfo.textContent = currentFileName ? currentFileName + ' (PDF)' : 'Dokumen PDF';
            if (modalPdfLink && currentDocUrl) modalPdfLink.href = currentDocUrl;
        } else if (currentDocUrl) {
            if (modalImgContainer) modalImgContainer.classList.remove('hidden');
            if (modalZoomControls) modalZoomControls.classList.remove('hidden');
            if (modalPdfContainer) modalPdfContainer.classList.add('hidden');
            if (modalPreviewImg) {
                modalPreviewImg.src = currentDocUrl;
                modalPreviewImg.style.display = 'block';
            }
            if (modalPreviewSubtitle) modalPreviewSubtitle.textContent = 'Scroll untuk Zoom • Drag untuk Menggeser Posisi';
            if (modalFileInfo) modalFileInfo.textContent = currentFileName || 'Gambar Kartu Keluarga';
        } else {
            return;
        }
        modalPreviewKk.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    function closeDocModal() {
        if (!modalPreviewKk) return;
        modalPreviewKk.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (btnCloseModalKk) btnCloseModalKk.addEventListener('click', closeDocModal);
    if (btnCloseModalKkFooter) btnCloseModalKkFooter.addEventListener('click', closeDocModal);
    if (modalPreviewKk) {
        modalPreviewKk.addEventListener('click', function(e) {
            if (e.target === modalPreviewKk) closeDocModal();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalPreviewKk && !modalPreviewKk.classList.contains('hidden')) {
            closeDocModal();
        }
    });
});
</script>

<!-- MODAL PREVIEW DOKUMEN KK (FULL VIEW, ZOOM & DRAG/GESER) -->
<div id="modal-preview-kk" class="fixed inset-0 z-50 bg-slate-950/85 backdrop-blur-sm flex items-center justify-center p-2 sm:p-4 hidden transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-700/50 max-w-5xl w-full overflow-hidden my-auto flex flex-col h-[90vh] max-h-[92vh]">
        <!-- Header Modal -->
        <div class="bg-gradient-to-r from-[#0b7c89] to-[#065b65] text-white px-4 sm:px-6 py-3 flex items-center justify-between shrink-0 shadow-md">
            <div class="flex items-center gap-2.5 min-w-0 mr-2">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center text-teal-200 shrink-0">
                    <i class="fa-solid fa-file-shield text-base"></i>
                </div>
                <div class="truncate">
                    <h4 class="text-xs sm:text-sm font-bold truncate">Pratinjau Dokumen Kartu Keluarga (KK)</h4>
                    <p class="text-[10px] sm:text-[11px] text-teal-100 truncate" id="modal-preview-subtitle">Scroll untuk Zoom • Drag untuk Menggeser Posisi</p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <!-- Toolbar Zoom Gambar -->
                <div id="modal-zoom-controls" class="flex items-center bg-black/25 backdrop-blur-md rounded-xl p-1 border border-white/20">
                    <button type="button" id="btn-zoom-out" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer" title="Perkecil (Zoom Out)">
                        <i class="fa-solid fa-minus text-xs"></i>
                    </button>
                    <span id="zoom-level-label" class="text-xs font-mono font-bold px-2 text-teal-200 min-w-[46px] text-center select-none">100%</span>
                    <button type="button" id="btn-zoom-in" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer" title="Perbesar (Zoom In)">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                    <div class="h-4 w-px bg-white/20 mx-1"></div>
                    <button type="button" id="btn-zoom-rotate" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer" title="Putar 90°">
                        <i class="fa-solid fa-rotate-right text-xs"></i>
                    </button>
                    <button type="button" id="btn-zoom-reset" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer" title="Reset Posisi & Zoom">
                        <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    </button>
                </div>

                <button type="button" id="btn-close-modal-kk" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/30 text-teal-100 hover:text-white flex items-center justify-center transition cursor-pointer" title="Tutup">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body Modal Viewport Interaktif -->
        <div class="relative flex-1 bg-slate-950 overflow-hidden flex items-center justify-center select-none">
            <!-- Image Pan & Zoom Stage -->
            <div id="modal-img-container" class="w-full h-full relative overflow-hidden flex items-center justify-center cursor-grab active:cursor-grabbing bg-slate-900/95" style="touch-action: none; min-height: 250px;">
                <div class="w-full h-full flex items-center justify-center pointer-events-none p-4">
                    <img id="modal-preview-img" 
                         src="{{ ($hasDocCitizen && !$isPdfCitizen) ? $docCitizenUrl : '' }}" 
                         alt="Pratinjau Dokumen KK" 
                         class="max-h-[72vh] max-w-[88%] w-auto h-auto rounded-md shadow-2xl object-contain block border border-slate-700 bg-white pointer-events-none select-none transition-transform duration-75 ease-out"
                         style="transform-origin: center center;">
                </div>
            </div>

            <!-- PDF Container -->
            <div id="modal-pdf-container" class="hidden w-full h-full flex flex-col items-center justify-center p-6 bg-slate-900 text-center">
                <div class="w-20 h-20 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-4 border border-rose-500/30">
                    <i class="fa-solid fa-file-pdf text-4xl"></i>
                </div>
                <h5 class="text-base font-bold text-white" id="modal-pdf-name">{{ $hasDocCitizen ? basename($citizen->doc_family_card) : 'Dokumen Kartu Keluarga (PDF)' }}</h5>
                <p class="text-xs text-slate-400 mt-1.5 max-w-sm mx-auto">Dokumen Kartu Keluarga berformat PDF dapat dilihat dan dibuka melalui tautan berikut.</p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                    <a id="modal-pdf-link" href="{{ ($hasDocCitizen && $isPdfCitizen) ? $docCitizenUrl : '#' }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-white bg-[#0b7c89] hover:bg-[#065b65] px-4 py-2.5 rounded-xl shadow-md transition">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Dokumen PDF di Tab Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2 overflow-hidden mr-3">
                <i class="fa-solid fa-file-circle-check text-emerald-600 shrink-0"></i>
                <span class="text-xs text-slate-700 truncate font-medium" id="modal-file-info">{{ $hasDocCitizen ? basename($citizen->doc_family_card) : '-' }}</span>
            </div>
            <button type="button" id="btn-close-modal-kk-footer" class="text-xs font-bold bg-[#0b7c89] hover:bg-[#065b65] text-white px-4 py-2 rounded-xl shadow-xs transition cursor-pointer">
                Tutup Pratinjau
            </button>
        </div>
    </div>
</div>
@endsection
