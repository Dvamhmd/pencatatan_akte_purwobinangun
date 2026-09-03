@extends('layouts.app')

@section('title', 'Menu Pencatatan Akte Kelahiran & Kematian')

@section('content')
<div class="space-y-6">

    <!-- 1. Header Section & Card Menu Pelayanan Administrasi Kependudukan -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden dissolve-card">
        <div class="bg-[#095b8c] text-white px-4 py-2.5 flex items-center justify-between">
            <h2 class="font-bold text-xs md:text-sm tracking-wide uppercase flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-amber-300"></i> MENU PELAYANAN ADMINISTRASI KEPENDUDUKAN
            </h2>
            <span class="text-[11px] bg-[#059cb8] px-2.5 py-0.5 rounded text-white font-semibold">Kalurahan Purwobinangun</span>
        </div>

        <!-- Banner Selamat Datang & Penegasan Sub-Menu -->
        <div class="p-5 md:p-6 bg-gradient-to-r from-teal-50/70 via-white to-sky-50/50 border-b border-slate-100">
            <div class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#095b8c] bg-sky-100 px-2.5 py-0.5 rounded-full">
                        <i class="fa-solid fa-link text-[#059cb8]"></i> Terintegrasi Web purwobinangun.desa.id
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">
                        <i class="fa-solid fa-circle-check"></i> Layanan Resmi 100% Gratis
                    </span>
                </div>

                <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Pencatatan & Pengurusan Surat Pengantar Akte Kelahiran dan Akte Kematian
                </h2>
                <p class="text-xs md:text-sm text-slate-600 mt-2 leading-relaxed">
                    Selamat datang di menu pelayanan kependudukan Pemerintah Kalurahan Purwobinangun. Warga dapat mengajukan permohonan penerbitan surat pengantar pembuatan akta secara online, mengunggah berkas persyaratan, dan memantau proses verifikasi secara transparan.
                </p>

                @if(Auth::check() && Auth::user()->isWarga())
                    <div class="mt-4 p-3.5 bg-white rounded-xl border border-teal-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-100 text-[#095b8c] flex items-center justify-center text-lg font-bold shrink-0">
                                <i class="fa-solid fa-circle-user"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Halo, {{ Auth::user()->name }} (NIK: {{ Auth::user()->nik }})</p>
                                <p class="text-[11px] text-slate-500">Anda masuk dengan akun warga terdaftar. Data profil & kontak dapat diperbarui kapan saja.</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.index') }}" class="inline-flex items-center justify-center gap-1.5 bg-teal-50 hover:bg-[#095b8c] text-[#095b8c] hover:text-white border border-teal-200 font-bold text-xs px-3.5 py-2 rounded-lg transition shrink-0 shadow-2xs">
                            <i class="fa-solid fa-user-pen"></i> Ubah Profil & Data Warga
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Dua Kartu Layanan Utama -->
        <div class="p-5 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Kartu Layanan Akte Kelahiran -->
            <div class="bg-white border-2 border-teal-100 hover:border-[#059cb8] rounded-xl overflow-hidden shadow-xs civic-card flex flex-col justify-between dissolve-card">
                <div>
                    <!-- Header Kartu -->
                    <div class="bg-gradient-to-br from-[#095b8c] to-[#059cb8] p-5 text-white relative">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-amber-400 text-slate-950 font-bold text-[10px] uppercase px-2.5 py-0.5 rounded shadow-xs">
                                Layanan Kelahiran
                            </span>
                            <i class="fa-solid fa-baby text-3xl text-white/30"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white leading-snug">
                            Pengajuan Akte Kelahiran
                        </h3>
                        <p class="text-xs text-teal-100 mt-1">
                            Penerbitan surat pengantar kelahiran bagi anak/bayi warga Kalurahan Purwobinangun.
                        </p>
                    </div>

                    <!-- Body Info -->
                    <div class="p-4 space-y-2.5 text-xs text-slate-600 bg-slate-50/50">
                        <div class="flex items-center gap-2 text-[11px] text-slate-500 pb-2 border-b border-slate-200/60">
                            <span class="flex items-center gap-1"><i class="fa-solid fa-clock text-[#059cb8]"></i> Proses 1-2 Hari Kerja</span>
                            <span>•</span>
                            <span class="text-emerald-600 font-semibold flex items-center gap-1"><i class="fa-solid fa-check-circle"></i> Gratis</span>
                        </div>
                        <ul class="space-y-1.5 text-[11px]">
                            <li class="flex items-start gap-1.5">
                                <i class="fa-solid fa-file-lines text-[#059cb8] mt-0.5"></i>
                                <span>Surat Keterangan Lahir (RS/Bidan/Puskesmas)</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <i class="fa-solid fa-file-lines text-[#059cb8] mt-0.5"></i>
                                <span>Buku Nikah / Akta Perkawinan Orang Tua</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <i class="fa-solid fa-file-lines text-[#059cb8] mt-0.5"></i>
                                <span>Kartu Keluarga (KK) & KTP-el Orang Tua</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('birth.create') }}" class="w-full text-center bg-[#095b8c] hover:bg-[#059cb8] text-white font-bold text-xs py-2.5 px-4 rounded-lg shadow-xs transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> Isi Formulir Akte Kelahiran
                    </a>
                </div>
            </div>

            <!-- Kartu Layanan Akte Kematian -->
            <div class="bg-white border-2 border-rose-100 hover:border-rose-500 rounded-xl overflow-hidden shadow-xs civic-card flex flex-col justify-between dissolve-card">
                <div>
                    <!-- Header Kartu -->
                    <div class="bg-gradient-to-br from-rose-700 to-rose-900 p-5 text-white relative">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-amber-400 text-slate-950 font-bold text-[10px] uppercase px-2.5 py-0.5 rounded shadow-xs">
                                Layanan Kematian
                            </span>
                            <i class="fa-solid fa-book-skull text-3xl text-white/30"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white leading-snug">
                            Pengajuan Akte Kematian
                        </h3>
                        <p class="text-xs text-rose-100 mt-1">
                            Penerbitan surat pengantar pelaporan kematian warga di wilayah Kalurahan Purwobinangun.
                        </p>
                    </div>

                    <!-- Body Info -->
                    <div class="p-4 space-y-2.5 text-xs text-slate-600 bg-slate-50/50">
                        <div class="flex items-center gap-2 text-[11px] text-slate-500 pb-2 border-b border-slate-200/60">
                            <span class="flex items-center gap-1"><i class="fa-solid fa-clock text-rose-600"></i> Proses 1-2 Hari Kerja</span>
                            <span>•</span>
                            <span class="text-emerald-600 font-semibold flex items-center gap-1"><i class="fa-solid fa-check-circle"></i> Gratis</span>
                        </div>
                        <ul class="space-y-1.5 text-[11px]">
                            <li class="flex items-start gap-1.5">
                                <i class="fa-solid fa-file-lines text-rose-600 mt-0.5"></i>
                                <span>Surat Kematian dari Dokter / Rumah Sakit / RT</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <i class="fa-solid fa-file-lines text-rose-600 mt-0.5"></i>
                                <span>KTP-el Asli Almarhum/Almarhumah</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <i class="fa-solid fa-file-lines text-rose-600 mt-0.5"></i>
                                <span>Kartu Keluarga (KK) & KTP-el Pelapor (Ahli Waris)</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('death.create') }}" class="w-full text-center bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs py-2.5 px-4 rounded-lg shadow-xs transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> Isi Formulir Akte Kematian
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- 2. Quick Tracker Box: Lacak Status Pengajuan Akta Anda -->
    <div class="bg-gradient-to-r from-[#095b8c] via-[#059cb8] to-[#3887c2] rounded-xl p-5 text-white shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 dissolve-card">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl font-bold text-amber-300 shrink-0">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-sm md:text-base text-white">Lacak Status Pengajuan Akta Anda</h3>
                <p class="text-xs text-teal-100">Masukkan Nomor Registrasi (misal: AKL-20260901-0001) atau NIK Pemohon untuk cek tahapan proses.</p>
            </div>
        </div>
        <form action="{{ route('tracking.index') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
            <input type="text" name="keyword" placeholder="Nomor Registrasi / NIK..." required class="bg-white px-3.5 py-2 text-xs rounded-lg border border-teal-300 focus:outline-none focus:ring-2 focus:ring-amber-400 text-slate-800 font-medium placeholder-slate-400 w-full md:w-64">
            <button type="submit" class="bg-amber-400 hover:bg-amber-500 text-slate-950 font-bold text-xs px-4 py-2 rounded-lg transition whitespace-nowrap shadow-xs">
                Lacak
            </button>
        </form>
    </div>

    <!-- 3. Alur Pelayanan 4 Langkah: 4 Tahapan Pengurusan Surat Pengantar Akta -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5 md:p-6 dissolve-card">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-5 flex items-center gap-2 pb-2 border-b border-slate-100">
            <i class="fa-solid fa-route text-[#059cb8]"></i> 4 Tahapan Pengurusan Surat Pengantar Akta
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-center relative dissolve-card">
                <span class="absolute -top-2.5 left-4 bg-[#095b8c] text-white text-[10px] font-bold w-6 h-6 rounded-full flex items-center justify-center">1</span>
                <i class="fa-solid fa-file-pen text-2xl text-[#059cb8] mb-2 mt-1"></i>
                <h4 class="font-bold text-xs text-slate-800 mb-1">Isi Formulir Online</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed">Lengkapi formulir permohonan dan unggah foto/scan dokumen persyaratan asli.</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-center relative dissolve-card">
                <span class="absolute -top-2.5 left-4 bg-[#095b8c] text-white text-[10px] font-bold w-6 h-6 rounded-full flex items-center justify-center">2</span>
                <i class="fa-solid fa-receipt text-2xl text-[#059cb8] mb-2 mt-1"></i>
                <h4 class="font-bold text-xs text-slate-800 mb-1">Dapatkan Nomor Resi</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed">Simpan atau cetak tanda terima bukti pendaftaran yang memuat QR Code.</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-center relative dissolve-card">
                <span class="absolute -top-2.5 left-4 bg-[#095b8c] text-white text-[10px] font-bold w-6 h-6 rounded-full flex items-center justify-center">3</span>
                <i class="fa-solid fa-user-check text-2xl text-[#059cb8] mb-2 mt-1"></i>
                <h4 class="font-bold text-xs text-slate-800 mb-1">Verifikasi Petugas</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed">Petugas pelayanan Kalurahan memeriksa kelengkapan dan keabsahan berkas.</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-center relative dissolve-card">
                <span class="absolute -top-2.5 left-4 bg-[#095b8c] text-white text-[10px] font-bold w-6 h-6 rounded-full flex items-center justify-center">4</span>
                <i class="fa-solid fa-envelope-circle-check text-2xl text-[#059cb8] mb-2 mt-1"></i>
                <h4 class="font-bold text-xs text-slate-800 mb-1">Surat Pengantar Terbit</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed">Ambil surat pengantar fisik di Kantor Kalurahan untuk diteruskan ke Disdukcapil.</p>
            </div>

        </div>
    </div>

    <!-- 4. Statistik Pelayanan Kependudukan Kalurahan -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-5 md:p-6 dissolve-card">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center justify-between pb-2 border-b border-slate-100">
            <span>Statistik Pelayanan Kependudukan Kalurahan</span>
            <i class="fa-solid fa-chart-simple text-[#059cb8]"></i>
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
            <div class="p-3 bg-teal-50 rounded-lg border border-teal-100 dissolve-card">
                <p class="text-xl font-extrabold text-[#095b8c]">{{ $stats['birth_total'] }}</p>
                <p class="text-[11px] text-slate-600 font-medium">Pengajuan Kelahiran</p>
            </div>
            <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-100 dissolve-card">
                <p class="text-xl font-extrabold text-emerald-600">{{ $stats['birth_completed'] }}</p>
                <p class="text-[11px] text-slate-600 font-medium">Kelahiran Selesai</p>
            </div>
            <div class="p-3 bg-rose-50 rounded-lg border border-rose-100 dissolve-card">
                <p class="text-xl font-extrabold text-rose-600">{{ $stats['death_total'] }}</p>
                <p class="text-[11px] text-slate-600 font-medium">Pengajuan Kematian</p>
            </div>
            <div class="p-3 bg-indigo-50 rounded-lg border border-indigo-100 dissolve-card">
                <p class="text-xl font-extrabold text-indigo-600">{{ $stats['death_completed'] }}</p>
                <p class="text-[11px] text-slate-600 font-medium">Kematian Selesai</p>
            </div>
        </div>
    </div>


</div>
@endsection
