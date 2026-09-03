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

                <form action="{{ route('admin.birth.update_status', $birth) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Ubah Status Permohonan</label>
                        <select name="status" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] font-medium">
                            <option value="pending" {{ $birth->status === 'pending' ? 'selected' : '' }}>1. Menunggu Verifikasi</option>
                            <option value="in_process" {{ ($birth->status === 'in_process' || $birth->status === 'verified') ? 'selected' : '' }}>2. Sedang Diproses</option>
                            <option value="revision" {{ $birth->status === 'revision' ? 'selected' : '' }}>3. Revisi Berkas</option>
                            <option value="rejected" {{ $birth->status === 'rejected' ? 'selected' : '' }}>4. Dibatalkan</option>
                            <option value="ready_for_pickup" {{ ($birth->status === 'ready_for_pickup' || $birth->status === 'completed') ? 'selected' : '' }}>5. Siap diambil</option>
                            <option value="picked_up" {{ ($birth->status === 'picked_up' || $birth->status === 'archived') ? 'selected' : '' }}>6. Sudah diambil (Masuk Arsip)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Catatan Verifikator / Alasan <span class="text-rose-600 font-bold">* (Wajib diisi)</span>
                        </label>
                        <textarea name="rejection_note" rows="3" required placeholder="Tuliskan catatan verifikasi, instruksi pengambilan berkas, atau alasan perubahan status..." class="w-full text-xs px-3 py-2 rounded-lg border @error('rejection_note') border-rose-500 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-[#0b7c89]">{{ old('rejection_note', $birth->rejection_note) }}</textarea>
                        @error('rejection_note')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="text-[11px] text-slate-500">
                        <p>Petugas Verifikator: <strong>{{ Auth::user()->name }}</strong></p>
                    </div>

                    <button type="submit" class="w-full bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs py-2.5 rounded-lg shadow-sm transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Status
                    </button>
                </form>

                @if(!$birth->is_archived && ($birth->status === 'rejected' || $birth->status === 'picked_up'))
                    <form action="{{ route('admin.archive.birth.archive', $birth) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENGARSIPKAN pengajuan akte kelahiran ini secara manual?');" class="btn-archive w-full bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs py-2.5 rounded-lg shadow-xs transition flex items-center justify-center gap-1.5" style="background-color: #334155; color: #ffffff;">
                            <i class="fa-solid fa-box-archive text-amber-300"></i> Arsipkan Pengajuan
                        </button>
                    </form>
                @elseif($birth->is_archived)
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-slate-500 text-[11px] mt-3">
                        <i class="fa-solid fa-circle-info text-[#0b7c89] mr-1"></i> Pengajuan ini sudah berada di dalam daftar arsip.
                    </div>
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
                <div><span class="text-slate-400 block text-[11px]">Kontak / WhatsApp:</span> <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $birth->applicant_phone) }}" target="_blank" class="text-[#0b7c89] font-bold hover:underline">{{ $birth->applicant_phone }}</a></div>
                <div><span class="text-slate-400 block text-[11px]">Wilayah Padukuhan:</span> <span>Padukuhan {{ $birth->padukuhan }}, RT {{ $birth->rt }} / RW {{ $birth->rw }}</span></div>
            </div>

        </div>

    </div>

</div>
@endsection
