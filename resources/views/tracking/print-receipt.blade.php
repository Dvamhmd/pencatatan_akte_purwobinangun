<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Pengajuan - {{ $data->registration_no }}</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 md:p-8 text-slate-800 antialiased font-sans">

    <!-- Floating Action Toolbar (No Print) -->
    <div class="max-w-2xl mx-auto mb-4 no-print flex items-center justify-end">
        <button onclick="window.print()" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
        </button>
    </div>

    <!-- Official Receipt Sheet -->
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-300 print-container">
        
        <!-- Header Kop Kalurahan -->
        <div class="border-b-2 border-slate-800 pb-4 text-center relative">
            <div class="absolute left-0 top-0">
                <div class="w-14 h-16 flex items-center justify-center">
                    <img src="{{ asset('images/logo-sleman.png') }}" alt="Logo Sleman" class="max-h-full max-w-full object-contain">
                </div>
            </div>
            <h4 class="text-xs uppercase tracking-widest font-bold text-slate-600">PEMERINTAH KABUPATEN SLEMAN</h4>
            <h4 class="text-xs uppercase tracking-wider font-bold text-slate-700">KAPANEWON PAKEM</h4>
            <h2 class="text-lg font-extrabold text-slate-900">PEMERINTAH KALURAHAN PURWOBINANGUN</h2>
            <p class="text-[10px] text-slate-500 mt-0.5">Alamat: Jl. Boyong, Purwobinangun, Pakem, Sleman, D.I. Yogyakarta 55582</p>
        </div>

        <div class="text-center my-5">
            <h3 class="text-sm font-extrabold uppercase tracking-wide text-slate-900">
                TANDA TERIMA PENDAFTARAN PELAYANAN KEPENDUDUKAN
            </h3>
            <p class="text-xs text-slate-500">Nomor Registrasi: <span class="font-bold text-[#0b7c89]">{{ $data->registration_no }}</span></p>
        </div>

        <!-- Detail Data -->
        <div class="space-y-4 text-xs">
            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                <table class="w-full">
                    <tr class="border-b border-slate-200/60">
                        <td class="py-1.5 text-slate-500 w-40">Jenis Permohonan</td>
                        <td class="py-1.5 font-bold text-slate-900">: {{ $type === 'birth' ? 'Surat Pengantar Akte Kelahiran' : 'Surat Pengantar Akte Kematian' }}</td>
                    </tr>
                    <tr class="border-b border-slate-200/60">
                        <td class="py-1.5 text-slate-500">Tanggal Pengajuan</td>
                        <td class="py-1.5 font-medium text-slate-900">: {{ $data->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
                    </tr>
                    <tr class="border-b border-slate-200/60">
                        <td class="py-1.5 text-slate-500">Nama Yang Dimohonkan</td>
                        <td class="py-1.5 font-bold text-slate-900">: {{ $type === 'birth' ? $data->child_name : $data->deceased_name }}</td>
                    </tr>
                    <tr class="border-b border-slate-200/60">
                        <td class="py-1.5 text-slate-500">Nama Pemohon / Pelapor</td>
                        <td class="py-1.5 font-medium text-slate-900">: {{ $data->applicant_name }} (NIK: {{ $data->applicant_nik }})</td>
                    </tr>
                    <tr class="border-b border-slate-200/60">
                        <td class="py-1.5 text-slate-500">Hubungan Keluarga</td>
                        <td class="py-1.5 font-medium text-slate-900">: {{ $data->applicant_relation }}</td>
                    </tr>
                    <tr class="border-b border-slate-200/60">
                        <td class="py-1.5 text-slate-500">Alamat Padukuhan</td>
                        <td class="py-1.5 font-medium text-slate-900">: Padukuhan {{ $data->padukuhan }}, RT {{ $data->rt }} / RW {{ $data->rw }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 text-slate-500">Status Permohonan</td>
                        <td class="py-1.5 font-bold text-[#0b7c89]">: {{ $data->status_label }}</td>
                    </tr>
                </table>
            </div>

            <!-- Petunjuk Pengambilan -->
            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 text-[11px] text-amber-900 space-y-1">
                <p class="font-bold"><i class="fa-solid fa-circle-info mr-1"></i> Catatan & Petunjuk:</p>
                <ol class="list-decimal list-inside space-y-0.5 text-amber-800">
                    <li>Simpan lembar bukti ini sebagai tanda terima resmi permohonan Anda.</li>
                    <li>Status berkas dapat dicek secara online melalui website resmi dengan memasukkan Nomor Registrasi.</li>
                    <li>Saat pengambilan surat pengantar di Kantor Kalurahan, mohon membawa <strong>berkas asli</strong> untuk verifikasi fisik petugas.</li>
                </ol>
            </div>

            <!-- Tanda Tangan & QR Code Stamp -->
            <div class="pt-6 flex justify-between items-end">
                <div class="text-center w-36">
                    <div class="w-24 h-24 mx-auto border-2 border-slate-800 p-1.5 rounded flex flex-col items-center justify-center bg-slate-50">
                        <i class="fa-solid fa-qrcode text-5xl text-slate-800"></i>
                        <span class="text-[8px] font-mono mt-1 text-slate-600">{{ $data->registration_no }}</span>
                    </div>
                    <p class="text-[9px] text-slate-400 mt-1">Validasi Digital Sistem</p>
                </div>

                <div class="text-center text-xs">
                    <p class="text-slate-600">Purwobinangun, {{ date('d F Y') }}</p>
                    <p class="font-bold text-slate-800 mt-0.5">Petugas Pelayanan Kalurahan</p>
                    
                    <div class="h-16 flex items-center justify-center">
                        <span class="text-[10px] font-bold text-teal-800 bg-teal-50 px-2 py-1 rounded border border-teal-200">
                            TERDAFTAR ELEKTRONIK
                        </span>
                    </div>

                    <p class="font-bold text-slate-900 underline">PELAYANAN PURWOBINANGUN</p>
                    <p class="text-[10px] text-slate-500">NIP. 19850712 201001 1 012</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
