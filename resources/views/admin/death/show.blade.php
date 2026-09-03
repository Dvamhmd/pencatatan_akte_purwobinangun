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

                <form action="{{ route('admin.death.update_status', $death) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Ubah Status Permohonan</label>
                        <select name="status" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-600 font-medium">
                            <option value="pending" {{ $death->status === 'pending' ? 'selected' : '' }}>1. Menunggu Verifikasi</option>
                            <option value="in_process" {{ ($death->status === 'in_process' || $death->status === 'verified') ? 'selected' : '' }}>2. Sedang Diproses</option>
                            <option value="revision" {{ $death->status === 'revision' ? 'selected' : '' }}>3. Revisi Berkas</option>
                            <option value="rejected" {{ $death->status === 'rejected' ? 'selected' : '' }}>4. Dibatalkan</option>
                            <option value="ready_for_pickup" {{ ($death->status === 'ready_for_pickup' || $death->status === 'completed') ? 'selected' : '' }}>5. Siap Diambil</option>
                            <option value="picked_up" {{ ($death->status === 'picked_up' || $death->status === 'archived') ? 'selected' : '' }}>6. Sudah Diambil (Masuk Arsip)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Catatan Verifikator / Alasan <span class="text-rose-600 font-bold">* (Wajib diisi)</span>
                        </label>
                        <textarea name="rejection_note" rows="3" required placeholder="Tuliskan catatan verifikasi, instruksi pengambilan berkas, atau alasan perubahan status..." class="w-full text-xs px-3 py-2 rounded-lg border @error('rejection_note') border-rose-500 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-rose-600">{{ old('rejection_note', $death->rejection_note) }}</textarea>
                        @error('rejection_note')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="text-[11px] text-slate-500">
                        <p>Petugas Verifikator: <strong>{{ Auth::user()->name }}</strong></p>
                    </div>

                    <button type="submit" class="w-full bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs py-2.5 rounded-lg shadow-sm transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Status
                    </button>
                </form>

                @if(!$death->is_archived && ($death->status === 'rejected' || $death->status === 'picked_up'))
                    <form action="{{ route('admin.archive.death.archive', $death) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENGARSIPKAN pengajuan akte kematian ini secara manual?');" class="btn-archive w-full bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs py-2.5 rounded-lg shadow-xs transition flex items-center justify-center gap-1.5" style="background-color: #334155; color: #ffffff;">
                            <i class="fa-solid fa-box-archive text-amber-300"></i> Arsipkan Pengajuan
                        </button>
                    </form>
                @elseif($death->is_archived)
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-slate-500 text-[11px] mt-3">
                        <i class="fa-solid fa-circle-info text-rose-700 mr-1"></i> Pengajuan ini sudah berada di dalam daftar arsip.
                    </div>
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
@endsection
