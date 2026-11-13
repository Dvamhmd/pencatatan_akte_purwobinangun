@extends('layouts.app')

@section('title', 'Formulir Pelaporan Kematian - Akta Kematian Kab. Sleman')

@push('styles')
<style>
    .stepper-progress-track {
        position: absolute;
        top: 20px;
        left: 28px;
        right: 28px;
        height: 4px;
        z-index: 1;
    }
    @media (max-width: 640px) {
        .stepper-progress-track {
            top: 16px;
            left: 18px;
            right: 18px;
        }
    }
</style>
@endpush

@section('content')
@php
    $rev = $revisionDeath ?? null;
@endphp
<div class="space-y-6">

    <!-- Card Utama Form Permohonan -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        
        <!-- Header Card Solid (Tanpa Gradasi) -->
        <div class="bg-rose-700 text-white px-4 py-3 flex flex-wrap items-center justify-between gap-2">
            <div>
                <h2 class="font-bold text-xs md:text-sm tracking-wide uppercase flex items-center gap-2">
                    <i class="fa-solid fa-book-skull text-amber-300"></i> FORMULIR PELAPORAN KEMATIAN
                </h2>
                <p class="text-[11px] text-rose-100 mt-0.5">
                    (Untuk Mendapatkan Akta Kematian) &bull; Pemerintah Kabupaten Sleman
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="hidden sm:inline-block text-[11px] bg-rose-900/80 px-2.5 py-0.5 rounded text-rose-100 font-medium">
                    Kalurahan Purwobinangun
                </span>
                <a href="{{ route('submissions.index') }}" class="bg-white/15 hover:bg-white/25 text-white border border-white/20 text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-list-check text-rose-200"></i> Daftar Pengajuan
                </a>
            </div>
        </div>

        <!-- Stepper Progress Bar (Langkah 1 - 5) -->
        <div class="bg-slate-50 border-b border-slate-200 px-4 py-4 sm:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <!-- Garis Penghubung Track -->
                    <div class="stepper-progress-track">
                        <div class="w-full h-1 bg-slate-200 rounded-full"></div>
                        <div id="progress-bar-line" class="h-1 bg-rose-700 rounded-full transition-all duration-300" style="width: 0%; margin-top: -4px;"></div>
                    </div>

                    <!-- Indikator 5 Bulatan -->
                    <div class="relative z-10 flex items-center justify-between">
                        <!-- Step 1 -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(1)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-xs bg-rose-700 text-white ring-4 ring-rose-100 ring-offset-1 cursor-default" id="step-circle-1" title="Langkah 1: Jenazah & KK">
                                <span class="step-num">1</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-bold text-rose-700 mt-1.5 text-center whitespace-nowrap" id="step-label-1">Jenazah & KK</span>
                        </div>

                        <!-- Step 2 -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(2)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-2xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600" id="step-circle-2" title="Langkah 2: Data Orang Tua">
                                <span class="step-num">2</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap" id="step-label-2">Orang Tua</span>
                        </div>

                        <!-- Step 3 -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(3)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-2xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600" id="step-circle-3" title="Langkah 3: Identitas Pelapor">
                                <span class="step-num">3</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap" id="step-label-3">Pelapor</span>
                        </div>

                        <!-- Step 4 -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(4)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-2xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600" id="step-circle-4" title="Langkah 4: Data Saksi">
                                <span class="step-num">4</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap" id="step-label-4">Saksi</span>
                        </div>

                        <!-- Step 5 -->
                        <div class="step-indicator flex flex-col items-center">
                            <button type="button" onclick="goToStepIfAllowed(5)" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-2xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600" id="step-circle-5" title="Langkah 5: Upload Berkas Dokumen">
                                <span class="step-num">5</span>
                            </button>
                            <span class="step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap" id="step-label-5">Upload Berkas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Element Utama -->
        <form id="death-application-form" action="{{ route('death.store') }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-6">
            @csrf

            @if(isset($rev) && $rev)
                <input type="hidden" name="registration_no" value="{{ $rev->registration_no }}">
                <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5 shrink-0"></i>
                    <div>
                        <h4 class="font-bold text-amber-900">Mode Revisi Berkas Permohonan: {{ $rev->registration_no }}</h4>
                        <p class="mt-0.5 text-amber-800">Catatan Petugas: <strong>{{ $rev->rejection_note ?: 'Mohon perbaiki data atau berkas dokumen yang belum sesuai.' }}</strong></p>
                        <p class="mt-1 text-[11px] text-amber-700">Berkas yang tidak Anda unggah ulang akan tetap menggunakan berkas yang sebelumnya telah Anda kirimkan.</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800">
                    <div class="font-bold mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i> Mohon periksa kembali isian Anda:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ===================================================================
                 LANGKAH 1: DATA KEPALA KELUARGA & JENAZAH
            =================================================================== -->
            <div id="step-1" class="space-y-5">
                
                <!-- Box Data Kartu Keluarga -->
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-address-card text-rose-600"></i>
                        Data Kartu Keluarga (KK)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                1. Nomor Kartu Keluarga (16 Digit) <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" maxlength="16" name="family_card_no" id="family_card_no"
                                value="{{ old('family_card_no', $user?->family_card_no ?? '') }}" required
                                placeholder="Contoh: 340405xxxxxxxxxx"
                                class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                2. Nama Kepala Keluarga <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="head_of_family_name" id="head_of_family_name"
                                value="{{ old('head_of_family_name', $user?->name ?? '') }}" required
                                placeholder="Nama kepala keluarga sesuai KK"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>
                    </div>
                </div>

                <!-- Box Biodata Jenazah -->
                <div class="space-y-3.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 pb-2 border-b border-rose-100 flex items-center gap-2">
                        <i class="fa-solid fa-user-xmark text-rose-600"></i>
                        Biodata Jenazah
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                1. NIK Jenazah (16 Digit) <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" maxlength="16" name="deceased_nik" id="deceased_nik"
                                value="{{ old('deceased_nik') }}" required
                                placeholder="340405xxxxxxxxxx"
                                class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                2. Nama Lengkap Jenazah <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="deceased_name" id="deceased_name"
                                value="{{ old('deceased_name') }}" required
                                placeholder="Nama almarhum / almarhumah sesuai KTP"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                3. Jenis Kelamin <span class="text-rose-600">*</span>
                            </label>
                            <div class="flex items-center gap-5 mt-2 text-xs">
                                <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                    <input type="radio" name="gender" value="L" {{ old('gender', 'L') === 'L' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-600">
                                    <span>Laki-laki</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                                    <input type="radio" name="gender" value="P" {{ old('gender') === 'P' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-600">
                                    <span>Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                4. Tempat Lahir <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="deceased_birth_place" id="deceased_birth_place"
                                value="{{ old('deceased_birth_place', 'Sleman') }}" required
                                placeholder="Kota / Kabupaten Lahir"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Tanggal Lahir <span class="text-rose-600">*</span>
                            </label>
                            <input type="date" name="birth_date" id="birth_date"
                                value="{{ old('birth_date') }}" required
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                5. Agama <span class="text-rose-600">*</span>
                            </label>
                            <select name="religion" id="religion" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $rel)
                                    <option value="{{ $rel }}" {{ old('religion', 'Islam') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                6. Pekerjaan Almarhum/ah
                            </label>
                            <select name="deceased_job" id="deceased_job" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                <option value="">-- Pilih Pekerjaan --</option>
                                @foreach($pekerjaanList as $job)
                                    <option value="{{ $job }}" {{ old('deceased_job') === $job ? 'selected' : '' }}>{{ $job }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Padukuhan di Purwobinangun <span class="text-rose-600">*</span>
                            </label>
                            <select name="padukuhan" id="padukuhan" required class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                <option value="">-- Pilih Padukuhan --</option>
                                @foreach($padukuhanList as $pad)
                                    <option value="{{ $pad }}" {{ old('padukuhan', $user?->padukuhan ?? '') === $pad ? 'selected' : '' }}>Padukuhan {{ $pad }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                RT / RW <span class="text-rose-600">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="rt" id="rt" maxlength="5" value="{{ old('rt', $user?->rt ?? '01') }}" required placeholder="RT" class="text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                <input type="text" name="rw" id="rw" maxlength="5" value="{{ old('rw', $user?->rw ?? '01') }}" required placeholder="RW" class="text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                6. Alamat Lengkap Sesuai KTP
                            </label>
                            <input type="text" name="deceased_address" id="deceased_address"
                                value="{{ old('deceased_address', $user?->address ?? '') }}"
                                placeholder="Alamat KTP almarhum/ah"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                7. Anak Ke (dengan huruf)
                            </label>
                            <input type="text" name="deceased_child_order" id="deceased_child_order"
                                value="{{ old('deceased_child_order') }}"
                                placeholder="Contoh: Satu, Dua, Tiga"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>
                    </div>
                </div>

                <!-- Box Peristiwa Kematian -->
                <div class="space-y-3.5 pt-3 border-t border-slate-200">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 pb-2 border-b border-rose-100 flex items-center gap-2">
                        <i class="fa-solid fa-clock text-rose-600"></i>
                        Peristiwa Kematian
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                8. Hari Meninggal <span class="text-rose-600">*</span>
                            </label>
                            <select name="death_day" id="death_day" required class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                <option value="">-- Pilih Hari --</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                    <option value="{{ $hari }}" {{ old('death_day') === $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Tanggal Meninggal <span class="text-rose-600">*</span>
                            </label>
                            <input type="date" name="death_date" id="death_date"
                                value="{{ old('death_date', date('Y-m-d')) }}" required
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                10. Jam Meninggal
                            </label>
                            <input type="text" name="death_time" id="death_time"
                                value="{{ old('death_time') }}"
                                placeholder="Contoh: 08:30 WIB"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                9. Tempat Meninggal <span class="text-rose-600">*</span>
                            </label>
                            <select name="death_place" id="death_place" required class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                @foreach(['Rumah Kediaman', 'Rumah Sakit', 'Puskesmas', 'Klinik / Tempat Nakes', 'Tempat Lainnya'] as $place)
                                    <option value="{{ $place }}" {{ old('death_place') === $place ? 'selected' : '' }}>{{ $place }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                11. Sebab Kematian <span class="text-rose-600">*</span>
                            </label>
                            <select name="cause_of_death" id="cause_of_death" required class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                <option value="Sakit Biasa / Tua" {{ old('cause_of_death') === 'Sakit Biasa / Tua' ? 'selected' : '' }}>Sakit Biasa / Tua</option>
                                <option value="Wabah Penyakit / Menular" {{ old('cause_of_death') === 'Wabah Penyakit / Menular' ? 'selected' : '' }}>Wabah Penyakit / Menular</option>
                                <option value="Kecelakaan" {{ old('cause_of_death') === 'Kecelakaan' ? 'selected' : '' }}>Kecelakaan</option>
                                <option value="Kriminalitas" {{ old('cause_of_death') === 'Kriminalitas' ? 'selected' : '' }}>Kriminalitas</option>
                                <option value="Bunuh Diri" {{ old('cause_of_death') === 'Bunuh Diri' ? 'selected' : '' }}>Bunuh Diri</option>
                                <option value="Lainnya" {{ old('cause_of_death') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                12. Tempat Kematian (Lokasi Rinci)
                            </label>
                            <input type="text" name="death_location_detail" id="death_location_detail"
                                value="{{ old('death_location_detail') }}"
                                placeholder="Contoh: RSUP Dr. Sardjito / Rumah Duka"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div class="col-span-full md:col-span-3 w-full" style="grid-column: 1 / -1;">
                            <label class="block text-xs font-semibold text-slate-700 mb-2">
                                13. Yang Menerangkan <span class="text-rose-600">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs w-full">
                                @php
                                    $optionsMenerangkan = [
                                        '1. Dokter' => 'Dokter',
                                        '2. Tenaga Kesehatan' => 'Tenaga Kesehatan',
                                        '3. Kepolisian' => 'Kepolisian',
                                        '4. Lainnya' => 'Lainnya',
                                    ];
                                @endphp
                                @foreach($optionsMenerangkan as $labelOpt => $valOpt)
                                    <label class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-rose-50 hover:border-rose-400 cursor-pointer transition select-none min-w-0">
                                        <input type="radio" name="reported_by_title" value="{{ $valOpt }}"
                                            {{ old('reported_by_title', 'Dokter') === $valOpt ? 'checked' : '' }}
                                            class="text-rose-600 focus:ring-rose-600 shrink-0">
                                        <span class="font-medium text-slate-700 text-xs break-words leading-tight">{{ $labelOpt }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigasi Langkah 1 -->
                <div class="flex justify-end pt-4 border-t border-slate-200">
                    <button type="button" id="btn-step1-next" class="bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs px-5 py-2.5 rounded-lg shadow-xs transition flex items-center gap-2">
                        <span>Selanjutnya: Data Orang Tua</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ===================================================================
                 LANGKAH 2: DATA ORANG TUA (IBU & AYAH)
            =================================================================== -->
            <div id="step-2" class="space-y-5 hidden">
                
                <!-- Box Data Ibu -->
                <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-3.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 pb-2 border-b border-rose-100 flex items-center gap-2">
                        <i class="fa-solid fa-person-dress text-base text-rose-600"></i>
                        Data Ibu Kandung
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                1. NIK Ibu (16 Digit)
                            </label>
                            <input type="text" maxlength="16" name="mother_nik" id="mother_nik"
                                value="{{ old('mother_nik') }}"
                                placeholder="340405xxxxxxxxxx"
                                class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                2. Nama Lengkap Ibu
                            </label>
                            <input type="text" name="mother_name" id="mother_name"
                                value="{{ old('mother_name') }}"
                                placeholder="Nama lengkap ibu kandung"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                3. Tempat Lahir Ibu
                            </label>
                            <input type="text" name="mother_birth_place" id="mother_birth_place"
                                value="{{ old('mother_birth_place') }}"
                                placeholder="Kota / Kabupaten Lahir"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Tanggal Lahir Ibu
                            </label>
                            <input type="date" name="mother_birth_date" id="mother_birth_date"
                                value="{{ old('mother_birth_date') }}"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                4. Pekerjaan Ibu
                            </label>
                            <select name="mother_job" id="mother_job" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                <option value="">-- Pilih Pekerjaan --</option>
                                @foreach($pekerjaanList as $job)
                                    <option value="{{ $job }}" {{ old('mother_job') === $job ? 'selected' : '' }}>{{ $job }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                5. Alamat Sesuai KTP
                            </label>
                            <input type="text" name="mother_address" id="mother_address"
                                value="{{ old('mother_address') }}"
                                placeholder="Alamat domisili ibu"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                6. Kewarganegaraan
                            </label>
                            <input type="text" name="mother_citizenship" id="mother_citizenship"
                                value="{{ old('mother_citizenship', 'WNI') }}"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>
                    </div>
                </div>

                <!-- Box Data Ayah -->
                <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-3.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 pb-2 border-b border-rose-100 flex items-center gap-2">
                        <i class="fa-solid fa-person text-base text-rose-600"></i>
                        Data Ayah Kandung
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                1. NIK Ayah (16 Digit)
                            </label>
                            <input type="text" maxlength="16" name="father_nik" id="father_nik"
                                value="{{ old('father_nik') }}"
                                placeholder="340405xxxxxxxxxx"
                                class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                2. Nama Lengkap Ayah
                            </label>
                            <input type="text" name="father_name" id="father_name"
                                value="{{ old('father_name') }}"
                                placeholder="Nama lengkap ayah kandung"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                3. Tempat Lahir Ayah
                            </label>
                            <input type="text" name="father_birth_place" id="father_birth_place"
                                value="{{ old('father_birth_place') }}"
                                placeholder="Kota / Kabupaten Lahir"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Tanggal Lahir Ayah
                            </label>
                            <input type="date" name="father_birth_date" id="father_birth_date"
                                value="{{ old('father_birth_date') }}"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                4. Pekerjaan Ayah
                            </label>
                            <select name="father_job" id="father_job" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                <option value="">-- Pilih Pekerjaan --</option>
                                @foreach($pekerjaanList as $job)
                                    <option value="{{ $job }}" {{ old('father_job') === $job ? 'selected' : '' }}>{{ $job }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                5. Alamat Sesuai KTP
                            </label>
                            <input type="text" name="father_address" id="father_address"
                                value="{{ old('father_address') }}"
                                placeholder="Alamat domisili ayah"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                6. Kewarganegaraan
                            </label>
                            <input type="text" name="father_citizenship" id="father_citizenship"
                                value="{{ old('father_citizenship', 'WNI') }}"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>
                    </div>
                </div>

                <!-- Navigasi Langkah 2 -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                    <button type="button" id="btn-step2-back" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali: Jenazah & KK</span>
                    </button>
                    <button type="button" id="btn-step2-next" class="bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs px-5 py-2.5 rounded-lg shadow-xs transition flex items-center gap-2">
                        <span>Selanjutnya: Data Pelapor</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ===================================================================
                 LANGKAH 3: DATA PELAPOR
            =================================================================== -->
            <div id="step-3" class="space-y-5 hidden">
                
                <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-3.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 pb-2 border-b border-rose-100 flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-base text-rose-600"></i>
                        Data Pelapor / Ahli Waris
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                1. NIK Pelapor (16 Digit) <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" maxlength="16" name="applicant_nik" id="applicant_nik"
                                value="{{ old('applicant_nik', $user?->nik ?? '') }}" required
                                placeholder="340405xxxxxxxxxx"
                                class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                2. Nama Lengkap Pelapor <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="applicant_name" id="applicant_name"
                                value="{{ old('applicant_name', $user?->name ?? '') }}" required
                                placeholder="Nama pelapor sesuai KTP"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                3. Tempat Lahir Pelapor
                            </label>
                            <input type="text" name="applicant_birth_place" id="applicant_birth_place"
                                value="{{ old('applicant_birth_place') }}"
                                placeholder="Kota / Kabupaten Lahir"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Tanggal Lahir Pelapor
                            </label>
                            <input type="date" name="applicant_birth_date" id="applicant_birth_date"
                                value="{{ old('applicant_birth_date') }}"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                4. Umur Pelapor (Tahun)
                            </label>
                            <input type="number" min="1" max="150" name="applicant_age" id="applicant_age"
                                value="{{ old('applicant_age') }}"
                                placeholder="Contoh: 35"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                5. Pekerjaan Pelapor
                            </label>
                            <select name="applicant_job" id="applicant_job" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                <option value="">-- Pilih Pekerjaan --</option>
                                @foreach($pekerjaanList as $job)
                                    <option value="{{ $job }}" {{ old('applicant_job') === $job ? 'selected' : '' }}>{{ $job }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                6. Alamat Sesuai KTP Pelapor
                            </label>
                            <input type="text" name="applicant_address" id="applicant_address"
                                value="{{ old('applicant_address', $user?->address ?? '') }}"
                                placeholder="Alamat tinggal pelapor sesuai KTP"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                7. Tanggal Lapor <span class="text-rose-600">*</span>
                            </label>
                            <input type="date" name="report_date" id="report_date"
                                value="{{ old('report_date', date('Y-m-d')) }}" required
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Hubungan dengan Almarhum/ah <span class="text-rose-600">*</span>
                            </label>
                            <select name="applicant_relation" id="applicant_relation" required class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                                <option value="">-- Pilih Hubungan --</option>
                                <option value="Anak Kandung" {{ old('applicant_relation') === 'Anak Kandung' ? 'selected' : '' }}>Anak Kandung</option>
                                <option value="Suami / Istri" {{ old('applicant_relation') === 'Suami / Istri' ? 'selected' : '' }}>Suami / Istri</option>
                                <option value="Orang Tua" {{ old('applicant_relation') === 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                <option value="Saudara Kandung" {{ old('applicant_relation') === 'Saudara Kandung' ? 'selected' : '' }}>Saudara Kandung</option>
                                <option value="Cucu" {{ old('applicant_relation') === 'Cucu' ? 'selected' : '' }}>Cucu</option>
                                <option value="Keluarga Lainnya" {{ old('applicant_relation') === 'Keluarga Lainnya' ? 'selected' : '' }}>Keluarga Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                No. WhatsApp / HP Aktif <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="applicant_phone" id="applicant_phone"
                                value="{{ old('applicant_phone', $user?->phone ?? '') }}" required
                                placeholder="081234567890"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>
                    </div>
                </div>

                <!-- Navigasi Langkah 3 -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                    <button type="button" id="btn-step3-back" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali: Data Orang Tua</span>
                    </button>
                    <button type="button" id="btn-step3-next" class="bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs px-5 py-2.5 rounded-lg shadow-xs transition flex items-center gap-2">
                        <span>Selanjutnya: Data Saksi</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ===================================================================
                 LANGKAH 4: DATA SAKSI (SAKSI I & SAKSI II)
            =================================================================== -->
            <div id="step-4" class="space-y-5 hidden">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Box Saksi I -->
                    <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 pb-2 border-b border-rose-100 flex items-center gap-2">
                            <i class="fa-solid fa-users text-rose-600"></i>
                            Data Saksi I
                        </h3>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                1. NIK Saksi I (16 Digit)
                            </label>
                            <input type="text" maxlength="16" name="witness1_nik" id="witness1_nik"
                                value="{{ old('witness1_nik') }}"
                                placeholder="340405xxxxxxxxxx"
                                class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                2. Nama Lengkap Saksi I
                            </label>
                            <input type="text" name="witness1_name" id="witness1_name"
                                value="{{ old('witness1_name') }}"
                                placeholder="Nama saksi sesuai KTP"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                3. Umur Saksi I (Tahun)
                            </label>
                            <input type="number" min="17" max="120" name="witness1_age" id="witness1_age"
                                value="{{ old('witness1_age') }}"
                                placeholder="Contoh: 45"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                4. Alamat Saksi I
                            </label>
                            <input type="text" name="witness1_address" id="witness1_address"
                                value="{{ old('witness1_address') }}"
                                placeholder="Alamat lengkap saksi I"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>
                    </div>

                    <!-- Box Saksi II -->
                    <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 pb-2 border-b border-rose-100 flex items-center gap-2">
                            <i class="fa-solid fa-users text-rose-600"></i>
                            Data Saksi II
                        </h3>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                1. NIK Saksi II (16 Digit)
                            </label>
                            <input type="text" maxlength="16" name="witness2_nik" id="witness2_nik"
                                value="{{ old('witness2_nik') }}"
                                placeholder="340405xxxxxxxxxx"
                                class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                2. Nama Lengkap Saksi II
                            </label>
                            <input type="text" name="witness2_name" id="witness2_name"
                                value="{{ old('witness2_name') }}"
                                placeholder="Nama saksi sesuai KTP"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                3. Umur Saksi II (Tahun)
                            </label>
                            <input type="number" min="17" max="120" name="witness2_age" id="witness2_age"
                                value="{{ old('witness2_age') }}"
                                placeholder="Contoh: 50"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                4. Alamat Saksi II
                            </label>
                            <input type="text" name="witness2_address" id="witness2_address"
                                value="{{ old('witness2_address') }}"
                                placeholder="Alamat lengkap saksi II"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>
                    </div>
                </div>

                <!-- Navigasi Langkah 4 -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                    <button type="button" id="btn-step4-back" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali: Data Pelapor</span>
                    </button>
                    <button type="button" id="btn-step4-next" class="bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs px-5 py-2.5 rounded-lg shadow-xs transition flex items-center gap-2">
                        <span>Selanjutnya: Upload Berkas</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ===================================================================
                 LANGKAH 5: UPLOAD DOKUMEN PERSYARATAN (LANGKAH TERAKHIR)
            =================================================================== -->
            <div id="step-5" class="space-y-5 hidden">
                
                <div class="border-b border-rose-100 pb-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800 flex items-center gap-2">
                        <i class="fa-solid fa-folder-open text-rose-600"></i>
                        Upload Dokumen Persyaratan (Format: JPG, PNG, PDF &bull; Maks. 3 MB)
                    </h3>
                </div>

                <!-- 6 Upload Cards Bersih & Rapi -->
                @php
                    $deathDocCards = [
                        ['id' => 'doc_death_statement', 'key' => 'death', 'title' => 'Surat Kematian', 'desc' => 'Dari RS, Puskesmas, Dokter, atau RT'],
                        ['id' => 'doc_rt_rw_statement', 'key' => 'rtrw', 'title' => 'Surat Pengantar RT / RW', 'desc' => 'Surat pengantar kematian RT/RW'],
                        ['id' => 'doc_family_card', 'key' => 'kk', 'title' => 'Kartu Keluarga (KK)', 'desc' => 'KK almarhum / almarhumah'],
                        ['id' => 'doc_deceased_ktp', 'key' => 'deceased-ktp', 'title' => 'KTP Almarhum / Almarhumah', 'desc' => 'KTP-el asli yang meninggal'],
                        ['id' => 'doc_heir_family_card', 'key' => 'heir-kk', 'title' => 'KK Ahli Waris', 'desc' => 'Kartu Keluarga ahli waris'],
                        ['id' => 'doc_heir_ktp', 'key' => 'heir-ktp', 'title' => 'KTP Ahli Waris', 'desc' => 'KTP-el asli ahli waris / pelapor'],
                    ];
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    @foreach($deathDocCards as $index => $card)
                        <div class="bg-white border border-slate-200 rounded-xl p-3.5 flex flex-col justify-between">
                            <div class="mb-2.5">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-bold text-[11px] flex items-center justify-center">{{ $index + 1 }}</span>
                                    <span class="text-[10px] bg-rose-100 text-rose-800 px-2 py-0.5 rounded font-bold">Wajib</span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800">{{ $card['title'] }}</h4>
                                <p class="text-[11px] text-slate-500">{{ $card['desc'] }}</p>
                            </div>
                            <div class="mt-auto w-full">
                                <input type="file" name="{{ $card['id'] }}" id="{{ $card['id'] }}" accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="handleFileChange(this, '{{ $card['key'] }}')">

                                <!-- Box Upload Standar -->
                                <label for="{{ $card['id'] }}" id="placeholder-{{ $card['key'] }}" class="cursor-pointer flex flex-col items-center justify-center text-center bg-slate-50 hover:bg-rose-50 hover:text-rose-700 border-2 border-dashed border-slate-300 hover:border-rose-400 rounded-xl w-full p-3 transition" style="height: 165px;">
                                    <div class="w-9 h-9 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mb-1.5 border border-rose-200">
                                        <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                    </div>
                                    <span class="block text-xs font-bold text-slate-700">Pilih Berkas</span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">JPG, PNG, PDF (Maks. 3MB)</span>
                                </label>

                                <!-- Box Preview Dokumen -->
                                <div id="preview-box-{{ $card['key'] }}" class="hidden bg-white border border-emerald-200 rounded-xl p-2 shadow-xs w-full flex flex-col justify-between" style="height: 165px; overflow: hidden;">
                                    <div id="img-preview-wrap-{{ $card['key'] }}" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group hidden shrink-0" style="height: 108px;">
                                        <img id="img-preview-{{ $card['key'] }}" src="" alt="Preview {{ $card['title'] }}" class="w-full h-full object-cover object-center block">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                            <button type="button" onclick="viewFullDeathImage('{{ $card['key'] }}', '{{ $card['title'] }}')" class="cursor-pointer bg-white text-slate-800 text-[11px] font-bold px-2.5 py-1 rounded-md shadow-sm hover:bg-slate-100 transition flex items-center gap-1">
                                                <i class="fa-solid fa-eye text-rose-600"></i> Lihat Gambar
                                            </button>
                                        </div>
                                    </div>
                                    <div id="pdf-preview-wrap-{{ $card['key'] }}" class="w-full bg-rose-50 rounded-lg flex flex-col items-center justify-center border border-rose-200 text-rose-700 hidden shrink-0" style="height: 108px;">
                                        <i class="fa-solid fa-file-pdf text-2xl mb-1"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Dokumen PDF</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 shrink-0">
                                        <div class="truncate flex-1 min-w-0">
                                            <p id="file-name-{{ $card['key'] }}" class="text-[11px] font-bold text-slate-800 truncate"></p>
                                            <p id="file-size-{{ $card['key'] }}" class="text-[10px] text-emerald-700 font-medium"></p>
                                        </div>
                                        <label for="{{ $card['id'] }}" class="shrink-0 text-[10px] font-bold text-rose-700 hover:text-rose-800 cursor-pointer bg-rose-50 px-2 py-1 rounded border border-rose-200 transition">
                                            <i class="fa-solid fa-pen-to-square"></i> Ganti
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pernyataan Kebenaran Data -->
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-xs">
                    <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-700">
                        <input type="checkbox" id="declaration-check" required class="rounded text-rose-600 focus:ring-rose-600">
                        <span>Saya menyatakan data dan dokumen yang dilampirkan adalah benar dan sah.</span>
                    </label>
                </div>

                <!-- Navigasi Langkah 5 -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                    <button type="button" id="btn-step5-back" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali: Data Saksi</span>
                    </button>
                    <button type="button" id="btn-submit-form" class="bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs px-6 py-2.5 rounded-lg shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Kirim Permohonan Akta Kematian</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

<!-- Modal Pratinjau & Konfirmasi Akhir -->
<div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-xl max-w-md w-full p-5 shadow-2xl border border-slate-200 space-y-3.5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
            <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-check text-rose-600"></i>
                Konfirmasi Permohonan
            </h3>
            <button type="button" onclick="closeConfirmModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="bg-slate-50 rounded-lg p-3.5 border border-slate-200 text-xs space-y-2">
            <div class="flex justify-between"><span class="text-slate-500">No. Kartu Keluarga:</span> <span id="prev-kk" class="font-mono font-bold text-slate-800">-</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Nama Jenazah:</span> <strong id="prev-deceased-name" class="text-rose-700">-</strong></div>
            <div class="flex justify-between"><span class="text-slate-500">NIK Jenazah:</span> <span id="prev-deceased-nik" class="font-mono text-slate-800">-</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Waktu Kematian:</span> <span id="prev-death-time" class="text-slate-800">-</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Tempat Kematian:</span> <span id="prev-death-place" class="text-slate-800">-</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Nama Pelapor:</span> <span id="prev-applicant-name" class="font-semibold text-slate-800">-</span></div>
            <div class="flex justify-between"><span class="text-slate-500">No. WhatsApp:</span> <span id="prev-applicant-phone" class="font-semibold text-slate-800">-</span></div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <button type="button" onclick="closeConfirmModal()" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition">
                Periksa Lagi
            </button>
            <button type="button" id="btn-final-send" class="px-4 py-2 text-xs font-bold text-white bg-rose-700 hover:bg-rose-800 rounded-lg shadow-xs transition flex items-center gap-1.5">
                <i class="fa-solid fa-check"></i> Ya, Kirim Sekarang
            </button>
        </div>
    </div>
</div>

<script>
    const CURRENT_USER_ID = '{{ Auth::id() ?? "guest" }}';
    const DRAFT_KEY = 'purwobinangun_death_form_draft_' + CURRENT_USER_ID;

    let currentStep = 1;
    const totalSteps = 5;

    // Elements
    const step1El = document.getElementById('step-1');
    const step2El = document.getElementById('step-2');
    const step3El = document.getElementById('step-3');
    const step4El = document.getElementById('step-4');
    const step5El = document.getElementById('step-5');
    const stepElements = [step1El, step2El, step3El, step4El, step5El];
    const deathForm = document.getElementById('death-application-form');

    // ==========================================
    // LocalStorage Auto-Save Form Draft
    // ==========================================
    function saveFormDraft() {
        try {
            if (!deathForm) return;

            const draft = {
                currentStep: currentStep,
                family_card_no: document.getElementById('family_card_no')?.value || '',
                head_of_family_name: document.getElementById('head_of_family_name')?.value || '',
                deceased_nik: document.getElementById('deceased_nik')?.value || '',
                deceased_name: document.getElementById('deceased_name')?.value || '',
                gender: deathForm.querySelector('input[name="gender"]:checked')?.value || 'L',
                deceased_birth_place: document.getElementById('deceased_birth_place')?.value || '',
                birth_date: document.getElementById('birth_date')?.value || '',
                religion: document.getElementById('religion')?.value || 'Islam',
                deceased_job: document.getElementById('deceased_job')?.value || '',
                padukuhan: document.getElementById('padukuhan')?.value || '',
                rt: document.getElementById('rt')?.value || '',
                rw: document.getElementById('rw')?.value || '',
                deceased_address: document.getElementById('deceased_address')?.value || '',
                deceased_child_order: document.getElementById('deceased_child_order')?.value || '',
                death_day: document.getElementById('death_day')?.value || '',
                death_date: document.getElementById('death_date')?.value || '',
                death_time: document.getElementById('death_time')?.value || '',
                death_place: document.getElementById('death_place')?.value || '',
                cause_of_death: document.getElementById('cause_of_death')?.value || '',
                death_location_detail: document.getElementById('death_location_detail')?.value || '',
                reported_by_title: deathForm.querySelector('input[name="reported_by_title"]:checked')?.value || 'Dokter',

                mother_nik: document.getElementById('mother_nik')?.value || '',
                mother_name: document.getElementById('mother_name')?.value || '',
                mother_birth_place: document.getElementById('mother_birth_place')?.value || '',
                mother_birth_date: document.getElementById('mother_birth_date')?.value || '',
                mother_job: document.getElementById('mother_job')?.value || '',
                mother_address: document.getElementById('mother_address')?.value || '',
                mother_citizenship: document.getElementById('mother_citizenship')?.value || 'WNI',

                father_nik: document.getElementById('father_nik')?.value || '',
                father_name: document.getElementById('father_name')?.value || '',
                father_birth_place: document.getElementById('father_birth_place')?.value || '',
                father_birth_date: document.getElementById('father_birth_date')?.value || '',
                father_job: document.getElementById('father_job')?.value || '',
                father_address: document.getElementById('father_address')?.value || '',
                father_citizenship: document.getElementById('father_citizenship')?.value || 'WNI',

                applicant_nik: document.getElementById('applicant_nik')?.value || '',
                applicant_name: document.getElementById('applicant_name')?.value || '',
                applicant_birth_place: document.getElementById('applicant_birth_place')?.value || '',
                applicant_birth_date: document.getElementById('applicant_birth_date')?.value || '',
                applicant_age: document.getElementById('applicant_age')?.value || '',
                applicant_job: document.getElementById('applicant_job')?.value || '',
                applicant_address: document.getElementById('applicant_address')?.value || '',
                report_date: document.getElementById('report_date')?.value || '',
                applicant_relation: document.getElementById('applicant_relation')?.value || '',
                applicant_phone: document.getElementById('applicant_phone')?.value || '',

                witness1_nik: document.getElementById('witness1_nik')?.value || '',
                witness1_name: document.getElementById('witness1_name')?.value || '',
                witness1_age: document.getElementById('witness1_age')?.value || '',
                witness1_address: document.getElementById('witness1_address')?.value || '',

                witness2_nik: document.getElementById('witness2_nik')?.value || '',
                witness2_name: document.getElementById('witness2_name')?.value || '',
                witness2_age: document.getElementById('witness2_age')?.value || '',
                witness2_address: document.getElementById('witness2_address')?.value || '',

                declaration_check: document.getElementById('declaration-check')?.checked || false
            };

            const json = JSON.stringify(draft);
            localStorage.setItem(DRAFT_KEY, json);
            localStorage.setItem('purwobinangun_death_form_draft', json);
        } catch (e) {
            console.warn('Gagal menyimpan draft form kematian:', e);
        }
    }

    // ==========================================
    // LocalStorage Restore Form Draft on Reload
    // ==========================================
    function restoreFormDraft() {
        try {
            const raw = localStorage.getItem(DRAFT_KEY) || localStorage.getItem('purwobinangun_death_form_draft');
            if (!raw) return;
            const draft = JSON.parse(raw);
            if (!draft || typeof draft !== 'object') return;

            // Simple text / date / number / textarea / select fields
            const simpleFields = [
                'family_card_no', 'head_of_family_name', 'deceased_nik', 'deceased_name',
                'deceased_birth_place', 'birth_date', 'religion', 'deceased_job', 'padukuhan',
                'rt', 'rw', 'deceased_address', 'deceased_child_order', 'death_day', 'death_date',
                'death_time', 'death_place', 'cause_of_death', 'death_location_detail',
                'mother_nik', 'mother_name', 'mother_birth_place', 'mother_birth_date', 'mother_job',
                'mother_address', 'mother_citizenship',
                'father_nik', 'father_name', 'father_birth_place', 'father_birth_date', 'father_job',
                'father_address', 'father_citizenship',
                'applicant_nik', 'applicant_name', 'applicant_birth_place', 'applicant_birth_date',
                'applicant_age', 'applicant_job', 'applicant_address', 'report_date',
                'applicant_relation', 'applicant_phone',
                'witness1_nik', 'witness1_name', 'witness1_age', 'witness1_address',
                'witness2_nik', 'witness2_name', 'witness2_age', 'witness2_address'
            ];

            simpleFields.forEach(id => {
                const el = document.getElementById(id);
                if (el && draft[id] !== undefined && draft[id] !== null && draft[id] !== '') {
                    el.value = draft[id];
                }
            });

            // Radios: gender
            if (draft.gender && deathForm) {
                const rGender = deathForm.querySelector(`input[name="gender"][value="${draft.gender}"]`);
                if (rGender) rGender.checked = true;
            }

            // Radios: reported_by_title
            if (draft.reported_by_title && deathForm) {
                const rTitle = deathForm.querySelector(`input[name="reported_by_title"][value="${draft.reported_by_title}"]`);
                if (rTitle) rTitle.checked = true;
            }

            // Checkbox: declaration_check
            const decl = document.getElementById('declaration-check');
            if (decl && draft.declaration_check !== undefined) {
                decl.checked = Boolean(draft.declaration_check);
            }

            // Step terakhir yang sedang dibuka
            if (draft.currentStep && draft.currentStep >= 1 && draft.currentStep <= totalSteps) {
                setStep(draft.currentStep, false);
            }
        } catch (e) {
            console.warn('Gagal memulihkan draft form kematian:', e);
        }
    }

    // Helper Auto Set Hari
    const daysName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const deathDateInput = document.getElementById('death_date');
    const deathDaySelect = document.getElementById('death_day');

    if (deathDateInput && deathDaySelect) {
        deathDateInput.addEventListener('change', function () {
            if (this.value) {
                const d = new Date(this.value);
                if (!isNaN(d.getTime())) {
                    deathDaySelect.value = daysName[d.getDay()];
                }
            }
        });
        if (deathDateInput.value && !deathDaySelect.value) {
            const d = new Date(deathDateInput.value);
            if (!isNaN(d.getTime())) {
                deathDaySelect.value = daysName[d.getDay()];
            }
        }
    }

    // Helper Auto Hitung Umur Pelapor
    const appBirthDate = document.getElementById('applicant_birth_date');
    const appAgeInput = document.getElementById('applicant_age');
    if (appBirthDate && appAgeInput) {
        appBirthDate.addEventListener('change', function () {
            if (this.value) {
                const b = new Date(this.value);
                const now = new Date();
                let age = now.getFullYear() - b.getFullYear();
                const m = now.getMonth() - b.getMonth();
                if (m < 0 || (m === 0 && now.getDate() < b.getDate())) age--;
                if (age >= 0 && age <= 150) appAgeInput.value = age;
            }
        });
    }

    // Fungsi Utama Switch Langkah (Set Step)
    function setStep(step, smoothScroll = true) {
        step = parseInt(step);
        if (step < 1 || step > totalSteps) return;

        // Tampilkan hanya langkah terpilih, sembunyikan sisanya
        stepElements.forEach((el, index) => {
            if (el) {
                if (index + 1 === step) {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            }
        });

        // Update Indikator Stepper di atas
        for (let i = 1; i <= totalSteps; i++) {
            const circle = document.getElementById(`step-circle-${i}`);
            const label = document.getElementById(`step-label-${i}`);

            if (i < step) {
                circle.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-2xs bg-rose-600 text-white cursor-pointer';
                circle.innerHTML = '<i class="fa-solid fa-check text-xs"></i>';
                label.className = 'step-label text-[10px] sm:text-xs font-semibold text-rose-700 mt-1.5 text-center whitespace-nowrap';
            } else if (i === step) {
                circle.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-xs bg-rose-700 text-white ring-4 ring-rose-100 ring-offset-1 cursor-default';
                circle.innerHTML = `<span class="step-num">${i}</span>`;
                label.className = 'step-label text-[10px] sm:text-xs font-bold text-rose-700 mt-1.5 text-center whitespace-nowrap';
            } else {
                circle.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm transition-all duration-200 shadow-2xs bg-white text-slate-400 border-2 border-slate-300 cursor-pointer hover:border-slate-400 hover:text-slate-600';
                circle.innerHTML = `<span class="step-num">${i}</span>`;
                label.className = 'step-label text-[10px] sm:text-xs font-medium text-slate-400 mt-1.5 text-center whitespace-nowrap';
            }
        }

        // Garis Progress Bar
        const progressLine = document.getElementById('progress-bar-line');
        const percent = ((step - 1) / (totalSteps - 1)) * 100;
        progressLine.style.width = `${percent}%`;

        currentStep = step;
        saveFormDraft();

        if (smoothScroll) {
            window.scrollTo({ top: 100, behavior: 'smooth' });
        }
    }

    // Validasi Langkah 1
    function validateStep1() {
        const familyCardNo = document.getElementById('family_card_no').value.trim();
        const headOfFamily = document.getElementById('head_of_family_name').value.trim();
        const deceasedNik = document.getElementById('deceased_nik').value.trim();
        const deceasedName = document.getElementById('deceased_name').value.trim();
        const birthPlace = document.getElementById('deceased_birth_place').value.trim();
        const birthDate = document.getElementById('birth_date').value.trim();
        const padukuhan = document.getElementById('padukuhan').value;
        const rt = document.getElementById('rt').value.trim();
        const rw = document.getElementById('rw').value.trim();
        const deathDay = document.getElementById('death_day').value;
        const deathDate = document.getElementById('death_date').value.trim();
        const deathPlace = document.getElementById('death_place').value;
        const causeOfDeath = document.getElementById('cause_of_death').value;

        if (familyCardNo.length !== 16) {
            alert('Nomor Kartu Keluarga harus terdiri dari 16 digit angka.');
            document.getElementById('family_card_no').focus();
            return false;
        }
        if (!headOfFamily) {
            alert('Nama Kepala Keluarga wajib diisi.');
            document.getElementById('head_of_family_name').focus();
            return false;
        }
        if (deceasedNik.length !== 16) {
            alert('NIK Jenazah harus terdiri dari 16 digit angka.');
            document.getElementById('deceased_nik').focus();
            return false;
        }
        if (!deceasedName) {
            alert('Nama Lengkap Jenazah wajib diisi.');
            document.getElementById('deceased_name').focus();
            return false;
        }
        if (!birthPlace || !birthDate) {
            alert('Tempat dan Tanggal Lahir Jenazah wajib diisi.');
            return false;
        }
        if (!padukuhan) {
            alert('Silakan pilih Padukuhan domisili di Purwobinangun.');
            document.getElementById('padukuhan').focus();
            return false;
        }
        if (!rt || !rw) {
            alert('RT dan RW Jenazah wajib diisi.');
            return false;
        }
        if (!deathDay || !deathDate) {
            alert('Hari dan Tanggal Kematian wajib diisi.');
            return false;
        }
        if (!deathPlace || !causeOfDeath) {
            alert('Tempat dan Sebab Kematian wajib ditentukan.');
            return false;
        }
        return true;
    }

    // Validasi Langkah 2
    function validateStep2() {
        const mNik = document.getElementById('mother_nik').value.trim();
        if (mNik && mNik.length !== 16) {
            alert('Jika NIK Ibu diisi, harus berupa 16 digit angka.');
            document.getElementById('mother_nik').focus();
            return false;
        }
        const fNik = document.getElementById('father_nik').value.trim();
        if (fNik && fNik.length !== 16) {
            alert('Jika NIK Ayah diisi, harus berupa 16 digit angka.');
            document.getElementById('father_nik').focus();
            return false;
        }
        return true;
    }

    // Validasi Langkah 3
    function validateStep3() {
        const appNik = document.getElementById('applicant_nik').value.trim();
        const appName = document.getElementById('applicant_name').value.trim();
        const appPhone = document.getElementById('applicant_phone').value.trim();
        const appRelation = document.getElementById('applicant_relation').value;
        const repDate = document.getElementById('report_date').value.trim();

        if (appNik.length !== 16) {
            alert('NIK Pelapor harus terdiri dari 16 digit angka.');
            document.getElementById('applicant_nik').focus();
            return false;
        }
        if (!appName) {
            alert('Nama Lengkap Pelapor wajib diisi.');
            document.getElementById('applicant_name').focus();
            return false;
        }
        if (!appRelation) {
            alert('Silakan pilih hubungan pelapor dengan almarhum/ah.');
            document.getElementById('applicant_relation').focus();
            return false;
        }
        if (!appPhone) {
            alert('Nomor WhatsApp / HP Pelapor wajib diisi.');
            document.getElementById('applicant_phone').focus();
            return false;
        }
        if (!repDate) {
            alert('Tanggal lapor wajib diisi.');
            return false;
        }
        return true;
    }

    // Validasi Langkah 4
    function validateStep4() {
        const w1Nik = document.getElementById('witness1_nik').value.trim();
        if (w1Nik && w1Nik.length !== 16) {
            alert('Jika NIK Saksi I diisi, harus berupa 16 digit angka.');
            document.getElementById('witness1_nik').focus();
            return false;
        }
        const w2Nik = document.getElementById('witness2_nik').value.trim();
        if (w2Nik && w2Nik.length !== 16) {
            alert('Jika NIK Saksi II diisi, harus berupa 16 digit angka.');
            document.getElementById('witness2_nik').focus();
            return false;
        }
        return true;
    }

    // Validasi Langkah 5
    function validateStep5() {
        const docFields = [
            { id: 'doc_death_statement', label: 'Surat Kematian' },
            { id: 'doc_rt_rw_statement', label: 'Surat Pengantar RT/RW' },
            { id: 'doc_family_card', label: 'Kartu Keluarga (KK)' },
            { id: 'doc_deceased_ktp', label: 'KTP Almarhum/ah' },
            { id: 'doc_heir_family_card', label: 'KK Ahli Waris' },
            { id: 'doc_heir_ktp', label: 'KTP Ahli Waris' }
        ];

        for (const doc of docFields) {
            const input = document.getElementById(doc.id);
            if (!input.files || input.files.length === 0) {
                alert(`Dokumen "${doc.label}" wajib diunggah.`);
                input.focus();
                return false;
            }
        }

        const decl = document.getElementById('declaration-check');
        if (!decl.checked) {
            alert('Silakan centang pernyataan kebenaran data terlebih dahulu.');
            decl.focus();
            return false;
        }
        return true;
    }

    // Event Listeners Navigasi
    document.getElementById('btn-step1-next').addEventListener('click', function () {
        if (validateStep1()) setStep(2);
    });

    document.getElementById('btn-step2-back').addEventListener('click', function () {
        setStep(1);
    });
    document.getElementById('btn-step2-next').addEventListener('click', function () {
        if (validateStep2()) setStep(3);
    });

    document.getElementById('btn-step3-back').addEventListener('click', function () {
        setStep(2);
    });
    document.getElementById('btn-step3-next').addEventListener('click', function () {
        if (validateStep3()) setStep(4);
    });

    document.getElementById('btn-step4-back').addEventListener('click', function () {
        setStep(3);
    });
    document.getElementById('btn-step4-next').addEventListener('click', function () {
        if (validateStep4()) setStep(5);
    });

    document.getElementById('btn-step5-back').addEventListener('click', function () {
        setStep(4);
    });

    // Direct Click Stepper Indicator
    window.goToStepIfAllowed = function (targetStep) {
        targetStep = parseInt(targetStep);
        if (targetStep === currentStep || targetStep < 1 || targetStep > totalSteps) return;

        if (targetStep < currentStep) {
            setStep(targetStep);
            return;
        }

        for (let s = currentStep; s < targetStep; s++) {
            if (s === 1 && !validateStep1()) return;
            if (s === 2 && !validateStep2()) return;
            if (s === 3 && !validateStep3()) return;
            if (s === 4 && !validateStep4()) return;
        }

        setStep(targetStep);
    };

    // File Preview Handler
    function handleFileChange(input, cardKey) {
        const placeholder = document.getElementById(`placeholder-${cardKey}`);
        const previewBox = document.getElementById(`preview-box-${cardKey}`);
        const imgWrap = document.getElementById(`img-preview-wrap-${cardKey}`);
        const imgEl = document.getElementById(`img-preview-${cardKey}`);
        const pdfWrap = document.getElementById(`pdf-preview-wrap-${cardKey}`);
        const fileNameEl = document.getElementById(`file-name-${cardKey}`);
        const fileSizeEl = document.getElementById(`file-size-${cardKey}`);

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

            if (file.size > 3 * 1024 * 1024) {
                alert(`Ukuran file "${file.name}" (${sizeMB} MB) melebihi batas maksimal 3 MB.`);
                input.value = '';
                if (previewBox) previewBox.classList.add('hidden');
                if (placeholder) placeholder.classList.remove('hidden');
                if (imgEl) imgEl.src = '';
                return;
            }

            if (fileNameEl) fileNameEl.textContent = file.name;
            if (fileSizeEl) {
                fileSizeEl.textContent = file.size >= 1024 * 1024
                    ? `${sizeMB} MB`
                    : `${(file.size / 1024).toFixed(1)} KB`;
            }

            const isImage = (file.type && file.type.startsWith('image/')) || /\.(jpe?g|png)$/i.test(file.name);
            if (isImage) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (imgEl) imgEl.src = e.target.result;
                    if (imgWrap) imgWrap.classList.remove('hidden');
                    if (pdfWrap) pdfWrap.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                if (imgEl) imgEl.src = '';
                if (imgWrap) imgWrap.classList.add('hidden');
                if (pdfWrap) pdfWrap.classList.remove('hidden');
            }

            if (placeholder) placeholder.classList.add('hidden');
            if (previewBox) previewBox.classList.remove('hidden');
        } else {
            if (imgEl) imgEl.src = '';
            if (imgWrap) imgWrap.classList.add('hidden');
            if (pdfWrap) pdfWrap.classList.add('hidden');
            if (previewBox) previewBox.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
        }
    }

    function viewFullDeathImage(cardKey, title) {
        const imgEl = document.getElementById(`img-preview-${cardKey}`);
        if (!imgEl || !imgEl.src) return;

        const src = imgEl.src;
        if (src.startsWith('data:')) {
            const newWindow = window.open('', '_blank');
            if (newWindow) {
                newWindow.document.title = title || 'Pratinjau Dokumen';
                newWindow.document.body.style.cssText = 'margin:0;background:#0f172a;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px;box-sizing:border-box;';
                const previewImg = newWindow.document.createElement('img');
                previewImg.src = src;
                previewImg.alt = title || 'Pratinjau Dokumen';
                previewImg.style.cssText = 'max-width:100%;max-height:95vh;object-fit:contain;border-radius:8px;box-shadow:0 10px 25px rgba(0,0,0,0.5);';
                newWindow.document.body.appendChild(previewImg);
            }
        } else {
            window.open(src, '_blank');
        }
    }

    // Modal Konfirmasi
    const confirmModal = document.getElementById('confirm-modal');
    const btnSubmitForm = document.getElementById('btn-submit-form');
    const btnFinalSend = document.getElementById('btn-final-send');

    btnSubmitForm.addEventListener('click', function () {
        if (!validateStep1()) { setStep(1); return; }
        if (!validateStep2()) { setStep(2); return; }
        if (!validateStep3()) { setStep(3); return; }
        if (!validateStep4()) { setStep(4); return; }
        if (!validateStep5()) { return; }

        document.getElementById('prev-kk').textContent = document.getElementById('family_card_no').value || '-';
        document.getElementById('prev-deceased-name').textContent = document.getElementById('deceased_name').value || '-';
        document.getElementById('prev-deceased-nik').textContent = document.getElementById('deceased_nik').value || '-';
        
        const dDay = document.getElementById('death_day').value;
        const dDate = document.getElementById('death_date').value;
        const dTime = document.getElementById('death_time').value;
        document.getElementById('prev-death-time').textContent = `${dDay}, ${dDate} ${dTime ? '(' + dTime + ')' : ''}`;
        document.getElementById('prev-death-place').textContent = document.getElementById('death_place').value || '-';
        document.getElementById('prev-applicant-name').textContent = document.getElementById('applicant_name').value || '-';
        document.getElementById('prev-applicant-phone').textContent = document.getElementById('applicant_phone').value || '-';

        confirmModal.classList.remove('hidden');
    });

    function closeConfirmModal() {
        confirmModal.classList.add('hidden');
    }

    btnFinalSend.addEventListener('click', function () {
        btnFinalSend.disabled = true;
        btnFinalSend.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirimkan...';
        try {
            localStorage.removeItem(DRAFT_KEY);
            localStorage.removeItem('purwobinangun_death_form_draft');
        } catch (e) {}
        deathForm.submit();
    });

    confirmModal.addEventListener('click', function (e) {
        if (e.target === confirmModal) closeConfirmModal();
    });

    // Event Listener Auto-Save Draft saat user mengetik atau mengubah field
    if (deathForm) {
        deathForm.addEventListener('input', saveFormDraft);
        deathForm.addEventListener('change', saveFormDraft);
        deathForm.addEventListener('submit', function () {
            try {
                localStorage.removeItem(DRAFT_KEY);
                localStorage.removeItem('purwobinangun_death_form_draft');
            } catch (e) {}
        });
    }

    // Pulihkan data draft yang tersimpan saat halaman dimuat / direfresh
    restoreFormDraft();
</script>
@endsection
