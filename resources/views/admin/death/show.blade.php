@extends('layouts.admin')

@section('title', 'Verifikasi Akte Kematian - ' . $death->registration_no)
@section('page_title', 'Detail & Verifikasi Permohonan Akte Kematian')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.death.index') }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. Registrasi:</span>
                <h3 class="text-base font-extrabold text-rose-700">{{ $death->registration_no }}</h3>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold px-3 py-1.5 rounded-full border {{ $death->status_badge_class }}">
                Status: {{ $death->status_label }}
            </span>
            <a href="{{ route('admin.death.print_letter', $death) }}" target="_blank" class="bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-print"></i> Cetak Surat Keterangan Kematian
            </a>
        </div>
    </div>

    <!-- Grid Content: Data & Verifikasi -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Kolom Data Permohonan (8 Kolom) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- 1. Data Almarhum/ah -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-rose-700 pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-user-xmark text-base"></i> 1. Data Almarhum / Almarhumah
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div><span class="text-slate-500 block text-[11px]">Nama Lengkap:</span> <strong class="text-slate-900 text-sm">{{ $death->deceased_name }}</strong></div>
                    <div><span class="text-slate-500 block text-[11px]">NIK Almarhum/ah:</span> <span class="font-mono font-bold text-slate-800">{{ $death->deceased_nik }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Jenis Kelamin:</span> <span class="font-medium text-slate-800">{{ $death->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Agama:</span> <span class="font-medium text-slate-800">{{ $death->religion }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Padukuhan Domisili:</span> <span class="font-medium text-slate-800">Padukuhan {{ $death->padukuhan }}, RT {{ $death->rt }} / RW {{ $death->rw }}</span></div>
                </div>
            </div>

            <!-- 2. Peristiwa Kematian -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-rose-700 pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-clock text-base"></i> 2. Rincian Peristiwa Kematian
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div><span class="text-slate-500 block text-[11px]">Hari / Tanggal Meninggal:</span> <span class="font-bold text-slate-800">{{ $death->death_date->translatedFormat('l, d F Y') }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Waktu / Pukul:</span> <span class="font-medium text-slate-800">{{ $death->death_time ?? '-' }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Tempat Kematian:</span> <span class="font-medium text-slate-800">{{ $death->death_place }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">Penyebab Kematian:</span> <span class="font-medium text-slate-800">{{ $death->cause_of_death }}</span></div>
                    <div class="sm:col-span-2"><span class="text-slate-500 block text-[11px]">Yang Menerangkan:</span> <span class="font-medium text-slate-800">{{ $death->reported_by_title }}</span></div>
                </div>
            </div>

            <!-- 3. Berkas Dokumen Terunggah -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-rose-700 pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-folder-open text-base"></i> 3. Berkas Lampiran Pendukung
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    
                    @php
                        $docs = [
                            'Surat Keterangan Kematian (RS/Dokter/RT)' => $death->doc_death_statement,
                            'Kartu Keluarga (KK) Almarhum/ah' => $death->doc_family_card,
                            'KTP Asli Almarhum/ah' => $death->doc_deceased_ktp,
                            'KTP Pelapor / Ahli Waris' => $death->doc_applicant_ktp,
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
            <div class="bg-white rounded-xl shadow-xs border-2 border-rose-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-stamp text-rose-700"></i> Validasi Petugas
                </h4>

                <form id="statusDeathForm" action="{{ route('admin.death.update_status', $death) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Ubah Status Permohonan</label>
                        <select name="status" id="statusDeathSelect" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600 font-medium">
                            <option value="pending" {{ $death->status === 'pending' ? 'selected' : '' }}>1. Menunggu Verifikasi</option>
                            <option value="in_process" {{ ($death->status === 'in_process' || $death->status === 'verified') ? 'selected' : '' }}>2. Sedang Diproses</option>
                            <option value="revision" {{ $death->status === 'revision' ? 'selected' : '' }}>3. Revisi Berkas</option>
                            <option value="rejected" {{ $death->status === 'rejected' ? 'selected' : '' }}>4. Dibatalkan</option>
                            <option value="ready_for_pickup" {{ ($death->status === 'ready_for_pickup' || $death->status === 'completed') ? 'selected' : '' }}>5. Siap Diambil</option>
                            <option value="picked_up" {{ ($death->status === 'picked_up' || $death->status === 'archived') ? 'selected' : '' }}>6. Sudah Diambil (Masuk Arsip)</option>
                        </select>
                    </div>

                    <!-- Warning jika status diubah namun catatan belum diganti -->
                    <div id="statusDeathChangeWarning" class="hidden p-3 bg-amber-50 border border-amber-300 rounded-lg text-[11px] text-amber-900 flex items-start gap-2 animate-fadeIn">
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
                        <textarea name="rejection_note" id="rejectionDeathNote" rows="3" required placeholder="Tuliskan catatan verifikasi, instruksi pengambilan berkas, atau alasan perubahan status..." class="w-full text-xs px-3 py-2 rounded-lg border @error('rejection_note') border-rose-500 ring-2 ring-rose-200 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-rose-600 transition">{{ old('rejection_note', $death->rejection_note) }}</textarea>
                        @error('rejection_note')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                        <p id="noteDeathJsError" class="hidden text-rose-600 text-[11px] mt-1 font-medium"></p>
                    </div>

                    <div class="text-[11px] text-slate-500">
                        <p>Petugas Verifikator: <strong>{{ Auth::user()->name }}</strong></p>
                    </div>

                    <!-- Hidden Inputs untuk Nilai Toggle Notifikasi yang dipilih di Modal -->
                    <input type="hidden" name="send_email" id="formSendEmail" value="{{ !empty($death->applicant_email) ? '1' : '0' }}">
                    <input type="hidden" name="send_whatsapp" id="formSendWhatsApp" value="{{ !empty($death->applicant_phone) ? '1' : '0' }}">

                    <button type="button" id="openModalSubmitBtn" class="w-full bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs py-2.5 rounded-lg shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Status
                    </button>
                </form>

                @if($death->status === 'rejected' || $death->status === 'picked_up' || $death->status === 'archived')
                    <form action="{{ route('admin.archive.death.archive', $death) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENGARSIPKAN pengajuan akte kematian ini secara manual?');" class="btn-archive w-full bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs py-2.5 rounded-lg shadow-xs transition flex items-center justify-center gap-1.5" style="background-color: #334155; color: #ffffff;">
                            <i class="fa-solid fa-box-archive text-amber-300"></i> Arsipkan Pengajuan
                        </button>
                    </form>
                @endif
            </div>

            <!-- Ringkasan Pelapor & Saksi -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5 text-xs space-y-2.5">
                <h4 class="font-bold text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-address-card text-rose-700"></i> Data Pelapor (Ahli Waris)
                </h4>
                <div><span class="text-slate-400 block text-[11px]">Nama Pelapor:</span> <strong class="text-slate-800">{{ $death->applicant_name }}</strong></div>
                <div><span class="text-slate-400 block text-[11px]">NIK Pelapor:</span> <span class="font-mono">{{ $death->applicant_nik }}</span></div>
                <div><span class="text-slate-400 block text-[11px]">Hubungan:</span> <span>{{ $death->applicant_relation }}</span></div>
                <div><span class="text-slate-400 block text-[11px]">Email Warga:</span> <span class="font-medium text-slate-800">{{ $death->applicant_email ?: '-' }}</span></div>
                <div><span class="text-slate-400 block text-[11px]">Kontak / WhatsApp:</span> <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $death->applicant_phone) }}" target="_blank" class="text-rose-700 font-bold hover:underline">{{ $death->applicant_phone }}</a></div>
                @if($death->witness_name)
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block text-[11px]">Saksi Kematian:</span>
                        <span class="font-medium">{{ $death->witness_name }} (NIK: {{ $death->witness_nik ?? '-' }})</span>
                    </div>
                @endif
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
                <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-700 border border-rose-200/70">
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
                Status pengajuan akan diperbarui menjadi <strong id="modalTargetStatus" class="text-rose-700 font-extrabold">Sedang Diproses</strong>. Tentukan saluran notifikasi otomatis yang ingin dikirimkan ke pelapor:
            </p>

            <div class="grid grid-cols-2 gap-3">
                <!-- Card Pilihan Email -->
                <div id="cardToggleEmail" class="bg-rose-50/30 rounded-xl border-2 border-rose-400 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-rose-500">
                    <div class="flex flex-col items-center space-y-1 mb-3 pointer-events-none w-full">
                        <div id="iconBgEmail" class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-700 border border-rose-200 shadow-2xs transition">
                            <i class="fa-solid fa-envelope text-2xl"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-800 mt-1">Email Warga</span>
                        <span class="block text-[11px] text-slate-600 font-medium truncate max-w-[130px] px-1" title="{{ $death->applicant_email ?: ($death->user?->email ?: 'Email tidak tersedia') }}">
                            {{ $death->applicant_email ?: ($death->user?->email ?: 'Tidak ada email') }}
                        </span>
                    </div>

                    <div class="toggle-switch-container pointer-events-none">
                        <input type="checkbox" id="modalSendEmail" class="sr-only" {{ !empty($death->applicant_email) ? 'checked' : '' }}>
                        <div id="switchBgEmail" class="toggle-switch-track active-email">
                            <div id="switchDotEmail" class="toggle-switch-thumb active"></div>
                        </div>
                        <span id="labelStatusEmail" class="text-xs font-bold text-rose-700">Aktif</span>
                    </div>
                </div>

                <!-- Card Pilihan WhatsApp -->
                <div id="cardToggleWhatsApp" class="bg-emerald-50/30 rounded-xl border-2 border-emerald-600 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-emerald-700">
                    <div class="flex flex-col items-center space-y-1 mb-3 pointer-events-none w-full">
                        <div id="iconBgWhatsApp" class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-200 shadow-2xs transition">
                            <i class="fa-brands fa-whatsapp text-3xl"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-800 mt-1">WhatsApp</span>
                        <span class="block text-[11px] text-slate-600 font-medium truncate max-w-[130px] px-1" title="{{ $death->applicant_phone ?: 'No. HP tidak tersedia' }}">
                            {{ $death->applicant_phone ?: 'Tidak ada No. HP' }}
                        </span>
                    </div>

                    <div class="toggle-switch-container pointer-events-none">
                        <input type="checkbox" id="modalSendWhatsApp" class="sr-only" {{ !empty($death->applicant_phone) ? 'checked' : '' }}>
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
            <button type="button" id="confirmModalSubmitBtn" class="px-4 py-2 text-xs font-bold text-white bg-rose-700 hover:bg-rose-800 rounded-lg shadow-sm transition cursor-pointer">
                Kirim & Simpan Status
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusForm = document.getElementById('statusDeathForm');
    const statusSelect = document.getElementById('statusDeathSelect');
    const rejectionNote = document.getElementById('rejectionDeathNote');
    const statusChangeWarning = document.getElementById('statusDeathChangeWarning');
    const noteJsError = document.getElementById('noteDeathJsError');

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
    const initialNote = @json((string) $death->rejection_note).trim();

    const iconBgEmail = document.getElementById('iconBgEmail');
    const iconBgWhatsApp = document.getElementById('iconBgWhatsApp');

    function updateEmailToggleUi() {
        if (!modalSendEmail || !switchBgEmail || !switchDotEmail || !labelStatusEmail || !cardToggleEmail) return;
        const isChecked = modalSendEmail.checked;
        if (isChecked) {
            switchBgEmail.className = 'toggle-switch-track active-email';
            switchDotEmail.className = 'toggle-switch-thumb active';
            labelStatusEmail.textContent = 'Aktif';
            labelStatusEmail.className = 'text-xs font-bold text-rose-700';
            cardToggleEmail.className = 'bg-rose-50/30 rounded-xl border-2 border-rose-400 p-3.5 flex flex-col items-center justify-between text-center transition-all duration-200 cursor-pointer shadow-xs select-none hover:border-rose-500';
            if (iconBgEmail) iconBgEmail.className = 'w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-700 border border-rose-200 shadow-2xs transition';
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
