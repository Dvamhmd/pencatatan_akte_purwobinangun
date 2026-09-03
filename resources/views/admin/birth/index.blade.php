@extends('layouts.admin')

@section('title', 'Data Pengajuan Akte Kelahiran')
@section('page_title', 'Kelola Permohonan Akte Kelahiran')

@section('content')
<div class="space-y-5">

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-4">
        <form action="{{ route('admin.birth.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-3">
            
            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <label class="text-sm font-bold text-slate-700">Status:</label>
                <select name="status" onchange="this.form.submit()" class="text-sm px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]">
                    <option value="">Semua Status Aktif</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>1. Menunggu Verifikasi</option>
                    <option value="in_process" {{ $status === 'in_process' ? 'selected' : '' }}>2. Sedang Diproses</option>
                    <option value="revision" {{ $status === 'revision' ? 'selected' : '' }}>3. Revisi Berkas</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>4. Dibatalkan</option>
                    <option value="ready_for_pickup" {{ $status === 'ready_for_pickup' ? 'selected' : '' }}>5. Siap diambil</option>
                </select>
                <a href="{{ route('admin.archive.index', ['tab' => 'birth']) }}" class="hidden lg:inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-[#0b7c89] font-bold px-2.5 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 transition" title="Lihat berkas yang sudah diambil / diarsipkan">
                    <i class="fa-solid fa-box-archive text-amber-500"></i> Arsip (Sudah diambil)
                </a>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, No. Registrasi..." class="w-full sm:w-72 text-sm px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]">
                <button type="submit" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-sm px-4 py-2 rounded-lg transition">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>

        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-base">
                <thead class="bg-slate-50 text-slate-700 uppercase text-xs tracking-wider border-b border-slate-200 font-bold">
                    <tr>
                        <th class="py-4 px-4">No. Registrasi</th>
                        <th class="py-4 px-4">Nama Bayi</th>
                        <th class="py-4 px-4">Tempat, Tgl Lahir</th>
                        <th class="py-4 px-4">Nama Orang Tua</th>
                        <th class="py-4 px-4">Padukuhan</th>
                        <th class="py-4 px-4">Tgl Pengajuan</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($births as $b)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-4 font-mono font-bold text-base text-[#0b7c89]">{{ $b->registration_no }}</td>
                            <td class="py-4 px-4 font-bold text-base text-slate-900">{{ $b->child_name }} <span class="text-sm font-semibold text-slate-500">({{ $b->gender }})</span></td>
                            <td class="py-4 px-4 text-base">{{ $b->birth_place }}, {{ $b->birth_date->translatedFormat('d M Y') }}</td>
                            <td class="py-4 px-4 text-base">{{ $b->father_name }} / {{ $b->mother_name }}</td>
                            <td class="py-4 px-4 text-base">Padukuhan {{ $b->padukuhan }}</td>
                            <td class="py-4 px-4 text-base text-slate-500">{{ $b->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $b->status_badge_class }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.birth.show', $b) }}" class="bg-teal-50 hover:bg-teal-100 text-[#0b7c89] font-bold text-sm px-3.5 py-1.5 rounded-lg border border-teal-200 transition inline-block">
                                    <i class="fa-solid fa-eye mr-1"></i> Detail & Verifikasi
                                </a>
                                @if($b->isReadyForPickup() || $b->isInProcess() || $b->isPickedUp())
                                    <a href="{{ route('admin.birth.print_letter', $b) }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm px-3.5 py-1.5 rounded-lg border border-slate-300 transition inline-block">
                                        <i class="fa-solid fa-print"></i> Surat
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-base text-slate-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 text-slate-300 block"></i>
                                <p>Tidak ditemukan data permohonan akte kelahiran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($births->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $births->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
