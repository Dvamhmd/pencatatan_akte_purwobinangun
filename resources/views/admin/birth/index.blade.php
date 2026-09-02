@extends('layouts.admin')

@section('title', 'Data Pengajuan Akte Kelahiran')
@section('page_title', 'Kelola Permohonan Akte Kelahiran')

@section('content')
<div class="space-y-5">

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-4">
        <form action="{{ route('admin.birth.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-3">
            
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="text-xs font-bold text-slate-600">Status:</label>
                <select name="status" onchange="this.form.submit()" class="text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Berkas Terverifikasi</option>
                    <option value="in_process" {{ $status === 'in_process' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, No. Registrasi..." class="w-full sm:w-64 text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]">
                <button type="submit" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-4 py-2 rounded-lg transition">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>

        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No. Registrasi</th>
                        <th class="py-3 px-4">Nama Bayi</th>
                        <th class="py-3 px-4">Tempat, Tgl Lahir</th>
                        <th class="py-3 px-4">Nama Orang Tua</th>
                        <th class="py-3 px-4">Padukuhan</th>
                        <th class="py-3 px-4">Tgl Pengajuan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($births as $b)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-mono font-bold text-[#0b7c89]">{{ $b->registration_no }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $b->child_name }} ({{ $b->gender }})</td>
                            <td class="py-3 px-4">{{ $b->birth_place }}, {{ $b->birth_date->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-4">{{ $b->father_name }} / {{ $b->mother_name }}</td>
                            <td class="py-3 px-4">Padukuhan {{ $b->padukuhan }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $b->created_at->translatedFormat('d/m/Y H:i') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $b->status_badge_class }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.birth.show', $b) }}" class="bg-teal-50 hover:bg-teal-100 text-[#0b7c89] font-bold text-[11px] px-2.5 py-1 rounded border border-teal-200 transition">
                                    <i class="fa-solid fa-eye mr-0.5"></i> Detail & Verifikasi
                                </a>
                                @if($b->status === 'completed' || $b->status === 'in_process')
                                    <a href="{{ route('admin.birth.print_letter', $b) }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] px-2.5 py-1 rounded border border-slate-300 transition">
                                        <i class="fa-solid fa-print"></i> Surat
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
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
