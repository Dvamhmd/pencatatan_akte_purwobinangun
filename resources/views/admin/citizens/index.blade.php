@extends('layouts.admin')

@section('title', 'Verifikasi Akun Warga')
@section('page_title', 'Kelola & Verifikasi Akun Warga')

@section('content')
<div class="space-y-6">

    <!-- Statistik Akun Warga -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-xs font-semibold">Total Pendaftar</span>
                <i class="fa-solid fa-users text-[#0b7c89]"></i>
            </div>
            <p class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-2xs">
            <div class="flex items-center justify-between text-amber-600 mb-1">
                <span class="text-xs font-semibold">Menunggu Verifikasi</span>
                <i class="fa-solid fa-user-clock"></i>
            </div>
            <p class="text-2xl font-black text-amber-700">{{ $stats['pending'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-emerald-200 shadow-2xs">
            <div class="flex items-center justify-between text-emerald-600 mb-1">
                <span class="text-xs font-semibold">Akun Aktif</span>
                <i class="fa-solid fa-user-check"></i>
            </div>
            <p class="text-2xl font-black text-emerald-700">{{ $stats['active'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-rose-200 shadow-2xs">
            <div class="flex items-center justify-between text-rose-600 mb-1">
                <span class="text-xs font-semibold">Akun Ditolak</span>
                <i class="fa-solid fa-user-xmark"></i>
            </div>
            <p class="text-2xl font-black text-rose-700">{{ $stats['rejected'] }}</p>
        </div>

    </div>

    <!-- Filter & Search Table -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-address-book text-[#0b7c89]"></i> Daftar Pendaftaran Akun Warga
                </h3>
                <p class="text-xs text-slate-500">Pemeriksaan NIK, Nomor KK, dan status verifikasi akun mandiri warga.</p>
            </div>

            <!-- Form Pencarian & Filter -->
            <form action="{{ route('admin.citizens.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari NIK, KK, Nama, Alamat..." class="text-xs px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] w-full sm:w-64 bg-white">
                
                <select name="status" class="text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif / Terverifikasi</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>

                <button type="submit" class="bg-[#0b7c89] hover:bg-[#065b65] text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-2xs">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if($status || $search)
                    <a href="{{ route('admin.citizens.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs px-2.5 py-2 rounded-lg transition" title="Reset">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Akun Warga -->
        <div class="overflow-x-auto">
            @if($citizens->count() > 0)
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5">Identitas (NIK & KK)</th>
                            <th class="px-4 py-3.5">Nama & Jenis Kelamin</th>
                            <th class="px-4 py-3.5">Alamat / Wilayah</th>
                            <th class="px-4 py-3.5">Kontak & Email</th>
                            <th class="px-4 py-3.5">Waktu Daftar</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-4 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($citizens as $citizen)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5">
                                    <p class="font-mono font-bold text-slate-900">{{ $citizen->nik }}</p>
                                    <span class="text-[10px] text-teal-800 bg-teal-50 px-1.5 py-0.5 rounded border border-teal-200 font-mono">
                                        KK: {{ $citizen->family_card_no }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="font-bold text-slate-900">{{ $citizen->name }}</p>
                                    <span class="text-[10px] text-slate-500">
                                        {{ $citizen->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}, 
                                        {{ $citizen->birth_place }}, {{ $citizen->birth_date ? $citizen->birth_date->translatedFormat('d M Y') : '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="text-slate-800 font-medium">{{ $citizen->address }}</p>
                                    <span class="text-[10px] text-slate-500">RT {{ $citizen->rt }} / RW {{ $citizen->rw }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="text-slate-800 font-semibold"><i class="fa-brands fa-whatsapp text-emerald-600"></i> {{ $citizen->phone }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $citizen->email ?: 'Tidak ada email' }}</p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="text-slate-700 font-medium">{{ $citizen->created_at->translatedFormat('d M Y') }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $citizen->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $citizen->status_badge_class }}">
                                        {{ $citizen->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <a href="{{ route('admin.citizens.show', $citizen) }}" class="inline-flex items-center gap-1.5 bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-[11px] px-3 py-1.5 rounded-lg transition shadow-2xs">
                                        <i class="fa-solid fa-user-check"></i> Detail & Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12 bg-white">
                    <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                    <p class="font-bold text-sm text-slate-700">Tidak ada data pendaftaran warga ditemukan.</p>
                    <p class="text-xs text-slate-500 mt-1">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($citizens->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $citizens->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
