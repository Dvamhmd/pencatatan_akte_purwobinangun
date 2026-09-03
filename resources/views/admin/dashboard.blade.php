@extends('layouts.admin')

@section('title', 'Dashboard Petugas')
@section('page_title', 'Dashboard Pelayanan Kependudukan')

@section('content')
<div class="space-y-6">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-[#0b7c89] to-[#065b65] text-white rounded-xl p-6 md:p-7 shadow-xs flex flex-col md:flex-row items-center justify-between gap-5">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider bg-white/20 px-3 py-1 rounded-md">Panel Petugas Kalurahan</span>
            <h2 class="text-3xl font-extrabold mt-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-base text-teal-100 mt-1.5 leading-relaxed">Kelola verifikasi berkas permohonan Akte Kelahiran dan Kematian warga Kalurahan Purwobinangun secara real-time.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.citizens.index', ['status' => 'pending']) }}" class="bg-amber-400 hover:bg-amber-500 text-slate-900 font-bold text-base px-5 py-3 rounded-xl shadow-sm transition inline-flex items-center gap-2">
                <i class="fa-solid fa-user-clock text-lg"></i> {{ $citizenStats['pending'] }} Warga Perlu Verifikasi
            </a>
        </div>
    </div>

    <!-- Statistik Ringkas: Warga & Pelayanan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Box Statistik Akun Warga -->
        <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-6">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="font-bold text-base uppercase tracking-wider text-slate-800">Akun Warga</h3>
                </div>
                <a href="{{ route('admin.citizens.index') }}" class="text-base font-bold text-[#0b7c89] hover:underline">Kelola &rarr;</a>
            </div>

            <div class="grid grid-cols-3 gap-2 mt-5 text-center">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                    <p class="text-2xl font-black text-slate-800">{{ $citizenStats['total'] }}</p>
                    <p class="text-xs text-slate-600 font-semibold mt-1">Total</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/60">
                    <p class="text-2xl font-black text-amber-700">{{ $citizenStats['pending'] }}</p>
                    <p class="text-xs text-amber-700 font-bold mt-1">Menunggu</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200/60">
                    <p class="text-2xl font-black text-emerald-700">{{ $citizenStats['active'] }}</p>
                    <p class="text-xs text-emerald-700 font-bold mt-1">Aktif</p>
                </div>
            </div>
        </div>
        
        <!-- Box Statistik Akte Kelahiran -->
        <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-6">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-100 text-[#0b7c89] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-baby"></i>
                    </div>
                    <h3 class="font-bold text-base uppercase tracking-wider text-slate-800">Akte Kelahiran</h3>
                </div>
                <a href="{{ route('admin.birth.index') }}" class="text-base font-bold text-[#0b7c89] hover:underline">Kelola Data &rarr;</a>
            </div>

            <div class="grid grid-cols-4 gap-3 mt-5 text-center">
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                    <p class="text-3xl font-black text-slate-800">{{ $birthStats['total'] }}</p>
                    <p class="text-sm text-slate-600 font-semibold mt-1">Total Masuk</p>
                </div>
                <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200/60">
                    <p class="text-3xl font-black text-amber-700">{{ $birthStats['pending'] }}</p>
                    <p class="text-sm text-amber-700 font-bold mt-1">Menunggu</p>
                </div>
                <div class="p-3.5 bg-blue-50 rounded-xl border border-blue-200/60">
                    <p class="text-3xl font-black text-blue-700">{{ $birthStats['in_process'] }}</p>
                    <p class="text-sm text-blue-700 font-bold mt-1">Diproses</p>
                </div>
                <div class="p-3.5 bg-emerald-50 rounded-xl border border-emerald-200/60">
                    <p class="text-3xl font-black text-emerald-700">{{ $birthStats['completed'] }}</p>
                    <p class="text-sm text-emerald-700 font-bold mt-1">Selesai</p>
                </div>
            </div>
        </div>

        <!-- Box Statistik Akte Kematian -->
        <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-6">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-book-skull"></i>
                    </div>
                    <h3 class="font-bold text-base uppercase tracking-wider text-slate-800">Akte Kematian</h3>
                </div>
                <a href="{{ route('admin.death.index') }}" class="text-base font-bold text-rose-700 hover:underline">Kelola Data &rarr;</a>
            </div>

            <div class="grid grid-cols-4 gap-3 mt-5 text-center">
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                    <p class="text-3xl font-black text-slate-800">{{ $deathStats['total'] }}</p>
                    <p class="text-sm text-slate-600 font-semibold mt-1">Total Masuk</p>
                </div>
                <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200/60">
                    <p class="text-3xl font-black text-amber-700">{{ $deathStats['pending'] }}</p>
                    <p class="text-sm text-amber-700 font-bold mt-1">Menunggu</p>
                </div>
                <div class="p-3.5 bg-blue-50 rounded-xl border border-blue-200/60">
                    <p class="text-3xl font-black text-blue-700">{{ $deathStats['in_process'] }}</p>
                    <p class="text-sm text-blue-700 font-bold mt-1">Diproses</p>
                </div>
                <div class="p-3.5 bg-emerald-50 rounded-xl border border-emerald-200/60">
                    <p class="text-3xl font-black text-emerald-700">{{ $deathStats['completed'] }}</p>
                    <p class="text-sm text-emerald-700 font-bold mt-1">Selesai</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Permohonan Kelahiran Terbaru -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-base uppercase tracking-wider text-slate-800 flex items-center gap-2.5">
                <i class="fa-solid fa-baby text-[#0b7c89] text-lg"></i> Pengajuan Akte Kelahiran Terbaru
            </h3>
            <a href="{{ route('admin.birth.index') }}" class="text-base font-bold text-[#0b7c89] hover:underline">Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-base">
                <thead class="bg-slate-50 text-slate-700 uppercase text-xs tracking-wider border-b border-slate-200 font-bold">
                    <tr>
                        <th class="py-4 px-4">No. Registrasi</th>
                        <th class="py-4 px-4">Nama Bayi</th>
                        <th class="py-4 px-4">Orang Tua</th>
                        <th class="py-4 px-4">Padukuhan</th>
                        <th class="py-4 px-4">Tanggal Masuk</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($latestBirths as $b)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-4 font-mono font-bold text-base text-[#0b7c89]">{{ $b->registration_no }}</td>
                            <td class="py-4 px-4 font-bold text-base text-slate-900">{{ $b->child_name }} <span class="text-sm font-semibold text-slate-500">({{ $b->gender }})</span></td>
                            <td class="py-4 px-4 text-base">{{ $b->father_name }} & {{ $b->mother_name }}</td>
                            <td class="py-4 px-4 text-base">Padukuhan {{ $b->padukuhan }}</td>
                            <td class="py-4 px-4 text-base text-slate-500">{{ $b->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $b->status_badge_class }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('admin.birth.show', $b) }}" class="bg-teal-50 hover:bg-teal-100 text-[#0b7c89] font-bold text-sm px-3.5 py-1.5 rounded-lg border border-teal-200 transition inline-block">
                                    Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-base text-slate-400">Belum ada data permohonan kelahiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permohonan Kematian Terbaru -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-base uppercase tracking-wider text-slate-800 flex items-center gap-2.5">
                <i class="fa-solid fa-book-skull text-rose-700 text-lg"></i> Pengajuan Akte Kematian Terbaru
            </h3>
            <a href="{{ route('admin.death.index') }}" class="text-base font-bold text-rose-700 hover:underline">Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-base">
                <thead class="bg-slate-50 text-slate-700 uppercase text-xs tracking-wider border-b border-slate-200 font-bold">
                    <tr>
                        <th class="py-4 px-4">No. Registrasi</th>
                        <th class="py-4 px-4">Nama Almarhum/ah</th>
                        <th class="py-4 px-4">Pelapor (Hubungan)</th>
                        <th class="py-4 px-4">Padukuhan</th>
                        <th class="py-4 px-4">Tanggal Kematian</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($latestDeaths as $d)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-4 font-mono font-bold text-base text-rose-700">{{ $d->registration_no }}</td>
                            <td class="py-4 px-4 font-bold text-base text-slate-900">{{ $d->deceased_name }}</td>
                            <td class="py-4 px-4 text-base">{{ $d->applicant_name }} <span class="text-sm font-semibold text-slate-500">({{ $d->applicant_relation }})</span></td>
                            <td class="py-4 px-4 text-base">Padukuhan {{ $d->padukuhan }}</td>
                            <td class="py-4 px-4 text-base text-slate-500">{{ $d->death_date->translatedFormat('d M Y') }}</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $d->status_badge_class }}">
                                    {{ $d->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('admin.death.show', $d) }}" class="bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-sm px-3.5 py-1.5 rounded-lg border border-rose-200 transition inline-block">
                                    Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-base text-slate-400">Belum ada data permohonan kematian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

