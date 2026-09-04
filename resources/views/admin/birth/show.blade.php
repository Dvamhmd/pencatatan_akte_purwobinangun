@extends('layouts.admin')

@section('title', 'Verifikasi Akte Kelahiran - ' . $birth->registration_no)
@section('page_title', 'Detail & Verifikasi Permohonan Akte Kelahiran')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.birth.index') }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. Registrasi:</span>
                <h3 class="text-base font-extrabold text-[#0b7c89]">{{ $birth->registration_no }}</h3>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold px-3 py-1.5 rounded-full border {{ $birth->status_badge_class }}">
                Status: {{ $birth->status_label }}
            </span>
            <a href="{{ route('admin.birth.print_letter', $birth) }}" target="_blank" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-print"></i> Cetak Surat Pengantar
            </a>
        </div>
    </div>

    <!-- Grid Content: Data & Verifikasi -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Kolom Data Permohonan (8 Kolom) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- 1. Data Bayi -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b7c89] pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-baby text-base"></i> 1. Data Bayi / Anak
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div><span class="text-slate-500 block text-[11px]">Nama Lengkap:</span> <strong class="text-slate-900 text-sm">{{ $birth->child_name }}</strong></div>
                    <div><span class="text-slate-500 block text-[11px]">Jenis Kelamin:</span> <span class="font-medium text-slate-800">{{ $birth->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Tempat, Tgl Lahir:</span> <span class="font-medium text-slate-800">{{ $birth->birth_place }}, {{ $birth->birth_date->translatedFormat('d F Y') }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Waktu / Pukul Lahir:</span> <span class="font-medium text-slate-800">{{ $birth->birth_time ?? '-' }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Jenis & Anak Ke-:</span> <span class="font-medium text-slate-800">{{ $birth->birth_type }} (Anak ke-{{ $birth->birth_order }})</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Penolong Kelahiran:</span> <span class="font-medium text-slate-800">{{ $birth->birth_helper }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Berat / Panjang Badan:</span> <span class="font-medium text-slate-800">{{ $birth->weight_kg ?? '-' }} kg / {{ $birth->length_cm ?? '-' }} cm</span></div>
                </div>
            </div>

            <!-- 2. Data Orang Tua -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b7c89] pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-users text-base"></i> 2. Data Orang Tua
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <!-- Ayah -->
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/70 space-y-2">
                        <p class="font-bold text-slate-800 text-teal-800"><i class="fa-solid fa-user-tie mr-1"></i> Ayah Kandung</p>
                        <div><span class="text-slate-500 text-[11px] block">NIK Ayah:</span> <span class="font-mono font-bold">{{ $birth->father_nik }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">Nama Lengkap:</span> <span class="font-medium">{{ $birth->father_name }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">Tgl Lahir:</span> <span class="font-medium">{{ $birth->father_birth_date ? $birth->father_birth_date->translatedFormat('d F Y') : '-' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">Pekerjaan:</span> <span class="font-medium">{{ $birth->father_job ?? '-' }}</span></div>
                    </div>

                    <!-- Ibu -->
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/70 space-y-2">
                        <p class="font-bold text-slate-800 text-teal-800"><i class="fa-solid fa-user-nurse mr-1"></i> Ibu Kandung</p>
                        <div><span class="text-slate-500 text-[11px] block">NIK Ibu:</span> <span class="font-mono font-bold">{{ $birth->mother_nik }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">Nama Lengkap:</span> <span class="font-medium">{{ $birth->mother_name }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">Tgl Lahir:</span> <span class="font-medium">{{ $birth->mother_birth_date ? $birth->mother_birth_date->translatedFormat('d F Y') : '-' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">Pekerjaan:</span> <span class="font-medium">{{ $birth->mother_job ?? '-' }}</span></div>
                    </div>
                </div>
            </div>

            <!-- 3. Berkas Dokumen Terunggah -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b7c89] pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-folder-open text-base"></i> 3. Berkas Lampiran Pendukung
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    
                    @php
                        $docs = [
                            'Surat Keterangan Lahir (RS/Bidan)' => $birth->doc_birth_cert,
                            'Kartu Keluarga (KK)' => $birth->doc_family_card,
                            'Buku Nikah / Akta Perkawinan' => $birth->doc_marriage_cert,
                            'KTP Kedua Orang Tua' => $birth->doc_parents_ktp,
                            'KTP Saksi Kelahiran' => $birth->doc_witness_ktp,
                        ];
                    @endphp

                    @foreach($docs as $title => $path)
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-slate-800 text-[11px]">{{ $title }}</p>
                                <p class="text-[10px] text-slate-400">{{ $path ? 'Dokumen Terlampir' : 'Tidak Ada Dokumen' }}</p>
                            </div>
                            @if($path)
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white font-bold text-[10px] px-2.5 py-1 rounded transition flex items-center gap-1">
                                    <i class="fa-solid fa-file-arrow-down"></i> Lihat
                                </a>
                            @else
                                <span class="text-[10px] text-slate-400 italic">Kosong</span>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>

        </div>

        <!-- Kolom Verifikasi & Tindakan Petugas (4 Kolom) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Panel Aksi Verifikasi Status -->
            <div class="bg-white rounded-xl shadow-xs border-2 border-teal-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-stamp text-[#0b7c89]"></i> Validasi Petugas
                </h4>

                <form id="statusForm" action="{{ route('admin.birth.update_status', $birth) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Ubah Status Permohonan</label>
                        <select name="status" id="statusSelect" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] font-medium">
                            <option value="pending" {{ $birth->status === 'pending' ? 'selected' : '' }}>1. Menunggu Verifikasi</option>
                            <option value="in_process" {{ ($birth->status === 'in_process' || $birth->status === 'verified') ? 'selected' : '' }}>2. Sedang Diproses</option>
                            <option value="revision" {{ $birth->status === 'revision' ? 'selected' : '' }}>3. Revisi Berkas</option>
                            <option value="rejected" {{ $birth->status === 'rejected' ? 'selected' : '' }}>4. Dibatalkan</option>
                            <option value="ready_for_pickup" {{ ($birth->status === 'ready_for_pickup' || $birth->status === 'completed') ? 'selected' : '' }}>5. Siap diambil</option>
                            <option value="picked_up" {{ ($birth->status === 'picked_up' || $birth->status === 'archived') ? 'selected' : '' }}>6. Sudah diambil (Masuk Arsip)</option>
                        </select>
                    </div>

                    <!-- Warning jika status diubah namun catatan belum diganti -->
                    <div id="statusChangeWarning" class="hidden p-3 bg-amber-50 border border-amber-300 rounded-lg text-[11px] text-amber-900 flex items-start gap-2 animate-fadeIn">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm mt-0.5 shrink-0"></i>
                        <div>
                            <p class="font-bold">Perhatian: Status Pengajuan Diubah</p>
                            <p class="text-amber-800">Silakan perbarui catatan / pesan verifikator di bawah sesuai dengan status baru sebelum menyimpan.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Catatan Verifikator / Alasan <span class="text-rose-600 font-bold">* (Wajib diisi)</span>
                        </label>
                        <textarea name="rejection_note" id="rejectionNote" rows="3" required placeholder="Tuliskan catatan verifikasi, instruksi pengambilan berkas, atau alasan perubahan status..." class="w-full text-xs px-3 py-2 rounded-lg border @error('rejection_note') border-rose-500 ring-2 ring-rose-200 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#0b7c89] transition">{{ old('rejection_note', $birth->rejection_note) }}</textarea>
                        @error('rejection_note')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                        <p id="noteJsError" class="hidden text-rose-600 text-[11px] mt-1 font-medium"></p>
                    </div>

                    <div class="text-[11px] text-slate-500">
                        <p>Petugas Verifikator: <strong>{{ Auth::user()->name }}</strong></p>
                    </div>

                    <!-- Hidden Inputs untuk Nilai Toggle Notifikasi yang dipilih di Modal -->
                    <input type="hidden" name="send_email" id="formSendEmail" value="{{ !empty($birth->applicant_email) ? '1' : '0' }}">
                    <input type="hidden" name="send_whatsapp" id="formSendWhatsApp" value="{{ !empty($birth->applicant_phone) ? '1' : '0' }}">

                    <button type="button" id="openModalSubmitBtn" class="w-full bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs py-2.5 rounded-lg shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Status
                    </button>
                </form>

                @if($birth->status === 'rejected' || $birth->status === 'picked_up' || $birth->status === 'archived')
                    <form action="{{ route('admin.archive.birth.archive', $birth) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENGARSIPKAN pengajuan akte kelahiran ini secara manual?');" class="btn-archive w-full bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs py-2.5 rounded-lg shadow-xs transition flex items-center justify-center gap-1.5" style="background-color: #334155; color: #ffffff;">
                            <i class="fa-solid fa-box-archive text-amber-300"></i> Arsipkan Pengajuan
                        </button>
                    </form>
                @endif
            </div>

            <!-- Ringkasan Pemohon -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5 text-xs space-y-2.5">
                <h4 class="font-bold text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-address-card text-[#0b7c89]"></i> Data Pemohon / Pelapor
                </h4>
                <div><span class="text-slate-400 block text-[11px]">Nama Pelapor:</span> <strong class="text-slate-800">{{ $birth->applicant_name }}</strong></div>
                <div><span class="text-slate-400 block text-[11px]">NIK Pelapor:</span> <span class="font-mono">{{ $birth->applicant_nik }}</span></div>
                <div><span class="text-slate-400 block text-[11px]">Hubungan:</span> <span>{{ $birth->applicant_relation }}</span></div>
                <div><span class="text-slate-400 block text-[11px]">Email Warga:</span> <span class="font-medium text-slate-800">{{ $birth->applicant_email ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-[11px]">Kontak / WhatsApp:</span> <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $birth->applicant_phone) }}" target="_blank" class="text-[#0b7c89] font-bold hover:underline">{{ $birth->applicant_phone }}</a></div>
                <div><span class="text-slate-400 block text-[11px]">Wilayah Padukuhan:</span> <span>Padukuhan {{ $birth->padukuhan }}, RT {{ $birth->rt }} / RW {{ $birth->rw }}</span></div>
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
                Status pengajuan akan diperbarui menjadi <strong id="modalTargetStatus" class="text-[#0b7c89] font-extrabold">Sedang Diproses</strong>. Tentukan saluran notifikasi otomatis yang ingin dikirimkan ke pemohon:
            </p>

            <div class="grid grid-cols-2 gap-3">
                <!-- Card Pilihan Email -->
                <div id="cardToggleEmail" class="bg-teal-50/30 rounded-xl border-2 border-teal-600 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-teal-700">
                    <div class="flex flex-col items-center space-y-1 mb-3 pointer-events-none w-full">
                        <div id="iconBgEmail" class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-[#0b7c89] border border-teal-200 shadow-2xs transition">
                            <i class="fa-solid fa-envelope text-2xl"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-800 mt-1">Email Warga</span>
                        <span class="block text-[11px] text-slate-600 font-medium truncate max-w-[130px] px-1" title="{{ $birth->applicant_email ?: ($birth->user?->email ?: 'Email tidak tersedia') }}">
                            {{ $birth->applicant_email ?: ($birth->user?->email ?: 'Tidak ada email') }}
                        </span>
                    </div>

                    <div class="toggle-switch-container pointer-events-none">
                        <input type="checkbox" id="modalSendEmail" class="sr-only" {{ !empty($birth->applicant_email) ? 'checked' : '' }}>
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
                        <span class="block text-[11px] text-slate-600 font-medium truncate max-w-[130px] px-1" title="{{ $birth->applicant_phone ?: 'No. HP tidak tersedia' }}">
                            {{ $birth->applicant_phone ?: 'Tidak ada No. HP' }}
                        </span>
                    </div>

                    <div class="toggle-switch-container pointer-events-none">
                        <input type="checkbox" id="modalSendWhatsApp" class="sr-only" {{ !empty($birth->applicant_phone) ? 'checked' : '' }}>
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
    const statusForm = document.getElementById('statusForm');
    const statusSelect = document.getElementById('statusSelect');
    const rejectionNote = document.getElementById('rejectionNote');
    const statusChangeWarning = document.getElementById('statusChangeWarning');
    const noteJsError = document.getElementById('noteJsError');

    const openModalSubmitBtn = document.getElementById('openModalSubmitBtn');
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

    const formSendEmail = document.getElementById('formSendEmail');
    const formSendWhatsApp = document.getElementById('formSendWhatsApp');

    if (!statusForm || !statusSelect || !rejectionNote) return;

    const initialStatus = statusSelect.value;
    const initialNote = @json((string) $birth->rejection_note).trim();

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

    // Inisialisasi tampilan awal status toggle sesuai data
    updateEmailToggleUi();
    updateWhatsAppToggleUi();

    function validateNoteChange() {
        const currentStatus = statusSelect.value;
        const currentNote = rejectionNote.value.trim();
        const isStatusChanged = (currentStatus !== initialStatus);
        const isNoteUnchanged = (initialNote !== '' && currentNote === initialNote);

        if (isStatusChanged && isNoteUnchanged) {
            if (statusChangeWarning) statusChangeWarning.classList.remove('hidden');
            return false;
        } else {
            if (statusChangeWarning) statusChangeWarning.classList.add('hidden');
            if (noteJsError) noteJsError.classList.add('hidden');
            rejectionNote.classList.remove('border-rose-500', 'ring-2', 'ring-rose-400');
            return true;
        }
    }

    statusSelect.addEventListener('change', function() {
        validateNoteChange();
    });

    rejectionNote.addEventListener('input', function() {
        validateNoteChange();
    });

    // Buka Pop-up Dissolve Modal
    function openModal() {
        if (!notificationConfirmModal || !modalCardContent) return;
        const selectedText = statusSelect.options[statusSelect.selectedIndex]?.text || statusSelect.value;
        if (modalTargetStatus) {
            modalTargetStatus.textContent = selectedText.replace(/^[0-9]+\.\s*/, '');
        }

        notificationConfirmModal.classList.remove('opacity-0', 'pointer-events-none');
        notificationConfirmModal.classList.add('opacity-100', 'pointer-events-auto');
        modalCardContent.classList.remove('scale-95');
        modalCardContent.classList.add('scale-100');
    }

    // Tutup Pop-up Dissolve Modal
    function closeModal() {
        if (!notificationConfirmModal || !modalCardContent) return;
        notificationConfirmModal.classList.remove('opacity-100', 'pointer-events-auto');
        notificationConfirmModal.classList.add('opacity-0', 'pointer-events-none');
        modalCardContent.classList.remove('scale-100');
        modalCardContent.classList.add('scale-95');
    }

    if (closeModalXBtn) closeModalXBtn.addEventListener('click', closeModal);
    if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);

    // Klik di luar card modal untuk menutup
    if (notificationConfirmModal) {
        notificationConfirmModal.addEventListener('click', function(e) {
            if (e.target === notificationConfirmModal) {
                closeModal();
            }
        });
    }

    // Trigger saat tombol simpan diklik di form
    if (openModalSubmitBtn) {
        openModalSubmitBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const currentStatus = statusSelect.value;
            const currentNote = rejectionNote.value.trim();
            const isStatusChanged = (currentStatus !== initialStatus);
            const isNoteUnchanged = (initialNote !== '' && currentNote === initialNote);

            // Validasi jika catatan belum diganti saat status berubah
            if (isStatusChanged && isNoteUnchanged) {
                if (statusChangeWarning) statusChangeWarning.classList.remove('hidden');
                if (noteJsError) {
                    noteJsError.textContent = 'Peringatan: Status pengajuan telah diubah, silakan perbarui Catatan Verifikator / Pesan terlebih dahulu.';
                    noteJsError.classList.remove('hidden');
                }
                rejectionNote.classList.add('border-rose-500', 'ring-2', 'ring-rose-400');
                rejectionNote.focus();
                alert('Peringatan: Status pengajuan telah diubah tetapi pesan/catatan verifikator masih belum diganti. Silakan ubah catatan terlebih dahulu sebelum menyimpan.');
                return;
            }

            // Validasi jika catatan kosong
            if (currentNote.length < 3) {
                rejectionNote.focus();
                rejectionNote.reportValidity();
                return;
            }

            // Buka Modal Pop-up Card Dissolve
            openModal();
        });
    }

    // Submit form dari tombol konfirmasi di modal
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

            statusForm.submit();
        });
    }
});
</script>
@endsection
