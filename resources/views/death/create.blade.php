@extends('layouts.app')

@section('title', 'Formulir Permohonan Akte Kematian')

@section('content')
<div class="space-y-6">

    <!-- Card Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-rose-700 text-white px-4 py-3 flex flex-wrap items-center justify-between gap-2">
            <h2 class="font-bold text-xs md:text-sm tracking-wide uppercase flex items-center gap-2">
                <i class="fa-solid fa-book-skull text-amber-300"></i> FORMULIR PENGAJUAN SURAT PENGANTAR AKTE KEMATIAN
            </h2>
            <div class="flex items-center gap-2">
                <span class="hidden sm:inline-block text-[11px] bg-rose-900/80 px-2 py-0.5 rounded text-rose-100 font-medium">Kalurahan Purwobinangun</span>
                <a href="{{ route('submissions.index') }}" class="bg-white/15 hover:bg-white/25 text-white border border-white/20 text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-list-check text-rose-200"></i> Daftar Pengajuan
                </a>
            </div>
        </div>

        <div class="p-5 md:p-6 bg-slate-50 border-b border-slate-200/60">
            <p class="text-xs text-slate-600 leading-relaxed">
                Silakan lengkapi data almarhum/almarhumah, waktu dan tempat peristiwa kematian, serta data pelapor/ahli waris dengan benar sesuai dokumen kependudukan yang sah.
            </p>
        </div>

        <!-- Form Permohonan -->
        <form action="{{ route('death.store') }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-6 space-y-8">
            @csrf

            <!-- Bagian 1: Data Almarhum / Almarhumah -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-rose-700 flex items-center gap-2 pb-2 border-b border-rose-100">
                    <span class="w-5 h-5 rounded-full bg-rose-700 text-white text-[10px] flex items-center justify-center">1</span>
                    Data Almarhum / Almarhumah yang Meninggal Dunia
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK Almarhum/ah (16 Digit) <span class="text-rose-500">*</span></label>
                        <input type="text" maxlength="16" name="deceased_nik" value="{{ old('deceased_nik') }}" required placeholder="Contoh: 3404050101450001" class="w-full text-xs px-3.5 py-2.5 rounded-lg border @error('deceased_nik') border-rose-500 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-rose-600">
                        @error('deceased_nik') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Almarhum/ah <span class="text-rose-500">*</span></label>
                        <input type="text" name="deceased_name" value="{{ old('deceased_name') }}" required placeholder="Nama lengkap sesuai KTP/KK" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <div class="flex items-center gap-6 mt-1.5 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="L" {{ old('gender', 'L') === 'L' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-600">
                                <span>Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="P" {{ old('gender') === 'P' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-600">
                                <span>Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Agama</label>
                        <select name="religion" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                            <option value="Islam" {{ old('religion') === 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('religion') === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('religion') === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('religion') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('religion') === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('religion') === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Padukuhan Domisili di Purwobinangun <span class="text-rose-500">*</span></label>
                        <select name="padukuhan" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                            <option value="">-- Pilih Padukuhan --</option>
                            @foreach($padukuhanList as $pad)
                                <option value="{{ $pad }}" {{ old('padukuhan') === $pad ? 'selected' : '' }}>Padukuhan {{ $pad }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">RT / RW <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="rt" value="{{ old('rt', '01') }}" required placeholder="RT" class="text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                            <input type="text" name="rw" value="{{ old('rw', '01') }}" required placeholder="RW" class="text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Data Peristiwa Kematian -->
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-rose-700 flex items-center gap-2 pb-2 border-b border-rose-100">
                    <span class="w-5 h-5 rounded-full bg-rose-700 text-white text-[10px] flex items-center justify-center">2</span>
                    Rincian Peristiwa Kematian
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Meninggal Dunia <span class="text-rose-500">*</span></label>
                        <input type="date" name="death_date" value="{{ old('death_date') }}" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Waktu / Pukul Meninggal (Format 24 Jam)</label>
                        <input type="time" name="death_time" value="{{ old('death_time') }}" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600 bg-white cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tempat Kematian <span class="text-rose-500">*</span></label>
                        <input type="text" name="death_place" value="{{ old('death_place', 'Rumah') }}" required placeholder="Contoh: Rumah / RSUD Sleman / Puskesmas" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Penyebab Kematian <span class="text-rose-500">*</span></label>
                        <input type="text" name="cause_of_death" value="{{ old('cause_of_death', 'Sakit / Usia Tua') }}" required placeholder="Contoh: Sakit / Usia Tua / Kecelakaan" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Yang Menerangkan Kematian <span class="text-rose-500">*</span></label>
                        <select name="reported_by_title" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                            <option value="Dokter" {{ old('reported_by_title') === 'Dokter' ? 'selected' : '' }}>Dokter / Rumah Sakit</option>
                            <option value="Paramedis / Perawat" {{ old('reported_by_title') === 'Paramedis / Perawat' ? 'selected' : '' }}>Paramedis / Perawat</option>
                            <option value="Kepala Dusun / RT" {{ old('reported_by_title') === 'Kepala Dusun / RT' ? 'selected' : '' }}>Kepala Dusun / RT Setempat</option>
                            <option value="Lainnya" {{ old('reported_by_title') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Data Pelapor & Saksi -->
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-rose-700 flex items-center gap-2 pb-2 border-b border-rose-100">
                    <span class="w-5 h-5 rounded-full bg-rose-700 text-white text-[10px] flex items-center justify-center">3</span>
                    Data Pelapor (Ahli Waris) & Saksi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK Pelapor (16 Digit) <span class="text-rose-500">*</span></label>
                        <input type="text" maxlength="16" name="applicant_nik" value="{{ old('applicant_nik', $user->nik ?? '') }}" required placeholder="NIK Pelapor" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Pelapor <span class="text-rose-500">*</span></label>
                        <input type="text" name="applicant_name" value="{{ old('applicant_name', $user->name ?? '') }}" required placeholder="Nama lengkap pelapor" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP Pelapor <span class="text-rose-500">*</span></label>
                        <input type="text" name="applicant_phone" value="{{ old('applicant_phone', $user->phone ?? '') }}" required placeholder="Contoh: 081234567890" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hubungan dengan Almarhum/ah <span class="text-rose-500">*</span></label>
                        <select name="applicant_relation" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                            <option value="Anak" {{ old('applicant_relation') === 'Anak' ? 'selected' : '' }}>Anak Kandung</option>
                            <option value="Suami / Istri" {{ old('applicant_relation') === 'Suami / Istri' ? 'selected' : '' }}>Suami / Istri</option>
                            <option value="Orang Tua" {{ old('applicant_relation') === 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                            <option value="Saudara" {{ old('applicant_relation') === 'Saudara' ? 'selected' : '' }}>Saudara Kandung</option>
                            <option value="Ahli Waris / Kuasa" {{ old('applicant_relation') === 'Ahli Waris / Kuasa' ? 'selected' : '' }}>Ahli Waris / Kuasa Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK Saksi (Opsional)</label>
                        <input type="text" maxlength="16" name="witness_nik" value="{{ old('witness_nik') }}" placeholder="NIK Saksi Kematian" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Saksi (Opsional)</label>
                        <input type="text" name="witness_name" value="{{ old('witness_name') }}" placeholder="Nama Saksi Kematian" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600">
                    </div>
                </div>
            </div>

            <!-- Bagian 4: Upload Dokumen Persyaratan -->
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-2 border-b border-rose-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-700 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-rose-700 text-white text-[10px] flex items-center justify-center">4</span>
                        Upload Berkas Persyaratan
                    </h3>
                    <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded font-medium">Format: PDF, JPG, PNG (Maks. 3MB)</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- 1. Surat Keterangan Kematian -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-between hover:border-rose-600 transition h-full" style="min-height: 270px;">
                        <div class="h-14 mb-2 flex flex-col justify-start shrink-0" style="height: 48px; min-height: 48px;">
                            <label class="block text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-file-medical text-rose-700"></i> 1. Surat Kematian <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-slate-500 leading-tight">Surat Keterangan Kematian (RS/Dokter/RT)</p>
                        </div>

                        <div class="mt-auto w-full">
                            <input type="file" id="doc_death_statement" name="doc_death_statement" accept=".jpg,.jpeg,.png,.pdf" required class="hidden file-input" data-card="death_statement">
                            
                            <!-- Box Upload Standar -->
                            <label for="doc_death_statement" id="placeholder-death_statement" class="cursor-pointer flex flex-col items-center justify-center text-center bg-white hover:bg-rose-50/50 text-rose-700 border-2 border-dashed border-rose-300 hover:border-rose-600 rounded-xl w-full p-3 transition shadow-2xs" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box;">
                                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-700 flex items-center justify-center mb-2 border border-rose-200">
                                    <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                </div>
                                <span class="block text-xs font-bold">Pilih Berkas Kematian</span>
                                <span class="block text-[10px] text-slate-400 mt-1">JPG, PNG, PDF (Maks. 3MB)</span>
                            </label>

                            <!-- Box Preview Dokumen (Ukuran Fixed) -->
                            <div id="preview-box-death_statement" class="hidden bg-white border border-rose-200 rounded-xl p-2.5 shadow-xs w-full flex flex-col justify-between" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box; overflow: hidden;">
                                <div id="img-preview-wrap-death_statement" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%; position: relative; overflow: hidden;">
                                    <img id="img-preview-death_statement" src="" alt="Preview Surat Keterangan Kematian" class="w-full h-full object-cover object-center block" style="width: 100%; height: 115px; min-height: 115px; max-height: 115px; object-fit: cover; object-position: center; display: block;">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center" style="position: absolute; inset: 0;">
                                        <label for="doc_death_statement" class="cursor-pointer bg-white text-slate-800 text-[11px] font-bold px-3 py-1.5 rounded-md shadow-sm hover:bg-slate-100">
                                            <i class="fa-solid fa-arrow-rotate-right"></i> Ganti Berkas
                                        </label>
                                    </div>
                                </div>
                                <div id="pdf-preview-wrap-death_statement" class="w-full bg-rose-50 rounded-lg flex flex-col items-center justify-center border border-rose-200 text-rose-700 hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%;">
                                    <i class="fa-solid fa-file-pdf text-3xl mb-1.5"></i>
                                    <span class="text-[11px] font-bold uppercase tracking-wider">Dokumen PDF</span>
                                </div>
                                <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 shrink-0" style="height: 35px; min-height: 35px; max-height: 35px; box-sizing: border-box;">
                                    <div class="truncate flex-1 min-w-0">
                                        <p id="file-name-death_statement" class="text-xs font-bold text-slate-800 truncate"></p>
                                        <p id="file-size-death_statement" class="text-[10px] text-slate-500"></p>
                                    </div>
                                    <label for="doc_death_statement" class="shrink-0 text-[11px] font-bold text-rose-700 hover:text-rose-800 cursor-pointer bg-rose-50 px-2 py-1 rounded border border-rose-200 transition">
                                        <i class="fa-solid fa-pen-to-square"></i> Ganti
                                    </label>
                                </div>
                            </div>

                            @error('doc_death_statement') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- 2. Kartu Keluarga (KK) Almarhum/ah -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-between hover:border-rose-600 transition h-full" style="min-height: 270px;">
                        <div class="h-14 mb-2 flex flex-col justify-start shrink-0" style="height: 48px; min-height: 48px;">
                            <label class="block text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-users text-rose-700"></i> 2. Kartu Keluarga (KK) <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-slate-500 leading-tight">Foto / Scan Kartu Keluarga asli almarhum/ah</p>
                        </div>

                        <div class="mt-auto w-full">
                            <input type="file" id="doc_family_card" name="doc_family_card" accept=".jpg,.jpeg,.png,.pdf" required class="hidden file-input" data-card="family_card">
                            
                            <!-- Box Upload Standar -->
                            <label for="doc_family_card" id="placeholder-family_card" class="cursor-pointer flex flex-col items-center justify-center text-center bg-white hover:bg-rose-50/50 text-rose-700 border-2 border-dashed border-rose-300 hover:border-rose-600 rounded-xl w-full p-3 transition shadow-2xs" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box;">
                                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-700 flex items-center justify-center mb-2 border border-rose-200">
                                    <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                </div>
                                <span class="block text-xs font-bold">Pilih Berkas KK</span>
                                <span class="block text-[10px] text-slate-400 mt-1">JPG, PNG, PDF (Maks. 3MB)</span>
                            </label>

                            <!-- Box Preview Dokumen (Ukuran Fixed) -->
                            <div id="preview-box-family_card" class="hidden bg-white border border-rose-200 rounded-xl p-2.5 shadow-xs w-full flex flex-col justify-between" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box; overflow: hidden;">
                                <div id="img-preview-wrap-family_card" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%; position: relative; overflow: hidden;">
                                    <img id="img-preview-family_card" src="" alt="Preview Kartu Keluarga" class="w-full h-full object-cover object-center block" style="width: 100%; height: 115px; min-height: 115px; max-height: 115px; object-fit: cover; object-position: center; display: block;">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center" style="position: absolute; inset: 0;">
                                        <label for="doc_family_card" class="cursor-pointer bg-white text-slate-800 text-[11px] font-bold px-3 py-1.5 rounded-md shadow-sm hover:bg-slate-100">
                                            <i class="fa-solid fa-arrow-rotate-right"></i> Ganti Berkas
                                        </label>
                                    </div>
                                </div>
                                <div id="pdf-preview-wrap-family_card" class="w-full bg-rose-50 rounded-lg flex flex-col items-center justify-center border border-rose-200 text-rose-700 hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%;">
                                    <i class="fa-solid fa-file-pdf text-3xl mb-1.5"></i>
                                    <span class="text-[11px] font-bold uppercase tracking-wider">Dokumen PDF</span>
                                </div>
                                <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 shrink-0" style="height: 35px; min-height: 35px; max-height: 35px; box-sizing: border-box;">
                                    <div class="truncate flex-1 min-w-0">
                                        <p id="file-name-family_card" class="text-xs font-bold text-slate-800 truncate"></p>
                                        <p id="file-size-family_card" class="text-[10px] text-slate-500"></p>
                                    </div>
                                    <label for="doc_family_card" class="shrink-0 text-[11px] font-bold text-rose-700 hover:text-rose-800 cursor-pointer bg-rose-50 px-2 py-1 rounded border border-rose-200 transition">
                                        <i class="fa-solid fa-pen-to-square"></i> Ganti
                                    </label>
                                </div>
                            </div>

                            @error('doc_family_card') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- 3. KTP-el Asli Almarhum/ah -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-between hover:border-rose-600 transition h-full" style="min-height: 270px;">
                        <div class="h-14 mb-2 flex flex-col justify-start shrink-0" style="height: 48px; min-height: 48px;">
                            <label class="block text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-id-card text-rose-700"></i> 3. KTP Almarhum/ah <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-slate-500 leading-tight">Foto / Scan KTP-el asli almarhum/ah</p>
                        </div>

                        <div class="mt-auto w-full">
                            <input type="file" id="doc_deceased_ktp" name="doc_deceased_ktp" accept=".jpg,.jpeg,.png,.pdf" required class="hidden file-input" data-card="deceased_ktp">
                            
                            <!-- Box Upload Standar -->
                            <label for="doc_deceased_ktp" id="placeholder-deceased_ktp" class="cursor-pointer flex flex-col items-center justify-center text-center bg-white hover:bg-rose-50/50 text-rose-700 border-2 border-dashed border-rose-300 hover:border-rose-600 rounded-xl w-full p-3 transition shadow-2xs" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box;">
                                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-700 flex items-center justify-center mb-2 border border-rose-200">
                                    <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                </div>
                                <span class="block text-xs font-bold">Pilih KTP Almarhum/ah</span>
                                <span class="block text-[10px] text-slate-400 mt-1">JPG, PNG, PDF (Maks. 3MB)</span>
                            </label>

                            <!-- Box Preview Dokumen (Ukuran Fixed) -->
                            <div id="preview-box-deceased_ktp" class="hidden bg-white border border-rose-200 rounded-xl p-2.5 shadow-xs w-full flex flex-col justify-between" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box; overflow: hidden;">
                                <div id="img-preview-wrap-deceased_ktp" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%; position: relative; overflow: hidden;">
                                    <img id="img-preview-deceased_ktp" src="" alt="Preview KTP Almarhum/ah" class="w-full h-full object-cover object-center block" style="width: 100%; height: 115px; min-height: 115px; max-height: 115px; object-fit: cover; object-position: center; display: block;">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center" style="position: absolute; inset: 0;">
                                        <label for="doc_deceased_ktp" class="cursor-pointer bg-white text-slate-800 text-[11px] font-bold px-3 py-1.5 rounded-md shadow-sm hover:bg-slate-100">
                                            <i class="fa-solid fa-arrow-rotate-right"></i> Ganti Berkas
                                        </label>
                                    </div>
                                </div>
                                <div id="pdf-preview-wrap-deceased_ktp" class="w-full bg-rose-50 rounded-lg flex flex-col items-center justify-center border border-rose-200 text-rose-700 hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%;">
                                    <i class="fa-solid fa-file-pdf text-3xl mb-1.5"></i>
                                    <span class="text-[11px] font-bold uppercase tracking-wider">Dokumen PDF</span>
                                </div>
                                <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 shrink-0" style="height: 35px; min-height: 35px; max-height: 35px; box-sizing: border-box;">
                                    <div class="truncate flex-1 min-w-0">
                                        <p id="file-name-deceased_ktp" class="text-xs font-bold text-slate-800 truncate"></p>
                                        <p id="file-size-deceased_ktp" class="text-[10px] text-slate-500"></p>
                                    </div>
                                    <label for="doc_deceased_ktp" class="shrink-0 text-[11px] font-bold text-rose-700 hover:text-rose-800 cursor-pointer bg-rose-50 px-2 py-1 rounded border border-rose-200 transition">
                                        <i class="fa-solid fa-pen-to-square"></i> Ganti
                                    </label>
                                </div>
                            </div>

                            @error('doc_deceased_ktp') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- 4. KTP-el Pelapor / Ahli Waris -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-between hover:border-rose-600 transition h-full" style="min-height: 270px;">
                        <div class="h-14 mb-2 flex flex-col justify-start shrink-0" style="height: 48px; min-height: 48px;">
                            <label class="block text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-address-card text-rose-700"></i> 4. KTP Pelapor <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-slate-500 leading-tight">Foto / Scan KTP-el pelapor atau ahli waris</p>
                        </div>

                        <div class="mt-auto w-full">
                            <input type="file" id="doc_applicant_ktp" name="doc_applicant_ktp" accept=".jpg,.jpeg,.png,.pdf" required class="hidden file-input" data-card="applicant_ktp">
                            
                            <!-- Box Upload Standar -->
                            <label for="doc_applicant_ktp" id="placeholder-applicant_ktp" class="cursor-pointer flex flex-col items-center justify-center text-center bg-white hover:bg-rose-50/50 text-rose-700 border-2 border-dashed border-rose-300 hover:border-rose-600 rounded-xl w-full p-3 transition shadow-2xs" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box;">
                                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-700 flex items-center justify-center mb-2 border border-rose-200">
                                    <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                </div>
                                <span class="block text-xs font-bold">Pilih KTP Pelapor</span>
                                <span class="block text-[10px] text-slate-400 mt-1">JPG, PNG, PDF (Maks. 3MB)</span>
                            </label>

                            <!-- Box Preview Dokumen (Ukuran Fixed) -->
                            <div id="preview-box-applicant_ktp" class="hidden bg-white border border-rose-200 rounded-xl p-2.5 shadow-xs w-full flex flex-col justify-between" style="height: 175px; min-height: 175px; max-height: 175px; box-sizing: border-box; overflow: hidden;">
                                <div id="img-preview-wrap-applicant_ktp" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%; position: relative; overflow: hidden;">
                                    <img id="img-preview-applicant_ktp" src="" alt="Preview KTP Pelapor" class="w-full h-full object-cover object-center block" style="width: 100%; height: 115px; min-height: 115px; max-height: 115px; object-fit: cover; object-position: center; display: block;">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center" style="position: absolute; inset: 0;">
                                        <label for="doc_applicant_ktp" class="cursor-pointer bg-white text-slate-800 text-[11px] font-bold px-3 py-1.5 rounded-md shadow-sm hover:bg-slate-100">
                                            <i class="fa-solid fa-arrow-rotate-right"></i> Ganti Berkas
                                        </label>
                                    </div>
                                </div>
                                <div id="pdf-preview-wrap-applicant_ktp" class="w-full bg-rose-50 rounded-lg flex flex-col items-center justify-center border border-rose-200 text-rose-700 hidden shrink-0" style="height: 115px; min-height: 115px; max-height: 115px; width: 100%;">
                                    <i class="fa-solid fa-file-pdf text-3xl mb-1.5"></i>
                                    <span class="text-[11px] font-bold uppercase tracking-wider">Dokumen PDF</span>
                                </div>
                                <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 shrink-0" style="height: 35px; min-height: 35px; max-height: 35px; box-sizing: border-box;">
                                    <div class="truncate flex-1 min-w-0">
                                        <p id="file-name-applicant_ktp" class="text-xs font-bold text-slate-800 truncate"></p>
                                        <p id="file-size-applicant_ktp" class="text-[10px] text-slate-500"></p>
                                    </div>
                                    <label for="doc_applicant_ktp" class="shrink-0 text-[11px] font-bold text-rose-700 hover:text-rose-800 cursor-pointer bg-rose-50 px-2 py-1 rounded border border-rose-200 transition">
                                        <i class="fa-solid fa-pen-to-square"></i> Ganti
                                    </label>
                                </div>
                            </div>

                            @error('doc_applicant_ktp') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                </div>
            </div>

            <!-- Pernyataan & Tombol Submit -->
            <div class="pt-4 border-t border-slate-200 space-y-4">
                <label class="flex items-start gap-2.5 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" required class="mt-0.5 rounded text-rose-700 focus:ring-rose-700">
                    <span>Saya menyatakan dengan sesungguhnya bahwa data dan peristiwa kematian yang saya laporkan adalah benar sesuai kejadian yang sebenarnya.</span>
                </label>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Beranda
                    </a>
                    <button type="submit" class="bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs md:text-sm px-6 py-3 rounded-lg shadow-sm transition">
                        Kirim Permohonan Akte Kematian
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
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

        if (file.size > 3 * 1024 * 1024) {
            alert('Ukuran berkas ' + file.name + ' melebihi batas maksimal 3MB. Silakan pilih berkas lain.');
            input.value = '';
            return;
        }

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
    }

    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                const file = this.files[0];
                updateFilePreview(this, file);
            }
        });
    });
});
</script>
@endsection
