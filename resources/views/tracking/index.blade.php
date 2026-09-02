@extends('layouts.app')

@section('title', 'Lacak Status Permohonan')

@section('content')
<div class="space-y-6">

    <!-- Header Box -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-[#0b7c89] text-white px-4 py-2.5 flex items-center justify-between">
            <h2 class="font-bold text-xs md:text-sm tracking-wide uppercase flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-amber-300"></i> LACAK STATUS PERMOHONAN AKTA
            </h2>
            <span class="text-[11px] bg-teal-800/80 px-2 py-0.5 rounded text-teal-100 font-medium">Kalurahan Purwobinangun</span>
        </div>

        <div class="p-5 md:p-6 bg-slate-50 border-b border-slate-200/60">
            <p class="text-xs text-slate-600 leading-relaxed mb-4">
                Masukkan <strong>Nomor Registrasi</strong> (contoh: <code class="bg-slate-200 px-1.5 py-0.5 rounded text-slate-800">AKL-20260901-0001</code> atau <code class="bg-slate-200 px-1.5 py-0.5 rounded text-slate-800">AKM-20260901-0001</code>) atau <strong>NIK Pemohon / Orang Tua / Almarhum</strong>.
            </p>

            <form action="{{ route('tracking.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 max-w-xl">
                <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Masukkan Nomor Registrasi / NIK 16 Digit..." required class="w-full text-xs px-4 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] bg-white font-medium">
                <button type="submit" class="w-full sm:w-auto bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-6 py-2.5 rounded-lg shadow-sm transition flex items-center justify-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-search"></i> Cari Data
                </button>
            </form>
        </div>

        <!-- Result Section -->
        <div class="p-5 md:p-6">
            @if(!empty($keyword))
                @if($result)
                    <div class="bg-white border-2 border-teal-100 rounded-xl p-5 shadow-xs space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded {{ $type === 'birth' ? 'bg-teal-100 text-[#0b7c89]' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $type === 'birth' ? 'Permohonan Akte Kelahiran' : 'Permohonan Akte Kematian' }}
                                </span>
                                <h3 class="text-base font-extrabold text-slate-800 mt-1">
                                    {{ $result->registration_no }}
                                </h3>
                            </div>
                            <div>
                                <span class="inline-block text-xs font-bold px-3 py-1 rounded-full border {{ $result->status_badge_class }}">
                                    {{ $result->status_label }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-600">
                            <div>
                                <p class="text-slate-400 text-[11px]">Nama Yang Dimohonkan:</p>
                                <p class="font-bold text-slate-800 text-sm">
                                    {{ $type === 'birth' ? $result->child_name : $result->deceased_name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-slate-400 text-[11px]">Nama Pemohon / Pelapor:</p>
                                <p class="font-bold text-slate-800 text-sm">{{ $result->applicant_name }} ({{ $result->applicant_relation }})</p>
                            </div>
                            <div>
                                <p class="text-slate-400 text-[11px]">Domisili:</p>
                                <p class="font-medium text-slate-800">Padukuhan {{ $result->padukuhan }}, RT {{ $result->rt }} / RW {{ $result->rw }}</p>
                            </div>
                            <div>
                                <p class="text-slate-400 text-[11px]">Tanggal Pengajuan:</p>
                                <p class="font-medium text-slate-800">{{ $result->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>

                        @if($result->rejection_note && $result->status === 'rejected')
                            <div class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800">
                                <p class="font-bold mb-0.5"><i class="fa-solid fa-circle-exclamation mr-1"></i> Catatan Perbaikan dari Petugas:</p>
                                <p>{{ $result->rejection_note }}</p>
                            </div>
                        @endif

                        <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                            <a href="{{ route('tracking.show', ['type' => $type, 'registrationNo' => $result->registration_no]) }}" class="text-xs font-bold text-[#0b7c89] hover:text-[#065b65] flex items-center gap-1">
                                Lihat Timeline Lengkap <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('tracking.print_receipt', ['type' => $type, 'registrationNo' => $result->registration_no]) }}" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5">
                                <i class="fa-solid fa-print"></i> Cetak Tanda Terima
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-10 bg-slate-50 rounded-xl border border-dashed border-slate-300 p-6">
                        <i class="fa-solid fa-magnifying-glass-chart text-4xl text-slate-300 mb-3"></i>
                        <h4 class="font-bold text-sm text-slate-700">Data Permohonan Tidak Ditemukan</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                            Nomor Registrasi atau NIK "<strong>{{ $keyword }}</strong>" tidak cocok dengan data permohonan yang ada di Kalurahan Purwobinangun. Mohon periksa kembali nomor yang Anda masukkan.
                        </p>
                    </div>
                @endif
            @else
                <div class="text-center py-10 text-slate-400">
                    <i class="fa-solid fa-receipt text-5xl text-slate-200 mb-3"></i>
                    <p class="text-xs">Silakan masukkan nomor registrasi permohonan Anda di atas.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
