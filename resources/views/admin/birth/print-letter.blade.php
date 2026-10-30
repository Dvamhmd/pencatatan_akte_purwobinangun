<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Akte Kelahiran - {{ $birth->registration_no }}</title>
    
    <!-- Google Fonts for Serif Body and Javanese Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Javanese:wght@400;700&family=Tinos:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Times New Roman', Times, 'Tinos', 'Bookman Old Style', serif;
            color: #000;
        }

        .font-javanese {
            font-family: 'Noto Sans Javanese', serif;
        }

        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
        }

        @media print {
            .no-print { 
                display: none !important; 
            }
            body { 
                background: white !important; 
                padding: 0 !important; 
                margin: 0 !important;
                color: black !important; 
            }
            .a4-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 md:p-8 antialiased">

    <!-- Action Toolbar (No Print) -->
    <div class="max-w-[210mm] mx-auto mb-4 no-print flex items-center justify-between">
        @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ route('admin.birth.show', $birth) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Pengajuan
            </a>
        @else
            <a href="{{ url()->previous() ?: route('submissions.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        @endif
        <button onclick="window.print()" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak Surat Keterangan (A4)
        </button>
    </div>

    <!-- Official Letter Sheet (A4 Size Container) -->
    <div class="a4-container max-w-[210mm] mx-auto bg-white px-12 py-10 rounded-sm shadow-md border border-slate-300 text-black">
        
        <!-- Kop Surat Resmi -->
        <div class="relative pb-1">
            <!-- Logo Sleman -->
            <div class="absolute left-0 top-1">
                <img src="{{ asset('images/logo-sleman.png') }}" alt="Logo Sleman" class="w-20 h-24 object-contain">
            </div>

            <!-- Teks Kop Surat -->
            <div class="text-center pl-16 pr-2">
                <h4 class="text-[14pt] font-bold tracking-wide uppercase leading-tight">
                    PEMERINTAH KABUPATEN SLEMAN
                </h4>
                <h4 class="text-[14pt] font-bold tracking-wide uppercase leading-tight mt-0.5">
                    KAPANEWON PAKEM
                </h4>
                <h3 class="text-[16pt] font-extrabold tracking-wide uppercase leading-tight mt-0.5">
                    PEMERINTAH KALURAHAN PURWOBINANGUN
                </h3>
                <div class="font-javanese text-[12pt] leading-tight my-0.5">
                    ꦥꦩꦫꦺꦤ꧀ꦠꦃꦏꦭꦸꦫꦲꦤ꧀ꦥꦸꦂꦮꦧꦶꦤꦔꦸꦤ꧀
                </div>
                <p class="text-[9pt] leading-tight">
                    Alamat : Jl. Pakem-Turi Km.04 Watuadeg Purwobinangun Pakem Sleman Yogyakarta. 55582
                </p>
                <p class="text-[9pt] leading-tight mt-0.5">
                    Laman : www.purwobinangun.slemankab.go.id Surel : puwobinangun@slemankab.go.id
                </p>
            </div>

            <!-- Garis Double Pembatas Kop -->
            <div class="mt-2.5" style="border-top: 2.5px solid #000; border-bottom: 1px solid #000; height: 3px;"></div>
        </div>

        <!-- Judul Surat -->
        <div class="text-center my-5">
            <h2 class="text-[13pt] font-bold tracking-wider uppercase underline">
                SURAT KETERANGAN
            </h2>
            <p class="text-[11pt] mt-1 tracking-wide">
                Nomor : 474.1/ &nbsp;&nbsp;{{ substr($birth->registration_no, -4) }}&nbsp;&nbsp; / &nbsp;&nbsp;{{ $birth->created_at ? $birth->created_at->format('m') : date('m') }}&nbsp;&nbsp; / &nbsp;&nbsp;{{ $birth->created_at ? $birth->created_at->format('Y') : date('Y') }}
            </p>
        </div>

        <!-- Isi Surat -->
        <div class="text-[11pt] leading-[1.65] space-y-4 text-black">
            
            <!-- Yang bertandatangan di bawah ini -->
            <div>
                <table class="w-full">
                    <tr>
                        <td class="w-64 align-top">Yang bertandatangan di bawah ini</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top"></td>
                    </tr>
                </table>
                <table class="w-full ml-10 mt-1">
                    <tr>
                        <td class="w-36 align-top">Nama</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top font-bold">R. Heri Suasana</td>
                    </tr>
                    <tr>
                        <td class="w-36 align-top">Jabatan</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top">Lurah Purwobinangun</td>
                    </tr>
                </table>
            </div>

            <!-- Dengan ini menerangkan bahwa -->
            <div>
                <table class="w-full">
                    <tr>
                        <td class="w-64 align-top">Dengan ini menerangkan bahwa</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top"></td>
                    </tr>
                </table>
                <table class="w-full ml-10 mt-1">
                    <tr>
                        <td class="w-36 align-top">Nama Lengkap</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top font-bold uppercase">{{ $birth->applicant_name ?: ($birth->father_name ?: ($birth->mother_name ?: '-')) }}</td>
                    </tr>
                    <tr>
                        <td class="w-36 align-top">NIK</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top">{{ $birth->applicant_nik ?: ($birth->father_nik ?: ($birth->mother_nik ?: '-')) }}</td>
                    </tr>
                    <tr>
                        <td class="w-36 align-top">Tanggal Lahir</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top">
                            @if($birth->applicant_birth_date)
                                {{ \Carbon\Carbon::parse($birth->applicant_birth_date)->translatedFormat('d F Y') }}
                            @elseif($birth->father_birth_date)
                                {{ \Carbon\Carbon::parse($birth->father_birth_date)->translatedFormat('d F Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="w-36 align-top">Pekerjaan</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top">{{ $birth->applicant_job ?: ($birth->father_job ?: ($birth->mother_job ?: '-')) }}</td>
                    </tr>
                    <tr>
                        <td class="w-36 align-top">Alamat</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top leading-snug">
                            @if($birth->padukuhan || $birth->rt)
                                {{ $birth->padukuhan ? 'Padukuhan ' . $birth->padukuhan . ', ' : '' }}{{ $birth->rt ? 'RT ' . $birth->rt . ' / RW ' . $birth->rw : '' }}<br>
                            @elseif($birth->address)
                                {{ $birth->address }}<br>
                            @endif
                            Kalurahan Purwobinangun<br>
                            Kapanewon Pakem<br>
                            Kabupaten Sleman<br>
                            Provinsi Di Yogyakarta
                        </td>
                    </tr>
                    <tr>
                        <td class="w-36 align-top pt-2">Pergi ke</td>
                        <td class="w-4 align-top pt-2">:</td>
                        <td class="align-top pt-2 leading-snug">
                            Dinas Kependudukan dan Catatan Sipil<br>
                            Sleman
                        </td>
                    </tr>
                    <tr>
                        <td class="w-36 align-top pt-2">Kaperluan</td>
                        <td class="w-4 align-top pt-2">:</td>
                        <td class="align-top pt-2">
                            Mohon Akta Kelahiran untuk {{ strtoupper($birth->child_name) }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Paragraf Penutup -->
            <div class="pt-2 space-y-3 text-justify" style="text-indent: 2.5rem;">
                <p>
                    Sehubungan dengan maksud yang bersangkutan, mohon yang berwenang memberikan bantuan serta fasilitas seperlunya.
                </p>
                <p>
                    Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.
                </p>
            </div>

            <!-- Tanda Tangan Lurah Purwobinangun -->
            <div class="pt-6 flex justify-end">
                <div class="text-left w-72 pr-2">
                    <p>
                        Purwobinangun, {{ $birth->created_at ? $birth->created_at->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </p>
                    <p class="mt-0.5">
                        Lurah Purwobinangun
                    </p>
                    
                    <div class="h-24"></div>

                    <p class="font-medium">
                        R. Heri Suasana
                    </p>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
