@extends('layouts.admin')

@section('title', 'Dashboard Petugas')
@section('page_title', 'Dashboard Pelayanan Kependudukan')

@section('content')
<div class="space-y-6">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-[#0b7c89] to-[#065b65] text-white rounded-xl p-5 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded">Panel Petugas Kalurahan</span>
            <h2 class="text-xl font-bold mt-1">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-xs text-teal-100 mt-0.5">Kelola verifikasi berkas permohonan Akte Kelahiran dan Kematian warga Kalurahan Purwobinangun secara real-time.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.birth.index', ['status' => 'pending']) }}" class="bg-amber-400 hover:bg-amber-500 text-slate-900 font-bold text-xs px-3.5 py-2 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-bell mr-1"></i> {{ $birthStats['pending'] + $deathStats['pending'] }} Menunggu Verifikasi
            </a>
        </div>
    </div>

    <!-- Statistik Permohonan Kelahiran & Kematian -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Box Statistik Akte Kelahiran -->
        <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-teal-100 text-[#0b7c89] flex items-center justify-center">
                        <i class="fa-solid fa-baby"></i>
                    </div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-800">Akte Kelahiran</h3>
                </div>
                <a href="{{ route('admin.birth.index') }}" class="text-xs font-bold text-[#0b7c89] hover:underline">Kelola Data &rarr;</a>
            </div>

            <div class="grid grid-cols-4 gap-2 mt-4 text-center">
                <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-200/60">
                    <p class="text-lg font-extrabold text-slate-800">{{ $birthStats['total'] }}</p>
                    <p class="text-[10px] text-slate-500 font-medium">Total Masuk</p>
                </div>
                <div class="p-2.5 bg-amber-50 rounded-lg border border-amber-200/60">
                    <p class="text-lg font-extrabold text-amber-700">{{ $birthStats['pending'] }}</p>
                    <p class="text-[10px] text-amber-700 font-medium">Menunggu</p>
                </div>
                <div class="p-2.5 bg-blue-50 rounded-lg border border-blue-200/60">
                    <p class="text-lg font-extrabold text-blue-700">{{ $birthStats['in_process'] }}</p>
                    <p class="text-[10px] text-blue-700 font-medium">Diproses</p>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-lg border border-emerald-200/60">
                    <p class="text-lg font-extrabold text-emerald-700">{{ $birthStats['completed'] }}</p>
                    <p class="text-[10px] text-emerald-700 font-medium">Selesai</p>
                </div>
            </div>
        </div>

        <!-- Box Statistik Akte Kematian -->
        <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center">
                        <i class="fa-solid fa-book-skull"></i>
                    </div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-800">Akte Kematian</h3>
                </div>
                <a href="{{ route('admin.death.index') }}" class="text-xs font-bold text-rose-700 hover:underline">Kelola Data &rarr;</a>
            </div>

            <div class="grid grid-cols-4 gap-2 mt-4 text-center">
                <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-200/60">
                    <p class="text-lg font-extrabold text-slate-800">{{ $deathStats['total'] }}</p>
                    <p class="text-[10px] text-slate-500 font-medium">Total Masuk</p>
                </div>
                <div class="p-2.5 bg-amber-50 rounded-lg border border-amber-200/60">
                    <p class="text-lg font-extrabold text-amber-700">{{ $deathStats['pending'] }}</p>
                    <p class="text-[10px] text-amber-700 font-medium">Menunggu</p>
                </div>
                <div class="p-2.5 bg-blue-50 rounded-lg border border-blue-200/60">
                    <p class="text-lg font-extrabold text-blue-700">{{ $deathStats['in_process'] }}</p>
                    <p class="text-[10px] text-blue-700 font-medium">Diproses</p>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-lg border border-emerald-200/60">
                    <p class="text-lg font-extrabold text-emerald-700">{{ $deathStats['completed'] }}</p>
                    <p class="text-[10px] text-emerald-700 font-medium">Selesai</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Permohonan Kelahiran Terbaru -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-xs uppercase tracking-wider text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-baby text-[#0b7c89]"></i> Pengajuan Akte Kelahiran Terbaru
            </h3>
            <a href="{{ route('admin.birth.index') }}" class="text-xs font-semibold text-[#0b7c89] hover:underline">Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No. Registrasi</th>
                        <th class="py-3 px-4">Nama Bayi</th>
                        <th class="py-3 px-4">Orang Tua</th>
                        <th class="py-3 px-4">Padukuhan</th>
                        <th class="py-3 px-4">Tanggal Masuk</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($latestBirths as $b)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-mono font-bold text-[#0b7c89]">{{ $b->registration_no }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $b->child_name }} ({{ $b->gender }})</td>
                            <td class="py-3 px-4">{{ $b->father_name }} & {{ $b->mother_name }}</td>
                            <td class="py-3 px-4">Padukuhan {{ $b->padukuhan }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $b->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $b->status_badge_class }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.birth.show', $b) }}" class="bg-teal-50 hover:bg-teal-100 text-[#0b7c89] font-bold text-[11px] px-2.5 py-1 rounded border border-teal-200 transition">
                                    Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-slate-400">Belum ada data permohonan kelahiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permohonan Kematian Terbaru -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-xs uppercase tracking-wider text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-book-skull text-rose-700"></i> Pengajuan Akte Kematian Terbaru
            </h3>
            <a href="{{ route('admin.death.index') }}" class="text-xs font-semibold text-rose-700 hover:underline">Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No. Registrasi</th>
                        <th class="py-3 px-4">Nama Almarhum/ah</th>
                        <th class="py-3 px-4">Pelapor (Hubungan)</th>
                        <th class="py-3 px-4">Padukuhan</th>
                        <th class="py-3 px-4">Tanggal Kematian</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($latestDeaths as $d)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-mono font-bold text-rose-700">{{ $d->registration_no }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $d->deceased_name }}</td>
                            <td class="py-3 px-4">{{ $d->applicant_name }} ({{ $d->applicant_relation }})</td>
                            <td class="py-3 px-4">Padukuhan {{ $d->padukuhan }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $d->death_date->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $d->status_badge_class }}">
                                    {{ $d->status_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.death.show', $d) }}" class="bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] px-2.5 py-1 rounded border border-rose-200 transition">
                                    Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-slate-400">Belum ada data permohonan kematian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
