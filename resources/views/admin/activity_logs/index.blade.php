@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem')
@section('page_title', 'Log Aktivitas Sistem')

@section('content')
<div class="space-y-6">

    <!-- Ringkasan Statistik Log Aktivitas -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-xs font-semibold">Total Log Tercatat</span>
                <i class="fa-solid fa-list-check text-[#0b7c89]"></i>
            </div>
            <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total'] ?? 0) }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Seluruh riwayat transaksi sistem</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-cyan-200 shadow-2xs">
            <div class="flex items-center justify-between text-cyan-600 mb-1">
                <span class="text-xs font-semibold">Aktivitas Hari Ini</span>
                <i class="fa-regular fa-calendar-check"></i>
            </div>
            <p class="text-2xl font-black text-cyan-700">{{ number_format($stats['today'] ?? 0) }}</p>
            <p class="text-[11px] text-cyan-600/80 mt-0.5">Tanggal {{ date('d M Y') }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-teal-200 shadow-2xs">
            <div class="flex items-center justify-between text-teal-600 mb-1">
                <span class="text-xs font-semibold">Tindakan Admin</span>
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <p class="text-2xl font-black text-teal-700">{{ number_format($stats['admin'] ?? 0) }}</p>
            <p class="text-[11px] text-teal-600/80 mt-0.5">ACC, penolakan, edit, arsip</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-blue-200 shadow-2xs">
            <div class="flex items-center justify-between text-blue-600 mb-1">
                <span class="text-xs font-semibold">Pengajuan Warga</span>
                <i class="fa-solid fa-users"></i>
            </div>
            <p class="text-2xl font-black text-blue-700">{{ number_format($stats['warga'] ?? 0) }}</p>
            <p class="text-[11px] text-blue-600/80 mt-0.5">Permohonan akte & akun</p>
        </div>

    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5">
        <form action="{{ route('admin.activity_logs.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                
                <!-- Pencarian Kata Kunci -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Cari Keterangan / NIK / Nama / No. Reg</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari aktivitas, nama warga/admin, NIK..." class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] bg-white">
                </div>

                <!-- Filter Modul -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Modul Layanan</label>
                    <select name="module" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] bg-white">
                        <option value="">Semua Modul</option>
                        <option value="akte_kelahiran" {{ ($filters['module'] ?? '') === 'akte_kelahiran' ? 'selected' : '' }}>Akte Kelahiran</option>
                        <option value="akte_kematian" {{ ($filters['module'] ?? '') === 'akte_kematian' ? 'selected' : '' }}>Akte Kematian</option>
                        <option value="akun_warga" {{ ($filters['module'] ?? '') === 'akun_warga' ? 'selected' : '' }}>Akun Warga</option>
                        <option value="perubahan_profil" {{ ($filters['module'] ?? '') === 'perubahan_profil' ? 'selected' : '' }}>Perubahan Data Profil</option>
                    </select>
                </div>

                <!-- Filter Jenis Aksi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Aksi</label>
                    <select name="action_type" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] bg-white">
                        <option value="">Semua Aksi</option>
                        <option value="pengajuan" {{ ($filters['action_type'] ?? '') === 'pengajuan' ? 'selected' : '' }}>Pengajuan Permohonan</option>
                        <option value="setujui" {{ ($filters['action_type'] ?? '') === 'setujui' ? 'selected' : '' }}>Disetujui / ACC</option>
                        <option value="tolak" {{ ($filters['action_type'] ?? '') === 'tolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="ubah" {{ ($filters['action_type'] ?? '') === 'ubah' ? 'selected' : '' }}>Perubahan Data</option>
                        <option value="arsip" {{ ($filters['action_type'] ?? '') === 'arsip' ? 'selected' : '' }}>Diarsipkan</option>
                        <option value="pulihkan" {{ ($filters['action_type'] ?? '') === 'pulihkan' ? 'selected' : '' }}>Dipulihkan (Restore)</option>
                        <option value="hapus" {{ ($filters['action_type'] ?? '') === 'hapus' ? 'selected' : '' }}>Dihapus Permanen</option>
                    </select>
                </div>

                <!-- Filter Peran Pelaku -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pelaku (Aktor)</label>
                    <select name="actor_role" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] bg-white">
                        <option value="">Semua Pelaku</option>
                        <option value="admin" {{ ($filters['actor_role'] ?? '') === 'admin' ? 'selected' : '' }}>Petugas Kelurahan</option>
                        <option value="warga" {{ ($filters['actor_role'] ?? '') === 'warga' ? 'selected' : '' }}>Warga Pemohon</option>
                        <option value="system" {{ ($filters['actor_role'] ?? '') === 'system' ? 'selected' : '' }}>Sistem Otomatis</option>
                    </select>
                </div>

            </div>

            <!-- Baris Filter Tanggal & Tombol Submit -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <span class="text-xs text-slate-500 font-medium">Rentang Tanggal:</span>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] bg-white">
                    <span class="text-xs text-slate-400">s/d</span>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] bg-white">
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button type="submit" class="bg-[#0b7c89] hover:bg-[#065b65] text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-2xs flex items-center gap-1.5">
                        <i class="fa-solid fa-filter"></i> Terapkan Filter
                    </button>
                    @if(!empty($filters['search']) || !empty($filters['module']) || !empty($filters['action_type']) || !empty($filters['actor_role']) || !empty($filters['date_from']) || !empty($filters['date_to']))
                        <a href="{{ route('admin.activity_logs.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center gap-1">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Log Aktivitas -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-[#0b7c89]"></i> Riwayat Seluruh Aktivitas Sistem
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Catatan lengkap pengajuan oleh warga, verifikasi/ACC oleh admin, perubahan data, arsip, hingga penghapusan.</p>
            </div>
            <div class="text-xs font-medium text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                Default: <strong>10 data per halaman</strong>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3 px-4 w-36 border-r border-slate-200">Waktu</th>
                        <th class="py-3 px-4 w-48 border-r border-slate-200">Pelaku (Aktor)</th>
                        <th class="py-3 px-4 w-36 border-r border-slate-200">Modul & Aksi</th>
                        <th class="py-3 px-4 border-r border-slate-200">Keterangan Aktivitas</th>
                        <th class="py-3 px-4 text-center w-28">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            
                            <!-- Kolom 1: Waktu & Tanggal -->
                            <td class="py-3.5 px-4 whitespace-nowrap align-top border-r border-slate-200">
                                <div class="font-bold text-slate-800">
                                    {{ $log->created_at ? $log->created_at->format('d/m/Y') : '-' }}
                                </div>
                                <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                    {{ $log->created_at ? $log->created_at->format('H:i:s') : '-' }} WIB
                                </div>
                            </td>

                            <!-- Kolom 2: Pelaku (Aktor) -->
                            <td class="py-3.5 px-4 align-top border-r border-slate-200">
                                @if($log->actor_role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-100 text-teal-800 border border-teal-200">
                                        <i class="fa-solid fa-user-shield text-[11px]"></i> Petugas Kelurahan
                                    </span>
                                @elseif($log->actor_role === 'warga')
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                            <i class="fa-solid fa-user text-[9px]"></i> Warga
                                        </span>
                                    </div>
                                    <div class="font-bold text-slate-800 break-words leading-snug">
                                        {{ $log->actor_name ?: 'Pengguna Anonim' }}
                                    </div>
                                    @if($log->actor_nik)
                                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                            NIK: {{ $log->actor_nik }}
                                        </div>
                                    @endif
                                @else
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            <i class="fa-solid fa-robot text-[9px]"></i> Sistem
                                        </span>
                                    </div>
                                    <div class="font-bold text-slate-800 break-words leading-snug">
                                        {{ $log->actor_name ?: 'Sistem' }}
                                    </div>
                                @endif
                            </td>

                            <!-- Kolom 3: Modul & Aksi -->
                            <td class="py-3.5 px-4 align-top whitespace-nowrap border-r border-slate-200">
                                <div class="mb-1.5">
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $log->module_label }}
                                    </span>
                                </div>
                                <div>
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $log->action_badge_class }}">
                                        {{ $log->action_label }}
                                    </span>
                                </div>
                            </td>

                            <!-- Kolom 4: Keterangan Aktivitas -->
                            <td class="py-3.5 px-4 align-top border-r border-slate-200">
                                <p class="text-xs font-semibold text-slate-800 leading-relaxed">
                                    {{ $log->description }}
                                </p>

                                @if($log->reference_no)
                                    <div class="mt-1 flex items-center gap-1.5 text-[11px] text-slate-500">
                                        <span class="font-medium text-slate-400">Referensi:</span>
                                        <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-700 font-bold border border-slate-200">
                                            {{ $log->reference_no }}
                                        </span>
                                    </div>
                                @endif

                                @if(!empty($log->metadata) && is_array($log->metadata))
                                    <details class="mt-1.5 text-[11px] text-slate-500 cursor-pointer">
                                        <summary class="hover:text-[#0b7c89] select-none text-[10px] uppercase font-bold tracking-wider text-slate-400">
                                            Rincian Tambahan
                                        </summary>
                                        <div class="bg-slate-50 border border-slate-200 rounded p-2 mt-1 space-y-0.5 font-mono text-[10px] text-slate-600">
                                            @foreach($log->metadata as $key => $val)
                                                @if(is_scalar($val) && !in_array($key, ['ip_address', 'user_agent']))
                                                    <div><strong class="text-slate-700">{{ str_replace('_', ' ', $key) }}:</strong> {{ $val }}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </details>
                                @endif
                            </td>

                            <!-- Kolom 5: Detail -->
                            <td class="py-3.5 px-4 align-top text-center">
                                @if($log->subject_type === \App\Models\BirthCertificate::class && $log->subject)
                                    <a href="{{ route('admin.birth.show', $log->subject_id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-50 hover:bg-teal-100 text-[#0b7c89] border border-teal-200 transition" title="Buka Detail Akte Kelahiran">
                                        <i class="fa-solid fa-eye text-[11px]"></i> Lihat
                                    </a>
                                @elseif($log->subject_type === \App\Models\DeathCertificate::class && $log->subject)
                                    <a href="{{ route('admin.death.show', $log->subject_id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-50 hover:bg-teal-100 text-[#0b7c89] border border-teal-200 transition" title="Buka Detail Akte Kematian">
                                        <i class="fa-solid fa-eye text-[11px]"></i> Lihat
                                    </a>
                                @elseif($log->subject_type === \App\Models\User::class && $log->subject)
                                    <a href="{{ route('admin.citizens.show', $log->subject_id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-50 hover:bg-teal-100 text-[#0b7c89] border border-teal-200 transition" title="Buka Detail Akun Warga">
                                        <i class="fa-solid fa-eye text-[11px]"></i> Lihat
                                    </a>
                                @elseif($log->subject_type === \App\Models\ProfileUpdateRequest::class && $log->subject)
                                    <a href="{{ route('admin.profile_requests.show', $log->subject_id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-50 hover:bg-teal-100 text-[#0b7c89] border border-teal-200 transition" title="Buka Permohonan Perubahan Profil">
                                        <i class="fa-solid fa-eye text-[11px]"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-slate-300 text-[11px]">-</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <i class="fa-solid fa-clock-rotate-left text-4xl text-slate-300"></i>
                                    <p class="font-bold text-slate-700 text-sm">Belum Ada Riwayat Log</p>
                                    <p class="text-xs text-slate-500">Tidak ada catatan aktivitas yang cocok dengan kriteria pencarian atau filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section (Default 10 per page) -->
        @if($logs->hasPages() || $logs->total() > 0)
            <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">
                <div class="text-xs text-slate-500">
                    Menampilkan <span class="font-bold text-slate-700">{{ $logs->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-700">{{ $logs->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-700">{{ $logs->total() }}</span> total riwayat aktivitas
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
