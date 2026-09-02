@extends('layouts.app')

@section('title', 'Formulir Pengajuan Akte Kelahiran')

@section('content')
<style>
    /* Pop-up Modal Top Layer & Smooth Transitions */
    .modal-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 999999 !important;
        display: none;
        align-items: center;
        justify-content: center;
        background-color: rgba(15, 23, 42, 0.7) !important;
        backdrop-filter: blur(4px) !important;
        -webkit-backdrop-filter: blur(4px) !important;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.28s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.28s;
        padding: 1rem;
        box-sizing: border-box;
        overflow-y: auto;
    }
    .modal-overlay.active {
        display: flex !important;
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
    }
    .modal-dialog-box {
        opacity: 0;
        transform: scale(0.92) translateY(15px);
        transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.32s cubic-bezier(0.16, 1, 0.3, 1);
        width: 100%;
        max-width: 38rem;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        z-index: 1000000 !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4) !important;
        margin: auto;
    }
    .modal-overlay.active .modal-dialog-box {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
    /* Scrollbar halus modal */
    .custom-modal-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-modal-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .custom-modal-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
    .custom-modal-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="space-y-6">

    <!-- Card Container Utama Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Headbar Toska Purwobinangun (Warna Solid #095b8c, Tanpa Gradasi) -->
        <div class="bg-[#095b8c] text-white px-5 py-4 flex flex-wrap items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-xs flex items-center justify-center text-amber-300 shadow-xs border border-white/20">
                    <i class="fa-solid fa-baby text-xl"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-sm md:text-base tracking-wide uppercase">
                        FORMULIR PENGAJUAN SURAT PENGANTAR AKTE KELAHIRAN
                    </h2>
                    <p class="text-[11px] text-teal-100 font-normal">Pemerintah Kalurahan Purwobinangun, Kapanewon Pakem, Kabupaten Sleman</p>
                </div>
            </div>
            <a href="{{ route('birth.list') }}" class="bg-white/15 hover:bg-white/25 text-white border border-white/20 text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-list-check text-teal-200"></i> Daftar Pengajuan
            </a>
        </div>

        <!-- Form Element -->
        <form id="birth-application-form" action="{{ route('birth.store') }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-8">
            @csrf

            <!-- ======================================================= -->
            <!-- STEP 1: FORM DATA PEMOHON & UPLOAD DOKUMEN PERSYARATAN -->
            <!-- ======================================================= -->
            <div id="step-content-1" class="space-y-6">
                
                <!-- Banner Petunjuk Step 1 -->
                <div class="bg-teal-50 p-4 rounded-xl border border-teal-100 flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-[#095b8c] text-lg mt-0.5 shrink-0"></i>
                    <div>
                        <h3 class="text-xs md:text-sm font-bold text-[#095b8c]">Langkah 1: Identitas Pemohon & Dokumen Persyaratan</h3>
                        <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">
                            Silakan isi data diri pemohon (orang tua / pelapor) serta unggah berkas KTP, Kartu Keluarga, dan Surat Kelahiran dari Rumah Sakit atau Bidan. Setelah klik <strong>Kirim</strong>, pratinjau data akan muncul sebagai pop-up di tengah layar untuk dikonfirmasi sebelum mengisi biodata bayi.
                        </p>
                    </div>
                </div>

                <!-- Sub-bagian A: Data Diri Pemohon -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2 pb-2 border-b border-teal-100">
                        <i class="fa-solid fa-user-pen text-amber-500"></i> Data Diri Pemohon
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Lengkap Pemohon -->
                        <div>
                            <label for="applicant_name" class="block text-xs font-semibold text-slate-700 mb-1">
                                Nama Lengkap Pemohon <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" required placeholder="Masukkan Nama Lengkap Pemohon" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-applicant_name">Nama lengkap pemohon wajib diisi.</span>
                        </div>

                        <!-- NIK Pemohon -->
                        <div>
                            <label for="applicant_nik" class="block text-xs font-semibold text-slate-700 mb-1">
                                NIK Pemohon (16 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="applicant_nik" name="applicant_nik" value="{{ old('applicant_nik') }}" maxlength="16" required placeholder="Masukkan NIK Pemohon (16 Angka)" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-applicant_nik">NIK wajib 16 digit angka.</span>
                        </div>

                        <!-- Hubungan Pemohon -->
                        <div>
                            <label for="applicant_relation" class="block text-xs font-semibold text-slate-700 mb-1">
                                Hubungan dengan Bayi
                            </label>
                            <select id="applicant_relation" name="applicant_relation" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                <option value="Ayah Kandung" selected>Ayah Kandung</option>
                                <option value="Ibu Kandung">Ibu Kandung</option>
                                <option value="Wali / Keluarga">Wali / Keluarga</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Nomor HP / WhatsApp -->
                        <div>
                            <label for="applicant_phone" class="block text-xs font-semibold text-slate-700 mb-1">
                                No. HP / WhatsApp Aktif <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="applicant_phone" name="applicant_phone" value="{{ old('applicant_phone') }}" required placeholder="Masukkan Nomor HP / WhatsApp Aktif" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-applicant_phone">Nomor HP/WA aktif wajib diisi.</span>
                        </div>

                        <!-- Padukuhan & RT/RW -->
                        <div>
                            <label for="padukuhan" class="block text-xs font-semibold text-slate-700 mb-1">
                                Padukuhan Domisili di Purwobinangun
                            </label>
                            <select id="padukuhan" name="padukuhan" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                <option value="">-- Pilih Padukuhan (Opsional) --</option>
                                @foreach($padukuhanList as $pad)
                                    <option value="{{ $pad }}" {{ old('padukuhan') === $pad ? 'selected' : '' }}>Padukuhan {{ $pad }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">RT / RW</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" id="rt" name="rt" value="{{ old('rt', '01') }}" placeholder="RT" class="text-xs px-3 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] text-center">
                                <input type="text" id="rw" name="rw" value="{{ old('rw', '01') }}" placeholder="RW" class="text-xs px-3 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] text-center">
                            </div>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-xs font-semibold text-slate-700 mb-1">
                                Alamat Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="address" name="address" rows="2" required placeholder="Masukkan Alamat Lengkap" class="w-full text-xs px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">{{ old('address') }}</textarea>
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-address">Alamat lengkap pemohon wajib diisi.</span>
                        </div>
                    </div>
                </div>

                <!-- Sub-bagian B: Upload Dokumen Persyaratan -->
                <div class="space-y-4 pt-4 border-t border-slate-200">
                    <div class="flex flex-wrap items-center justify-between gap-2 pb-2 border-b border-teal-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                            <i class="fa-solid fa-cloud-arrow-up text-amber-500"></i> Upload Dokumen Persyaratan
                        </h4>
                        <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded font-medium">Format: PDF, JPG, PNG (Maks. 3MB)</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <!-- 1. Dokumen KTP -->
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-between hover:border-[#095b8c] transition h-full" style="min-height: 270px;">
                            <div class="h-14 mb-2 flex flex-col justify-start shrink-0" style="height: 48px; min-height: 48px;">
                                <label class="block text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-id-card text-[#095b8c]"></i> 1. Dokumen KTP <span class="text-rose-500">*</span>
                                </label>
                                <p class="text-[11px] text-slate-500 leading-tight">Foto / Scan KTP-el Pemohon atau Orang Tua</p>
                            </div>
                            
                            <div class="mt-auto w-full">
                                <input type="file" id="doc_parents_ktp" name="doc_parents_ktp" accept=".jpg,.jpeg,.png,.pdf" required class="hidden file-input" data-card="ktp">
                                
                                <!-- Box Upload Standar -->
                                <label for="doc_parents_ktp" id="placeholder-ktp" class="cursor-pointer flex flex-col items-center justify-center text-center bg-white hover:bg-teal-50 text-[#095b8c] border-2 border-dashed border-teal-300 hover:border-[#095b8c] rounded-xl w-full p-3 transition shadow-2xs" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box;">
                                    <div class="w-10 h-10 rounded-full bg-teal-50 text-[#095b8c] flex items-center justify-center mb-2 border border-teal-200">
                                        <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                    </div>
                                    <span class="block text-xs font-bold">Pilih Berkas KTP</span>
                                    <span class="block text-[10px] text-slate-400 mt-1">JPG, PNG, PDF (Maks. 3MB)</span>
                                </label>

                                <!-- Box Preview Dokumen (Ukuran Fixed) -->
                                <div id="preview-box-ktp" class="hidden bg-white border border-teal-200 rounded-xl p-2.5 shadow-xs w-full flex flex-col justify-between" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box; overflow: hidden;">
                                    <div id="img-preview-wrap-ktp" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%; position: relative; overflow: hidden;">
                                        <img id="img-preview-ktp" src="" alt="Preview KTP" class="w-full h-full object-cover object-center block" style="width: 100%; height: 115px; min-height: 115px; max-height: 115px; object-fit: cover; object-position: center; display: block;">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center" style="position: absolute; inset: 0;">
                                            <label for="doc_parents_ktp" class="cursor-pointer bg-white text-slate-800 text-[11px] font-bold px-3 py-1.5 rounded-md shadow-sm hover:bg-slate-100">
                                                <i class="fa-solid fa-arrow-rotate-right"></i> Ganti Berkas
                                            </label>
                                        </div>
                                    </div>
                                    <div id="pdf-preview-wrap-ktp" class="w-full bg-rose-50 rounded-lg flex flex-col items-center justify-center border border-rose-200 text-rose-700 hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%;">
                                        <i class="fa-solid fa-file-pdf text-3xl mb-1.5"></i>
                                        <span class="text-[11px] font-bold uppercase tracking-wider">Dokumen PDF</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 shrink-0" style="height: 35px; min-height: 35px; max-height: 35px; box-sizing: border-box;">
                                        <div class="truncate flex-1 min-w-0">
                                            <p id="file-name-ktp" class="text-xs font-bold text-slate-800 truncate"></p>
                                            <p id="file-size-ktp" class="text-[10px] text-slate-500"></p>
                                        </div>
                                        <label for="doc_parents_ktp" class="shrink-0 text-[11px] font-bold text-[#095b8c] hover:text-[#04869e] cursor-pointer bg-teal-50 px-2 py-1 rounded border border-teal-200 transition">
                                            <i class="fa-solid fa-pen-to-square"></i> Ganti
                                        </label>
                                    </div>
                                </div>

                                <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-doc_parents_ktp">Dokumen KTP wajib diunggah.</span>
                            </div>
                        </div>

                        <!-- 2. Dokumen Kartu Keluarga -->
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-between hover:border-[#095b8c] transition h-full" style="min-height: 270px;">
                            <div class="h-14 mb-2 flex flex-col justify-start shrink-0" style="height: 48px; min-height: 48px;">
                                <label class="block text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-users text-[#095b8c]"></i> 2. Kartu Keluarga (KK) <span class="text-rose-500">*</span>
                                </label>
                                <p class="text-[11px] text-slate-500 leading-tight">Foto / Scan Kartu Keluarga asli orang tua</p>
                            </div>

                            <div class="mt-auto w-full">
                                <input type="file" id="doc_family_card" name="doc_family_card" accept=".jpg,.jpeg,.png,.pdf" required class="hidden file-input" data-card="kk">
                                
                                <!-- Box Upload Standar -->
                                <label for="doc_family_card" id="placeholder-kk" class="cursor-pointer flex flex-col items-center justify-center text-center bg-white hover:bg-teal-50 text-[#095b8c] border-2 border-dashed border-teal-300 hover:border-[#095b8c] rounded-xl w-full p-3 transition shadow-2xs" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box;">
                                    <div class="w-10 h-10 rounded-full bg-teal-50 text-[#095b8c] flex items-center justify-center mb-2 border border-teal-200">
                                        <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                    </div>
                                    <span class="block text-xs font-bold">Pilih Berkas KK</span>
                                    <span class="block text-[10px] text-slate-400 mt-1">JPG, PNG, PDF (Maks. 3MB)</span>
                                </label>

                                <!-- Box Preview Dokumen (Ukuran Fixed) -->
                                <div id="preview-box-kk" class="hidden bg-white border border-teal-200 rounded-xl p-2.5 shadow-xs w-full flex flex-col justify-between" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box; overflow: hidden;">
                                    <div id="img-preview-wrap-kk" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%; position: relative; overflow: hidden;">
                                        <img id="img-preview-kk" src="" alt="Preview Kartu Keluarga" class="w-full h-full object-cover object-center block" style="width: 100%; height: 115px; min-height: 115px; max-height: 115px; object-fit: cover; object-position: center; display: block;">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center" style="position: absolute; inset: 0;">
                                            <label for="doc_family_card" class="cursor-pointer bg-white text-slate-800 text-[11px] font-bold px-3 py-1.5 rounded-md shadow-sm hover:bg-slate-100">
                                                <i class="fa-solid fa-arrow-rotate-right"></i> Ganti Berkas
                                            </label>
                                        </div>
                                    </div>
                                    <div id="pdf-preview-wrap-kk" class="w-full bg-rose-50 rounded-lg flex flex-col items-center justify-center border border-rose-200 text-rose-700 hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%;">
                                        <i class="fa-solid fa-file-pdf text-3xl mb-1.5"></i>
                                        <span class="text-[11px] font-bold uppercase tracking-wider">Dokumen PDF</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 shrink-0" style="height: 35px; min-height: 35px; max-height: 35px; box-sizing: border-box;">
                                        <div class="truncate flex-1 min-w-0">
                                            <p id="file-name-kk" class="text-xs font-bold text-slate-800 truncate"></p>
                                            <p id="file-size-kk" class="text-[10px] text-slate-500"></p>
                                        </div>
                                        <label for="doc_family_card" class="shrink-0 text-[11px] font-bold text-[#095b8c] hover:text-[#04869e] cursor-pointer bg-teal-50 px-2 py-1 rounded border border-teal-200 transition">
                                            <i class="fa-solid fa-pen-to-square"></i> Ganti
                                        </label>
                                    </div>
                                </div>

                                <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-doc_family_card">Dokumen Kartu Keluarga wajib diunggah.</span>
                            </div>
                        </div>

                        <!-- 3. Surat Kelahiran RS/Bidan -->
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-between hover:border-[#095b8c] transition h-full" style="min-height: 270px;">
                            <div class="h-14 mb-2 flex flex-col justify-start shrink-0" style="height: 48px; min-height: 48px;">
                                <label class="block text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-hospital-user text-[#095b8c]"></i> 3. Surat Kelahiran RS/Bidan <span class="text-rose-500">*</span>
                                </label>
                                <p class="text-[11px] text-slate-500 leading-tight">Surat Keterangan Lahir dari RS / Puskesmas / Bidan</p>
                            </div>

                            <div class="mt-auto w-full">
                                <input type="file" id="doc_birth_cert" name="doc_birth_cert" accept=".jpg,.jpeg,.png,.pdf" required class="hidden file-input" data-card="lahir">
                                
                                <!-- Box Upload Standar -->
                                <label for="doc_birth_cert" id="placeholder-lahir" class="cursor-pointer flex flex-col items-center justify-center text-center bg-white hover:bg-teal-50 text-[#095b8c] border-2 border-dashed border-teal-300 hover:border-[#095b8c] rounded-xl w-full p-3 transition shadow-2xs" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box;">
                                    <div class="w-10 h-10 rounded-full bg-teal-50 text-[#095b8c] flex items-center justify-center mb-2 border border-teal-200">
                                        <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                    </div>
                                    <span class="block text-xs font-bold">Pilih Berkas Surat Lahir</span>
                                    <span class="block text-[10px] text-slate-400 mt-1">JPG, PNG, PDF (Maks. 3MB)</span>
                                </label>

                                <!-- Box Preview Dokumen (Ukuran Fixed) -->
                                <div id="preview-box-lahir" class="hidden bg-white border border-teal-200 rounded-xl p-2.5 shadow-xs w-full flex flex-col justify-between" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box; overflow: hidden;">
                                    <div id="img-preview-wrap-lahir" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%; position: relative; overflow: hidden;">
                                        <img id="img-preview-lahir" src="" alt="Preview Surat Kelahiran" class="w-full h-full object-cover object-center block" style="width: 100%; height: 115px; min-height: 115px; max-height: 115px; object-fit: cover; object-position: center; display: block;">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center" style="position: absolute; inset: 0;">
                                            <label for="doc_birth_cert" class="cursor-pointer bg-white text-slate-800 text-[11px] font-bold px-3 py-1.5 rounded-md shadow-sm hover:bg-slate-100">
                                                <i class="fa-solid fa-arrow-rotate-right"></i> Ganti Berkas
                                            </label>
                                        </div>
                                    </div>
                                    <div id="pdf-preview-wrap-lahir" class="w-full bg-rose-50 rounded-lg flex flex-col items-center justify-center border border-rose-200 text-rose-700 hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%;">
                                        <i class="fa-solid fa-file-pdf text-3xl mb-1.5"></i>
                                        <span class="text-[11px] font-bold uppercase tracking-wider">Dokumen PDF</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 shrink-0" style="height: 35px; min-height: 35px; max-height: 35px; box-sizing: border-box;">
                                        <div class="truncate flex-1 min-w-0">
                                            <p id="file-name-lahir" class="text-xs font-bold text-slate-800 truncate"></p>
                                            <p id="file-size-lahir" class="text-[10px] text-slate-500"></p>
                                        </div>
                                        <label for="doc_birth_cert" class="shrink-0 text-[11px] font-bold text-[#095b8c] hover:text-[#04869e] cursor-pointer bg-teal-50 px-2 py-1 rounded border border-teal-200 transition">
                                            <i class="fa-solid fa-pen-to-square"></i> Ganti
                                        </label>
                                    </div>
                                </div>

                                <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-doc_birth_cert">Surat Kelahiran RS/Bidan wajib diunggah.</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Tombol Kirim Step 1 (Membuka Pop-up Card Pratinjau di Tengah Layar) -->
                <div class="pt-6 border-t border-slate-200 flex items-center justify-between gap-4">
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition flex items-center gap-1.5 shrink-0 py-2.5 px-3 rounded-lg hover:bg-slate-100">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                    </a>
                    <button type="button" id="btn-to-preview" class="shrink-0 bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-sm rounded-lg shadow-sm hover:shadow-md transition cursor-pointer flex items-center justify-center" style="width: 140px; height: 42px; padding: 0 20px;">
                        Kirim
                    </button>
                </div>

            </div>

            <!-- ======================================================== -->
            <!-- STEP 2: FORM BIODATA BAYI & SUBMIT AKHIR                -->
            <!-- (Dilakukan setelah konfirmasi pada pop-up preview)       -->
            <!-- ======================================================== -->
            <div id="step-content-2" class="space-y-6 hidden">
                
                <div class="bg-teal-50 p-4 rounded-xl border border-teal-200 flex items-start gap-3">
                    <i class="fa-solid fa-baby text-[#095b8c] text-lg mt-0.5 shrink-0"></i>
                    <div>
                        <h3 class="text-xs md:text-sm font-bold text-[#095b8c]">Langkah 2: Pengisian Biodata Bayi yang Dilahirkan</h3>
                        <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">
                            Silakan lengkapi data kelahiran bayi dengan tepat dan sesuai dengan Surat Keterangan Lahir dari fasilitas kesehatan.
                        </p>
                    </div>
                </div>

                <!-- Form Biodata Bayi -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2 pb-2 border-b border-teal-100">
                        <i class="fa-solid fa-id-badge text-amber-500"></i> Biodata Lengkap Bayi
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- Nama Lengkap Anak -->
                        <div class="md:col-span-2">
                            <label for="child_name" class="block text-xs font-semibold text-slate-700 mb-1">
                                Nama Lengkap Anak <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="child_name" name="child_name" value="{{ old('child_name') }}" required placeholder="Masukkan Nama Lengkap Anak" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition font-medium">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-child_name">Nama lengkap anak wajib diisi.</span>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Jenis Kelamin <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border border-slate-300 hover:border-[#095b8c] cursor-pointer transition bg-white has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500 has-[:checked]:text-blue-700">
                                    <input type="radio" name="gender" value="L" checked class="text-[#095b8c] focus:ring-[#095b8c]">
                                    <i class="fa-solid fa-mars text-blue-600"></i>
                                    <span class="text-xs font-semibold">Laki-laki</span>
                                </label>

                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border border-slate-300 hover:border-[#095b8c] cursor-pointer transition bg-white has-[:checked]:bg-pink-50 has-[:checked]:border-pink-500 has-[:checked]:text-pink-700">
                                    <input type="radio" name="gender" value="P" class="text-[#095b8c] focus:ring-[#095b8c]">
                                    <i class="fa-solid fa-venus text-pink-600"></i>
                                    <span class="text-xs font-semibold">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tempat Kelahiran -->
                        <div>
                            <label for="birth_place" class="block text-xs font-semibold text-slate-700 mb-1">
                                Tempat Kelahiran (Kabupaten / Kota) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place', 'Sleman') }}" required placeholder="Masukkan Tempat Kelahiran" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-birth_place">Tempat kelahiran wajib diisi.</span>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="birth_date" class="block text-xs font-semibold text-slate-700 mb-1">
                                Tanggal Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-birth_date">Tanggal lahir wajib diisi.</span>
                        </div>

                        <!-- Jam Kelahiran -->
                        <div>
                            <label for="birth_time" class="block text-xs font-semibold text-slate-700 mb-1">
                                Jam Kelahiran (Pukul / Waktu Lahir)
                            </label>
                            <input type="text" id="birth_time" name="birth_time" value="{{ old('birth_time') }}" placeholder="Contoh: 08:30 WIB" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition">
                        </div>

                        <!-- Anak ke- -->
                        <div>
                            <label for="birth_order" class="block text-xs font-semibold text-slate-700 mb-1">
                                Kelahiran Anak ke- <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="birth_order" name="birth_order" min="1" max="25" value="{{ old('birth_order', 1) }}" required placeholder="Masukkan Kelahiran Anak ke-" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-birth_order">Kelahiran anak ke- wajib diisi.</span>
                        </div>

                        <!-- Jenis Kelahiran -->
                        <div>
                            <label for="birth_type" class="block text-xs font-semibold text-slate-700 mb-1">
                                Jenis Kelahiran <span class="text-rose-500">*</span>
                            </label>
                            <select id="birth_type" name="birth_type" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                <option value="Tunggal" selected>Tunggal</option>
                                <option value="Kembar 2">Kembar 2</option>
                                <option value="Kembar 3">Kembar 3</option>
                                <option value="Input Jumlah">Input Jumlah (Kembar 4+)</option>
                            </select>

                            <!-- Input Jumlah Kustom jika dipilih Input Jumlah -->
                            <div id="custom-birth-type-box" class="mt-2 hidden">
                                <input type="number" id="birth_type_custom" name="birth_type_custom" min="4" max="10" placeholder="Masukkan Jumlah Anak Kembar" class="w-full text-xs px-3 py-2 rounded-lg border border-teal-400 bg-teal-50/40 focus:outline-none focus:ring-2 focus:ring-[#095b8c]">
                            </div>
                        </div>

                        <!-- Tempat Dilahirkan -->
                        <div>
                            <label for="birth_place_type" class="block text-xs font-semibold text-slate-700 mb-1">
                                Tempat Dilahirkan <span class="text-rose-500">*</span>
                            </label>
                            <select id="birth_place_type" name="birth_place_type" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                <option value="Rumah Sakit" selected>Rumah Sakit</option>
                                <option value="Puskesmas">Puskesmas</option>
                                <option value="Klinik">Klinik</option>
                                <option value="Rumah">Rumah</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>

                            <!-- Input Kustom jika Tempat Dilahirkan Lainnya -->
                            <div id="custom-birth-place-box" class="mt-2 hidden">
                                <input type="text" id="birth_place_other" name="birth_place_other" placeholder="Masukkan Tempat Dilahirkan" class="w-full text-xs px-3 py-2 rounded-lg border border-teal-400 bg-teal-50/40 focus:outline-none focus:ring-2 focus:ring-[#095b8c]">
                            </div>
                        </div>

                        <!-- Berat & Panjang Badan Lahir (Opsional) -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Data Medis Bayi saat Lahir <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0.5" max="10" id="weight_kg" name="weight_kg" value="{{ old('weight_kg') }}" placeholder="Masukkan Berat Badan Bayi" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]">
                                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">kg</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 mt-0.5 block">Berat badan saat lahir</span>
                                </div>

                                <div>
                                    <div class="relative">
                                        <input type="number" step="0.1" min="20" max="100" id="length_cm" name="length_cm" value="{{ old('length_cm') }}" placeholder="Masukkan Panjang Badan Bayi" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]">
                                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">cm</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 mt-0.5 block">Panjang badan saat lahir</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Pernyataan & Persetujuan -->
                <div class="pt-4 border-t border-slate-200 space-y-3">
                    <label class="flex items-start gap-2.5 text-xs text-slate-600 cursor-pointer bg-slate-50 p-3 rounded-lg border border-slate-200">
                        <input type="checkbox" id="declaration_check" required class="mt-0.5 rounded text-[#095b8c] focus:ring-[#095b8c]">
                        <span>Saya menyatakan dengan sesungguhnya bahwa seluruh data dan dokumen yang saya unggah adalah benar, sah, dan sesuai hukum yang berlaku di Kalurahan Purwobinangun.</span>
                    </label>
                    <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-declaration">Anda harus menyetujui pernyataan keabsahan data di atas.</span>
                </div>

                <!-- Tombol Aksi Step 2 -->
                <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <button type="button" id="btn-back-to-step1" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-5 py-2.5 rounded-lg transition flex items-center justify-center gap-2 cursor-pointer border border-slate-300">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Pemohon
                    </button>

                    <button type="button" id="btn-submit-final" class="w-full sm:w-auto bg-[#095b8c] hover:bg-[#059cb8] text-white font-extrabold text-xs md:text-sm px-8 py-3 rounded-lg shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                        <span id="btn-submit-text">Kirim Pengajuan Akte Kelahiran</span>
                        <i id="btn-submit-spinner" class="fa-solid fa-circle-notch fa-spin hidden text-base"></i>
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>

<!-- ========================================================================= -->
<!-- POP-UP MODAL CARD: PRATINJAU DATA PENGAJUAN (DI TENGAH LAYAR DENGAN ANIMASI) -->
<!-- ========================================================================= -->
<div id="preview-modal" class="modal-overlay fixed inset-0 z-50 bg-slate-950/65 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto">
    <div id="preview-modal-dialog" class="modal-dialog-box bg-white rounded-2xl shadow-2xl border border-slate-200/80 max-w-xl w-full overflow-hidden my-auto max-h-[90vh] flex flex-col">
        
        <!-- Header Pop-up Modal Preview (Warna Solid #095b8c) -->
        <div class="bg-[#095b8c] text-white px-5 sm:px-6 py-4 flex items-center justify-between shrink-0 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/15 backdrop-blur-xs flex items-center justify-center text-amber-300 border border-white/20 shrink-0">
                    <i class="fa-solid fa-clipboard-check text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-xs sm:text-sm uppercase tracking-wide">Pratinjau Data Pengajuan</h3>
                    <p class="text-[11px] text-teal-100">Periksa ringkasan data sebelum mengisi biodata kelahiran bayi</p>
                </div>
            </div>
            <button type="button" id="btn-close-preview-modal" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-teal-100 hover:text-white flex items-center justify-center transition cursor-pointer shrink-0">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <!-- Body Pop-up Modal Preview (Scrollable) -->
        <div class="p-5 sm:p-6 space-y-4 sm:space-y-5 overflow-y-auto custom-modal-scroll max-h-[75vh]">
            
            <!-- Info Banner Notifikasi -->
            <div class="p-3 bg-teal-50/80 rounded-xl border border-teal-200/80 flex items-start gap-2.5 text-xs text-teal-900">
                <i class="fa-solid fa-circle-info text-[#095b8c] text-sm mt-0.5 shrink-0"></i>
                <p class="text-[11px] leading-relaxed text-slate-700">
                    Pastikan identitas pemohon dan dokumen persyaratan yang dilampirkan sudah benar sebelum melanjutkan.
                </p>
            </div>

            <!-- Rincian Identitas Pemohon -->
            <div class="bg-slate-50/90 p-4 rounded-xl border border-slate-200 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-amber-500"></i> Identitas Pemohon
                    </h4>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-[#095b8c]">Langkah 1</span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Nama Pemohon</span>
                        <span id="modal-applicant-name-val" class="font-bold text-slate-900 text-xs block break-words">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">NIK Pemohon</span>
                        <span id="modal-applicant-nik-val" class="font-bold text-slate-900 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">No. HP / WhatsApp</span>
                        <span id="modal-applicant-phone-val" class="font-bold text-emerald-700 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Hubungan dengan Bayi</span>
                        <span id="modal-applicant-relation-val" class="font-bold text-slate-800 text-xs block">-</span>
                    </div>
                    <div class="sm:col-span-2 bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Alamat Lengkap</span>
                        <span id="modal-applicant-address-val" class="font-medium text-slate-800 text-xs leading-relaxed block">-</span>
                    </div>
                </div>
            </div>

            <!-- Rincian Dokumen Terlampir -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                        <i class="fa-solid fa-paperclip text-amber-500"></i> Berkas Dokumen Terlampir
                    </h4>
                    <span class="text-[10px] font-semibold text-slate-500">3 Dokumen Wajib</span>
                </div>
                
                <div class="space-y-2.5">
                    
                    <!-- 1. KTP Card Modal -->
                    <div class="flex items-center gap-3.5 p-3 bg-slate-50/80 hover:bg-slate-50 rounded-xl border border-slate-200 transition">
                        <div class="w-13 h-13 rounded-lg overflow-hidden border border-slate-200 shrink-0 bg-slate-100 flex items-center justify-center shadow-2xs" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px;">
                            <img id="modal-thumb-ktp" src="" alt="Thumbnail KTP" class="w-full h-full object-cover object-center block hidden" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px; max-width: 52px; max-height: 52px; object-fit: cover; object-position: center;">
                            <div id="modal-pdf-ktp" class="w-full h-full bg-rose-50 flex flex-col items-center justify-center text-rose-600 hidden" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px;">
                                <i class="fa-solid fa-file-pdf text-xl mb-0.5"></i>
                                <span class="text-[8px] font-bold uppercase">PDF</span>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-bold text-[#095b8c] uppercase tracking-wide">1. KTP Pemohon</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 shrink-0">
                                    <i class="fa-solid fa-check text-[9px]"></i> Terlampir
                                </span>
                            </div>
                            <p id="modal-file-ktp" class="text-xs font-medium text-slate-700 truncate mt-0.5">-</p>
                        </div>
                    </div>

                    <!-- 2. KK Card Modal -->
                    <div class="flex items-center gap-3.5 p-3 bg-slate-50/80 hover:bg-slate-50 rounded-xl border border-slate-200 transition">
                        <div class="w-13 h-13 rounded-lg overflow-hidden border border-slate-200 shrink-0 bg-slate-100 flex items-center justify-center shadow-2xs" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px;">
                            <img id="modal-thumb-kk" src="" alt="Thumbnail KK" class="w-full h-full object-cover object-center block hidden" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px; max-width: 52px; max-height: 52px; object-fit: cover; object-position: center;">
                            <div id="modal-pdf-kk" class="w-full h-full bg-rose-50 flex flex-col items-center justify-center text-rose-600 hidden" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px;">
                                <i class="fa-solid fa-file-pdf text-xl mb-0.5"></i>
                                <span class="text-[8px] font-bold uppercase">PDF</span>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-bold text-[#095b8c] uppercase tracking-wide">2. Kartu Keluarga (KK)</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 shrink-0">
                                    <i class="fa-solid fa-check text-[9px]"></i> Terlampir
                                </span>
                            </div>
                            <p id="modal-file-kk" class="text-xs font-medium text-slate-700 truncate mt-0.5">-</p>
                        </div>
                    </div>

                    <!-- 3. Surat Lahir Card Modal -->
                    <div class="flex items-center gap-3.5 p-3 bg-slate-50/80 hover:bg-slate-50 rounded-xl border border-slate-200 transition">
                        <div class="w-13 h-13 rounded-lg overflow-hidden border border-slate-200 shrink-0 bg-slate-100 flex items-center justify-center shadow-2xs" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px;">
                            <img id="modal-thumb-lahir" src="" alt="Thumbnail Surat Lahir" class="w-full h-full object-cover object-center block hidden" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px; max-width: 52px; max-height: 52px; object-fit: cover; object-position: center;">
                            <div id="modal-pdf-lahir" class="w-full h-full bg-rose-50 flex flex-col items-center justify-center text-rose-600 hidden" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px;">
                                <i class="fa-solid fa-file-pdf text-xl mb-0.5"></i>
                                <span class="text-[8px] font-bold uppercase">PDF</span>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-bold text-[#095b8c] uppercase tracking-wide">3. Surat Kelahiran RS/Bidan</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 shrink-0">
                                    <i class="fa-solid fa-check text-[9px]"></i> Terlampir
                                </span>
                            </div>
                            <p id="modal-file-lahir" class="text-xs font-medium text-slate-700 truncate mt-0.5">-</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Footer Tombol Konfirmasi & Ubah Data -->
        <div class="px-5 sm:px-6 py-4 bg-slate-50/95 border-t border-slate-200/90 flex flex-col-reverse sm:flex-row items-center justify-between gap-3 shrink-0">
            <button type="button" id="btn-modal-edit" class="w-full sm:w-auto bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-xl border border-slate-300 transition-all flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                <i class="fa-solid fa-arrow-left text-slate-500 text-[11px]"></i>
                <i class="fa-solid fa-pen-to-square text-amber-600"></i>
                <span>Ubah Data</span>
            </button>
            <button type="button" id="btn-confirm-to-baby" class="w-full sm:w-auto bg-[#095b8c] hover:bg-[#07476e] active:scale-[0.98] text-white font-extrabold text-xs sm:text-sm px-6 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2.5 cursor-pointer border border-[#07476e]">
                <span>Konfirmasi</span>
                <i class="fa-solid fa-arrow-right text-xs text-teal-200"></i>
            </button>
        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL POP-UP: PENGAJUAN BERHASIL TERKIRIM                                 -->
<!-- ========================================================================= -->
<div id="success-modal" class="modal-overlay fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto">
    
    <div id="success-modal-dialog" class="modal-dialog-box bg-white rounded-2xl shadow-2xl border border-teal-100 max-w-lg w-full overflow-hidden my-auto">
        
        <!-- Modal Top Decoration -->
        <div class="bg-gradient-to-r from-[#095b8c] via-[#059cb8] to-teal-600 p-6 text-center text-white relative shadow-xs">
            <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-3 ring-8 ring-white/10 shadow-lg animate-bounce">
                <i class="fa-solid fa-circle-check text-3xl text-emerald-300"></i>
            </div>
            <span class="text-[11px] uppercase tracking-widest font-extrabold bg-amber-400 text-slate-950 px-3 py-0.5 rounded-full shadow-xs">
                PENGAJUAN BERHASIL TERKIRIM
            </span>
            <h3 class="text-lg md:text-xl font-extrabold text-white mt-2">
                Permohonan Akte Kelahiran Diterima
            </h3>
            <p class="text-xs text-teal-100 mt-1 max-w-sm mx-auto">
                Berkas permohonan Anda telah berhasil didaftarkan ke sistem pelayanan Kalurahan Purwobinangun.
            </p>
        </div>

        <!-- Modal Body Content -->
        <div class="p-5 md:p-6 space-y-4">
            
            <!-- Registration Number Highlight Box -->
            <div class="p-4 bg-teal-50/80 border-2 border-dashed border-[#059cb8] rounded-xl text-center">
                <span class="text-[11px] font-bold text-[#095b8c] uppercase tracking-wider block">Nomor Registrasi Permohonan</span>
                <div class="flex items-center justify-center gap-2 my-1">
                    <span id="modal-reg-no" class="text-xl md:text-2xl font-black text-[#095b8c] tracking-widest font-mono">AKL-XXXXXXXX-XXXX</span>
                    <button type="button" id="btn-copy-reg" title="Salin Nomor Registrasi" class="text-slate-400 hover:text-[#095b8c] transition p-1.5 rounded-lg hover:bg-white cursor-pointer">
                        <i class="fa-regular fa-copy text-sm"></i>
                    </button>
                </div>
                <p class="text-[10px] text-slate-500">Gunakan nomor ini untuk melacak status proses permohonan Anda.</p>
            </div>

            <!-- Summary Details -->
            <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200 space-y-2 text-xs">
                <div class="flex justify-between items-center py-1 border-b border-slate-200">
                    <span class="text-slate-500">Nama Bayi:</span>
                    <span id="modal-child-name" class="font-bold text-slate-800">-</span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-200">
                    <span class="text-slate-500">Nama Pemohon:</span>
                    <span id="modal-applicant-name" class="font-semibold text-slate-800">-</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-slate-500">Status Permohonan:</span>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu Verifikasi
                    </span>
                </div>
            </div>

            <!-- Important Info Notice -->
            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 flex items-start gap-2.5 text-xs text-amber-900">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm mt-0.5 shrink-0"></i>
                <p class="text-[11px] leading-relaxed">
                    Petugas Kalurahan akan memverifikasi berkas Anda dalam waktu <strong>1-3 hari kerja</strong>. Anda dapat memantau status secara berkala melalui menu <strong>Lacak Berkas</strong>.
                </p>
            </div>

            <!-- Modal Action Buttons -->
            <div class="pt-2 space-y-2.5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <a id="modal-btn-receipt" href="#" target="_blank" class="w-full bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-xs py-2.5 px-3 rounded-lg text-center transition flex items-center justify-center gap-1.5 shadow-xs">
                        <i class="fa-solid fa-print"></i> Cetak Bukti Resi
                    </a>
                    <a id="modal-btn-tracking" href="#" class="w-full bg-teal-50 hover:bg-teal-100 text-[#095b8c] font-bold text-xs py-2.5 px-3 rounded-lg border border-teal-200 text-center transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass"></i> Lacak Status Berkas
                    </a>
                </div>
                <a id="modal-btn-list" href="{{ route('birth.list') }}" class="w-full block bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs py-2.5 px-3 rounded-lg text-center transition border border-slate-300">
                    <i class="fa-solid fa-list-check mr-1"></i> Buka Daftar Pengajuan Akte Kelahiran
                </a>
            </div>

        </div>

    </div>

</div>

<!-- Multi-Step Wizard & Modal Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;

    // Elements
    const form = document.getElementById('birth-application-form');
    const step1 = document.getElementById('step-content-1');
    const step2 = document.getElementById('step-content-2');

    // Input elements Step 1
    const applicantName = document.getElementById('applicant_name');
    const applicantNik = document.getElementById('applicant_nik');
    const applicantRelation = document.getElementById('applicant_relation');
    const address = document.getElementById('address');
    const applicantPhone = document.getElementById('applicant_phone');
    const padukuhan = document.getElementById('padukuhan');
    const rt = document.getElementById('rt');
    const rw = document.getElementById('rw');

    const docKtp = document.getElementById('doc_parents_ktp');
    const docKk = document.getElementById('doc_family_card');
    const docLahir = document.getElementById('doc_birth_cert');
    
    // Input elements Step 2
    const childName = document.getElementById('child_name');
    const birthPlace = document.getElementById('birth_place');
    const birthDate = document.getElementById('birth_date');
    const birthTime = document.getElementById('birth_time');
    const birthOrder = document.getElementById('birth_order');
    const birthType = document.getElementById('birth_type');
    const birthTypeCustom = document.getElementById('birth_type_custom');
    const customBirthTypeBox = document.getElementById('custom-birth-type-box');
    const birthPlaceType = document.getElementById('birth_place_type');
    const birthPlaceOther = document.getElementById('birth_place_other');
    const customBirthPlaceBox = document.getElementById('custom-birth-place-box');
    const weightKg = document.getElementById('weight_kg');
    const lengthCm = document.getElementById('length_cm');
    const declarationCheck = document.getElementById('declaration_check');

    // Preview Modal Elements
    const previewModal = document.getElementById('preview-modal');
    const btnClosePreviewModal = document.getElementById('btn-close-preview-modal');
    const btnModalEdit = document.getElementById('btn-modal-edit');
    const btnConfirmToBaby = document.getElementById('btn-confirm-to-baby');

    // Success Modal Element
    const successModal = document.getElementById('success-modal');

    // Teleport pop-up modal elements directly to document.body to ensure top-layer positioning above all elements
    if (previewModal && previewModal.parentElement !== document.body) {
        document.body.appendChild(previewModal);
    }
    if (successModal && successModal.parentElement !== document.body) {
        document.body.appendChild(successModal);
    }

    // Open & Hide Modal Helpers with Smooth Animations
    function showModal(modalEl) {
        if (!modalEl) return;
        modalEl.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function hideModal(modalEl) {
        if (!modalEl) return;
        modalEl.classList.remove('active');
        document.body.style.overflow = '';
    }

    // File Input Label & Image/PDF Thumbnail Preview Handler
    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function () {
            const cardKey = this.getAttribute('data-card');
            const placeholder = document.getElementById(`placeholder-${cardKey}`);
            const previewBox = document.getElementById(`preview-box-${cardKey}`);
            const imgWrap = document.getElementById(`img-preview-wrap-${cardKey}`);
            const imgEl = document.getElementById(`img-preview-${cardKey}`);
            const pdfWrap = document.getElementById(`pdf-preview-wrap-${cardKey}`);
            const fileNameEl = document.getElementById(`file-name-${cardKey}`);
            const fileSizeEl = document.getElementById(`file-size-${cardKey}`);

            if (this.files && this.files.length > 0) {
                const file = this.files[0];
                fileNameEl.textContent = file.name;
                fileSizeEl.textContent = (file.size / 1024).toFixed(1) + ' KB';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imgEl.src = e.target.result;
                        imgWrap.classList.remove('hidden');
                        pdfWrap.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imgWrap.classList.add('hidden');
                    pdfWrap.classList.remove('hidden');
                }

                placeholder.classList.add('hidden');
                previewBox.classList.remove('hidden');

                // Hide error message if any
                const errEl = document.getElementById('error-' + this.id);
                if (errEl) errEl.classList.add('hidden');
            }
        });
    });

    // Handle Custom Inputs
    birthType.addEventListener('change', function () {
        if (this.value === 'Input Jumlah') {
            customBirthTypeBox.classList.remove('hidden');
            birthTypeCustom.required = true;
        } else {
            customBirthTypeBox.classList.add('hidden');
            birthTypeCustom.required = false;
        }
    });

    birthPlaceType.addEventListener('change', function () {
        if (this.value === 'Lainnya') {
            customBirthPlaceBox.classList.remove('hidden');
            birthPlaceOther.required = true;
        } else {
            customBirthPlaceBox.classList.add('hidden');
            birthPlaceOther.required = false;
        }
    });

    // Helper: Switch steps
    function setStep(step) {
        currentStep = step;
        
        if (step === 1) {
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
        } else if (step === 2) {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
        } else if (step === 3) {
            step1.classList.add('hidden');
            step2.classList.add('hidden');
        }

        window.scrollTo({ top: 180, behavior: 'smooth' });
    }

    // Open Preview Modal Function (Populates data & shows centered modal)
    function openPreviewModal() {
        document.getElementById('modal-applicant-name-val').textContent = applicantName.value.trim();
        document.getElementById('modal-applicant-nik-val').textContent = applicantNik.value.trim();
        document.getElementById('modal-applicant-phone-val').textContent = applicantPhone.value.trim();
        document.getElementById('modal-applicant-relation-val').textContent = applicantRelation ? applicantRelation.value : '-';

        let fullAddress = address.value.trim();
        if (padukuhan && padukuhan.value) {
            fullAddress += `, Padukuhan ${padukuhan.value}`;
        }
        if (rt && rw && (rt.value || rw.value)) {
            fullAddress += ` (RT ${rt.value || '-'}/RW ${rw.value || '-'})`;
        }
        document.getElementById('modal-applicant-address-val').textContent = fullAddress;

        document.getElementById('modal-file-ktp').textContent = docKtp.files[0]?.name || '-';
        document.getElementById('modal-file-kk').textContent = docKk.files[0]?.name || '-';
        document.getElementById('modal-file-lahir').textContent = docLahir.files[0]?.name || '-';

        // Populate thumbnails in modal
        const docKeys = [
            { key: 'ktp', input: docKtp },
            { key: 'kk', input: docKk },
            { key: 'lahir', input: docLahir }
        ];

        docKeys.forEach(item => {
            const step1Img = document.getElementById(`img-preview-${item.key}`);
            const modalImg = document.getElementById(`modal-thumb-${item.key}`);
            const modalPdf = document.getElementById(`modal-pdf-${item.key}`);

            if (item.input.files && item.input.files[0] && item.input.files[0].type.startsWith('image/')) {
                modalImg.src = step1Img.src;
                modalImg.classList.remove('hidden');
                modalPdf.classList.add('hidden');
            } else {
                modalImg.classList.add('hidden');
                modalPdf.classList.remove('hidden');
            }
        });

        // Buka Pop-up Modal di Tengah Layar dengan Animasi Halus
        showModal(previewModal);
    }

    // Step 1 Validation & Proceed to Pop-up Preview Modal
    document.getElementById('btn-to-preview').addEventListener('click', function () {
        let valid = true;
        let firstInvalid = null;

        // Reset errors
        document.querySelectorAll('.error-text').forEach(el => el.classList.add('hidden'));

        if (!applicantName.value.trim()) {
            document.getElementById('error-applicant_name').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = applicantName;
        }

        const nikVal = applicantNik.value.trim();
        if (!nikVal || nikVal.length !== 16 || isNaN(nikVal)) {
            document.getElementById('error-applicant_nik').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = applicantNik;
        }

        if (!address.value.trim()) {
            document.getElementById('error-address').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = address;
        }

        if (!applicantPhone.value.trim()) {
            document.getElementById('error-applicant_phone').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = applicantPhone;
        }

        if (!docKtp.files || docKtp.files.length === 0) {
            document.getElementById('error-doc_parents_ktp').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = document.getElementById('placeholder-ktp');
        }

        if (!docKk.files || docKk.files.length === 0) {
            document.getElementById('error-doc_family_card').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = document.getElementById('placeholder-kk');
        }

        if (!docLahir.files || docLahir.files.length === 0) {
            document.getElementById('error-doc_birth_cert').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = document.getElementById('placeholder-lahir');
        }

        if (!valid) {
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
            }
            return;
        }

        openPreviewModal();
    });

    // Close Modal Button handlers
    btnClosePreviewModal.addEventListener('click', () => hideModal(previewModal));
    btnModalEdit.addEventListener('click', () => hideModal(previewModal));

    // Klik di luar kartu modal untuk menutup
    previewModal.addEventListener('click', function (e) {
        if (e.target === previewModal) {
            hideModal(previewModal);
        }
    });

    // Modal Action: Konfirmasi & Lanjutkan ke Pengisian Biodata Bayi (Step 2)
    btnConfirmToBaby.addEventListener('click', function () {
        hideModal(previewModal);
        setStep(2);
    });

    // Step 2 Action: Kembali ke Data Pemohon
    document.getElementById('btn-back-to-step1').addEventListener('click', function () {
        setStep(1);
    });

    // Step 2: Final Submit with Validation & Pop-up Modal (Pengajuan Berhasil)
    document.getElementById('btn-submit-final').addEventListener('click', function () {
        let valid = true;
        let firstInvalid = null;

        document.querySelectorAll('.error-text').forEach(el => el.classList.add('hidden'));

        if (!childName.value.trim()) {
            document.getElementById('error-child_name').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = childName;
        }

        if (!birthPlace.value.trim()) {
            document.getElementById('error-birth_place').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = birthPlace;
        }

        if (!birthDate.value) {
            document.getElementById('error-birth_date').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = birthDate;
        }

        if (!birthOrder.value || birthOrder.value < 1) {
            document.getElementById('error-birth_order').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = birthOrder;
        }

        if (!declarationCheck.checked) {
            document.getElementById('error-declaration').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = declarationCheck;
        }

        if (!valid) {
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
            }
            return;
        }

        // Disable button & show spinner
        const submitBtn = document.getElementById('btn-submit-final');
        const submitText = document.getElementById('btn-submit-text');
        const submitSpinner = document.getElementById('btn-submit-spinner');
        
        submitBtn.disabled = true;
        submitText.classList.add('opacity-50');
        submitSpinner.classList.remove('hidden');

        // Build FormData
        const formData = new FormData(form);

        // Send AJAX Request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Save to localStorage for citizen convenience
                try {
                    const existing = JSON.parse(localStorage.getItem('purwobinangun_birth_submissions') || '[]');
                    existing.unshift({
                        registration_no: data.registration_no,
                        child_name: data.birth.child_name,
                        applicant_name: data.birth.applicant_name,
                        created_at: data.birth.created_at,
                        status_label: data.birth.status_label
                    });
                    localStorage.setItem('purwobinangun_birth_submissions', JSON.stringify(existing.slice(0, 20)));
                } catch (e) {
                    console.error("Local storage error:", e);
                }

                // Populate Step 3 Success Pop-up Modal
                document.getElementById('modal-reg-no').textContent = data.registration_no;
                document.getElementById('modal-child-name').textContent = data.birth.child_name;
                document.getElementById('modal-applicant-name').textContent = data.birth.applicant_name;
                
                document.getElementById('modal-btn-receipt').href = data.receipt_url;
                document.getElementById('modal-btn-tracking').href = data.tracking_url;
                document.getElementById('modal-btn-list').href = data.list_url;

                setStep(3);

                // Open Success Pop-up Modal Card with Smooth Animation
                showModal(successModal);
            }
        })
        .catch(error => {
            console.error("Submission error:", error);
            submitBtn.disabled = false;
            submitText.classList.remove('opacity-50');
            submitSpinner.classList.add('hidden');

            if (error.errors) {
                const firstKey = Object.keys(error.errors)[0];
                alert('Terdapat kesalahan pengisian data: ' + error.errors[firstKey][0]);
            } else {
                alert('Terjadi kendala saat mengirim permohonan. Formulir akan dikirimkan secara langsung.');
                form.submit();
            }
        });
    });

    // Copy Registration Number
    document.getElementById('btn-copy-reg').addEventListener('click', function () {
        const regNo = document.getElementById('modal-reg-no').textContent;
        navigator.clipboard.writeText(regNo).then(() => {
            this.innerHTML = '<i class="fa-solid fa-check text-emerald-600"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="fa-regular fa-copy text-sm"></i>';
            }, 2000);
        });
    });

    // ESC Key untuk menutup modal pratinjau
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && previewModal.classList.contains('active')) {
            hideModal(previewModal);
        }
    });

});
</script>
@endsection
