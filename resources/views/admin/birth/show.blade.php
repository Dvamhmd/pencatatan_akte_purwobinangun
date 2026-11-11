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
                    <div><span class="text-slate-500 block text-[11px]">1. Nama Lengkap:</span> <strong class="text-slate-900 text-sm">{{ $birth->child_name }}</strong></div>
                    <div><span class="text-slate-500 block text-[11px]">2. NIK Bayi / Anak:</span> <span class="font-mono font-bold text-slate-800">{{ $birth->child_nik ?? '-' }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">3. Jenis Kelamin:</span> <span class="font-medium text-slate-800">{{ $birth->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">4. Tempat Dilahirkan:</span> <span class="font-medium text-slate-800">{{ $birth->birth_place_type ?? '-' }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">5. Tempat Kelahiran:</span> <span class="font-medium text-slate-800">{{ $birth->birth_place }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">6. Tanggal Lahir:</span> <span class="font-medium text-slate-800">{{ $birth->birth_day ? $birth->birth_day . ', ' : '' }}{{ $birth->birth_date->translatedFormat('d F Y') }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">7. Waktu / Pukul Lahir:</span> <span class="font-medium text-slate-800">{{ $birth->birth_time ?? '-' }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">8. Jenis & 9. Anak Ke-:</span> <span class="font-medium text-slate-800">{{ $birth->birth_type }} (Anak ke-{{ $birth->birth_order }})</span></div>
                    <div><span class="text-slate-500 block text-[11px]">10. Penolong Kelahiran:</span> <span class="font-medium text-slate-800">{{ $birth->birth_helper }}</span></div>
                    <div><span class="text-slate-500 block text-[11px]">11. Berat / 12. Panjang Badan:</span> <span class="font-medium text-slate-800">{{ $birth->weight_gram ? $birth->weight_gram . ' gram (' . $birth->weight_kg . ' kg)' : ($birth->weight_kg ? $birth->weight_kg . ' kg' : '-') }} / {{ $birth->length_cm ?? '-' }} cm</span></div>
                </div>
            </div>

            <!-- 2. Data Orang Tua -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b7c89] pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-users text-base"></i> 2. Data Orang Tua
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <!-- Ibu -->
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200/70 space-y-2">
                        <p class="font-bold text-pink-700 pb-1 border-b border-pink-100 flex items-center justify-between">
                            <span><i class="fa-solid fa-user-nurse mr-1"></i> Ibu Kandung</span>
                            <span class="text-[10px] bg-pink-100 text-pink-700 px-2 py-0.5 rounded font-normal">Bagian IBU</span>
                        </p>
                        <div><span class="text-slate-500 text-[11px] block">1. NIK Ibu:</span> <span class="font-mono font-bold">{{ $birth->mother_nik }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">2. Nama Lengkap:</span> <span class="font-medium">{{ $birth->mother_name }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">3. Tgl Lahir / Umur:</span> <span class="font-medium">{{ $birth->mother_birth_date ? $birth->mother_birth_date->translatedFormat('d F Y') : '-' }} {{ $birth->mother_age ? '(' . $birth->mother_age . ' tahun)' : '' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">4. Pekerjaan:</span> <span class="font-medium">{{ $birth->mother_job ?? '-' }}</span></div>
                        <div class="pt-1 border-t border-slate-200/60">
                            <span class="text-slate-500 text-[11px] block">5. Alamat Domisili:</span>
                            <span class="font-medium block">
                                {{ $birth->mother_address ?: '-' }}
                                @if($birth->mother_rt || $birth->mother_rw)
                                    (RT {{ $birth->mother_rt ?? '-' }} / RW {{ $birth->mother_rw ?? '-' }})
                                @endif
                                @if($birth->mother_village || $birth->mother_district)
                                    <br><span class="text-slate-600 text-[11px]">{{ $birth->mother_village ?? '' }}, {{ $birth->mother_district ?? '' }}, {{ $birth->mother_city ?? '' }} {{ $birth->mother_province ? '('.$birth->mother_province.')' : '' }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-200/60">
                            <div><span class="text-slate-500 text-[11px] block">6. Kewarganegaraan:</span> <span class="font-medium">{{ $birth->mother_citizenship ?? 'WNI' }} ({{ $birth->mother_nationality ?? 'INDONESIA' }})</span></div>
                            <div><span class="text-slate-500 text-[11px] block">8. Tgl Perkawinan:</span> <span class="font-medium">{{ $birth->marriage_date ? $birth->marriage_date->translatedFormat('d F Y') : '-' }}</span></div>
                        </div>
                    </div>

                    <!-- Ayah -->
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200/70 space-y-2">
                        <p class="font-bold text-blue-700 pb-1 border-b border-blue-100 flex items-center justify-between">
                            <span><i class="fa-solid fa-user-tie mr-1"></i> Ayah Kandung</span>
                            <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-normal">Bagian AYAH</span>
                        </p>
                        <div><span class="text-slate-500 text-[11px] block">1. NIK Ayah:</span> <span class="font-mono font-bold">{{ $birth->father_nik }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">2. Nama Lengkap:</span> <span class="font-medium">{{ $birth->father_name }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">3. Tgl Lahir / Umur:</span> <span class="font-medium">{{ $birth->father_birth_date ? $birth->father_birth_date->translatedFormat('d F Y') : '-' }} {{ $birth->father_age ? '(' . $birth->father_age . ' tahun)' : '' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">4. Pekerjaan:</span> <span class="font-medium">{{ $birth->father_job ?? '-' }}</span></div>
                        <div class="pt-1 border-t border-slate-200/60">
                            <span class="text-slate-500 text-[11px] block">5. Alamat Domisili:</span>
                            <span class="font-medium block">
                                {{ $birth->father_address ?: '-' }}
                                @if($birth->father_rt || $birth->father_rw)
                                    (RT {{ $birth->father_rt ?? '-' }} / RW {{ $birth->father_rw ?? '-' }})
                                @endif
                                @if($birth->father_village || $birth->father_district)
                                    <br><span class="text-slate-600 text-[11px]">{{ $birth->father_village ?? '' }}, {{ $birth->father_district ?? '' }}, {{ $birth->father_city ?? '' }} {{ $birth->father_province ? '('.$birth->father_province.')' : '' }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="pt-1 border-t border-slate-200/60">
                            <span class="text-slate-500 text-[11px] block">6. Kewarganegaraan:</span>
                            <span class="font-medium">{{ $birth->father_citizenship ?? 'WNI' }} ({{ $birth->father_nationality ?? 'INDONESIA' }})</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Data Saksi Kelahiran (Saksi 1 & Saksi 2) -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b7c89] pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-user-group text-base"></i> 3. Data Saksi Kelahiran
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <!-- Saksi 1 -->
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200/70 space-y-2">
                        <p class="font-bold text-teal-800 pb-1 border-b border-teal-100 flex items-center justify-between">
                            <span><i class="fa-solid fa-user mr-1 text-teal-600"></i> Saksi 1</span>
                            <span class="text-[10px] bg-teal-100 text-teal-800 px-2 py-0.5 rounded font-normal">Bagian SAKSI 1</span>
                        </p>
                        <div><span class="text-slate-500 text-[11px] block">1. NIK Saksi 1:</span> <span class="font-mono font-bold">{{ $birth->witness_1_nik ?: '-' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">2. Nama Lengkap:</span> <span class="font-medium">{{ $birth->witness_1_name ?: '-' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">3. Tgl Lahir / Umur:</span> <span class="font-medium">{{ $birth->witness_1_birth_date ? $birth->witness_1_birth_date->translatedFormat('d F Y') : '-' }} {{ $birth->witness_1_age ? '(' . $birth->witness_1_age . ' tahun)' : '' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">4. Jenis Kelamin:</span> <span class="font-medium">{{ $birth->witness_1_gender === 'L' ? 'Laki-laki' : ($birth->witness_1_gender === 'P' ? 'Perempuan' : '-') }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">5. Pekerjaan:</span> <span class="font-medium">{{ $birth->witness_1_job ?: '-' }}</span></div>
                        <div class="pt-1 border-t border-slate-200/60">
                            <span class="text-slate-500 text-[11px] block">6. Alamat Domisili:</span>
                            <span class="font-medium block">
                                {{ $birth->witness_1_address ?: '-' }}
                                @if($birth->witness_1_rt || $birth->witness_1_rw)
                                    (RT {{ $birth->witness_1_rt ?? '-' }} / RW {{ $birth->witness_1_rw ?? '-' }})
                                @endif
                                @if($birth->witness_1_village || $birth->witness_1_district)
                                    <br><span class="text-slate-600 text-[11px]">{{ $birth->witness_1_village ?? '' }}, {{ $birth->witness_1_district ?? '' }}, {{ $birth->witness_1_city ?? '' }} {{ $birth->witness_1_province ? '('.$birth->witness_1_province.')' : '' }}</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Saksi 2 -->
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200/70 space-y-2">
                        <p class="font-bold text-teal-800 pb-1 border-b border-teal-100 flex items-center justify-between">
                            <span><i class="fa-solid fa-user mr-1 text-teal-600"></i> Saksi 2</span>
                            <span class="text-[10px] bg-teal-100 text-teal-800 px-2 py-0.5 rounded font-normal">Bagian SAKSI 2</span>
                        </p>
                        <div><span class="text-slate-500 text-[11px] block">1. NIK Saksi 2:</span> <span class="font-mono font-bold">{{ $birth->witness_2_nik ?: '-' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">2. Nama Lengkap:</span> <span class="font-medium">{{ $birth->witness_2_name ?: '-' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">3. Tgl Lahir / Umur:</span> <span class="font-medium">{{ $birth->witness_2_birth_date ? $birth->witness_2_birth_date->translatedFormat('d F Y') : '-' }} {{ $birth->witness_2_age ? '(' . $birth->witness_2_age . ' tahun)' : '' }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">4. Jenis Kelamin:</span> <span class="font-medium">{{ $birth->witness_2_gender === 'L' ? 'Laki-laki' : ($birth->witness_2_gender === 'P' ? 'Perempuan' : '-') }}</span></div>
                        <div><span class="text-slate-500 text-[11px] block">5. Pekerjaan:</span> <span class="font-medium">{{ $birth->witness_2_job ?: '-' }}</span></div>
                        <div class="pt-1 border-t border-slate-200/60">
                            <span class="text-slate-500 text-[11px] block">6. Alamat Domisili:</span>
                            <span class="font-medium block">
                                {{ $birth->witness_2_address ?: '-' }}
                                @if($birth->witness_2_rt || $birth->witness_2_rw)
                                    (RT {{ $birth->witness_2_rt ?? '-' }} / RW {{ $birth->witness_2_rw ?? '-' }})
                                @endif
                                @if($birth->witness_2_village || $birth->witness_2_district)
                                    <br><span class="text-slate-600 text-[11px]">{{ $birth->witness_2_village ?? '' }}, {{ $birth->witness_2_district ?? '' }}, {{ $birth->witness_2_city ?? '' }} {{ $birth->witness_2_province ? '('.$birth->witness_2_province.')' : '' }}</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Berkas Dokumen Terunggah -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b7c89] pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-folder-open text-base"></i> 4. Berkas Lampiran Pendukung
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    
                    @php
                        $docs = [
                            'Surat Keterangan Lahir (RS/Bidan)' => $birth->doc_birth_cert,
                            'Kartu Keluarga (KK)' => $birth->doc_family_card,
                            'Buku Nikah / Akta Perkawinan' => $birth->doc_marriage_cert,
                            'KTP Kedua Orang Tua' => $birth->doc_parents_ktp,
                            'Surat Pengantar RT/RW' => $birth->doc_witness_ktp,
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
                <div><span class="text-slate-400 block text-[11px]">No. HP / Telepon:</span> <span class="font-bold text-slate-800">{{ $birth->applicant_phone }}</span></div>
                <div><span class="text-slate-400 block text-[11px]">Wilayah Padukuhan:</span> <span>Padukuhan {{ $birth->padukuhan }}, RT {{ $birth->rt }} / RW {{ $birth->rw }}</span></div>
                
                <!-- Tanda Tangan Digital Pemohon -->
                <div class="pt-2.5 border-t border-slate-100">
                    <span class="text-slate-400 block text-[11px] mb-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-signature text-[#0b7c89]"></i> Tanda Tangan Digital Pemohon:
                    </span>
                    @if($birth->applicant_signature)
                        <div class="p-2 bg-slate-50 rounded-lg border border-slate-200 inline-block max-w-full">
                            <img src="{{ asset('storage/' . $birth->applicant_signature) }}" alt="Tanda Tangan Pemohon" class="h-16 max-w-full object-contain mx-auto bg-white rounded p-1 border border-slate-200/60 shadow-2xs">
                        </div>
                    @else
                        <span class="text-slate-400 italic text-[11px]">Belum dibubuhkan</span>
                    @endif
                </div>
            </div>

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

    if (!statusForm || !statusSelect || !rejectionNote) return;

    const initialStatus = statusSelect.value;
    const initialNote = @json((string) $birth->rejection_note).trim();

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

    statusSelect.addEventListener('change', validateNoteChange);
    rejectionNote.addEventListener('input', validateNoteChange);

    if (openModalSubmitBtn) {
        openModalSubmitBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const currentStatus = statusSelect.value;
            const currentNote = rejectionNote.value.trim();
            const isStatusChanged = (currentStatus !== initialStatus);
            const isNoteUnchanged = (initialNote !== '' && currentNote === initialNote);

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

            if (currentNote.length < 3) {
                rejectionNote.focus();
                rejectionNote.reportValidity();
                return;
            }

            openModalSubmitBtn.disabled = true;
            openModalSubmitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...';
            statusForm.submit();
        });
    }
});
</script>
@endsection
