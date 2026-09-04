@extends('layouts.admin')

@section('title', 'Verifikasi Perubahan Data Warga')
@section('page_title', 'Permohonan Perubahan Data Warga')

@section('content')
<div class="space-y-6">

    <!-- Statistik Permohonan Perubahan Data -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-xs font-semibold">Total Pengajuan</span>
                <i class="fa-solid fa-list-check text-[#0b7c89]"></i>
            </div>
            <p class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-2xs">
            <div class="flex items-center justify-between text-amber-600 mb-1">
                <span class="text-xs font-semibold">Menunggu Verifikasi</span>
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <p class="text-2xl font-black text-amber-700">{{ $stats['pending'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-emerald-200 shadow-2xs">
            <div class="flex items-center justify-between text-emerald-600 mb-1">
                <span class="text-xs font-semibold">Disetujui</span>
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <p class="text-2xl font-black text-emerald-700">{{ $stats['approved'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-rose-200 shadow-2xs">
            <div class="flex items-center justify-between text-rose-600 mb-1">
                <span class="text-xs font-semibold">Ditolak</span>
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <p class="text-2xl font-black text-rose-700">{{ $stats['rejected'] }}</p>
        </div>

    </div>

    <!-- Filter & Search Table -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-[#0b7c89]"></i> Daftar Permohonan Perubahan Profil & Keluarga
                </h3>
                <p class="text-xs text-slate-500">Periksa pengajuan peremajaan identitas dan susunan anggota keluarga yang diajukan warga.</p>
            </div>

            <!-- Form Pencarian & Filter -->
            <form action="{{ route('admin.profile_requests.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Nama, NIK, No. KK..." class="text-xs px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] w-full sm:w-60 bg-white">
                
                <select name="status" class="text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>

                <button type="submit" class="bg-[#0b7c89] hover:bg-[#065b65] text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-2xs">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if($status || $search)
                    <a href="{{ route('admin.profile_requests.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs px-2.5 py-2 rounded-lg transition" title="Reset">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Pengajuan -->
        <div class="overflow-x-auto">
            @if($requests->count() > 0)
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5">Identitas Pemohon (NIK & KK)</th>
                            <th class="px-4 py-3.5">Nama & Hubungan</th>
                            <th class="px-4 py-3.5">Jumlah Anggota Baru</th>
                            <th class="px-4 py-3.5">Waktu Pengajuan</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-4 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($requests as $req)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5">
                                    <p class="font-mono font-bold text-slate-900">{{ $req->nik }}</p>
                                    <span class="text-[10px] text-teal-800 bg-teal-50 px-1.5 py-0.5 rounded border border-teal-200 font-mono">
                                        KK: {{ $req->family_card_no }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="font-bold text-slate-900">{{ $req->name }}</p>
                                    <span class="text-[10px] text-slate-500">
                                        {{ $req->family_relationship ?: 'Kepala Keluarga' }} &bull; {{ $req->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    @php
                                        $membersCount = is_array($req->family_members_data) ? count($req->family_members_data) : 0;
                                    @endphp
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#0b7c89] bg-teal-50 px-2 py-0.5 rounded border border-teal-200">
                                        <i class="fa-solid fa-users text-[10px]"></i> {{ $membersCount }} Orang
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="text-slate-800 font-medium">{{ $req->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                    @if($req->processed_at)
                                        <span class="text-[10px] text-slate-500">Diproses: {{ $req->processed_at->translatedFormat('d M Y') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $req->status_badge_class }}">
                                        {{ $req->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <a href="{{ route('admin.profile_requests.show', $req) }}" class="inline-flex items-center gap-1 bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-[11px] px-3 py-1.5 rounded-lg transition shadow-2xs" title="Periksa Komparasi & Verifikasi">
                                        <i class="fa-solid fa-eye"></i> Periksa Data
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12 bg-white">
                    <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-user-pen text-xl"></i>
                    </div>
                    <p class="font-bold text-sm text-slate-700">Tidak ada permohonan perubahan data ditemukan.</p>
                    <p class="text-xs text-slate-500 mt-1">Belum ada warga yang mengajukan perubahan data profil atau anggota keluarga.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $requests->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
