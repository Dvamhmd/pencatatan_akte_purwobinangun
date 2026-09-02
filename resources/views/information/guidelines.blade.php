@extends('layouts.app')

@section('title', 'Syarat & Panduan Pengurusan Akta')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-[#0b7c89] text-white px-4 py-2.5 flex items-center justify-between">
            <h2 class="font-bold text-xs md:text-sm tracking-wide uppercase flex items-center gap-2">
                <i class="fa-solid fa-book-open text-amber-300"></i> PANDUAN & PERSYARATAN ADMINISTRASI KEPENDUDUKAN
            </h2>
            <span class="text-[11px] bg-teal-800/80 px-2 py-0.5 rounded text-teal-100 font-medium">Kalurahan Purwobinangun</span>
        </div>

        <div class="p-5 md:p-6 space-y-6">
            
            <!-- Panduan Akte Kelahiran -->
            <div class="border border-teal-200 rounded-xl overflow-hidden shadow-xs">
                <div class="bg-teal-50 px-4 py-3 border-b border-teal-200 flex items-center justify-between">
                    <h3 class="font-bold text-sm text-[#0b7c89] flex items-center gap-2">
                        <i class="fa-solid fa-baby text-lg"></i> Persyaratan Pembuatan Akte Kelahiran
                    </h3>
                    <a href="{{ route('birth.create') }}" class="text-xs bg-[#0b7c89] hover:bg-[#065b65] text-white font-semibold px-3 py-1.5 rounded-lg transition">
                        Ajukan Sekarang
                    </a>
                </div>
                <div class="p-4 bg-white space-y-4 text-xs text-slate-700">
                    <p class="leading-relaxed">
                        Permohonan akte kelahiran dianjurkan dilakukan selambat-lambatnya <strong>60 (enam puluh) hari</strong> sejak peristiwa kelahiran bayi. Berkas yang harus disiapkan dalam bentuk scan/foto asli:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/60 flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-teal-100 text-[#0b7c89] font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                            <div>
                                <h4 class="font-bold text-slate-800">Surat Keterangan Kelahiran</h4>
                                <p class="text-[11px] text-slate-500">Asli dari Rumah Sakit / Puskesmas / Bidan penolong persalinan.</p>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/60 flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-teal-100 text-[#0b7c89] font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                            <div>
                                <h4 class="font-bold text-slate-800">Buku Nikah / Akta Perkawinan</h4>
                                <p class="text-[11px] text-slate-500">Fotokopi / scan legalisir buku nikah orang tua.</p>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/60 flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-teal-100 text-[#0b7c89] font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                            <div>
                                <h4 class="font-bold text-slate-800">Kartu Keluarga (KK)</h4>
                                <p class="text-[11px] text-slate-500">KK orang tua yang beralamat di wilayah Kalurahan Purwobinangun.</p>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/60 flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-teal-100 text-[#0b7c89] font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                            <div>
                                <h4 class="font-bold text-slate-800">KTP-el Kedua Orang Tua & Saksi</h4>
                                <p class="text-[11px] text-slate-500">Foto KTP Ayah, Ibu, serta 2 (dua) orang saksi peristiwa kelahiran.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panduan Akte Kematian -->
            <div class="border border-rose-200 rounded-xl overflow-hidden shadow-xs">
                <div class="bg-rose-50 px-4 py-3 border-b border-rose-200 flex items-center justify-between">
                    <h3 class="font-bold text-sm text-rose-800 flex items-center gap-2">
                        <i class="fa-solid fa-book-skull text-lg"></i> Persyaratan Pembuatan Akte Kematian
                    </h3>
                    <a href="{{ route('death.create') }}" class="text-xs bg-rose-700 hover:bg-rose-800 text-white font-semibold px-3 py-1.5 rounded-lg transition">
                        Ajukan Sekarang
                    </a>
                </div>
                <div class="p-4 bg-white space-y-4 text-xs text-slate-700">
                    <p class="leading-relaxed">
                        Pelaporan kematian wajib dilakukan oleh pihak keluarga / ahli waris selambat-lambatnya <strong>30 (tiga puluh) hari</strong> sejak peristiwa kematian:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/60 flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                            <div>
                                <h4 class="font-bold text-slate-800">Surat Keterangan Kematian</h4>
                                <p class="text-[11px] text-slate-500">Asli dari Rumah Sakit/Dokter atau Surat Pengantar dari RT/RW/Dukuh jika meninggal di rumah.</p>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/60 flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                            <div>
                                <h4 class="font-bold text-slate-800">KTP-el Asli Almarhum/Almarhumah</h4>
                                <p class="text-[11px] text-slate-500">Identitas KTP jenazah yang meninggal dunia.</p>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/60 flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                            <div>
                                <h4 class="font-bold text-slate-800">Kartu Keluarga (KK)</h4>
                                <p class="text-[11px] text-slate-500">Kartu Keluarga yang mencantumkan nama almarhum/almarhumah.</p>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200/60 flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                            <div>
                                <h4 class="font-bold text-slate-800">KTP-el Pelapor & Saksi</h4>
                                <p class="text-[11px] text-slate-500">KTP pelapor (ahli waris) dan saksi yang mengetahui peristiwa kematian.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jam Pelayanan & Bantuan -->
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 flex items-start gap-3">
                <i class="fa-solid fa-headset text-amber-600 text-xl mt-0.5"></i>
                <div>
                    <h4 class="font-bold text-sm text-amber-950">Pusat Informasi & Bantuan Warga</h4>
                    <p class="text-[11px] mt-1 leading-relaxed text-amber-800">
                        Jika menemui kendala teknis atau memiliki pertanyaan mengenai kelengkapan berkas permohonan, silakan hubungi bagian Pelayanan Umum Kalurahan Purwobinangun di nomor telepon <strong>(0274) 895123</strong> atau WhatsApp <strong>0812-3456-7890</strong> pada hari dan jam kerja.
                    </p>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
