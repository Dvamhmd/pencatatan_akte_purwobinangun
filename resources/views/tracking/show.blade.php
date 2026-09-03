@extends('layouts.app')

@section('title', 'Status Permohonan - ' . $data->registration_no)

@section('content')
<div class="space-y-6">

    <!-- Card Container -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-[#0b7c89] text-white px-4 py-3 flex items-center justify-between">
            <h2 class="font-bold text-xs md:text-sm tracking-wide uppercase flex items-center gap-2">
                <i class="fa-solid fa-timeline text-amber-300"></i> DETAIL & STATUS PERMOHONAN
            </h2>
            <span class="text-[11px] bg-teal-800/80 px-2 py-0.5 rounded text-teal-100 font-medium">Kalurahan Purwobinangun</span>
        </div>

        <div class="p-5 md:p-6 space-y-6">
            
            <!-- Header Permohonan -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded {{ $type === 'birth' ? 'bg-teal-100 text-[#0b7c89]' : 'bg-rose-100 text-rose-700' }}">
                        {{ $type === 'birth' ? 'Permohonan Akte Kelahiran' : 'Permohonan Akte Kematian' }}
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-800 mt-1">{{ $data->registration_no }}</h3>
                    <p class="text-xs text-slate-500">Diajukan pada {{ $data->created_at->translatedFormat('l, d F Y - H:i') }} WIB</p>
                </div>
                <div class="flex flex-col items-start sm:items-end gap-1">
                    <span class="text-[11px] text-slate-400">Status Permohonan:</span>
                    <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $data->status_badge_class }}">
                        {{ $data->status_label }}
                    </span>
                </div>
            </div>

            <!-- Timeline Visual Stepper -->
            <div class="p-5 bg-white rounded-xl border border-slate-200">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-[#0b7c89]"></i> Tahapan Proses Pelayanan
                </h4>

                @php
                    $steps = [
                        'pending' => ['title' => 'Menunggu Verifikasi', 'desc' => 'Dokumen berhasil dikirim dan masuk antrean verifikasi.'],
                        'in_process' => ['title' => 'Sedang Diproses', 'desc' => 'Petugas Kalurahan sedang memproses dan menyiapkan dokumen.'],
                        'ready_for_pickup' => ['title' => 'Siap Diambil', 'desc' => 'Akte / Surat pengantar siap diambil di Kantor Kalurahan.'],
                        'picked_up' => ['title' => 'Sudah Diambil', 'desc' => 'Dokumen telah selesai dan diserahkan kepada pemohon.'],
                    ];

                    $statusOrder = [
                        'pending' => 1,
                        'verified' => 2,
                        'in_process' => 2,
                        'ready_for_pickup' => 3,
                        'completed' => 3,
                        'picked_up' => 4,
                        'archived' => 4,
                        'revision' => 0,
                        'rejected' => 0
                    ];
                    $currentOrder = $statusOrder[$data->status] ?? 1;
                @endphp

                @if($data->status === 'rejected')
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl mb-4 text-xs text-rose-800 flex items-start gap-3">
                        <i class="fa-solid fa-circle-xmark text-rose-600 text-xl mt-0.5"></i>
                        <div>
                            <h5 class="font-bold text-sm text-rose-900">Permohonan {{ $data->status_label }}</h5>
                            <p class="mt-1 text-rose-700">{{ $data->rejection_note ?? 'Silakan lengkapi kembali dokumen persyaratan Anda dan ajukan ulang.' }}</p>
                        </div>
                    </div>
                @elseif($data->status === 'revision')
                    <div class="p-4 bg-orange-50 border border-orange-200 rounded-xl mb-4 text-xs text-orange-800 flex items-start gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-orange-600 text-xl mt-0.5"></i>
                        <div>
                            <h5 class="font-bold text-sm text-orange-900">Permohonan Perlu Revisi Berkas</h5>
                            <p class="mt-1 text-orange-700">{{ $data->rejection_note ?? 'Terdapat berkas yang perlu diperbaiki atau dilengkapi sesuai catatan petugas.' }}</p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    @foreach($steps as $key => $step)
                        @php
                            $stepNum = $statusOrder[$key];
                            $isPassed = $currentOrder >= $stepNum && !in_array($data->status, ['rejected', 'revision']);
                            $isCurrent = $currentOrder === $stepNum && !in_array($data->status, ['rejected', 'revision']);
                        @endphp
                        <div class="p-3 rounded-lg border {{ $isPassed ? 'bg-teal-50/70 border-[#0b7c89]' : 'bg-slate-50 border-slate-200 opacity-60' }} relative">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center {{ $isPassed ? 'bg-[#0b7c89] text-white' : 'bg-slate-300 text-slate-700' }}">
                                    @if($isPassed)
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    @else
                                        {{ $stepNum }}
                                    @endif
                                </span>
                                <h5 class="font-bold text-xs {{ $isPassed ? 'text-[#0b7c89]' : 'text-slate-700' }}">
                                    {{ $step['title'] }}
                                </h5>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Rincian Data Permohonan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom 1 -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-2.5">
                    <h4 class="font-bold text-slate-800 border-b border-slate-200 pb-1.5 flex items-center gap-2 text-teal-800">
                        <i class="fa-solid fa-id-card"></i> Informasi Yang Diajukan
                    </h4>
                    @if($type === 'birth')
                        <div class="flex justify-between"><span class="text-slate-500">Nama Bayi:</span> <span class="font-bold text-slate-800">{{ $data->child_name }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Jenis Kelamin:</span> <span class="font-medium text-slate-800">{{ $data->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Tempat, Tgl Lahir:</span> <span class="font-medium text-slate-800">{{ $data->birth_place }}, {{ $data->birth_date->translatedFormat('d F Y') }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Anak Ke-:</span> <span class="font-medium text-slate-800">{{ $data->birth_order }} ({{ $data->birth_type }})</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Nama Orang Tua:</span> <span class="font-medium text-slate-800">{{ $data->father_name }} & {{ $data->mother_name }}</span></div>
                    @else
                        <div class="flex justify-between"><span class="text-slate-500">Nama Almarhum/ah:</span> <span class="font-bold text-slate-800">{{ $data->deceased_name }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">NIK Almarhum/ah:</span> <span class="font-medium text-slate-800">{{ $data->deceased_nik }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Jenis Kelamin:</span> <span class="font-medium text-slate-800">{{ $data->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Waktu Kematian:</span> <span class="font-medium text-slate-800">{{ $data->death_date->translatedFormat('d F Y') }} ({{ $data->death_time ?? '-' }})</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Tempat Kematian:</span> <span class="font-medium text-slate-800">{{ $data->death_place }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Sebab Kematian:</span> <span class="font-medium text-slate-800">{{ $data->cause_of_death }}</span></div>
                    @endif
                </div>

                <!-- Kolom 2 -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-2.5">
                    <h4 class="font-bold text-slate-800 border-b border-slate-200 pb-1.5 flex items-center gap-2 text-teal-800">
                        <i class="fa-solid fa-user-check"></i> Informasi Pemohon & Domisili
                    </h4>
                    <div class="flex justify-between"><span class="text-slate-500">Nama Pemohon:</span> <span class="font-bold text-slate-800">{{ $data->applicant_name }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">NIK Pemohon:</span> <span class="font-medium text-slate-800">{{ $data->applicant_nik }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Hubungan:</span> <span class="font-medium text-slate-800">{{ $data->applicant_relation }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Kontak HP / WA:</span> <span class="font-medium text-slate-800">{{ $data->applicant_phone }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Padukuhan:</span> <span class="font-medium text-slate-800">Padukuhan {{ $data->padukuhan }}, RT {{ $data->rt }} / RW {{ $data->rw }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Verifikator:</span> <span class="font-medium text-slate-800">{{ $data->processed_by ?? 'Menunggu Petugas' }}</span></div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('tracking.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Lacak Nomor Lain
                </a>
                <a href="{{ route('tracking.print_receipt', ['type' => $type, 'registrationNo' => $data->registration_no]) }}" target="_blank" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-5 py-2.5 rounded-lg shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Cetak Tanda Terima Permohonan
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
