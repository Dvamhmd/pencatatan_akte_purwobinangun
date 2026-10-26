@extends('layouts.app')

@section('title', 'Formulir Pengajuan Akte Kelahiran')

@section('content')
@php
    $pekerjaanList = $pekerjaanList ?? [
        'Belum / Tidak Bekerja', 'Mengurus Rumah Tangga', 'Pelajar / Mahasiswa', 'Pensiunan',
        'Pegawai Negeri Sipil (PNS)', 'Tentara Nasional Indonesia (TNI)', 'Kepolisian RI (POLRI)',
        'Karyawan Swasta', 'Karyawan BUMN', 'Karyawan BUMD', 'Karyawan Honorer', 'Buruh Harian Lepas',
        'Buruh Tani / Perkebunan', 'Buruh Nelayan / Perikanan', 'Buruh Peternakan', 'Pembantu Rumah Tangga',
        'Petani / Pekebun', 'Peternak', 'Nelayan / Perikanan', 'Perdagangan / Pedagang', 'Wiraswasta',
        'Industri', 'Konstruksi', 'Transportasi', 'Sopir', 'Mekanik', 'Tukang Listrik', 'Tukang Kayu',
        'Tukang Batu', 'Tukang Jahit', 'Tukang Las / Pandai Besi', 'Tukang Cukur / Penata Rambut',
        'Penata Rias', 'Penata Busana', 'Juru Masak', 'Dosen', 'Guru', 'Dokter', 'Bidan', 'Perawat',
        'Apoteker', 'Psikiater / Psikolog', 'Pilot', 'Pelaut', 'Pengacara', 'Notaris', 'Arsitek',
        'Akuntan', 'Konsultan', 'Wartawan', 'Peneliti', 'Seniman', 'Perangkat Desa', 'Kepala Desa',
        'Tokoh Agama / Rohaniwan', 'Anggota Legislatif (DPR / DPD / DPRD)', 'Lainnya'
    ];
@endphp
<style>
    /* Pop-up Modal Top Layer & Smooth Transitions */
    .modal-overlay {
        position: fixed !important;
        inset: 0 !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100% !important;
        height: 100% !important;
        max-width: 100% !important;
        max-height: 100% !important;
        margin: 0 !important;
        z-index: 999999 !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        background-color: rgba(15, 23, 42, 0.72) !important;
        backdrop-filter: blur(6px) !important;
        -webkit-backdrop-filter: blur(6px) !important;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 1rem;
        box-sizing: border-box;
        overflow-y: auto !important;
        overscroll-behavior: contain;
    }
    .modal-overlay.active {
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
    }
    .modal-dialog-box {
        opacity: 0;
        transform: scale(0.90) translateY(32px);
        transition: transform 0.75s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1);
        width: 100% !important;
        max-width: 38rem !important;
        max-height: calc(100% - 1.5rem) !important;
        display: flex !important;
        flex-direction: column !important;
        z-index: 1000000 !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45) !important;
        margin: auto !important;
        overflow: hidden;
        will-change: transform, opacity;
    }
    .modal-overlay.active .modal-dialog-box {
        opacity: 1 !important;
        transform: scale(1) translateY(0) !important;
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
    /* Hilangkan panah spinner bawaan browser pada input number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none !important;
        margin: 0 !important;
    }
    input[type=number] {
        -moz-appearance: textfield !important;
        appearance: textfield !important;
    }
    /* Garis penghubung stepper tepat terpusat secara vertikal terhadap bulatan indikator */
    .stepper-progress-track {
        position: absolute;
        top: 16px;
        left: 16px;
        right: 16px;
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 0;
    }
    @media (min-width: 640px) {
        .stepper-progress-track {
            top: 20px;
            left: 20px;
            right: 20px;
        }
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
            <a href="{{ route('submissions.index') }}" class="bg-white/15 hover:bg-white/25 text-white border border-white/20 text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-list-check text-teal-200"></i> Daftar Pengajuan
            </a>
        </div>

        <!-- Stepper Progress Langkah Pengajuan (1 - 5) -->
        <div class="bg-gradient-to-b from-slate-50 to-slate-100/70 border-b border-slate-200 px-4 py-4 sm:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <!-- Progress Line Background (Center Vertikal) -->
                    <div class="stepper-progress-track">
                        <div class="w-full h-1 bg-slate-200 rounded-full"></div>
                        <div id="progress-bar-line" class="h-1 bg-[#095b8c] rounded-full transition-all duration-300" style="width: 0%; margin-top: -4px;"></div>
                    </div>

                    <!-- Steps Bulatan 1 - 5 -->
                    <div class="relative z-10 flex items-center justify-between">
                        <!-- Step 1: Bayi -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(1)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-md bg-[#095b8c] text-white ring-4 ring-teal-100 ring-offset-1 cursor-default" id="step-circle-1" title="Langkah 1: Biodata Bayi">
                                <span class="step-num">1</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-bold text-[#095b8c] mt-1.5 text-center whitespace-nowrap" id="step-label-1">Bayi</span>
                        </div>

                        <!-- Step 2: Orang Tua -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(2)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600" id="step-circle-2" title="Langkah 2: Data Orang Tua">
                                <span class="step-num">2</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap" id="step-label-2">Orang Tua</span>
                        </div>

                        <!-- Step 3: Pemohon -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(3)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600" id="step-circle-3" title="Langkah 3: Identitas Pemohon">
                                <span class="step-num">3</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap" id="step-label-3">Pemohon</span>
                        </div>

                        <!-- Step 4: Saksi -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(4)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600" id="step-circle-4" title="Langkah 4: Data Saksi">
                                <span class="step-num">4</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap" id="step-label-4">Saksi</span>
                        </div>

                        <!-- Step 5: Dokumen -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(5)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600" id="step-circle-5" title="Langkah 5: Unggah Dokumen">
                                <span class="step-num">5</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap" id="step-label-5">Dokumen</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Element -->
        <form id="birth-application-form" action="{{ route('birth.store') }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-8">
            @csrf

            <!-- ======================================================= -->
            <!-- LANGKAH 1: BIODATA BAYI                                   -->
            <!-- ======================================================= -->
            <div id="step-1" class="space-y-6">
                <!-- Form Biodata Bayi -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2 pb-2 border-b border-teal-100">
                        <i class="fa-solid fa-baby text-amber-500"></i> Biodata Lengkap Bayi
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- 1. Nama Lengkap Anak -->
                        <div>
                            <label for="child_name" class="block text-xs font-semibold text-slate-700 mb-1">
                                1. Nama Lengkap Bayi / Anak <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="child_name" name="child_name" value="{{ old('child_name') }}" required placeholder="Masukkan Nama Lengkap Bayi/Anak" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition font-medium">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-child_name">Nama lengkap anak wajib diisi.</span>
                        </div>

                        <!-- 2. NIK Anak -->
                        <div>
                            <label for="child_nik" class="block text-xs font-semibold text-slate-700 mb-1">
                                2. NIK Bayi / Anak <span class="text-slate-400 font-normal">(Opsional jika belum memiliki NIK)</span>
                            </label>
                            <input type="text" id="child_nik" name="child_nik" value="{{ old('child_nik') }}" maxlength="16" placeholder="Masukkan 16 Digit NIK Anak (Bila Ada)" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-child_nik">NIK anak harus berupa 16 digit angka.</span>
                        </div>

                        <!-- 3. Jenis Kelamin -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                3. Jenis Kelamin <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border border-slate-300 hover:border-[#095b8c] cursor-pointer transition bg-white has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500 has-[:checked]:text-blue-700">
                                    <input type="radio" name="gender" value="L" checked class="text-[#095b8c] focus:ring-[#095b8c]">
                                    <i class="fa-solid fa-mars text-blue-600"></i>
                                    <span class="text-xs font-semibold">1. Laki-laki</span>
                                </label>

                                <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border border-slate-300 hover:border-[#095b8c] cursor-pointer transition bg-white has-[:checked]:bg-pink-50 has-[:checked]:border-pink-500 has-[:checked]:text-pink-700">
                                    <input type="radio" name="gender" value="P" class="text-[#095b8c] focus:ring-[#095b8c]">
                                    <i class="fa-solid fa-venus text-pink-600"></i>
                                    <span class="text-xs font-semibold">2. Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <!-- 4. Tempat Dilahirkan -->
                        <div>
                            <label for="birth_place_type" class="block text-xs font-semibold text-slate-700 mb-1">
                                4. Tempat Dilahirkan <span class="text-rose-500">*</span>
                            </label>
                            <select id="birth_place_type" name="birth_place_type" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                <option value="RS/SB" selected>1. RS/SB (Rumah Sakit / Bersalin)</option>
                                <option value="Puskesmas">2. Puskesmas</option>
                                <option value="Polindes">3. Polindes</option>
                                <option value="Rumah">4. Rumah</option>
                                <option value="Lainnya">5. Lainnya</option>
                            </select>

                            <!-- Input Kustom jika Tempat Dilahirkan Lainnya -->
                            <div id="custom-birth-place-box" class="mt-2 hidden">
                                <input type="text" id="birth_place_other" name="birth_place_other" placeholder="Sebutkan Tempat Dilahirkan" class="w-full text-xs px-3 py-2 rounded-lg border border-teal-400 bg-teal-50/40 focus:outline-none focus:ring-2 focus:ring-[#095b8c]">
                            </div>
                        </div>

                        <!-- 5. Tempat Kelahiran -->
                        <div>
                            <label for="birth_place" class="block text-xs font-semibold text-slate-700 mb-1">
                                5. Tempat Kelahiran (Kabupaten / Kota) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place', 'Sleman') }}" required placeholder="Masukkan Kabupaten / Kota Kelahiran" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-birth_place">Tempat kelahiran wajib diisi.</span>
                        </div>

                        <!-- 6. Tanggal Lahir -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                6. Tanggal Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white cursor-pointer">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-birth_date">Tanggal lahir wajib diisi.</span>
                        </div>

                        <!-- 7. Pukul Kelahiran -->
                        <div>
                            <label for="birth_time" class="block text-xs font-semibold text-slate-700 mb-1">
                                7. Pukul Kelahiran <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="time" id="birth_time" name="birth_time" value="{{ old('birth_time') }}" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white cursor-pointer">
                        </div>

                        <!-- 8. Jenis Kelahiran -->
                        <div>
                            <label for="birth_type" class="block text-xs font-semibold text-slate-700 mb-1">
                                8. Jenis Kelahiran <span class="text-rose-500">*</span>
                            </label>
                            <select id="birth_type" name="birth_type" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                <option value="Tunggal" selected>1. Tunggal</option>
                                <option value="Kembar 2">2. Kembar 2</option>
                                <option value="Kembar 3">3. Kembar 3</option>
                                <option value="Kembar 4">4. Kembar 4</option>
                                <option value="Input Jumlah">Lainnya (Input Jumlah)</option>
                            </select>

                            <!-- Input Jumlah Kustom jika dipilih Input Jumlah -->
                            <div id="custom-birth-type-box" class="mt-2 hidden">
                                <input type="number" id="birth_type_custom" name="birth_type_custom" min="4" max="10" placeholder="Masukkan Jumlah Anak Kembar" class="w-full text-xs px-3 py-2 rounded-lg border border-teal-400 bg-teal-50/40 focus:outline-none focus:ring-2 focus:ring-[#095b8c]">
                            </div>
                        </div>

                        <!-- 9. Kelahiran Anak Ke -->
                        <div>
                            <label for="birth_order" class="block text-xs font-semibold text-slate-700 mb-1">
                                9. Kelahiran Anak Ke <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="birth_order" name="birth_order" min="1" max="25" value="{{ old('birth_order', 1) }}" required placeholder="Kelahiran Anak ke-" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-birth_order">Kelahiran anak ke- wajib diisi.</span>
                        </div>

                        <!-- 10. Penolong Kelahiran -->
                        <div>
                            <label for="birth_helper" class="block text-xs font-semibold text-slate-700 mb-1">
                                10. Penolong Kelahiran <span class="text-rose-500">*</span>
                            </label>
                            <select id="birth_helper" name="birth_helper" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                <option value="Dokter">1. Dokter</option>
                                <option value="Bidan / Perawat" selected>2. Bidan / Perawat</option>
                                <option value="Dukun">3. Dukun</option>
                                <option value="Lainnya">4. Lainnya</option>
                            </select>

                            <!-- Input Kustom jika Penolong Kelahiran Lainnya -->
                            <div id="custom-birth-helper-box" class="mt-2 hidden">
                                <input type="text" id="birth_helper_other" name="birth_helper_other" placeholder="Sebutkan Penolong Kelahiran" class="w-full text-xs px-3 py-2 rounded-lg border border-teal-400 bg-teal-50/40 focus:outline-none focus:ring-2 focus:ring-[#095b8c]">
                            </div>
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-birth_helper">Penolong kelahiran wajib dipilih.</span>
                        </div>

                        <!-- 11. Berat Bayi -->
                        <div>
                            <label for="weight_gram" class="block text-xs font-semibold text-slate-700 mb-1">
                                11. Berat Bayi <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="1" min="100" max="10000" id="weight_gram" name="weight_gram" value="{{ old('weight_gram') }}" placeholder="Contoh: 3200" class="w-full text-xs px-3.5 pr-14 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]" style="-moz-appearance: textfield; appearance: textfield;">
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">gram</span>
                            </div>
                            <span class="text-[10px] text-slate-500 mt-0.5 block">Berat badan bayi saat lahir dalam satuan gram</span>
                        </div>

                        <!-- 12. Panjang Bayi -->
                        <div>
                            <label for="length_cm" class="block text-xs font-semibold text-slate-700 mb-1">
                                12. Panjang Bayi <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="0.1" min="20" max="100" id="length_cm" name="length_cm" value="{{ old('length_cm') }}" placeholder="Contoh: 49.5" class="w-full text-xs px-3.5 pr-10 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]" style="-moz-appearance: textfield; appearance: textfield;">
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">cm</span>
                            </div>
                            <span class="text-[10px] text-slate-500 mt-0.5 block">Panjang badan bayi saat lahir dalam satuan cm</span>
                        </div>

                    </div>
                </div>

                <!-- Tombol Navigasi Langkah 1 -->
                <div class="pt-6 border-t border-slate-200 flex items-center justify-end">
                    <button type="button" id="btn-step1-next" class="shrink-0 bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-xs sm:text-sm rounded-lg shadow-sm hover:shadow-md transition cursor-pointer flex items-center justify-center gap-2" style="width: 140px; height: 42px; padding: 0 20px;">
                        <span>Selanjutnya</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- ======================================================= -->
            <!-- LANGKAH 2: DATA KEDUA ORANG TUA                           -->
            <!-- ======================================================= -->
            <div id="step-2" class="space-y-6 hidden">
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2 pb-2 border-b border-teal-100">
                        <i class="fa-solid fa-users text-amber-500"></i> Data Kedua Orang Tua
                    </h4>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5">
                        
                        <!-- ================= Data Ibu Kandung (Sesuai Formulir F-2.01) ================= -->
                        <div class="bg-slate-50/70 p-4 sm:p-5 rounded-xl border border-slate-200 space-y-4">
                            <div>
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 mb-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-md bg-pink-100 text-pink-700 flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid fa-user-nurse"></i>
                                        </div>
                                        <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Data Ibu Kandung</h5>
                                    </div>
                                    <span class="text-[10px] bg-pink-50 text-pink-700 px-2 py-0.5 rounded-full font-semibold border border-pink-200">Bagian IBU</span>
                                </div>

                                <div class="space-y-3">
                                    <!-- 1. NIK Ibu -->
                                    <div>
                                        <label for="mother_nik" class="block text-xs font-semibold text-slate-700 mb-1">
                                            1. NIK Ibu (16 Digit) <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" id="mother_nik" name="mother_nik" value="{{ old('mother_nik', $defaultMother['nik'] ?? '') }}" maxlength="16" required placeholder="Masukkan NIK Ibu (16 Angka)" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition font-mono">
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-mother_nik">NIK Ibu wajib 16 digit angka.</span>
                                    </div>

                                    <!-- 2. Nama Lengkap Ibu -->
                                    <div>
                                        <label for="mother_name" class="block text-xs font-semibold text-slate-700 mb-1">
                                            2. Nama Lengkap Ibu <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name', $defaultMother['name'] ?? '') }}" required placeholder="Masukkan Nama Lengkap Ibu" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-mother_name">Nama lengkap ibu wajib diisi.</span>
                                    </div>

                                    <!-- 3. Tanggal Lahir / Umur Ibu -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                                            3. Tanggal Lahir / Umur Ibu <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                            <div class="sm:col-span-2">
                                                <input type="date" id="mother_birth_date" name="mother_birth_date" value="{{ old('mother_birth_date', $defaultMother['birth_date'] ?? '') }}" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition bg-white">
                                            </div>
                                            <div>
                                                <div class="relative">
                                                    <input type="number" id="mother_age" name="mother_age" value="{{ old('mother_age') }}" placeholder="Umur" min="1" max="130" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white pr-9">
                                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-slate-400 font-medium">Thn</span>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-mother_birth_date">Tanggal lahir ibu wajib diisi.</span>
                                    </div>

                                    <!-- 4. Pekerjaan Ibu -->
                                    <div>
                                        <label for="mother_job" class="block text-xs font-semibold text-slate-700 mb-1">
                                            4. Pekerjaan Ibu <span class="text-rose-500">*</span>
                                        </label>
                                        <select id="mother_job" name="mother_job" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition bg-white">
                                            <option value="">-- Pilih Kategori Pekerjaan Ibu --</option>
                                            @foreach ($pekerjaanList as $job)
                                                <option value="{{ $job }}" {{ old('mother_job') == $job ? 'selected' : '' }}>{{ $job }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-mother_job">Pekerjaan ibu wajib dipilih.</span>
                                    </div>

                                    <!-- 5. Alamat Ibu -->
                                    <div class="pt-2 border-t border-slate-200/80 space-y-2.5">
                                        <label class="block text-xs font-semibold text-slate-700">
                                            5. Alamat Ibu
                                        </label>
                                        <div>
                                            <input type="text" id="mother_address" name="mother_address" value="{{ old('mother_address', $defaultMother['address'] ?? '') }}" placeholder="Alamat (Jalan / Dusun)" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <input type="text" id="mother_rt" name="mother_rt" value="{{ old('mother_rt', $defaultMother['rt'] ?? '01') }}" maxlength="5" placeholder="RT" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition text-center bg-white font-mono">
                                            </div>
                                            <div>
                                                <input type="text" id="mother_rw" name="mother_rw" value="{{ old('mother_rw', $defaultMother['rw'] ?? '01') }}" maxlength="5" placeholder="RW" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition text-center bg-white font-mono">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[11px] text-slate-500 mb-0.5">a. Desa / Kelurahan</label>
                                                <input type="text" id="mother_village" name="mother_village" value="{{ old('mother_village', $defaultMother['village'] ?? 'Purwobinangun') }}" placeholder="Desa/Kelurahan" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] text-slate-500 mb-0.5">b. Kecamatan</label>
                                                <input type="text" id="mother_district" name="mother_district" value="{{ old('mother_district', $defaultMother['district'] ?? 'Pakem') }}" placeholder="Kecamatan" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[11px] text-slate-500 mb-0.5">c. Kabupaten / Kota</label>
                                                <input type="text" id="mother_city" name="mother_city" value="{{ old('mother_city', $defaultMother['city'] ?? 'Sleman') }}" placeholder="Kabupaten/Kota" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] text-slate-500 mb-0.5">d. Propinsi</label>
                                                <input type="text" id="mother_province" name="mother_province" value="{{ old('mother_province', $defaultMother['province'] ?? 'D.I. Yogyakarta') }}" placeholder="Propinsi" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 6 & 7. Kewarganegaraan & Kebangsaan Ibu -->
                                    <div class="pt-2 border-t border-slate-200/80 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <div>
                                            <label for="mother_citizenship" class="block text-xs font-semibold text-slate-700 mb-1">
                                                6. Kewarganegaraan
                                            </label>
                                            <select id="mother_citizenship" name="mother_citizenship" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                                <option value="WNI" {{ old('mother_citizenship', $defaultMother['citizenship'] ?? 'WNI') === 'WNI' ? 'selected' : '' }}>1. WNI</option>
                                                <option value="WNA" {{ old('mother_citizenship', $defaultMother['citizenship'] ?? '') === 'WNA' ? 'selected' : '' }}>2. WNA</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="mother_nationality" class="block text-xs font-semibold text-slate-700 mb-1">
                                                7. Kebangsaan
                                            </label>
                                            <input type="text" id="mother_nationality" name="mother_nationality" value="{{ old('mother_nationality', $defaultMother['nationality'] ?? 'INDONESIA') }}" placeholder="Contoh: INDONESIA" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition uppercase bg-white">
                                        </div>
                                    </div>

                                    <!-- 8. Tgl Pencatatan Perkawinan -->
                                    <div class="pt-2 border-t border-slate-200/80">
                                        <label for="marriage_date" class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                                            <span>8. Tgl Pencatatan Perkawinan</span>
                                            <span class="text-[10px] text-slate-400 font-normal">Sesuai Buku Nikah/Akta</span>
                                        </label>
                                        <input type="date" id="marriage_date" name="marriage_date" value="{{ old('marriage_date') }}" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                        <span class="text-[11px] text-slate-500 mt-1 block">Diisi tanggal pernikahan/perkawinan orang tua.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ================= Data Ayah Kandung (Sesuai Formulir F-2.01) ================= -->
                        <div class="bg-slate-50/70 p-4 sm:p-5 rounded-xl border border-slate-200 space-y-4">
                            <div>
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 mb-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-md bg-blue-100 text-blue-700 flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                        <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Data Ayah Kandung</h5>
                                    </div>
                                    <button type="button" id="btn-copy-mother-address" class="text-[10px] sm:text-[11px] font-semibold text-[#095b8c] hover:text-white bg-blue-50 hover:bg-[#095b8c] border border-blue-200 px-2 py-1 rounded-md transition flex items-center gap-1 cursor-pointer" title="Salin alamat dari data Ibu">
                                        <i class="fa-solid fa-copy"></i> Samakan Alamat dgn Ibu
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    <!-- 1. NIK Ayah -->
                                    <div>
                                        <label for="father_nik" class="block text-xs font-semibold text-slate-700 mb-1">
                                            1. NIK Ayah (16 Digit) <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" id="father_nik" name="father_nik" value="{{ old('father_nik', $defaultFather['nik'] ?? '') }}" maxlength="16" required placeholder="Masukkan NIK Ayah (16 Angka)" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition font-mono">
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-father_nik">NIK Ayah wajib 16 digit angka.</span>
                                    </div>

                                    <!-- 2. Nama Lengkap Ayah -->
                                    <div>
                                        <label for="father_name" class="block text-xs font-semibold text-slate-700 mb-1">
                                            2. Nama Lengkap Ayah <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" id="father_name" name="father_name" value="{{ old('father_name', $defaultFather['name'] ?? '') }}" required placeholder="Masukkan Nama Lengkap Ayah" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-father_name">Nama lengkap ayah wajib diisi.</span>
                                    </div>

                                    <!-- 3. Tanggal Lahir / Umur Ayah -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                                            3. Tanggal Lahir / Umur Ayah <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                            <div class="sm:col-span-2">
                                                <input type="date" id="father_birth_date" name="father_birth_date" value="{{ old('father_birth_date', $defaultFather['birth_date'] ?? '') }}" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition bg-white">
                                            </div>
                                            <div>
                                                <div class="relative">
                                                    <input type="number" id="father_age" name="father_age" value="{{ old('father_age') }}" placeholder="Umur" min="1" max="130" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white pr-9">
                                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-slate-400 font-medium">Thn</span>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-father_birth_date">Tanggal lahir ayah wajib diisi.</span>
                                    </div>

                                    <!-- 4. Pekerjaan Ayah -->
                                    <div>
                                        <label for="father_job" class="block text-xs font-semibold text-slate-700 mb-1">
                                            4. Pekerjaan Ayah <span class="text-rose-500">*</span>
                                        </label>
                                        <select id="father_job" name="father_job" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition bg-white">
                                            <option value="">-- Pilih Kategori Pekerjaan Ayah --</option>
                                            @foreach ($pekerjaanList as $job)
                                                <option value="{{ $job }}" {{ old('father_job') == $job ? 'selected' : '' }}>{{ $job }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-father_job">Pekerjaan ayah wajib dipilih.</span>
                                    </div>

                                    <!-- 5. Alamat Ayah -->
                                    <div class="pt-2 border-t border-slate-200/80 space-y-2.5">
                                        <label class="block text-xs font-semibold text-slate-700">
                                            5. Alamat Ayah
                                        </label>
                                        <div>
                                            <input type="text" id="father_address" name="father_address" value="{{ old('father_address', $defaultFather['address'] ?? '') }}" placeholder="Alamat (Jalan / Dusun)" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <input type="text" id="father_rt" name="father_rt" value="{{ old('father_rt', $defaultFather['rt'] ?? '01') }}" maxlength="5" placeholder="RT" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition text-center bg-white font-mono">
                                            </div>
                                            <div>
                                                <input type="text" id="father_rw" name="father_rw" value="{{ old('father_rw', $defaultFather['rw'] ?? '01') }}" maxlength="5" placeholder="RW" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition text-center bg-white font-mono">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[11px] text-slate-500 mb-0.5">a. Desa / Kelurahan</label>
                                                <input type="text" id="father_village" name="father_village" value="{{ old('father_village', $defaultFather['village'] ?? 'Purwobinangun') }}" placeholder="Desa/Kelurahan" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] text-slate-500 mb-0.5">b. Kecamatan</label>
                                                <input type="text" id="father_district" name="father_district" value="{{ old('father_district', $defaultFather['district'] ?? 'Pakem') }}" placeholder="Kecamatan" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[11px] text-slate-500 mb-0.5">c. Kabupaten / Kota</label>
                                                <input type="text" id="father_city" name="father_city" value="{{ old('father_city', $defaultFather['city'] ?? 'Sleman') }}" placeholder="Kabupaten/Kota" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] text-slate-500 mb-0.5">d. Propinsi</label>
                                                <input type="text" id="father_province" name="father_province" value="{{ old('father_province', $defaultFather['province'] ?? 'D.I. Yogyakarta') }}" placeholder="Propinsi" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 6 & 7. Kewarganegaraan & Kebangsaan Ayah -->
                                    <div class="pt-2 border-t border-slate-200/80 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <div>
                                            <label for="father_citizenship" class="block text-xs font-semibold text-slate-700 mb-1">
                                                6. Kewarganegaraan
                                            </label>
                                            <select id="father_citizenship" name="father_citizenship" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition bg-white">
                                                <option value="WNI" {{ old('father_citizenship', $defaultFather['citizenship'] ?? 'WNI') === 'WNI' ? 'selected' : '' }}>1. WNI</option>
                                                <option value="WNA" {{ old('father_citizenship', $defaultFather['citizenship'] ?? '') === 'WNA' ? 'selected' : '' }}>2. WNA</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="father_nationality" class="block text-xs font-semibold text-slate-700 mb-1">
                                                7. Kebangsaan
                                            </label>
                                            <input type="text" id="father_nationality" name="father_nationality" value="{{ old('father_nationality', $defaultFather['nationality'] ?? 'INDONESIA') }}" placeholder="Contoh: INDONESIA" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] transition uppercase bg-white">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Informasi Kecil di Bawah Section -->
                    <div class="flex items-center gap-2 text-[11px] text-slate-500 bg-slate-50 px-3.5 py-2.5 rounded-lg border border-slate-200">
                        <i class="fa-solid fa-circle-info text-[#095b8c] text-xs shrink-0"></i>
                        <span>Data orang tua diisi otomatis berdasarkan data KK. Silakan periksa dan ubah apabila data tidak sesuai.</span>
                    </div>

                    <!-- Tombol Navigasi Langkah 2 -->
                    <div class="pt-6 border-t border-slate-200 flex items-center justify-between gap-4">
                        <button type="button" id="btn-step2-back" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition flex items-center gap-1.5 shrink-0 py-2.5 px-3 rounded-lg hover:bg-slate-100 cursor-pointer">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" id="btn-step2-next" class="shrink-0 bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-xs sm:text-sm rounded-lg shadow-sm hover:shadow-md transition cursor-pointer flex items-center justify-center gap-2" style="width: 140px; height: 42px; padding: 0 20px;">
                            <span>Selanjutnya</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                </div>
            </div>

            <!-- ======================================================= -->
            <!-- LANGKAH 3: IDENTITAS DIRI PEMOHON                         -->
            <!-- ======================================================= -->
            <div id="step-3" class="space-y-6 hidden">
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2 pb-2 border-b border-teal-100">
                        <i class="fa-solid fa-user-pen text-amber-500"></i> Identitas Diri Pemohon
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Lengkap Pemohon -->
                        <div>
                            <label for="applicant_name" class="block text-xs font-semibold text-slate-700 mb-1">
                                Nama Lengkap Pemohon <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="applicant_name" name="applicant_name" value="{{ old('applicant_name', $user->name ?? '') }}" required placeholder="Masukkan Nama Lengkap Pemohon" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-applicant_name">Nama lengkap pemohon wajib diisi.</span>
                        </div>

                        <!-- NIK Pemohon -->
                        <div>
                            <label for="applicant_nik" class="block text-xs font-semibold text-slate-700 mb-1">
                                NIK Pemohon (16 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="applicant_nik" name="applicant_nik" value="{{ old('applicant_nik', $user->nik ?? '') }}" maxlength="16" required placeholder="Masukkan NIK Pemohon (16 Angka)" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
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
                            <input type="text" id="applicant_phone" name="applicant_phone" value="{{ old('applicant_phone', $user->phone ?? '') }}" required placeholder="Masukkan Nomor HP / WhatsApp Aktif" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
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
                                <input type="text" id="rt" name="rt" value="{{ old('rt', $user->rt ?? '01') }}" placeholder="RT" class="text-xs px-3 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] text-center">
                                <input type="text" id="rw" name="rw" value="{{ old('rw', $user->rw ?? '01') }}" placeholder="RW" class="text-xs px-3 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] text-center">
                            </div>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-xs font-semibold text-slate-700 mb-1">
                                Alamat Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="address" name="address" rows="2" required placeholder="Masukkan Alamat Lengkap" class="w-full text-xs px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">{{ old('address', $user->address ?? '') }}</textarea>
                            <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-address">Alamat lengkap pemohon wajib diisi.</span>
                        </div>
                    </div>

                    <!-- Tombol Navigasi Langkah 3 -->
                    <div class="pt-6 border-t border-slate-200 flex items-center justify-between gap-4">
                        <button type="button" id="btn-step3-back" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition flex items-center gap-1.5 shrink-0 py-2.5 px-3 rounded-lg hover:bg-slate-100 cursor-pointer">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" id="btn-step3-next" class="shrink-0 bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-xs sm:text-sm rounded-lg shadow-sm hover:shadow-md transition cursor-pointer flex items-center justify-center gap-2" style="width: 140px; height: 42px; padding: 0 20px;">
                            <span>Selanjutnya</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                </div>
            </div>

            <!-- ======================================================= -->
            <!-- LANGKAH 4: DATA SAKSI 1 & SAKSI 2                         -->
            <!-- ======================================================= -->
            <div id="step-4" class="space-y-6 hidden">
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2 pb-2 border-b border-teal-100">
                        <i class="fa-solid fa-user-group text-amber-500"></i> Data Saksi Kelahiran (Saksi 1 & Saksi 2)
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                        
                        <!-- Data Saksi 1 -->
                        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-200 space-y-3.5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 mb-3.5">
                                    <div class="w-6 h-6 rounded-md bg-teal-100 text-[#095b8c] flex items-center justify-center text-xs shrink-0 font-bold">
                                        1
                                    </div>
                                    <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Data Saksi 1</h5>
                                </div>

                                <div class="space-y-3">
                                    <!-- NIK Saksi 1 -->
                                    <div>
                                        <label for="witness_1_nik" class="block text-xs font-semibold text-slate-700 mb-1">
                                            NIK Saksi 1 (16 Digit) <span class="text-slate-400 font-normal">(Opsional)</span>
                                        </label>
                                        <input type="text" id="witness_1_nik" name="witness_1_nik" value="{{ old('witness_1_nik') }}" maxlength="16" placeholder="Masukkan NIK Saksi 1 (16 Angka)" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition font-mono">
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-witness_1_nik">NIK Saksi 1 harus 16 digit angka.</span>
                                    </div>

                                    <!-- Nama Lengkap Saksi 1 -->
                                    <div>
                                        <label for="witness_1_name" class="block text-xs font-semibold text-slate-700 mb-1">
                                            Nama Lengkap Saksi 1
                                        </label>
                                        <input type="text" id="witness_1_name" name="witness_1_name" value="{{ old('witness_1_name') }}" placeholder="Masukkan Nama Lengkap Saksi 1" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
                                    </div>

                                    <!-- Tanggal Lahir Saksi 1 -->
                                    <div>
                                        <label for="witness_1_birth_date" class="block text-xs font-semibold text-slate-700 mb-1">
                                            Tanggal Lahir Saksi 1
                                        </label>
                                        <input type="date" id="witness_1_birth_date" name="witness_1_birth_date" value="{{ old('witness_1_birth_date') }}" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition bg-white">
                                    </div>

                                    <!-- Pekerjaan Saksi 1 -->
                                    <div>
                                        <label for="witness_1_job" class="block text-xs font-semibold text-slate-700 mb-1">
                                            Pekerjaan Saksi 1
                                        </label>
                                        <select id="witness_1_job" name="witness_1_job" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition bg-white">
                                            <option value="">-- Pilih Pekerjaan Saksi 1 --</option>
                                            @foreach ($pekerjaanList as $job)
                                                <option value="{{ $job }}" {{ old('witness_1_job') == $job ? 'selected' : '' }}>{{ $job }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Alamat Saksi 1 -->
                                    <div>
                                        <label for="witness_1_address" class="block text-xs font-semibold text-slate-700 mb-1">
                                            Alamat Domisili Saksi 1
                                        </label>
                                        <textarea id="witness_1_address" name="witness_1_address" rows="2" placeholder="Masukkan Alamat Saksi 1" class="w-full text-xs px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">{{ old('witness_1_address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Saksi 2 -->
                        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-200 space-y-3.5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 mb-3.5">
                                    <div class="w-6 h-6 rounded-md bg-teal-100 text-[#095b8c] flex items-center justify-center text-xs shrink-0 font-bold">
                                        2
                                    </div>
                                    <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Data Saksi 2</h5>
                                </div>

                                <div class="space-y-3">
                                    <!-- NIK Saksi 2 -->
                                    <div>
                                        <label for="witness_2_nik" class="block text-xs font-semibold text-slate-700 mb-1">
                                            NIK Saksi 2 (16 Digit) <span class="text-slate-400 font-normal">(Opsional)</span>
                                        </label>
                                        <input type="text" id="witness_2_nik" name="witness_2_nik" value="{{ old('witness_2_nik') }}" maxlength="16" placeholder="Masukkan NIK Saksi 2 (16 Angka)" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition font-mono">
                                        <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-witness_2_nik">NIK Saksi 2 harus 16 digit angka.</span>
                                    </div>

                                    <!-- Nama Lengkap Saksi 2 -->
                                    <div>
                                        <label for="witness_2_name" class="block text-xs font-semibold text-slate-700 mb-1">
                                            Nama Lengkap Saksi 2
                                        </label>
                                        <input type="text" id="witness_2_name" name="witness_2_name" value="{{ old('witness_2_name') }}" placeholder="Masukkan Nama Lengkap Saksi 2" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">
                                    </div>

                                    <!-- Tanggal Lahir Saksi 2 -->
                                    <div>
                                        <label for="witness_2_birth_date" class="block text-xs font-semibold text-slate-700 mb-1">
                                            Tanggal Lahir Saksi 2
                                        </label>
                                        <input type="date" id="witness_2_birth_date" name="witness_2_birth_date" value="{{ old('witness_2_birth_date') }}" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition bg-white">
                                    </div>

                                    <!-- Pekerjaan Saksi 2 -->
                                    <div>
                                        <label for="witness_2_job" class="block text-xs font-semibold text-slate-700 mb-1">
                                            Pekerjaan Saksi 2
                                        </label>
                                        <select id="witness_2_job" name="witness_2_job" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition bg-white">
                                            <option value="">-- Pilih Pekerjaan Saksi 2 --</option>
                                            @foreach ($pekerjaanList as $job)
                                                <option value="{{ $job }}" {{ old('witness_2_job') == $job ? 'selected' : '' }}>{{ $job }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Alamat Saksi 2 -->
                                    <div>
                                        <label for="witness_2_address" class="block text-xs font-semibold text-slate-700 mb-1">
                                            Alamat Domisili Saksi 2
                                        </label>
                                        <textarea id="witness_2_address" name="witness_2_address" rows="2" placeholder="Masukkan Alamat Saksi 2" class="w-full text-xs px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] focus:border-[#095b8c] transition">{{ old('witness_2_address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Informasi Kecil di Bawah Section Saksi -->
                    <div class="flex items-center gap-2 text-[11px] text-slate-500 bg-slate-50 px-3.5 py-2.5 rounded-lg border border-slate-200">
                        <i class="fa-solid fa-circle-info text-[#095b8c] text-xs shrink-0"></i>
                        <span>Saksi kelahiran adalah dua orang warga yang mengetahui peristiwa kelahiran secara langsung.</span>
                    </div>

                    <!-- Tombol Navigasi Langkah 4 -->
                    <div class="pt-6 border-t border-slate-200 flex items-center justify-between gap-4">
                        <button type="button" id="btn-step4-back" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition flex items-center gap-1.5 shrink-0 py-2.5 px-3 rounded-lg hover:bg-slate-100 cursor-pointer">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" id="btn-step4-next" class="shrink-0 bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-xs sm:text-sm rounded-lg shadow-sm hover:shadow-md transition cursor-pointer flex items-center justify-center gap-2" style="width: 140px; height: 42px; padding: 0 20px;">
                            <span>Selanjutnya</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                </div>
            </div>

            <!-- ======================================================= -->
            <!-- LANGKAH 5: UNGGAH DOKUMEN PERSYARATAN & SUBMIT           -->
            <!-- ======================================================= -->
            <div id="step-5" class="space-y-6 hidden">
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2 pb-2 border-b border-teal-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                            <i class="fa-solid fa-cloud-arrow-up text-amber-500"></i> Upload Dokumen Persyaratan
                        </h4>
                        <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded font-medium">Format: PDF, JPG, PNG (Maks. 3MB)</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
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

                        <!-- 4. Dokumen Buku Nikah / Akta Perkawinan -->
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-between hover:border-[#095b8c] transition h-full" style="min-height: 270px;">
                            <div class="h-14 mb-2 flex flex-col justify-start shrink-0" style="height: 48px; min-height: 48px;">
                                <label class="block text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-book-bookmark text-[#095b8c]"></i> 4. Buku Nikah / Akta Perkawinan <span class="text-rose-500">*</span>
                                </label>
                                <p class="text-[11px] text-slate-500 leading-tight">Foto / Scan Buku Nikah orang tua atau Akta Perkawinan</p>
                            </div>

                            <div class="mt-auto w-full">
                                <input type="file" id="doc_marriage_cert" name="doc_marriage_cert" accept=".jpg,.jpeg,.png,.pdf" required class="hidden file-input" data-card="nikah">
                                
                                <!-- Box Upload Standar -->
                                <label for="doc_marriage_cert" id="placeholder-nikah" class="cursor-pointer flex flex-col items-center justify-center text-center bg-white hover:bg-teal-50 text-[#095b8c] border-2 border-dashed border-teal-300 hover:border-[#095b8c] rounded-xl w-full p-3 transition shadow-2xs" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box;">
                                    <div class="w-10 h-10 rounded-full bg-teal-50 text-[#095b8c] flex items-center justify-center mb-2 border border-teal-200">
                                        <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                    </div>
                                    <span class="block text-xs font-bold">Pilih Berkas Buku Nikah</span>
                                    <span class="block text-[10px] text-slate-400 mt-1">JPG, PNG, PDF (Maks. 3MB)</span>
                                </label>

                                <!-- Box Preview Dokumen (Ukuran Fixed) -->
                                <div id="preview-box-nikah" class="hidden bg-white border border-teal-200 rounded-xl p-2.5 shadow-xs w-full flex flex-col justify-between" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box; overflow: hidden;">
                                    <div id="img-preview-wrap-nikah" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%; position: relative; overflow: hidden;">
                                        <img id="img-preview-nikah" src="" alt="Preview Buku Nikah" class="w-full h-full object-cover object-center block" style="width: 100%; height: 115px; min-height: 115px; max-height: 115px; object-fit: cover; object-position: center; display: block;">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center" style="position: absolute; inset: 0;">
                                            <label for="doc_marriage_cert" class="cursor-pointer bg-white text-slate-800 text-[11px] font-bold px-3 py-1.5 rounded-md shadow-sm hover:bg-slate-100">
                                                <i class="fa-solid fa-arrow-rotate-right"></i> Ganti Berkas
                                            </label>
                                        </div>
                                    </div>
                                    <div id="pdf-preview-wrap-nikah" class="w-full bg-rose-50 rounded-lg flex flex-col items-center justify-center border border-rose-200 text-rose-700 hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%;">
                                        <i class="fa-solid fa-file-pdf text-3xl mb-1.5"></i>
                                        <span class="text-[11px] font-bold uppercase tracking-wider">Dokumen PDF</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 shrink-0" style="height: 35px; min-height: 35px; max-height: 35px; box-sizing: border-box;">
                                        <div class="truncate flex-1 min-w-0">
                                            <p id="file-name-nikah" class="text-xs font-bold text-slate-800 truncate"></p>
                                            <p id="file-size-nikah" class="text-[10px] text-slate-500"></p>
                                        </div>
                                        <label for="doc_marriage_cert" class="shrink-0 text-[11px] font-bold text-[#095b8c] hover:text-[#04869e] cursor-pointer bg-teal-50 px-2 py-1 rounded border border-teal-200 transition">
                                            <i class="fa-solid fa-pen-to-square"></i> Ganti
                                        </label>
                                    </div>
                                </div>

                                <span class="text-[11px] text-rose-500 hidden error-text mt-1 block" id="error-doc_marriage_cert">Buku Nikah / Akta Perkawinan wajib diunggah.</span>
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

                <!-- Tombol Navigasi Langkah 5 -->
                <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <button type="button" id="btn-step5-back" class="w-full sm:w-auto text-xs font-semibold text-slate-600 hover:text-slate-900 transition flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg hover:bg-slate-100 cursor-pointer">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </button>

                    <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">
                        <button type="button" id="btn-to-preview" class="w-full sm:w-auto bg-teal-50 hover:bg-teal-100 text-[#095b8c] font-bold text-xs sm:text-sm rounded-xl transition flex items-center justify-center gap-2 cursor-pointer border border-teal-200 shadow-2xs" style="min-height: 44px; padding: 10px 20px; box-sizing: border-box;">
                            <i class="fa-solid fa-clipboard-check text-amber-500"></i>
                            <span>Pratinjau Data</span>
                        </button>
                        <button type="button" id="btn-submit-final" class="w-full sm:w-auto bg-[#095b8c] hover:bg-[#07476e] active:scale-[0.99] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center cursor-pointer border border-[#07476e]" style="min-height: 44px; padding: 10px 28px; box-sizing: border-box;">
                            <span id="btn-submit-text">Kirim Pengajuan Akte Kelahiran</span>
                        </button>
                    </div>
                </div>

            </div>

        </form>

    </div>

</div>

<!-- ========================================================================= -->
<!-- POP-UP MODAL CARD: PRATINJAU DATA PENGAJUAN (DI TENGAH LAYAR DENGAN ANIMASI) -->
<!-- ========================================================================= -->
<div id="preview-modal" class="modal-overlay fixed inset-0 z-50 bg-slate-950/65 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto">
    <div id="preview-modal-dialog" class="modal-dialog-box bg-white rounded-2xl shadow-2xl border border-slate-200/80 max-w-2xl w-full overflow-hidden my-auto flex flex-col" style="max-height: 90vh;">
        
        <!-- Header Pop-up Modal Preview (Warna Solid #095b8c) -->
        <div class="bg-[#095b8c] text-white px-5 sm:px-6 py-4 flex items-center justify-between shrink-0 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/15 backdrop-blur-xs flex items-center justify-center text-amber-300 border border-white/20 shrink-0">
                    <i class="fa-solid fa-clipboard-check text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-xs sm:text-sm uppercase tracking-wide">Pratinjau Data Pengajuan</h3>
                    <p class="text-[11px] text-teal-100">Periksa kembali ringkasan data sebelum mengirimkan permohonan</p>
                </div>
            </div>
            <button type="button" id="btn-close-preview-modal" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-teal-100 hover:text-white flex items-center justify-center transition cursor-pointer shrink-0">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <!-- Body Pop-up Modal Preview (Scrollable) -->
        <div class="p-5 sm:p-6 space-y-4 sm:space-y-5 overflow-y-auto custom-modal-scroll flex-1 min-h-0">
            
            <!-- Info Banner Notifikasi -->
            <div class="p-3 bg-teal-50/80 rounded-xl border border-teal-200/80 flex items-start gap-2.5 text-xs text-teal-900">
                <i class="fa-solid fa-circle-info text-[#095b8c] text-sm mt-0.5 shrink-0"></i>
                <p class="text-[11px] leading-relaxed text-slate-700">
                    Pastikan seluruh data kelahiran bayi, orang tua, identitas pemohon, saksi, dan berkas persyaratan telah terisi dengan benar sebelum dikirimkan.
                </p>
            </div>

            <!-- 1. Rincian Biodata Bayi (Langkah 1) -->
            <div class="bg-slate-50/90 p-4 rounded-xl border border-slate-200 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                        <i class="fa-solid fa-baby text-amber-500"></i> Biodata Lengkap Bayi
                    </h4>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-[#095b8c]">Langkah 1</span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Nama Lengkap Bayi</span>
                        <span id="modal-child-name-val" class="font-bold text-slate-900 text-xs block break-words">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">NIK Bayi / Anak</span>
                        <span id="modal-child-nik-val" class="font-mono font-bold text-slate-900 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Jenis Kelamin</span>
                        <span id="modal-gender-val" class="font-bold text-slate-900 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Tempat Dilahirkan</span>
                        <span id="modal-birth-place-type-val" class="font-medium text-slate-800 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Tempat Lahir (Kab/Kota)</span>
                        <span id="modal-birth-place-val" class="font-bold text-slate-900 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Tanggal Lahir</span>
                        <span id="modal-birth-date-val" class="font-bold text-slate-900 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Pukul Kelahiran</span>
                        <span id="modal-birth-time-val" class="font-bold text-slate-800 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Anak ke- & Jenis Kelahiran</span>
                        <span id="modal-birth-order-type-val" class="font-medium text-slate-800 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Penolong Kelahiran</span>
                        <span id="modal-birth-helper-val" class="font-medium text-slate-800 text-xs block">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Data Medis Bayi</span>
                        <span id="modal-medis-val" class="font-medium text-slate-800 text-xs block">-</span>
                    </div>
                </div>
            </div>

            <!-- 2. Rincian Data Kedua Orang Tua (Langkah 2) -->
            <div class="bg-slate-50/90 p-4 rounded-xl border border-slate-200 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                        <i class="fa-solid fa-users text-amber-500"></i> Data Kedua Orang Tua
                    </h4>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-[#095b8c]">Langkah 2</span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <!-- Ibu -->
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs space-y-1.5">
                        <div class="flex items-center gap-1.5 text-pink-700 font-bold pb-1 border-b border-slate-100">
                            <i class="fa-solid fa-user-nurse text-xs"></i>
                            <span class="uppercase text-[11px] tracking-wide">Ibu Kandung</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Nama Lengkap</span>
                            <span id="modal-mother-name-val" class="font-bold text-slate-900 text-xs block break-words">-</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">NIK Ibu</span>
                            <span id="modal-mother-nik-val" class="font-bold text-slate-900 text-xs font-mono block">-</span>
                        </div>
                        <div class="grid grid-cols-2 gap-1 pt-0.5">
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Tgl Lahir / Umur</span>
                                <span id="modal-mother-birth-date-val" class="font-medium text-slate-800 text-xs block">-</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Pekerjaan</span>
                                <span id="modal-mother-job-val" class="font-medium text-slate-800 text-xs block break-words">-</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Alamat Domisili</span>
                            <span id="modal-mother-address-val" class="font-medium text-slate-800 text-xs block break-words">-</span>
                        </div>
                        <div class="grid grid-cols-2 gap-1 pt-0.5 border-t border-slate-100 text-[11px]">
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Warga / Bangsa</span>
                                <span id="modal-mother-citizenship-val" class="font-medium text-slate-800 text-xs block">-</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Tgl Perkawinan</span>
                                <span id="modal-marriage-date-val" class="font-medium text-slate-800 text-xs block">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ayah -->
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs space-y-1.5">
                        <div class="flex items-center gap-1.5 text-blue-700 font-bold pb-1 border-b border-slate-100">
                            <i class="fa-solid fa-user-tie text-xs"></i>
                            <span class="uppercase text-[11px] tracking-wide">Ayah Kandung</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Nama Lengkap</span>
                            <span id="modal-father-name-val" class="font-bold text-slate-900 text-xs block break-words">-</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">NIK Ayah</span>
                            <span id="modal-father-nik-val" class="font-bold text-slate-900 text-xs font-mono block">-</span>
                        </div>
                        <div class="grid grid-cols-2 gap-1 pt-0.5">
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Tgl Lahir / Umur</span>
                                <span id="modal-father-birth-date-val" class="font-medium text-slate-800 text-xs block">-</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Pekerjaan</span>
                                <span id="modal-father-job-val" class="font-medium text-slate-800 text-xs block break-words">-</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Alamat Domisili</span>
                            <span id="modal-father-address-val" class="font-medium text-slate-800 text-xs block break-words">-</span>
                        </div>
                        <div class="pt-0.5 border-t border-slate-100 text-[11px]">
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Warga / Bangsa</span>
                            <span id="modal-father-citizenship-val" class="font-medium text-slate-800 text-xs block">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Rincian Identitas Pemohon (Langkah 3) -->
            <div class="bg-slate-50/90 p-4 rounded-xl border border-slate-200 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                        <i class="fa-solid fa-user-pen text-amber-500"></i> Identitas Pemohon
                    </h4>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-[#095b8c]">Langkah 3</span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">Nama Pemohon</span>
                        <span id="modal-applicant-name-val" class="font-bold text-slate-900 text-xs block break-words">-</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs">
                        <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider mb-0.5">NIK Pemohon</span>
                        <span id="modal-applicant-nik-val" class="font-bold text-slate-900 text-xs block font-mono">-</span>
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

            <!-- 4. Rincian Data Saksi Kelahiran (Langkah 4) -->
            <div class="bg-slate-50/90 p-4 rounded-xl border border-slate-200 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                        <i class="fa-solid fa-user-group text-amber-500"></i> Data Saksi Kelahiran
                    </h4>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-[#095b8c]">Langkah 4</span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <!-- Saksi 1 -->
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs space-y-1.5">
                        <div class="flex items-center gap-1.5 text-teal-700 font-bold pb-1 border-b border-slate-100">
                            <span class="w-5 h-5 rounded-full bg-teal-100 text-[#095b8c] text-[10px] flex items-center justify-center font-bold">1</span>
                            <span class="uppercase text-[11px] tracking-wide">Saksi 1</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Nama Lengkap</span>
                            <span id="modal-witness1-name-val" class="font-bold text-slate-900 text-xs block break-words">-</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">NIK Saksi 1</span>
                            <span id="modal-witness1-nik-val" class="font-bold text-slate-900 text-xs font-mono block">-</span>
                        </div>
                        <div class="grid grid-cols-2 gap-1 pt-0.5">
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Tgl Lahir</span>
                                <span id="modal-witness1-birth-date-val" class="font-medium text-slate-800 text-xs block">-</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Pekerjaan</span>
                                <span id="modal-witness1-job-val" class="font-medium text-slate-800 text-xs block break-words">-</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Alamat</span>
                            <span id="modal-witness1-address-val" class="font-medium text-slate-800 text-xs block break-words">-</span>
                        </div>
                    </div>

                    <!-- Saksi 2 -->
                    <div class="bg-white p-3 rounded-lg border border-slate-200/70 shadow-2xs space-y-1.5">
                        <div class="flex items-center gap-1.5 text-teal-700 font-bold pb-1 border-b border-slate-100">
                            <span class="w-5 h-5 rounded-full bg-teal-100 text-[#095b8c] text-[10px] flex items-center justify-center font-bold">2</span>
                            <span class="uppercase text-[11px] tracking-wide">Saksi 2</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Nama Lengkap</span>
                            <span id="modal-witness2-name-val" class="font-bold text-slate-900 text-xs block break-words">-</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">NIK Saksi 2</span>
                            <span id="modal-witness2-nik-val" class="font-bold text-slate-900 text-xs font-mono block">-</span>
                        </div>
                        <div class="grid grid-cols-2 gap-1 pt-0.5">
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Tgl Lahir</span>
                                <span id="modal-witness2-birth-date-val" class="font-medium text-slate-800 text-xs block">-</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Pekerjaan</span>
                                <span id="modal-witness2-job-val" class="font-medium text-slate-800 text-xs block break-words">-</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Alamat</span>
                            <span id="modal-witness2-address-val" class="font-medium text-slate-800 text-xs block break-words">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Rincian Dokumen Terlampir (Langkah 5) -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                        <i class="fa-solid fa-paperclip text-amber-500"></i> Berkas Dokumen Terlampir
                    </h4>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-[#095b8c]">Langkah 5</span>
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
                                <span class="text-[11px] font-bold text-[#095b8c] uppercase tracking-wide">1. KTP Pemohon / Orang Tua</span>
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

                    <!-- 4. Buku Nikah Card Modal -->
                    <div class="flex items-center gap-3.5 p-3 bg-slate-50/80 hover:bg-slate-50 rounded-xl border border-slate-200 transition">
                        <div class="w-13 h-13 rounded-lg overflow-hidden border border-slate-200 shrink-0 bg-slate-100 flex items-center justify-center shadow-2xs" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px;">
                            <img id="modal-thumb-nikah" src="" alt="Thumbnail Buku Nikah" class="w-full h-full object-cover object-center block hidden" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px; max-width: 52px; max-height: 52px; object-fit: cover; object-position: center;">
                            <div id="modal-pdf-nikah" class="w-full h-full bg-rose-50 flex flex-col items-center justify-center text-rose-600 hidden" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px;">
                                <i class="fa-solid fa-file-pdf text-xl mb-0.5"></i>
                                <span class="text-[8px] font-bold uppercase">PDF</span>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-bold text-[#095b8c] uppercase tracking-wide">4. Buku Nikah / Akta Perkawinan</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 shrink-0">
                                    <i class="fa-solid fa-check text-[9px]"></i> Terlampir
                                </span>
                            </div>
                            <p id="modal-file-nikah" class="text-xs font-medium text-slate-700 truncate mt-0.5">-</p>
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
            <button type="button" id="btn-confirm-submit" class="w-full sm:w-auto bg-[#095b8c] hover:bg-[#07476e] active:scale-[0.98] text-white font-extrabold text-xs sm:text-sm px-6 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2.5 cursor-pointer border border-[#07476e]">
                <i class="fa-solid fa-paper-plane text-xs text-teal-200"></i>
                <span>Konfirmasi & Kirim Permohonan</span>
            </button>
        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL POP-UP: PENGAJUAN BERHASIL TERKIRIM                                 -->
<!-- ========================================================================= -->
<div id="success-modal" class="modal-overlay fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto">
    
    <div id="success-modal-dialog" class="modal-dialog-box bg-white rounded-2xl shadow-2xl border border-teal-100 max-w-lg w-full overflow-hidden my-auto flex flex-col">
        
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
        <div class="p-5 md:p-6 space-y-4 overflow-y-auto custom-modal-scroll flex-1 min-h-0">
            
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
                <a id="modal-btn-list" href="{{ route('submissions.index') }}" class="w-full block bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs py-2.5 px-3 rounded-lg text-center transition border border-slate-300">
                    <i class="fa-solid fa-list-check mr-1"></i> Buka Daftar Pengajuan Permohonan Akta
                </a>
            </div>

        </div>

    </div>

</div>

<!-- Multi-Step Wizard & Modal Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;

    // Storage Keys (Scoped per User to prevent cross-account draft leaks)
    const CURRENT_USER_ID = '{{ Auth::id() ?? "guest" }}';
    const CURRENT_USER_NIK = '{{ Auth::user()->nik ?? "" }}';
    const DRAFT_KEY = 'purwobinangun_birth_form_draft_' + CURRENT_USER_ID;
    const DB_NAME = 'PurwobinangunBirthDB_' + CURRENT_USER_ID;
    const STORE_NAME = 'birthDraftFiles';

    // Clear legacy unscoped drafts to avoid cross-user data leakage
    try {
        localStorage.removeItem('purwobinangun_birth_form_draft');
    } catch (e) {}

    // Form & Steps
    const form = document.getElementById('birth-application-form');
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    const step4 = document.getElementById('step-4');
    const step5 = document.getElementById('step-5');

    // Step 1: Biodata Bayi
    const childName = document.getElementById('child_name');
    const childNik = document.getElementById('child_nik');
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
    const birthHelper = document.getElementById('birth_helper');
    const birthHelperOther = document.getElementById('birth_helper_other');
    const customBirthHelperBox = document.getElementById('custom-birth-helper-box');
    const weightGram = document.getElementById('weight_gram');
    const lengthCm = document.getElementById('length_cm');

    // Step 2: Data Kedua Orang Tua
    const fatherNik = document.getElementById('father_nik');
    const fatherName = document.getElementById('father_name');
    const fatherBirthDate = document.getElementById('father_birth_date');
    const fatherAge = document.getElementById('father_age');
    const fatherJob = document.getElementById('father_job');
    const fatherAddress = document.getElementById('father_address');
    const fatherRt = document.getElementById('father_rt');
    const fatherRw = document.getElementById('father_rw');
    const fatherVillage = document.getElementById('father_village');
    const fatherDistrict = document.getElementById('father_district');
    const fatherCity = document.getElementById('father_city');
    const fatherProvince = document.getElementById('father_province');
    const fatherCitizenship = document.getElementById('father_citizenship');
    const fatherNationality = document.getElementById('father_nationality');

    const motherNik = document.getElementById('mother_nik');
    const motherName = document.getElementById('mother_name');
    const motherBirthDate = document.getElementById('mother_birth_date');
    const motherAge = document.getElementById('mother_age');
    const motherJob = document.getElementById('mother_job');
    const motherAddress = document.getElementById('mother_address');
    const motherRt = document.getElementById('mother_rt');
    const motherRw = document.getElementById('mother_rw');
    const motherVillage = document.getElementById('mother_village');
    const motherDistrict = document.getElementById('mother_district');
    const motherCity = document.getElementById('mother_city');
    const motherProvince = document.getElementById('mother_province');
    const motherCitizenship = document.getElementById('mother_citizenship');
    const motherNationality = document.getElementById('mother_nationality');
    const marriageDate = document.getElementById('marriage_date');

    const btnCopyMotherAddress = document.getElementById('btn-copy-mother-address');

    // Step 3: Identitas Diri Pemohon
    const applicantName = document.getElementById('applicant_name');
    const applicantNik = document.getElementById('applicant_nik');
    const applicantRelation = document.getElementById('applicant_relation');
    const address = document.getElementById('address');
    const applicantPhone = document.getElementById('applicant_phone');
    const padukuhan = document.getElementById('padukuhan');
    const rt = document.getElementById('rt');
    const rw = document.getElementById('rw');

    // Step 4: Data Saksi Kelahiran
    const witness1Nik = document.getElementById('witness_1_nik');
    const witness1Name = document.getElementById('witness_1_name');
    const witness1BirthDate = document.getElementById('witness_1_birth_date');
    const witness1Job = document.getElementById('witness_1_job');
    const witness1Address = document.getElementById('witness_1_address');

    const witness2Nik = document.getElementById('witness_2_nik');
    const witness2Name = document.getElementById('witness_2_name');
    const witness2BirthDate = document.getElementById('witness_2_birth_date');
    const witness2Job = document.getElementById('witness_2_job');
    const witness2Address = document.getElementById('witness_2_address');

    // Step 5: Dokumen Persyaratan & Pernyataan
    const docKtp = document.getElementById('doc_parents_ktp');
    const docKk = document.getElementById('doc_family_card');
    const docLahir = document.getElementById('doc_birth_cert');
    const docNikah = document.getElementById('doc_marriage_cert');
    const declarationCheck = document.getElementById('declaration_check');

    // Preview Modal Elements
    const previewModal = document.getElementById('preview-modal');
    const btnClosePreviewModal = document.getElementById('btn-close-preview-modal');
    const btnModalEdit = document.getElementById('btn-modal-edit');
    const btnConfirmSubmit = document.getElementById('btn-confirm-submit');

    // Success Modal Element
    const successModal = document.getElementById('success-modal');

    // Teleport pop-up modal elements directly to document.body to ensure top-layer positioning
    if (previewModal && previewModal.parentElement !== document.body) {
        document.body.appendChild(previewModal);
    }
    if (successModal && successModal.parentElement !== document.body) {
        document.body.appendChild(successModal);
    }

    // ==========================================
    // IndexedDB Helper untuk Penyimpanan Berkas
    // ==========================================
    function openDraftDB() {
        return new Promise((resolve, reject) => {
            if (!window.indexedDB) {
                return reject(new Error('IndexedDB not supported'));
            }
            const request = indexedDB.open(DB_NAME, 1);
            request.onupgradeneeded = function (e) {
                const db = e.target.result;
                if (!db.objectStoreNames.contains(STORE_NAME)) {
                    db.createObjectStore(STORE_NAME, { keyPath: 'id' });
                }
            };
            request.onsuccess = function (e) {
                resolve(e.target.result);
            };
            request.onerror = function (e) {
                reject(e.target.error);
            };
        });
    }

    async function saveFileToDB(id, file) {
        try {
            const db = await openDraftDB();
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            store.put({
                id: id,
                file: file,
                name: file.name,
                type: file.type,
                size: file.size,
                lastModified: file.lastModified
            });
            return new Promise((resolve, reject) => {
                tx.oncomplete = () => resolve();
                tx.onerror = () => reject(tx.error);
            });
        } catch (err) {
            console.warn('Gagal menyimpan file ke draft DB:', err);
        }
    }

    async function getFileFromDB(id) {
        try {
            const db = await openDraftDB();
            const tx = db.transaction(STORE_NAME, 'readonly');
            const store = tx.objectStore(STORE_NAME);
            const req = store.get(id);
            return new Promise((resolve) => {
                req.onsuccess = () => resolve(req.result);
                req.onerror = () => resolve(null);
            });
        } catch (err) {
            console.warn('Gagal membaca file dari draft DB:', err);
            return null;
        }
    }

    async function clearDraftFilesFromDB() {
        try {
            if (!window.indexedDB) return;
            const db = await openDraftDB();
            const tx = db.transaction(STORE_NAME, 'readwrite');
            tx.objectStore(STORE_NAME).clear();
        } catch (err) {
            console.warn('Gagal membersihkan draft DB:', err);
        }
    }

    // Modal Helpers
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

    // Update File Preview Visual Card
    function updateFilePreview(input, file) {
        if (!input || !file) return;
        const cardKey = input.getAttribute('data-card');
        const placeholder = document.getElementById(`placeholder-${cardKey}`);
        const previewBox = document.getElementById(`preview-box-${cardKey}`);
        const imgWrap = document.getElementById(`img-preview-wrap-${cardKey}`);
        const imgEl = document.getElementById(`img-preview-${cardKey}`);
        const pdfWrap = document.getElementById(`pdf-preview-wrap-${cardKey}`);
        const fileNameEl = document.getElementById(`file-name-${cardKey}`);
        const fileSizeEl = document.getElementById(`file-size-${cardKey}`);

        if (fileNameEl) fileNameEl.textContent = file.name;
        if (fileSizeEl) fileSizeEl.textContent = (file.size / 1024).toFixed(1) + ' KB';

        if (file.type && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (imgEl) imgEl.src = e.target.result;
                if (imgWrap) imgWrap.classList.remove('hidden');
                if (pdfWrap) pdfWrap.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            if (imgWrap) imgWrap.classList.add('hidden');
            if (pdfWrap) pdfWrap.classList.remove('hidden');
        }

        if (placeholder) placeholder.classList.add('hidden');
        if (previewBox) previewBox.classList.remove('hidden');

        const errEl = document.getElementById('error-' + input.id);
        if (errEl) errEl.classList.add('hidden');
    }

    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', async function () {
            if (this.files && this.files.length > 0) {
                const file = this.files[0];
                updateFilePreview(this, file);
                await saveFileToDB(this.id, file);
                saveFormDraft();
            }
        });
    });

    // Handle Custom Inputs
    if (birthType) {
        birthType.addEventListener('change', function () {
            if (this.value === 'Input Jumlah') {
                customBirthTypeBox.classList.remove('hidden');
                birthTypeCustom.required = true;
            } else {
                customBirthTypeBox.classList.add('hidden');
                birthTypeCustom.required = false;
            }
            saveFormDraft();
        });
    }

    if (birthPlaceType) {
        birthPlaceType.addEventListener('change', function () {
            if (this.value === 'Lainnya') {
                customBirthPlaceBox.classList.remove('hidden');
                birthPlaceOther.required = true;
            } else {
                customBirthPlaceBox.classList.add('hidden');
                birthPlaceOther.required = false;
            }
            saveFormDraft();
        });
    }

    if (birthHelper) {
        birthHelper.addEventListener('change', function () {
            if (this.value === 'Lainnya') {
                customBirthHelperBox.classList.remove('hidden');
                birthHelperOther.required = true;
            } else {
                customBirthHelperBox.classList.add('hidden');
                birthHelperOther.required = false;
            }
            saveFormDraft();
        });
    }

    if (birthDate) {
        birthDate.addEventListener('change', function () {
            saveFormDraft();
        });
    }

    // Update Progress Indicator Stepper (Bulat 1-5 & Line)
    function updateProgressIndicator(step) {
        step = parseInt(step);
        const totalSteps = 5;
        const progressBarLine = document.getElementById('progress-bar-line');
        if (progressBarLine) {
            const pct = ((step - 1) / (totalSteps - 1)) * 100;
            progressBarLine.style.width = pct + '%';
        }

        for (let i = 1; i <= totalSteps; i++) {
            const circle = document.getElementById(`step-circle-${i}`);
            const label = document.getElementById(`step-label-${i}`);
            if (!circle || !label) continue;

            if (i < step) {
                // Completed Step
                circle.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-xs bg-[#095b8c] text-white cursor-pointer hover:bg-[#07476f]';
                label.className = 'step-label text-[10px] sm:text-xs font-semibold text-[#095b8c] mt-1.5 text-center whitespace-nowrap cursor-pointer';
            } else if (i === step) {
                // Current Active Step
                circle.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-md bg-[#095b8c] text-white ring-4 ring-teal-100 ring-offset-1 cursor-default';
                label.className = 'step-label text-[10px] sm:text-xs font-bold text-[#095b8c] mt-1.5 text-center whitespace-nowrap';
            } else {
                // Inactive / Upcoming Step
                circle.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600';
                label.className = 'step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap';
            }
        }
    }

    // Helper: Switch steps (1..5)
    function setStep(step, smoothScroll = true) {
        currentStep = parseInt(step);
        const steps = [step1, step2, step3, step4, step5];
        steps.forEach((el, idx) => {
            if (el) {
                if (idx + 1 === currentStep) {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            }
        });

        updateProgressIndicator(currentStep);

        if (smoothScroll) {
            window.scrollTo({ top: 180, behavior: 'smooth' });
        }

        saveFormDraft();
    }

    // ==========================================
    // LocalStorage Draft Form Management
    // ==========================================
    function saveFormDraft() {
        try {
            const draft = {
                user_id: CURRENT_USER_ID,
                user_nik: CURRENT_USER_NIK,
                currentStep: currentStep,
                child_name: childName ? childName.value : '',
                child_nik: childNik ? childNik.value : '',
                gender: form.querySelector('input[name="gender"]:checked')?.value || 'L',
                birth_place: birthPlace ? birthPlace.value : '',
                birth_date: birthDate ? birthDate.value : '',
                birth_time: birthTime ? birthTime.value : '',
                birth_order: birthOrder ? birthOrder.value : '',
                birth_type: birthType ? birthType.value : 'Tunggal',
                birth_type_custom: birthTypeCustom ? birthTypeCustom.value : '',
                birth_place_type: birthPlaceType ? birthPlaceType.value : 'RS/SB',
                birth_place_other: birthPlaceOther ? birthPlaceOther.value : '',
                birth_helper: birthHelper ? birthHelper.value : 'Bidan / Perawat',
                birth_helper_other: birthHelperOther ? birthHelperOther.value : '',
                weight_gram: weightGram ? weightGram.value : '',
                length_cm: lengthCm ? lengthCm.value : '',

                father_nik: fatherNik ? fatherNik.value : '',
                father_name: fatherName ? fatherName.value : '',
                father_birth_date: fatherBirthDate ? fatherBirthDate.value : '',
                father_age: fatherAge ? fatherAge.value : '',
                father_job: fatherJob ? fatherJob.value : '',
                father_address: fatherAddress ? fatherAddress.value : '',
                father_rt: fatherRt ? fatherRt.value : '',
                father_rw: fatherRw ? fatherRw.value : '',
                father_village: fatherVillage ? fatherVillage.value : '',
                father_district: fatherDistrict ? fatherDistrict.value : '',
                father_city: fatherCity ? fatherCity.value : '',
                father_province: fatherProvince ? fatherProvince.value : '',
                father_citizenship: fatherCitizenship ? fatherCitizenship.value : 'WNI',
                father_nationality: fatherNationality ? fatherNationality.value : 'INDONESIA',

                mother_nik: motherNik ? motherNik.value : '',
                mother_name: motherName ? motherName.value : '',
                mother_birth_date: motherBirthDate ? motherBirthDate.value : '',
                mother_age: motherAge ? motherAge.value : '',
                mother_job: motherJob ? motherJob.value : '',
                mother_address: motherAddress ? motherAddress.value : '',
                mother_rt: motherRt ? motherRt.value : '',
                mother_rw: motherRw ? motherRw.value : '',
                mother_village: motherVillage ? motherVillage.value : '',
                mother_district: motherDistrict ? motherDistrict.value : '',
                mother_city: motherCity ? motherCity.value : '',
                mother_province: motherProvince ? motherProvince.value : '',
                mother_citizenship: motherCitizenship ? motherCitizenship.value : 'WNI',
                mother_nationality: motherNationality ? motherNationality.value : 'INDONESIA',
                marriage_date: marriageDate ? marriageDate.value : '',

                applicant_name: applicantName ? applicantName.value : '',
                applicant_nik: applicantNik ? applicantNik.value : '',
                applicant_relation: applicantRelation ? applicantRelation.value : '',
                applicant_phone: applicantPhone ? applicantPhone.value : '',
                padukuhan: padukuhan ? padukuhan.value : '',
                rt: rt ? rt.value : '',
                rw: rw ? rw.value : '',
                address: address ? address.value : '',

                witness_1_nik: witness1Nik ? witness1Nik.value : '',
                witness_1_name: witness1Name ? witness1Name.value : '',
                witness_1_birth_date: witness1BirthDate ? witness1BirthDate.value : '',
                witness_1_job: witness1Job ? witness1Job.value : '',
                witness_1_address: witness1Address ? witness1Address.value : '',

                witness_2_nik: witness2Nik ? witness2Nik.value : '',
                witness_2_name: witness2Name ? witness2Name.value : '',
                witness_2_birth_date: witness2BirthDate ? witness2BirthDate.value : '',
                witness_2_job: witness2Job ? witness2Job.value : '',
                witness_2_address: witness2Address ? witness2Address.value : '',

                declaration_check: declarationCheck ? declarationCheck.checked : false
            };
            localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
        } catch (e) {
            console.warn('Gagal menyimpan draft ke localStorage:', e);
        }
    }

    function restoreFormDraft() {
        try {
            const rawDraft = localStorage.getItem(DRAFT_KEY);
            if (!rawDraft) return;
            const draft = JSON.parse(rawDraft);
            if (!draft) return;

            if (draft.user_id && draft.user_id !== CURRENT_USER_ID) {
                localStorage.removeItem(DRAFT_KEY);
                clearDraftFilesFromDB();
                return;
            }
            if (CURRENT_USER_NIK && draft.user_nik && draft.user_nik !== CURRENT_USER_NIK) {
                localStorage.removeItem(DRAFT_KEY);
                clearDraftFilesFromDB();
                return;
            }

            // Step 1
            if (childName && draft.child_name !== undefined) childName.value = draft.child_name;
            if (childNik && draft.child_nik !== undefined) childNik.value = draft.child_nik;
            if (draft.gender) {
                const genderRadio = form.querySelector(`input[name="gender"][value="${draft.gender}"]`);
                if (genderRadio) genderRadio.checked = true;
            }
            if (birthPlace && draft.birth_place !== undefined) birthPlace.value = draft.birth_place;
            if (birthDate && draft.birth_date !== undefined) birthDate.value = draft.birth_date;
            if (birthTime && draft.birth_time !== undefined) birthTime.value = draft.birth_time;
            if (birthOrder && draft.birth_order !== undefined) birthOrder.value = draft.birth_order;
            if (birthType && draft.birth_type !== undefined) {
                birthType.value = draft.birth_type;
                if (birthType.value === 'Input Jumlah') {
                    customBirthTypeBox.classList.remove('hidden');
                    birthTypeCustom.required = true;
                    if (birthTypeCustom && draft.birth_type_custom !== undefined) {
                        birthTypeCustom.value = draft.birth_type_custom;
                    }
                } else {
                    customBirthTypeBox.classList.add('hidden');
                    birthTypeCustom.required = false;
                }
            }
            if (birthPlaceType && draft.birth_place_type !== undefined) {
                birthPlaceType.value = draft.birth_place_type;
                if (birthPlaceType.value === 'Lainnya') {
                    customBirthPlaceBox.classList.remove('hidden');
                    birthPlaceOther.required = true;
                    if (birthPlaceOther && draft.birth_place_other !== undefined) {
                        birthPlaceOther.value = draft.birth_place_other;
                    }
                } else {
                    customBirthPlaceBox.classList.add('hidden');
                    birthPlaceOther.required = false;
                }
            }
            if (birthHelper && draft.birth_helper !== undefined) {
                birthHelper.value = draft.birth_helper;
                if (birthHelper.value === 'Lainnya') {
                    customBirthHelperBox.classList.remove('hidden');
                    birthHelperOther.required = true;
                    if (birthHelperOther && draft.birth_helper_other !== undefined) {
                        birthHelperOther.value = draft.birth_helper_other;
                    }
                } else {
                    customBirthHelperBox.classList.add('hidden');
                    birthHelperOther.required = false;
                }
            }
            if (weightGram && draft.weight_gram !== undefined) weightGram.value = draft.weight_gram;
            if (lengthCm && draft.length_cm !== undefined) lengthCm.value = draft.length_cm;

            // Step 2
            if (fatherNik && draft.father_nik !== undefined && (draft.father_nik !== '' || !fatherNik.value)) fatherNik.value = draft.father_nik;
            if (fatherName && draft.father_name !== undefined && (draft.father_name !== '' || !fatherName.value)) fatherName.value = draft.father_name;
            if (fatherBirthDate && draft.father_birth_date !== undefined && (draft.father_birth_date !== '' || !fatherBirthDate.value)) fatherBirthDate.value = draft.father_birth_date;
            if (fatherAge && draft.father_age !== undefined && draft.father_age !== '') fatherAge.value = draft.father_age;
            if (fatherJob && draft.father_job !== undefined) fatherJob.value = draft.father_job;
            if (fatherAddress && draft.father_address !== undefined) fatherAddress.value = draft.father_address;
            if (fatherRt && draft.father_rt !== undefined) fatherRt.value = draft.father_rt;
            if (fatherRw && draft.father_rw !== undefined) fatherRw.value = draft.father_rw;
            if (fatherVillage && draft.father_village !== undefined) fatherVillage.value = draft.father_village;
            if (fatherDistrict && draft.father_district !== undefined) fatherDistrict.value = draft.father_district;
            if (fatherCity && draft.father_city !== undefined) fatherCity.value = draft.father_city;
            if (fatherProvince && draft.father_province !== undefined) fatherProvince.value = draft.father_province;
            if (fatherCitizenship && draft.father_citizenship !== undefined) fatherCitizenship.value = draft.father_citizenship;
            if (fatherNationality && draft.father_nationality !== undefined) fatherNationality.value = draft.father_nationality;

            if (motherNik && draft.mother_nik !== undefined && (draft.mother_nik !== '' || !motherNik.value)) motherNik.value = draft.mother_nik;
            if (motherName && draft.mother_name !== undefined && (draft.mother_name !== '' || !motherName.value)) motherName.value = draft.mother_name;
            if (motherBirthDate && draft.mother_birth_date !== undefined && (draft.mother_birth_date !== '' || !motherBirthDate.value)) motherBirthDate.value = draft.mother_birth_date;
            if (motherAge && draft.mother_age !== undefined && draft.mother_age !== '') motherAge.value = draft.mother_age;
            if (motherJob && draft.mother_job !== undefined) motherJob.value = draft.mother_job;
            if (motherAddress && draft.mother_address !== undefined) motherAddress.value = draft.mother_address;
            if (motherRt && draft.mother_rt !== undefined) motherRt.value = draft.mother_rt;
            if (motherRw && draft.mother_rw !== undefined) motherRw.value = draft.mother_rw;
            if (motherVillage && draft.mother_village !== undefined) motherVillage.value = draft.mother_village;
            if (motherDistrict && draft.mother_district !== undefined) motherDistrict.value = draft.mother_district;
            if (motherCity && draft.mother_city !== undefined) motherCity.value = draft.mother_city;
            if (motherProvince && draft.mother_province !== undefined) motherProvince.value = draft.mother_province;
            if (motherCitizenship && draft.mother_citizenship !== undefined) motherCitizenship.value = draft.mother_citizenship;
            if (motherNationality && draft.mother_nationality !== undefined) motherNationality.value = draft.mother_nationality;
            if (marriageDate && draft.marriage_date !== undefined) marriageDate.value = draft.marriage_date;

            // Step 3
            if (applicantName && draft.applicant_name !== undefined) applicantName.value = draft.applicant_name;
            if (applicantNik && draft.applicant_nik !== undefined) applicantNik.value = draft.applicant_nik;
            if (applicantRelation && draft.applicant_relation !== undefined) applicantRelation.value = draft.applicant_relation;
            if (applicantPhone && draft.applicant_phone !== undefined) applicantPhone.value = draft.applicant_phone;
            if (padukuhan && draft.padukuhan !== undefined) padukuhan.value = draft.padukuhan;
            if (rt && draft.rt !== undefined) rt.value = draft.rt;
            if (rw && draft.rw !== undefined) rw.value = draft.rw;
            if (address && draft.address !== undefined) address.value = draft.address;

            // Step 4
            if (witness1Nik && draft.witness_1_nik !== undefined) witness1Nik.value = draft.witness_1_nik;
            if (witness1Name && draft.witness_1_name !== undefined) witness1Name.value = draft.witness_1_name;
            if (witness1BirthDate && draft.witness_1_birth_date !== undefined) witness1BirthDate.value = draft.witness_1_birth_date;
            if (witness1Job && draft.witness_1_job !== undefined) witness1Job.value = draft.witness_1_job;
            if (witness1Address && draft.witness_1_address !== undefined) witness1Address.value = draft.witness_1_address;

            if (witness2Nik && draft.witness_2_nik !== undefined) witness2Nik.value = draft.witness_2_nik;
            if (witness2Name && draft.witness_2_name !== undefined) witness2Name.value = draft.witness_2_name;
            if (witness2BirthDate && draft.witness_2_birth_date !== undefined) witness2BirthDate.value = draft.witness_2_birth_date;
            if (witness2Job && draft.witness_2_job !== undefined) witness2Job.value = draft.witness_2_job;
            if (witness2Address && draft.witness_2_address !== undefined) witness2Address.value = draft.witness_2_address;

            // Step 5
            if (declarationCheck && draft.declaration_check !== undefined) declarationCheck.checked = draft.declaration_check;

            if (draft.currentStep) {
                setStep(draft.currentStep, false);
            }
        } catch (e) {
            console.warn('Gagal memulihkan draft dari localStorage:', e);
        }
    }

    async function restoreDraftFiles() {
        const fileInputIds = ['doc_parents_ktp', 'doc_family_card', 'doc_birth_cert', 'doc_marriage_cert'];
        for (const inputId of fileInputIds) {
            const input = document.getElementById(inputId);
            if (!input) continue;
            const record = await getFileFromDB(inputId);
            if (record && record.file) {
                try {
                    const dt = new DataTransfer();
                    const restoredFile = new File([record.file], record.name, {
                        type: record.type,
                        lastModified: record.lastModified || Date.now()
                    });
                    dt.items.add(restoredFile);
                    input.files = dt.files;
                    updateFilePreview(input, restoredFile);
                } catch (e) {
                    console.warn('Gagal memulihkan berkas input:', e);
                }
            }
        }
    }

    form.addEventListener('input', saveFormDraft);
    form.addEventListener('change', saveFormDraft);

    restoreFormDraft();
    restoreDraftFiles();
    updateProgressIndicator(currentStep);

    // ==========================================
    // Fitur Otomatisasi Form Langkah 2 (Orang Tua)
    // ==========================================
    function calculateAgeFromDate(dateString) {
        if (!dateString) return '';
        const birthDate = new Date(dateString);
        if (isNaN(birthDate.getTime())) return '';
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age > 0 ? age : 0;
    }

    if (fatherBirthDate && fatherAge) {
        fatherBirthDate.addEventListener('change', function () {
            if (this.value) {
                const calculated = calculateAgeFromDate(this.value);
                if (calculated !== '') fatherAge.value = calculated;
                saveFormDraft();
            }
        });
        if (fatherBirthDate.value && !fatherAge.value) {
            const calculated = calculateAgeFromDate(fatherBirthDate.value);
            if (calculated !== '') fatherAge.value = calculated;
        }
    }

    if (motherBirthDate && motherAge) {
        motherBirthDate.addEventListener('change', function () {
            if (this.value) {
                const calculated = calculateAgeFromDate(this.value);
                if (calculated !== '') motherAge.value = calculated;
                saveFormDraft();
            }
        });
        if (motherBirthDate.value && !motherAge.value) {
            const calculated = calculateAgeFromDate(motherBirthDate.value);
            if (calculated !== '') motherAge.value = calculated;
        }
    }

    if (btnCopyMotherAddress) {
        btnCopyMotherAddress.addEventListener('click', function () {
            if (motherAddress && fatherAddress) fatherAddress.value = motherAddress.value;
            if (motherRt && fatherRt) fatherRt.value = motherRt.value;
            if (motherRw && fatherRw) fatherRw.value = motherRw.value;
            if (motherVillage && fatherVillage) fatherVillage.value = motherVillage.value;
            if (motherDistrict && fatherDistrict) fatherDistrict.value = motherDistrict.value;
            if (motherCity && fatherCity) fatherCity.value = motherCity.value;
            if (motherProvince && fatherProvince) fatherProvince.value = motherProvince.value;
            saveFormDraft();
            
            const originalText = btnCopyMotherAddress.innerHTML;
            btnCopyMotherAddress.innerHTML = '<i class="fa-solid fa-check text-emerald-600"></i> Tersalin!';
            btnCopyMotherAddress.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
            setTimeout(() => {
                btnCopyMotherAddress.innerHTML = originalText;
                btnCopyMotherAddress.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
            }, 1800);
        });
    }

    // Populate & Open Preview Modal
    function openPreviewModal() {
        // Step 1: Bayi
        document.getElementById('modal-child-name-val').textContent = childName ? childName.value.trim() || '-' : '-';
        document.getElementById('modal-child-nik-val').textContent = childNik && childNik.value.trim() ? childNik.value.trim() : '(Belum memiliki NIK)';
        const genderVal = form.querySelector('input[name="gender"]:checked')?.value;
        document.getElementById('modal-gender-val').textContent = genderVal === 'L' ? '1. Laki-laki' : '2. Perempuan';

        let placeTypeVal = birthPlaceType ? birthPlaceType.value : 'RS/SB';
        if (placeTypeVal === 'Lainnya' && birthPlaceOther && birthPlaceOther.value) {
            placeTypeVal = birthPlaceOther.value.trim();
        }
        document.getElementById('modal-birth-place-type-val').textContent = placeTypeVal;
        document.getElementById('modal-birth-place-val').textContent = birthPlace ? birthPlace.value.trim() || '-' : '-';

        const bDateStr = birthDate && birthDate.value ? birthDate.value : '-';
        let bDayFormatted = '';
        if (birthDate && birthDate.value) {
            const dateParts = birthDate.value.split('-');
            if (dateParts.length === 3) {
                const dateObj = new Date(parseInt(dateParts[0], 10), parseInt(dateParts[1], 10) - 1, parseInt(dateParts[2], 10));
                if (!isNaN(dateObj.getTime())) {
                    const daysIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    bDayFormatted = `${daysIndo[dateObj.getDay()]}, `;
                }
            }
        }
        document.getElementById('modal-birth-date-val').textContent = `${bDayFormatted}${bDateStr}`;
        document.getElementById('modal-birth-time-val').textContent = birthTime ? birthTime.value || 'Tidak dicantumkan' : 'Tidak dicantumkan';

        let birthTypeVal = birthType ? birthType.value : 'Tunggal';
        if (birthTypeVal === 'Input Jumlah' && birthTypeCustom && birthTypeCustom.value) {
            birthTypeVal = `Kembar ${birthTypeCustom.value}`;
        }
        document.getElementById('modal-birth-order-type-val').textContent = `Anak ke-${birthOrder ? birthOrder.value : 1} (${birthTypeVal})`;

        let helperVal = birthHelper ? birthHelper.value : 'Bidan / Perawat';
        if (helperVal === 'Lainnya' && birthHelperOther && birthHelperOther.value) {
            helperVal = birthHelperOther.value.trim();
        }
        document.getElementById('modal-birth-helper-val').textContent = helperVal;

        const w = weightGram && weightGram.value ? `${weightGram.value} gram` : '-';
        const l = lengthCm && lengthCm.value ? `${lengthCm.value} cm` : '-';
        document.getElementById('modal-medis-val').textContent = `Berat: ${w} | Panjang: ${l}`;

        // Step 2: Orang Tua
        const fAgeStr = fatherAge && fatherAge.value ? ` (${fatherAge.value} thn)` : '';
        const mAgeStr = motherAge && motherAge.value ? ` (${motherAge.value} thn)` : '';

        document.getElementById('modal-father-name-val').textContent = fatherName ? fatherName.value.trim() || '-' : '-';
        document.getElementById('modal-father-nik-val').textContent = fatherNik ? fatherNik.value.trim() || '-' : '-';
        document.getElementById('modal-father-birth-date-val').textContent = (fatherBirthDate ? fatherBirthDate.value || '-' : '-') + fAgeStr;
        document.getElementById('modal-father-job-val').textContent = fatherJob ? fatherJob.value.trim() || '-' : '-';

        let fAddr = fatherAddress ? fatherAddress.value.trim() : '';
        if (fatherRt && fatherRw && (fatherRt.value || fatherRw.value)) fAddr += ` (RT ${fatherRt.value || '-'}/RW ${fatherRw.value || '-'})`;
        if (fatherVillage && fatherVillage.value) fAddr += `, ${fatherVillage.value}`;
        if (fatherDistrict && fatherDistrict.value) fAddr += `, ${fatherDistrict.value}`;
        if (fatherCity && fatherCity.value) fAddr += `, ${fatherCity.value}`;
        if (document.getElementById('modal-father-address-val')) document.getElementById('modal-father-address-val').textContent = fAddr || '-';
        if (document.getElementById('modal-father-citizenship-val')) document.getElementById('modal-father-citizenship-val').textContent = `${fatherCitizenship ? fatherCitizenship.value : 'WNI'} / ${fatherNationality ? fatherNationality.value : 'INDONESIA'}`;

        document.getElementById('modal-mother-name-val').textContent = motherName ? motherName.value.trim() || '-' : '-';
        document.getElementById('modal-mother-nik-val').textContent = motherNik ? motherNik.value.trim() || '-' : '-';
        document.getElementById('modal-mother-birth-date-val').textContent = (motherBirthDate ? motherBirthDate.value || '-' : '-') + mAgeStr;
        document.getElementById('modal-mother-job-val').textContent = motherJob ? motherJob.value.trim() || '-' : '-';

        let mAddr = motherAddress ? motherAddress.value.trim() : '';
        if (motherRt && motherRw && (motherRt.value || motherRw.value)) mAddr += ` (RT ${motherRt.value || '-'}/RW ${motherRw.value || '-'})`;
        if (motherVillage && motherVillage.value) mAddr += `, ${motherVillage.value}`;
        if (motherDistrict && motherDistrict.value) mAddr += `, ${motherDistrict.value}`;
        if (motherCity && motherCity.value) mAddr += `, ${motherCity.value}`;
        if (document.getElementById('modal-mother-address-val')) document.getElementById('modal-mother-address-val').textContent = mAddr || '-';
        if (document.getElementById('modal-mother-citizenship-val')) document.getElementById('modal-mother-citizenship-val').textContent = `${motherCitizenship ? motherCitizenship.value : 'WNI'} / ${motherNationality ? motherNationality.value : 'INDONESIA'}`;
        if (document.getElementById('modal-marriage-date-val')) document.getElementById('modal-marriage-date-val').textContent = marriageDate && marriageDate.value ? marriageDate.value : '-';

        // Step 3: Pemohon
        document.getElementById('modal-applicant-name-val').textContent = applicantName ? applicantName.value.trim() || '-' : '-';
        document.getElementById('modal-applicant-nik-val').textContent = applicantNik ? applicantNik.value.trim() || '-' : '-';
        document.getElementById('modal-applicant-phone-val').textContent = applicantPhone ? applicantPhone.value.trim() || '-' : '-';
        document.getElementById('modal-applicant-relation-val').textContent = applicantRelation ? applicantRelation.value : '-';

        let fullAddress = address ? address.value.trim() : '';
        if (padukuhan && padukuhan.value) {
            fullAddress += `, Padukuhan ${padukuhan.value}`;
        }
        if (rt && rw && (rt.value || rw.value)) {
            fullAddress += ` (RT ${rt.value || '-'}/RW ${rw.value || '-'})`;
        }
        document.getElementById('modal-applicant-address-val').textContent = fullAddress || '-';

        // Step 4: Saksi
        document.getElementById('modal-witness1-name-val').textContent = witness1Name ? witness1Name.value.trim() || 'Tidak diisi' : 'Tidak diisi';
        document.getElementById('modal-witness1-nik-val').textContent = witness1Nik ? witness1Nik.value.trim() || '-' : '-';
        document.getElementById('modal-witness1-birth-date-val').textContent = witness1BirthDate ? witness1BirthDate.value || '-' : '-';
        document.getElementById('modal-witness1-job-val').textContent = witness1Job ? witness1Job.value || '-' : '-';
        document.getElementById('modal-witness1-address-val').textContent = witness1Address ? witness1Address.value.trim() || '-' : '-';

        document.getElementById('modal-witness2-name-val').textContent = witness2Name ? witness2Name.value.trim() || 'Tidak diisi' : 'Tidak diisi';
        document.getElementById('modal-witness2-nik-val').textContent = witness2Nik ? witness2Nik.value.trim() || '-' : '-';
        document.getElementById('modal-witness2-birth-date-val').textContent = witness2BirthDate ? witness2BirthDate.value || '-' : '-';
        document.getElementById('modal-witness2-job-val').textContent = witness2Job ? witness2Job.value || '-' : '-';
        document.getElementById('modal-witness2-address-val').textContent = witness2Address ? witness2Address.value.trim() || '-' : '-';

        // Step 5: Dokumen
        document.getElementById('modal-file-ktp').textContent = docKtp.files[0]?.name || '-';
        document.getElementById('modal-file-kk').textContent = docKk.files[0]?.name || '-';
        document.getElementById('modal-file-lahir').textContent = docLahir.files[0]?.name || '-';
        document.getElementById('modal-file-nikah').textContent = docNikah.files[0]?.name || '-';

        const docKeys = [
            { key: 'ktp', input: docKtp },
            { key: 'kk', input: docKk },
            { key: 'lahir', input: docLahir },
            { key: 'nikah', input: docNikah }
        ];

        docKeys.forEach(item => {
            const stepImg = document.getElementById(`img-preview-${item.key}`);
            const modalImg = document.getElementById(`modal-thumb-${item.key}`);
            const modalPdf = document.getElementById(`modal-pdf-${item.key}`);

            if (item.input.files && item.input.files[0] && item.input.files[0].type.startsWith('image/')) {
                if (modalImg && stepImg) {
                    modalImg.src = stepImg.src;
                    modalImg.classList.remove('hidden');
                }
                if (modalPdf) modalPdf.classList.add('hidden');
            } else {
                if (modalImg) modalImg.classList.add('hidden');
                if (modalPdf) modalPdf.classList.remove('hidden');
            }
        });

        showModal(previewModal);
    }

    // Validation Functions
    function validateStep1() {
        let valid = true;
        let firstInvalid = null;

        ['child_name', 'child_nik', 'birth_place', 'birth_date', 'birth_order', 'birth_helper'].forEach(field => {
            const err = document.getElementById(`error-${field}`);
            if (err) err.classList.add('hidden');
        });

        if (!childName.value.trim()) {
            document.getElementById('error-child_name').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = childName;
        }

        if (childNik && childNik.value.trim()) {
            const cNik = childNik.value.trim();
            if (cNik.length !== 16 || isNaN(cNik)) {
                document.getElementById('error-child_nik').classList.remove('hidden');
                valid = false;
                if (!firstInvalid) firstInvalid = childNik;
            }
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

        if (birthHelper && !birthHelper.value) {
            document.getElementById('error-birth_helper').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = birthHelper;
        }

        if (!valid && firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
        }

        return valid;
    }

    function validateStep2() {
        let valid = true;
        let firstInvalid = null;

        ['father_nik', 'father_name', 'father_birth_date', 'father_job', 'mother_nik', 'mother_name', 'mother_birth_date', 'mother_job'].forEach(field => {
            const err = document.getElementById(`error-${field}`);
            if (err) err.classList.add('hidden');
        });

        const fatherNikVal = fatherNik.value.trim();
        if (!fatherNikVal || fatherNikVal.length !== 16 || isNaN(fatherNikVal)) {
            document.getElementById('error-father_nik').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = fatherNik;
        }

        if (!fatherName.value.trim()) {
            document.getElementById('error-father_name').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = fatherName;
        }

        if (!fatherBirthDate.value) {
            document.getElementById('error-father_birth_date').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = fatherBirthDate;
        }

        if (!fatherJob.value.trim()) {
            document.getElementById('error-father_job').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = fatherJob;
        }

        const motherNikVal = motherNik.value.trim();
        if (!motherNikVal || motherNikVal.length !== 16 || isNaN(motherNikVal)) {
            document.getElementById('error-mother_nik').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = motherNik;
        }

        if (!motherName.value.trim()) {
            document.getElementById('error-mother_name').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = motherName;
        }

        if (!motherBirthDate.value) {
            document.getElementById('error-mother_birth_date').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = motherBirthDate;
        }

        if (!motherJob.value.trim()) {
            document.getElementById('error-mother_job').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = motherJob;
        }

        if (!valid && firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
        }

        return valid;
    }

    function validateStep3() {
        let valid = true;
        let firstInvalid = null;

        ['applicant_name', 'applicant_nik', 'address', 'applicant_phone'].forEach(field => {
            const err = document.getElementById(`error-${field}`);
            if (err) err.classList.add('hidden');
        });

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

        if (!applicantPhone.value.trim()) {
            document.getElementById('error-applicant_phone').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = applicantPhone;
        }

        if (!address.value.trim()) {
            document.getElementById('error-address').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = address;
        }

        if (!valid && firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
        }

        return valid;
    }

    function validateStep4() {
        let valid = true;
        let firstInvalid = null;

        ['witness_1_nik', 'witness_2_nik'].forEach(field => {
            const err = document.getElementById(`error-${field}`);
            if (err) err.classList.add('hidden');
        });

        if (witness1Nik && witness1Nik.value.trim()) {
            const val = witness1Nik.value.trim();
            if (val.length !== 16 || isNaN(val)) {
                document.getElementById('error-witness_1_nik').classList.remove('hidden');
                valid = false;
                if (!firstInvalid) firstInvalid = witness1Nik;
            }
        }

        if (witness2Nik && witness2Nik.value.trim()) {
            const val = witness2Nik.value.trim();
            if (val.length !== 16 || isNaN(val)) {
                document.getElementById('error-witness_2_nik').classList.remove('hidden');
                valid = false;
                if (!firstInvalid) firstInvalid = witness2Nik;
            }
        }

        if (!valid && firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
        }

        return valid;
    }

    function validateStep5() {
        let valid = true;
        let firstInvalid = null;

        ['doc_parents_ktp', 'doc_family_card', 'doc_birth_cert', 'doc_marriage_cert', 'declaration'].forEach(field => {
            const err = document.getElementById(`error-${field}`);
            if (err) err.classList.add('hidden');
        });

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

        if (!docNikah.files || docNikah.files.length === 0) {
            document.getElementById('error-doc_marriage_cert').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = document.getElementById('placeholder-nikah');
        }

        if (!declarationCheck.checked) {
            document.getElementById('error-declaration').classList.remove('hidden');
            valid = false;
            if (!firstInvalid) firstInvalid = declarationCheck;
        }

        if (!valid && firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
        }

        return valid;
    }

    // Navigation Buttons Event Listeners
    // Step 1 -> Next
    const btnStep1Next = document.getElementById('btn-step1-next');
    if (btnStep1Next) {
        btnStep1Next.addEventListener('click', function () {
            if (validateStep1()) {
                setStep(2);
            }
        });
    }

    // Step 2 -> Back & Next
    const btnStep2Back = document.getElementById('btn-step2-back');
    if (btnStep2Back) {
        btnStep2Back.addEventListener('click', function () {
            setStep(1);
        });
    }
    const btnStep2Next = document.getElementById('btn-step2-next');
    if (btnStep2Next) {
        btnStep2Next.addEventListener('click', function () {
            if (validateStep2()) {
                setStep(3);
            }
        });
    }

    // Step 3 -> Back & Next
    const btnStep3Back = document.getElementById('btn-step3-back');
    if (btnStep3Back) {
        btnStep3Back.addEventListener('click', function () {
            setStep(2);
        });
    }
    const btnStep3Next = document.getElementById('btn-step3-next');
    if (btnStep3Next) {
        btnStep3Next.addEventListener('click', function () {
            if (validateStep3()) {
                setStep(4);
            }
        });
    }

    // Step 4 -> Back & Next
    const btnStep4Back = document.getElementById('btn-step4-back');
    if (btnStep4Back) {
        btnStep4Back.addEventListener('click', function () {
            setStep(3);
        });
    }
    const btnStep4Next = document.getElementById('btn-step4-next');
    if (btnStep4Next) {
        btnStep4Next.addEventListener('click', function () {
            if (validateStep4()) {
                setStep(5);
            }
        });
    }

    // Step 5 -> Back
    const btnStep5Back = document.getElementById('btn-step5-back');
    if (btnStep5Back) {
        btnStep5Back.addEventListener('click', function () {
            setStep(4);
        });
    }

    // Step 5 -> Pratinjau Data
    const btnToPreview = document.getElementById('btn-to-preview');
    if (btnToPreview) {
        btnToPreview.addEventListener('click', function () {
            if (!validateStep1()) { setStep(1); return; }
            if (!validateStep2()) { setStep(2); return; }
            if (!validateStep3()) { setStep(3); return; }
            if (!validateStep4()) { setStep(4); return; }
            if (!validateStep5()) { return; }

            openPreviewModal();
        });
    }

    // Direct Click Stepper Indicator Handler
    window.goToStepIfAllowed = function (targetStep) {
        targetStep = parseInt(targetStep);
        if (targetStep === currentStep || targetStep < 1 || targetStep > 5) return;

        // Jika mundur ke langkah sebelumnya, langsung perbolehkan
        if (targetStep < currentStep) {
            setStep(targetStep);
            return;
        }

        // Jika maju ke langkah selanjutnya, periksa validasi setiap tahapan secara berurutan
        for (let s = currentStep; s < targetStep; s++) {
            if (s === 1 && !validateStep1()) return;
            if (s === 2 && !validateStep2()) return;
            if (s === 3 && !validateStep3()) return;
            if (s === 4 && !validateStep4()) return;
        }

        setStep(targetStep);
    };

    // Modal Action Handlers
    if (btnClosePreviewModal) {
        btnClosePreviewModal.addEventListener('click', () => hideModal(previewModal));
    }
    if (btnModalEdit) {
        btnModalEdit.addEventListener('click', () => hideModal(previewModal));
    }

    previewModal.addEventListener('click', function (e) {
        if (e.target === previewModal) {
            hideModal(previewModal);
        }
    });

    // Form Submission Function
    function executeFormSubmission() {
        if (!validateStep1()) { setStep(1); return; }
        if (!validateStep2()) { setStep(2); return; }
        if (!validateStep3()) { setStep(3); return; }
        if (!validateStep4()) { setStep(4); return; }
        if (!validateStep5()) { setStep(5); return; }

        hideModal(previewModal);

        const submitBtn = document.getElementById('btn-submit-final');
        const submitText = document.getElementById('btn-submit-text');
        
        if (submitBtn) submitBtn.disabled = true;
        if (submitText) submitText.classList.add('opacity-75');

        const formData = new FormData(form);

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
                try {
                    localStorage.removeItem(DRAFT_KEY);
                    localStorage.removeItem('purwobinangun_birth_form_draft');
                    clearDraftFilesFromDB();

                    ['ktp', 'kk', 'lahir', 'nikah'].forEach(key => {
                        const ph = document.getElementById(`placeholder-${key}`);
                        const pb = document.getElementById(`preview-box-${key}`);
                        if (ph) ph.classList.remove('hidden');
                        if (pb) pb.classList.add('hidden');
                    });
                } catch (e) {
                    console.warn('Gagal menghapus draft setelah submit:', e);
                }

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

                document.getElementById('modal-reg-no').textContent = data.registration_no;
                document.getElementById('modal-child-name').textContent = data.birth.child_name;
                document.getElementById('modal-applicant-name').textContent = data.birth.applicant_name;
                
                document.getElementById('modal-btn-receipt').href = data.receipt_url;
                document.getElementById('modal-btn-tracking').href = data.tracking_url;
                document.getElementById('modal-btn-list').href = data.list_url;

                setStep(1);

                showModal(successModal);
            }
        })
        .catch(error => {
            console.error("Submission error:", error);
            if (submitBtn) submitBtn.disabled = false;
            if (submitText) submitText.classList.remove('opacity-75');

            if (error.errors) {
                const firstKey = Object.keys(error.errors)[0];
                alert('Terdapat kesalahan pengisian data: ' + error.errors[firstKey][0]);
            } else {
                alert('Terjadi kendala saat mengirim permohonan. Formulir akan dikirimkan secara langsung.');
                form.submit();
            }
        });
    }

    // Submit from Step 5 directly or from Preview Modal Confirm
    const btnSubmitFinal = document.getElementById('btn-submit-final');
    if (btnSubmitFinal) {
        btnSubmitFinal.addEventListener('click', executeFormSubmission);
    }
    if (btnConfirmSubmit) {
        btnConfirmSubmit.addEventListener('click', executeFormSubmission);
    }

    // Copy Registration Number
    const btnCopyReg = document.getElementById('btn-copy-reg');
    if (btnCopyReg) {
        btnCopyReg.addEventListener('click', function () {
            const regNo = document.getElementById('modal-reg-no').textContent;
            navigator.clipboard.writeText(regNo).then(() => {
                this.innerHTML = '<i class="fa-solid fa-check text-emerald-600"></i>';
                setTimeout(() => {
                    this.innerHTML = '<i class="fa-regular fa-copy text-sm"></i>';
                }, 2000);
            });
        });
    }

    // ESC Key to close modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && previewModal.classList.contains('active')) {
            hideModal(previewModal);
        }
    });

});
</script>
@endsection
