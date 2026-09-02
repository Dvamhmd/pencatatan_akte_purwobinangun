@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - Akte Kematian')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8 text-center max-w-2xl mx-auto">
        <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-circle-check text-3xl"></i>
        </div>

        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-3 py-1 rounded-full">
            Pengajuan Berhasil Dikirim
        </span>

        <h2 class="text-xl md:text-2xl font-extrabold text-slate-800 mt-3">
            Permohonan Akte Kematian Diterima
        </h2>
        
        <p class="text-xs text-slate-600 mt-2 max-w-md mx-auto leading-relaxed">
            Terima kasih, permohonan penerbitan surat pengantar pelaporan Akte Kematian telah tercatat di sistem Kalurahan Purwobinangun.
        </p>

        <!-- Nomor Registrasi Box -->
        <div class="mt-6 p-4 bg-rose-50 border-2 border-dashed border-rose-600 rounded-xl">
            <p class="text-xs text-rose-800 font-semibold uppercase">Nomor Registrasi Permohonan</p>
            <p class="text-2xl md:text-3xl font-extrabold text-rose-700 tracking-wider my-1">{{ $death->registration_no }}</p>
            <p class="text-[11px] text-rose-600">Simpan nomor ini untuk melacak status atau mencetak tanda terima permohonan Anda.</p>
        </div>

        <!-- Ringkasan Data -->
        <div class="mt-6 text-left bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs space-y-2">
            <div class="flex justify-between border-b border-slate-200 pb-1.5">
                <span class="text-slate-500">Nama Almarhum/ah:</span>
                <span class="font-bold text-slate-800">{{ $death->deceased_name }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1.5">
                <span class="text-slate-500">Tanggal Meninggal:</span>
                <span class="font-medium text-slate-800">{{ $death->death_date->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1.5">
                <span class="text-slate-500">Nama Pelapor:</span>
                <span class="font-medium text-slate-800">{{ $death->applicant_name }} ({{ $death->applicant_relation }})</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1.5">
                <span class="text-slate-500">Padukuhan:</span>
                <span class="font-medium text-slate-800">Padukuhan {{ $death->padukuhan }}, RT {{ $death->rt }} / RW {{ $death->rw }}</span>
            </div>
            <div class="flex justify-between pt-1">
                <span class="text-slate-500">Status Saat Ini:</span>
                <span class="font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded text-[11px]">{{ $death->status_label }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('tracking.print_receipt', ['type' => 'death', 'registrationNo' => $death->registration_no]) }}" target="_blank" class="w-full sm:w-auto bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs px-5 py-2.5 rounded-lg shadow-sm transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak Tanda Terima / Resi
            </a>
            <a href="{{ route('tracking.show', ['type' => 'death', 'registrationNo' => $death->registration_no]) }}" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs px-5 py-2.5 rounded-lg transition flex items-center justify-center gap-2 border border-slate-300">
                <i class="fa-solid fa-list-check"></i> Cek Status Permohonan
            </a>
        </div>
    </div>

</div>
@endsection
