<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengantar Kelahiran - {{ $birth->registration_no }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: black !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 md:p-8 text-slate-900 antialiased font-sans">

    <!-- Action Toolbar (No Print) -->
    <div class="max-w-3xl mx-auto mb-4 no-print flex items-center justify-between">
        <a href="{{ route('admin.birth.show', $birth) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail
        </a>
        <button onclick="window.print()" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak Surat Pengantar Resmi
        </button>
    </div>

    <!-- Official Letter Sheet (Format Surat Dinas Kelurahan) -->
    <div class="max-w-3xl mx-auto bg-white p-10 rounded-xl shadow-sm border border-slate-300 print-container">
        
        <!-- Kop Surat Resmi -->
        <div class="border-b-4 border-double border-slate-900 pb-3 text-center relative">
            <div class="absolute left-2 top-0">
                <div class="w-16 h-20 flex items-center justify-center">
                    <img src="{{ asset('images/logo-sleman.png') }}" alt="Logo Sleman" class="max-h-full max-w-full object-contain">
                </div>
            </div>
            <h4 class="text-sm uppercase tracking-widest font-bold">PEMERINTAH KABUPATEN SLEMAN</h4>
            <h4 class="text-sm uppercase tracking-wider font-bold">KAPANEWON PAKEM</h4>
            <h2 class="text-xl font-extrabold tracking-wide mt-0.5">PEMERINTAH KALURAHAN PURWOBINANGUN</h2>
            <p class="text-xs text-slate-700 mt-1">Alamat: Jl. Boyong, Purwobinangun, Pakem, Sleman, D.I. Yogyakarta 55582 | Telp: (0274) 895123</p>
        </div>

        <!-- Judul Surat -->
        <div class="text-center my-6">
            <h3 class="text-sm font-extrabold uppercase tracking-wide underline">
                SURAT KETERANGAN / PENGANTAR KELAHIRAN
            </h3>
            <p class="text-xs text-slate-700 font-mono mt-1">Nomor: 474.1 / {{ substr($birth->registration_no, -4) }} / PEM-PW / {{ date('Y') }}</p>
        </div>

        <!-- Isi Surat -->
        <div class="space-y-4 text-xs leading-relaxed text-slate-800">
            <p>
                Yang bertanda tangan di bawah ini, Lurah Purwobinangun, Kapanewon Pakem, Kabupaten Sleman, Daerah Istimewa Yogyakarta, dengan ini menerangkan dengan sebenarnya bahwa:
            </p>

            <!-- Data Bayi -->
            <table class="w-full ml-4">
                <tr>
                    <td class="py-1 w-44 font-medium text-slate-600">Nama Anak</td>
                    <td class="py-1 font-bold">: {{ strtoupper($birth->child_name) }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium text-slate-600">Jenis Kelamin</td>
                    <td class="py-1 font-medium">: {{ $birth->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium text-slate-600">Tempat, Tanggal Lahir</td>
                    <td class="py-1 font-medium">: {{ $birth->birth_place }}, {{ $birth->birth_date->translatedFormat('d F Y') }} (Pukul: {{ $birth->birth_time ?? '-' }})</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium text-slate-600">Kelahiran Ke-</td>
                    <td class="py-1 font-medium">: {{ $birth->birth_order }} (Jenis: {{ $birth->birth_type }})</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium text-slate-600">Penolong Kelahiran</td>
                    <td class="py-1 font-medium">: {{ $birth->birth_helper }}</td>
                </tr>
            </table>

            <p class="mt-3">Adalah benar anak kandung dari pasangan suami-istri:</p>

            <!-- Data Orang Tua -->
            <table class="w-full ml-4">
                <tr>
                    <td class="py-1 w-44 font-medium text-slate-600">Nama Ayah</td>
                    <td class="py-1 font-bold">: {{ strtoupper($birth->father_name) }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium text-slate-600">NIK Ayah</td>
                    <td class="py-1 font-mono">: {{ $birth->father_nik }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium text-slate-600">Nama Ibu</td>
                    <td class="py-1 font-bold">: {{ strtoupper($birth->mother_name) }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium text-slate-600">NIK Ibu</td>
                    <td class="py-1 font-mono">: {{ $birth->mother_nik }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium text-slate-600">Alamat Domisili</td>
                    <td class="py-1 font-medium">: Padukuhan {{ $birth->padukuhan }}, RT {{ $birth->rt }} / RW {{ $birth->rw }}, Kalurahan Purwobinangun, Pakem, Sleman</td>
                </tr>
            </table>

            <p class="mt-3 text-justify">
                Surat pengantar ini diberikan kepada yang bersangkutan sebagai kelengkapan berkas permohonan penerbitan <strong>Kutipan Akta Kelahiran</strong> pada Dinas Kependudukan dan Pencatatan Sipil Kabupaten Sleman.
            </p>
            <p>
                Demikian surat keterangan kelahiran ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
            </p>
        </div>

        <!-- Tanda Tangan Lurah Purwobinangun -->
        <div class="pt-8 flex justify-between items-end text-xs">
            <div class="text-center w-36">
                <div class="w-20 h-20 mx-auto border border-slate-400 p-1 rounded flex flex-col items-center justify-center bg-slate-50">
                    <i class="fa-solid fa-qrcode text-4xl text-slate-800"></i>
                </div>
                <p class="text-[9px] text-slate-500 mt-1">ID: {{ $birth->registration_no }}</p>
            </div>

            <div class="text-center">
                <p class="text-slate-700">Purwobinangun, {{ date('d F Y') }}</p>
                <p class="font-bold text-slate-900 mt-0.5">Lurah Purwobinangun</p>
                
                <div class="h-20 flex items-center justify-center">
                    <span class="text-[10px] font-bold text-teal-900 bg-teal-50 px-3 py-1 rounded border border-teal-300">
                        [ TANDATANGAN ELEKTRONIK DISAHKAN ]
                    </span>
                </div>

                <p class="font-bold text-slate-900 underline uppercase">H. R. GOSTOMBO</p>
                <p class="text-[11px] text-slate-600">Pemerintah Kalurahan Purwobinangun</p>
            </div>
        </div>

    </div>

</body>
</html>
