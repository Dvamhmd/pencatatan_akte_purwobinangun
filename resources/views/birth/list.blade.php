@extends('layouts.app')

@section('title', 'Daftar Pengajuan Permohonan Akte')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="bg-[#095b8c] text-white px-5 py-3.5 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-amber-300">
                    <i class="fa-solid fa-list-check text-base"></i>
                </div>
                <div>
                    <h2 class="font-bold text-sm md:text-base tracking-wide uppercase">
                        DAFTAR PENGAJUAN PERMOHONAN AKTA
                    </h2>
                    <p class="text-[11px] text-teal-100 font-normal">
                        @if(Auth::check() && Auth::user()->isWarga())
                            Menampilkan data pengajuan untuk Nomor KK: <strong class="text-amber-300 font-mono">{{ Auth::user()->family_card_no }}</strong> ({{ Auth::user()->name }})
                        @else
                            Sistem Pelayanan Administrasi Kependudukan Kalurahan Purwobinangun
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('birth.create') }}" class="bg-amber-400 hover:bg-amber-500 text-slate-950 font-bold text-xs px-3.5 py-2 rounded-lg transition shadow-xs flex items-center gap-1.5">
                <i class="fa-solid fa-plus"></i> Buat Pengajuan Baru
            </a>
        </div>

        <!-- Banner Ringkasan Statistik -->
        <div class="p-5 md:p-6 bg-gradient-to-r from-teal-50/60 via-slate-50 to-sky-50/40 border-b border-slate-200/70">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-[11px] font-semibold">Total Pengajuan</span>
                        <i class="fa-solid fa-folder-open text-[#095b8c]"></i>
                    </div>
                    <p class="text-xl font-extrabold text-slate-800">{{ $totalCount }}</p>
                </div>

                <div class="bg-white p-3.5 rounded-xl border border-amber-200 shadow-2xs">
                    <div class="flex items-center justify-between text-amber-600 mb-1">
                        <span class="text-[11px] font-semibold">Menunggu Verifikasi</span>
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <p class="text-xl font-extrabold text-amber-700">{{ $pendingCount }}</p>
                </div>

                <div class="bg-white p-3.5 rounded-xl border border-blue-200 shadow-2xs">
                    <div class="flex items-center justify-between text-blue-600 mb-1">
                        <span class="text-[11px] font-semibold">Sedang Diproses</span>
                        <i class="fa-solid fa-spinner"></i>
                    </div>
                    <p class="text-xl font-extrabold text-blue-700">{{ $inProcessCount }}</p>
                </div>

                <div class="bg-white p-3.5 rounded-xl border border-emerald-200 shadow-2xs">
                    <div class="flex items-center justify-between text-emerald-600 mb-1">
                        <span class="text-[11px] font-semibold">Siap / Selesai</span>
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <p class="text-xl font-extrabold text-emerald-700">{{ $readyCount + $pickedUpCount }}</p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="p-5 bg-white border-b border-slate-200/70">
            <form action="{{ route('birth.list') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
                
                <!-- Search Input -->
                <div class="relative flex-1 w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Masukkan No. Registrasi, NIK, Nama Pemohon, atau Nama Anak..." class="w-full text-xs pl-9 pr-4 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] bg-white">
                </div>

                <!-- Status Filter -->
                <div class="w-full md:w-52">
                    <select name="status" class="w-full text-xs px-3 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>1. Menunggu Verifikasi</option>
                        <option value="in_process" {{ $status === 'in_process' ? 'selected' : '' }}>2. Sedang Diproses</option>
                        <option value="revision" {{ $status === 'revision' ? 'selected' : '' }}>3. Revisi Berkas</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>4. Dibatalkan</option>
                        <option value="ready_for_pickup" {{ $status === 'ready_for_pickup' ? 'selected' : '' }}>5. Siap diambil</option>
                        <option value="picked_up" {{ $status === 'picked_up' ? 'selected' : '' }}>6. Sudah diambil</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-initial bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-xs px-5 py-2.5 rounded-lg shadow-xs transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    @if($status || $search)
                        <a href="{{ route('birth.list') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-2.5 rounded-lg transition" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Submission List Table / Cards -->
        <div class="p-5 md:p-6">

            @if($submissions->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">No. Registrasi</th>
                                <th class="px-4 py-3">Nama Anak</th>
                                <th class="px-4 py-3">Data Pemohon</th>
                                <th class="px-4 py-3">Tgl Lahir / Tempat</th>
                                <th class="px-4 py-3">Tanggal Pengajuan</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($submissions as $sub)
                                <tr class="hover:bg-teal-50/30 transition">
                                    <td class="px-4 py-3.5">
                                        <div class="font-extrabold text-[#095b8c] flex items-center gap-1.5">
                                            <i class="fa-solid fa-receipt text-slate-400"></i>
                                            <span>{{ $sub->registration_no }}</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400">Akte Kelahiran</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-bold text-slate-900">{{ $sub->child_name }}</p>
                                        <span class="text-[10px] inline-flex items-center gap-1 {{ $sub->gender === 'L' ? 'text-blue-600 bg-blue-50' : 'text-pink-600 bg-pink-50' }} px-1.5 py-0.5 rounded font-semibold">
                                            <i class="fa-solid {{ $sub->gender === 'L' ? 'fa-mars' : 'fa-venus' }}"></i>
                                            {{ $sub->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-slate-800">{{ $sub->applicant_name }}</p>
                                        <p class="text-[10px] text-slate-500">NIK: {{ substr($sub->applicant_nik, 0, 6) }}******{{ substr($sub->applicant_nik, -4) }}</p>
                                        <p class="text-[10px] text-slate-500"><i class="fa-brands fa-whatsapp text-emerald-600"></i> {{ $sub->applicant_phone }}</p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-medium text-slate-800">{{ $sub->birth_date ? $sub->birth_date->translatedFormat('d M Y') : '-' }}</p>
                                        <p class="text-[10px] text-slate-500">{{ $sub->birth_place_type ?? $sub->birth_place }}</p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="text-slate-700 font-medium">{{ $sub->created_at->translatedFormat('d M Y') }}</p>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $sub->status_badge_class }}">
                                            {{ $sub->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('tracking.show', ['type' => 'birth', 'registrationNo' => $sub->registration_no]) }}" class="p-1.5 bg-teal-50 hover:bg-[#095b8c] text-[#095b8c] hover:text-white rounded-lg transition border border-teal-200" title="Detail Timeline">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('tracking.print_receipt', ['type' => 'birth', 'registrationNo' => $sub->registration_no]) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-slate-800 text-slate-700 hover:text-white rounded-lg transition border border-slate-200" title="Cetak Bukti">
                                                <i class="fa-solid fa-print text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $submissions->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-300 p-6">
                    <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-folder-open text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-sm text-slate-700">Belum Ada Data Pengajuan</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-4">
                        @if($search || $status)
                            Tidak ada pengajuan yang sesuai dengan kriteria filter pencarian Anda.
                        @else
                            Belum ada permohonan akte kelahiran yang terdaftar dalam sistem.
                        @endif
                    </p>
                    <a href="{{ route('birth.create') }}" class="inline-flex items-center gap-1.5 bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-xs px-4 py-2 rounded-lg transition shadow-xs">
                        <i class="fa-solid fa-plus"></i> Ajukan Akte Kelahiran Sekarang
                    </a>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
