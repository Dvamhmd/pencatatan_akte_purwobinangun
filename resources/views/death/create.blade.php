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
                <h3 class="text-xs font-bold uppercase tracking-wider text-rose-700 flex items-center gap-2 pb-2 border-b border-rose-100">
                    <span class="w-5 h-5 rounded-full bg-rose-700 text-white text-[10px] flex items-center justify-center">4</span>
                    Upload Berkas Persyaratan (Format JPG, PNG, atau PDF, Max 3MB)
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-xs font-semibold text-slate-800 mb-1">
                            1. Surat Keterangan Kematian (RS/Dokter/RT) <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" name="doc_death_statement" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-rose-700 file:text-white hover:file:bg-rose-800">
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-xs font-semibold text-slate-800 mb-1">
                            2. Kartu Keluarga (KK) Almarhum/ah <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" name="doc_family_card" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-rose-700 file:text-white hover:file:bg-rose-800">
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-xs font-semibold text-slate-800 mb-1">
                            3. KTP-el Asli Almarhum/ah <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" name="doc_deceased_ktp" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-rose-700 file:text-white hover:file:bg-rose-800">
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-xs font-semibold text-slate-800 mb-1">
                            4. KTP-el Pelapor / Ahli Waris <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" name="doc_applicant_ktp" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-rose-700 file:text-white hover:file:bg-rose-800">
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
                    <button type="submit" class="bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs md:text-sm px-6 py-3 rounded-lg shadow-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Permohonan Akte Kematian
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>
@endsection
