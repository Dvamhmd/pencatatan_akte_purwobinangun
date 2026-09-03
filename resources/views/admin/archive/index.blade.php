@extends('layouts.admin')

@section('title', 'Arsip Pengajuan & Akun')
@section('page_title', 'Arsip Pengajuan & Akun Nonaktif')

@section('content')
<div class="space-y-6">

    <!-- Ringkasan Statistik Arsip & Penolakan -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between text-slate-500 mb-1">
                <span class="text-xs font-semibold">Total Arsip & Ditolak</span>
                <i class="fa-solid fa-box-archive text-[#0b7c89]"></i>
            </div>
            <p class="text-2xl font-black text-slate-800">{{ $counts['total'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-2xs">
            <div class="flex items-center justify-between text-amber-600 mb-1">
                <span class="text-xs font-semibold">Akun Warga</span>
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <p class="text-2xl font-black text-amber-700">{{ $counts['citizens'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-teal-200 shadow-2xs">
            <div class="flex items-center justify-between text-teal-600 mb-1">
                <span class="text-xs font-semibold">Akte Kelahiran</span>
                <i class="fa-solid fa-baby"></i>
            </div>
            <p class="text-2xl font-black text-teal-700">{{ $counts['birth'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-rose-200 shadow-2xs">
            <div class="flex items-center justify-between text-rose-600 mb-1">
                <span class="text-xs font-semibold">Akte Kematian</span>
                <i class="fa-solid fa-book-skull"></i>
            </div>
            <p class="text-2xl font-black text-rose-700">{{ $counts['death'] }}</p>
        </div>

    </div>

    <!-- Main Container Card -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        
        <!-- Header & Nav Tabs -->
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-folder-tree text-[#0b7c89]"></i> Pengelolaan Data Arsip & Berkas Ditolak
                </h3>
                <p class="text-xs text-slate-500">Kelola pengajuan atau akun warga yang ditolak/dinonaktifkan, arsipkan secara permanen, atau pulihkan kembali jika diperlukan.</p>
            </div>

            <!-- Form Pencarian Sesuai Tab -->
            <form action="{{ route('admin.archive.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari di data arsip..." class="text-xs px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89] w-full sm:w-64 bg-white">
                
                <button type="submit" class="bg-[#0b7c89] hover:bg-[#065b65] text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-2xs">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.archive.index', ['tab' => $tab]) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs px-2.5 py-2 rounded-lg transition" title="Reset">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Tab Selector Menu -->
        <div class="flex border-b border-slate-200 bg-slate-50 px-5 gap-2 pt-2">
            <a href="{{ route('admin.archive.index', ['tab' => 'citizens']) }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-t-lg transition border-t border-x {{ $tab === 'citizens' ? 'bg-white text-[#0b7c89] border-slate-200 -mb-[1px]' : 'text-slate-500 hover:text-slate-800 border-transparent' }}">
                <i class="fa-solid fa-users"></i>
                <span>Akun Warga</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full {{ $tab === 'citizens' ? 'bg-teal-100 text-teal-800' : 'bg-slate-200 text-slate-600' }}">
                    {{ $counts['citizens'] }}
                </span>
            </a>

            <a href="{{ route('admin.archive.index', ['tab' => 'birth']) }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-t-lg transition border-t border-x {{ $tab === 'birth' ? 'bg-white text-[#0b7c89] border-slate-200 -mb-[1px]' : 'text-slate-500 hover:text-slate-800 border-transparent' }}">
                <i class="fa-solid fa-baby"></i>
                <span>Akte Kelahiran</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full {{ $tab === 'birth' ? 'bg-teal-100 text-teal-800' : 'bg-slate-200 text-slate-600' }}">
                    {{ $counts['birth'] }}
                </span>
            </a>

            <a href="{{ route('admin.archive.index', ['tab' => 'death']) }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-t-lg transition border-t border-x {{ $tab === 'death' ? 'bg-white text-[#0b7c89] border-slate-200 -mb-[1px]' : 'text-slate-500 hover:text-slate-800 border-transparent' }}">
                <i class="fa-solid fa-book-skull"></i>
                <span>Akte Kematian</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full {{ $tab === 'death' ? 'bg-teal-100 text-teal-800' : 'bg-slate-200 text-slate-600' }}">
                    {{ $counts['death'] }}
                </span>
            </a>
        </div>

        <!-- Content Per Tab -->
        <div class="overflow-x-auto">
            
            <!-- TAB 1: AKUN WARGA -->
            @if($tab === 'citizens')
                @if($citizens->count() > 0)
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3.5">Identitas (NIK & KK)</th>
                                <th class="px-4 py-3.5">Nama & Kontak</th>
                                <th class="px-4 py-3.5">Alamat Lengkap</th>
                                <th class="px-4 py-3.5">Alasan / Catatan Petugas</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-4 py-3.5 text-center">Aksi Pengelolaan</th>
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
                                        <p class="text-[11px] text-slate-500"><i class="fa-brands fa-whatsapp text-emerald-600"></i> {{ $citizen->phone }}</p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="text-slate-800 font-medium line-clamp-2">{{ $citizen->address }}</p>
                                        <span class="text-[10px] text-slate-500">RT {{ $citizen->rt }} / RW {{ $citizen->rw }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 max-w-xs">
                                        @if($citizen->rejection_reason)
                                            <p class="text-[11px] text-rose-800 bg-rose-50 p-2 rounded border border-rose-200 italic line-clamp-2">
                                                {{ $citizen->rejection_reason }}
                                            </p>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">- Tidak ada catatan -</span>
                                        @endif
                                        @if($citizen->verified_at)
                                            <span class="text-[10px] text-slate-400 block mt-1">Oleh {{ $citizen->verified_by }} • {{ $citizen->verified_at->translatedFormat('d M Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $citizen->status_badge_class }}">
                                            {{ $citizen->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            <a href="{{ route('admin.citizens.show', $citizen) }}" class="bg-teal-50 hover:bg-teal-100 text-[#0b7c89] font-bold text-[11px] px-2.5 py-1.5 rounded-lg border border-teal-200 transition" title="Lihat Detail Akun">
                                                <i class="fa-solid fa-eye"></i> Detail
                                            </a>



                                            <!-- Tombol Pulihkan & Aktifkan -->
                                            <form action="{{ route('admin.archive.citizen.restore', $citizen) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MEMULIHKAN dan MENGAKTIFKAN kembali akun warga ini?');" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-2.5 py-1.5 rounded-lg transition" title="Pulihkan & Aktifkan Akun">
                                                    <i class="fa-solid fa-rotate-left"></i> Pulihkan
                                                </button>
                                            </form>
                                        </div>
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
                        <p class="font-bold text-sm text-slate-700">Tidak ada data akun warga yang ditolak atau diarsipkan.</p>
                        <p class="text-xs text-slate-500 mt-1">Semua pendaftaran akun warga berjalan normal atau belum ada penolakan.</p>
                    </div>
                @endif

                @if($citizens->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $citizens->links() }}
                    </div>
                @endif
            @endif


            <!-- TAB 2: AKTE KELAHIRAN -->
            @if($tab === 'birth')
                @if($births->count() > 0)
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3.5">No. Registrasi</th>
                                <th class="px-4 py-3.5">Nama Bayi & Ortu</th>
                                <th class="px-4 py-3.5">Pemohon / Wilayah</th>
                                <th class="px-4 py-3.5">Catatan Penolakan / Arsip</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-4 py-3.5 text-center">Aksi Pengelolaan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($births as $birth)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3.5 font-mono font-bold text-[#0b7c89]">
                                        {{ $birth->registration_no }}
                                        <span class="text-[10px] text-slate-400 block font-normal">{{ $birth->created_at->translatedFormat('d M Y') }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-bold text-slate-900">{{ $birth->child_name }} ({{ $birth->gender }})</p>
                                        <span class="text-[10px] text-slate-500">Ortu: {{ $birth->father_name }} & {{ $birth->mother_name }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-slate-800">{{ $birth->applicant_name }}</p>
                                        <span class="text-[10px] text-slate-500">Padukuhan {{ $birth->padukuhan }} • {{ $birth->applicant_phone }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 max-w-xs">
                                        @if($birth->rejection_note)
                                            <p class="text-[11px] text-rose-800 bg-rose-50 p-2 rounded border border-rose-200 italic line-clamp-2">
                                                {{ $birth->rejection_note }}
                                            </p>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">- Tidak ada catatan -</span>
                                        @endif
                                        @if($birth->processed_by)
                                            <span class="text-[10px] text-slate-400 block mt-1">Diproses oleh {{ $birth->processed_by }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $birth->status_badge_class }}">
                                            {{ $birth->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            <a href="{{ route('admin.birth.show', $birth) }}" class="bg-teal-50 hover:bg-teal-100 text-[#0b7c89] font-bold text-[11px] px-2.5 py-1.5 rounded-lg border border-teal-200 transition" title="Lihat Berkas">
                                                <i class="fa-solid fa-eye"></i> Berkas
                                            </a>



                                            <!-- Tombol Pulihkan -->
                                            <form action="{{ route('admin.archive.birth.restore', $birth) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENGEMBALIKAN permohonan ini ke status Menunggu Verifikasi?');" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-2.5 py-1.5 rounded-lg transition" title="Kembalikan ke Menunggu Verifikasi">
                                                    <i class="fa-solid fa-rotate-left"></i> Pulihkan
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12 bg-white">
                        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fa-solid fa-baby text-xl"></i>
                        </div>
                        <p class="font-bold text-sm text-slate-700">Tidak ada pengajuan akte kelahiran yang diarsipkan atau ditolak.</p>
                        <p class="text-xs text-slate-500 mt-1">Data akte kelahiran yang sudah diambil atau ditolak akan tersimpan di sini.</p>
                    </div>
                @endif

                @if($births->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $births->links() }}
                    </div>
                @endif
            @endif


            <!-- TAB 3: AKTE KEMATIAN -->
            @if($tab === 'death')
                @if($deaths->count() > 0)
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3.5">No. Registrasi</th>
                                <th class="px-4 py-3.5">Nama Almarhum/ah & NIK</th>
                                <th class="px-4 py-3.5">Pelapor (Hubungan)</th>
                                <th class="px-4 py-3.5">Catatan Penolakan / Arsip</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-4 py-3.5 text-center">Aksi Pengelolaan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($deaths as $death)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3.5 font-mono font-bold text-rose-700">
                                        {{ $death->registration_no }}
                                        <span class="text-[10px] text-slate-400 block font-normal">{{ $death->created_at->translatedFormat('d M Y') }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-bold text-slate-900">{{ $death->deceased_name }}</p>
                                        <span class="text-[10px] text-slate-500 font-mono">NIK: {{ $death->deceased_nik }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-slate-800">{{ $death->applicant_name }}</p>
                                        <span class="text-[10px] text-slate-500">Hubungan: {{ $death->applicant_relation }} • Padukuhan {{ $death->padukuhan }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 max-w-xs">
                                        @if($death->rejection_note)
                                            <p class="text-[11px] text-rose-800 bg-rose-50 p-2 rounded border border-rose-200 italic line-clamp-2">
                                                {{ $death->rejection_note }}
                                            </p>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">- Tidak ada catatan -</span>
                                        @endif
                                        @if($death->processed_by)
                                            <span class="text-[10px] text-slate-400 block mt-1">Diproses oleh {{ $death->processed_by }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $death->status_badge_class }}">
                                            {{ $death->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            <a href="{{ route('admin.death.show', $death) }}" class="bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] px-2.5 py-1.5 rounded-lg border border-rose-200 transition" title="Lihat Berkas">
                                                <i class="fa-solid fa-eye"></i> Berkas
                                            </a>



                                            <!-- Tombol Pulihkan -->
                                            <form action="{{ route('admin.archive.death.restore', $death) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENGEMBALIKAN permohonan ini ke status Menunggu Verifikasi?');" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-2.5 py-1.5 rounded-lg transition" title="Kembalikan ke Menunggu Verifikasi">
                                                    <i class="fa-solid fa-rotate-left"></i> Pulihkan
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12 bg-white">
                        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fa-solid fa-book-skull text-xl"></i>
                        </div>
                        <p class="font-bold text-sm text-slate-700">Tidak ada pengajuan akte kematian yang ditolak atau diarsipkan.</p>
                        <p class="text-xs text-slate-500 mt-1">Semua berkas akte kematian berjalan dengan lancar.</p>
                    </div>
                @endif

                @if($deaths->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $deaths->links() }}
                    </div>
                @endif
            @endif

        </div>

    </div>

</div>
@endsection
