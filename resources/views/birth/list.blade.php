@extends('layouts.app')

@section('title', 'Daftar Pengajuan Permohonan Akta')

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
            <div class="flex items-center gap-2">
                <a href="{{ route('birth.create') }}" class="bg-amber-400 hover:bg-amber-500 text-slate-950 font-bold text-xs px-3 py-2 rounded-lg transition shadow-xs flex items-center gap-1.5">
                    <i class="fa-solid fa-baby"></i> Buat Akte Kelahiran
                </a>
                <a href="{{ route('death.create') }}" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-3 py-2 rounded-lg transition shadow-xs flex items-center gap-1.5 border border-rose-500/80">
                    <i class="fa-solid fa-book-skull"></i> Buat Akte Kematian
                </a>
            </div>
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
                    <div class="flex items-center gap-1.5 mt-1 text-[10px] text-slate-500 font-medium">
                        <span class="text-teal-700 font-semibold"><i class="fa-solid fa-baby text-[9px]"></i> {{ $birthCount }} Lahir</span>
                        <span>•</span>
                        <span class="text-rose-700 font-semibold"><i class="fa-solid fa-book-skull text-[9px]"></i> {{ $deathCount }} Mati</span>
                    </div>
                </div>

                <div class="bg-white p-3.5 rounded-xl border border-amber-200 shadow-2xs">
                    <div class="flex items-center justify-between text-amber-600 mb-1">
                        <span class="text-[11px] font-semibold">Menunggu Verifikasi</span>
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <p class="text-xl font-extrabold text-amber-700">{{ $pendingCount }}</p>
                    <span class="text-[10px] text-amber-600/80 mt-1 block">Antrean petugas</span>
                </div>

                <div class="bg-white p-3.5 rounded-xl border border-blue-200 shadow-2xs">
                    <div class="flex items-center justify-between text-blue-600 mb-1">
                        <span class="text-[11px] font-semibold">Sedang Diproses</span>
                        <i class="fa-solid fa-spinner"></i>
                    </div>
                    <p class="text-xl font-extrabold text-blue-700">{{ $inProcessCount }}</p>
                    <span class="text-[10px] text-blue-600/80 mt-1 block">Validasi & penyiapan</span>
                </div>

                <div class="bg-white p-3.5 rounded-xl border border-emerald-200 shadow-2xs">
                    <div class="flex items-center justify-between text-emerald-600 mb-1">
                        <span class="text-[11px] font-semibold">Siap / Selesai</span>
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <p class="text-xl font-extrabold text-emerald-700">{{ $readyCount + $pickedUpCount }}</p>
                    <span class="text-[10px] text-emerald-600/80 mt-1 block">Dapat diambil di kantor</span>
                </div>
            </div>
        </div>

        <!-- Tab Navigation Berdasarkan Jenis Akta & Filter Bar -->
        <div class="p-5 bg-white border-b border-slate-200/70 space-y-3.5">
            
            <!-- Tab Pills Jenis Akta -->
            <div class="flex flex-wrap items-center gap-2 pb-2 border-b border-slate-100">
                <a href="{{ route('submissions.index', array_merge(request()->except(['page']), ['type' => 'all'])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ $type === 'all' || empty($type) ? 'bg-[#095b8c] text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    <i class="fa-solid fa-layer-group text-[11px]"></i>
                    <span>Semua Pengajuan</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $type === 'all' || empty($type) ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700 font-bold' }}">{{ $totalCount }}</span>
                </a>

                <a href="{{ route('submissions.index', array_merge(request()->except(['page']), ['type' => 'birth'])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ $type === 'birth' ? 'bg-[#059cb8] text-white shadow-xs font-bold' : 'bg-teal-50/70 text-teal-800 hover:bg-teal-100 border border-teal-200/60' }}">
                    <i class="fa-solid fa-baby text-[11px]"></i>
                    <span>Akte Kelahiran</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $type === 'birth' ? 'bg-white/20 text-white' : 'bg-teal-100 text-teal-800 font-bold' }}">{{ $birthCount }}</span>
                </a>

                <a href="{{ route('submissions.index', array_merge(request()->except(['page']), ['type' => 'death'])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ $type === 'death' ? 'bg-rose-700 text-white shadow-xs font-bold' : 'bg-rose-50/70 text-rose-800 hover:bg-rose-100 border border-rose-200/60' }}">
                    <i class="fa-solid fa-book-skull text-[11px]"></i>
                    <span>Akte Kematian</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $type === 'death' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800 font-bold' }}">{{ $deathCount }}</span>
                </a>
            </div>

            <!-- Form Filter & Pencarian -->
            <form action="{{ route('submissions.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
                <input type="hidden" name="type" value="{{ $type }}">

                <!-- Search Input -->
                <div class="relative flex-1 w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Registrasi, NIK, Nama Anak, Nama Almarhum/ah, Pemohon..." class="w-full text-xs pl-9 pr-4 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c] bg-white">
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
                    @if($status || $search || ($type && $type !== 'all'))
                        <a href="{{ route('submissions.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-2.5 rounded-lg transition" title="Reset Filter">
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
                                <th class="px-4 py-3">Jenis & No. Registrasi</th>
                                <th class="px-4 py-3">Nama Yang Dimohonkan</th>
                                <th class="px-4 py-3">Data Pemohon</th>
                                <th class="px-4 py-3">Peristiwa (Tgl / Tempat)</th>
                                <th class="px-4 py-3">Tanggal Pengajuan</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($submissions as $sub)
                                <tr class="hover:bg-teal-50/20 transition">
                                    <!-- Kolom 1: Jenis & No. Registrasi -->
                                    <td class="px-4 py-3.5">
                                        <div class="font-extrabold flex items-center gap-1.5 {{ $sub->certificate_type === 'birth' ? 'text-[#095b8c]' : 'text-rose-700' }}">
                                            <i class="fa-solid fa-receipt text-slate-400"></i>
                                            <span>{{ $sub->registration_no }}</span>
                                        </div>
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full mt-1 {{ $sub->certificate_type === 'birth' ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            <i class="fa-solid {{ $sub->certificate_type === 'birth' ? 'fa-baby' : 'fa-book-skull' }} text-[9px]"></i>
                                            {{ $sub->certificate_type_name }}
                                        </span>
                                    </td>

                                    <!-- Kolom 2: Nama Yang Dimohonkan (Subjek) -->
                                    <td class="px-4 py-3.5">
                                        <p class="text-[10px] uppercase font-semibold text-slate-400">
                                            {{ $sub->certificate_type === 'birth' ? 'Nama Anak' : 'Almarhum/ah' }}
                                        </p>
                                        <p class="font-bold text-slate-900">{{ $sub->subject_name }}</p>
                                        <span class="text-[10px] inline-flex items-center gap-1 {{ $sub->gender === 'L' ? 'text-blue-600 bg-blue-50' : 'text-pink-600 bg-pink-50' }} px-1.5 py-0.5 rounded font-semibold mt-0.5">
                                            <i class="fa-solid {{ $sub->gender === 'L' ? 'fa-mars' : 'fa-venus' }}"></i>
                                            {{ $sub->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>

                                    <!-- Kolom 3: Data Pemohon / Pelapor -->
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-slate-800">
                                            {{ $sub->applicant_name }}
                                            @if($sub->applicant_relation)
                                                <span class="text-slate-500 font-normal text-[11px]">({{ $sub->applicant_relation }})</span>
                                            @endif
                                        </p>
                                        <p class="text-[10px] text-slate-500">NIK: {{ substr($sub->applicant_nik, 0, 6) }}******{{ substr($sub->applicant_nik, -4) }}</p>
                                        <p class="text-[10px] text-slate-500"><i class="fa-brands fa-whatsapp text-emerald-600"></i> {{ $sub->applicant_phone }}</p>
                                    </td>

                                    <!-- Kolom 4: Peristiwa (Tgl / Tempat) -->
                                    <td class="px-4 py-3.5">
                                        <span class="text-[10px] font-semibold flex items-center gap-1 {{ $sub->certificate_type === 'birth' ? 'text-teal-700' : 'text-rose-700' }}">
                                            <i class="fa-solid {{ $sub->certificate_type === 'birth' ? 'fa-cake-candles' : 'fa-cross' }} text-[9px]"></i>
                                            {{ $sub->certificate_type === 'birth' ? 'Tgl Lahir:' : 'Tgl Wafat:' }}
                                        </span>
                                        <p class="font-medium text-slate-800">{{ $sub->event_date ? $sub->event_date->translatedFormat('d M Y') : '-' }}</p>
                                        <p class="text-[10px] text-slate-500">{{ $sub->event_place ?: '-' }}</p>
                                    </td>

                                    <!-- Kolom 5: Tanggal Pengajuan -->
                                    <td class="px-4 py-3.5">
                                        <p class="text-slate-700 font-medium">{{ $sub->created_at->translatedFormat('d M Y') }}</p>
                                        <span class="text-[10px] text-slate-400 block">{{ $sub->created_at->diffForHumans() }}</span>
                                    </td>

                                    <!-- Kolom 6: Status -->
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $sub->status_badge_class }}">
                                            {{ $sub->status_label }}
                                        </span>
                                    </td>

                                    <!-- Kolom 7: Aksi -->
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('tracking.show', ['type' => $sub->certificate_type, 'registrationNo' => $sub->registration_no]) }}" class="p-1.5 bg-teal-50 hover:bg-[#095b8c] text-[#095b8c] hover:text-white rounded-lg transition border border-teal-200" title="Detail Timeline & Status">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('tracking.print_receipt', ['type' => $sub->certificate_type, 'registrationNo' => $sub->registration_no]) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-slate-800 text-slate-700 hover:text-white rounded-lg transition border border-slate-200" title="Cetak Bukti Pendaftaran">
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
                        @if($search || $status || ($type && $type !== 'all'))
                            Tidak ada pengajuan yang sesuai dengan kriteria filter pencarian Anda.
                        @else
                            Belum ada permohonan akte kelahiran maupun akte kematian yang terdaftar untuk Nomor KK Anda.
                        @endif
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-2.5">
                        <a href="{{ route('birth.create') }}" class="inline-flex items-center gap-1.5 bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-xs px-4 py-2 rounded-lg transition shadow-xs">
                            <i class="fa-solid fa-baby"></i> Ajukan Akte Kelahiran
                        </a>
                        <a href="{{ route('death.create') }}" class="inline-flex items-center gap-1.5 bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs px-4 py-2 rounded-lg transition shadow-xs">
                            <i class="fa-solid fa-book-skull"></i> Ajukan Akte Kematian
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
