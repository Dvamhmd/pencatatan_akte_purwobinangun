<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Kematian - {{ $death->deceased_name }} - {{ $death->registration_no }}</title>
    
    <!-- Google Fonts for Arial/Sans Body and Javanese Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Javanese:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- html2pdf for direct A4 PDF download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Arial, Helvetica, 'Plus Jakarta Sans', sans-serif;
            color: #000;
            background-color: #f1f5f9;
        }

        .font-javanese {
            font-family: 'Noto Sans Javanese', serif;
        }

        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm;
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
            .section-block {
                page-break-inside: avoid;
            }
        }

        .dots-line {
            letter-spacing: 2px;
            color: #1e293b;
        }
    </style>
</head>
<body class="p-4 md:p-8 antialiased">

    <!-- Action Toolbar (No Print) -->
    <div class="max-w-[210mm] mx-auto mb-4 no-print flex flex-wrap items-center justify-between gap-3 bg-white p-3.5 rounded-xl shadow-xs border border-slate-200">
        <div class="flex items-center gap-2">
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.death.show', $death) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail
                </a>
            @else
                <a href="{{ url()->previous() ?: route('submissions.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            @endif
        </div>
        
        <div class="flex items-center gap-2">
            <button onclick="downloadPDF()" id="btn-download-pdf" class="bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs px-4 py-2 rounded-lg shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-file-arrow-down"></i> Unduh File PDF (A4)
            </button>
            <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2 rounded-lg shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    @php
        $romans = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $monthNum = (int)($death->death_date ? $death->death_date->format('n') : date('n'));
        $monthRoman = $romans[$monthNum] ?? 'IX';
        $letterYear = $death->death_date ? $death->death_date->format('Y') : date('Y');
        $letterNumberSeq = (int)substr($death->registration_no, -4) ?: '82';

        // Hitung Umur Jenazah
        $deceasedAge = null;
        if ($death->birth_date && $death->death_date) {
            $deceasedAge = $death->birth_date->diffInYears($death->death_date);
        } elseif ($death->birth_date) {
            $deceasedAge = $death->birth_date->diffInYears(now());
        }

        // Hitung Umur Ibu & Ayah jika ada tgl lahir
        $motherAge = $death->mother_birth_date ? $death->mother_birth_date->diffInYears($death->death_date ?? now()) : null;
        $fatherAge = $death->father_birth_date ? $death->father_birth_date->diffInYears($death->death_date ?? now()) : null;

        // Gender formatting
        $genderText = $death->gender === 'L' ? 'LAKI – LAKI' : 'PEREMPUAN';
    @endphp

    <!-- Sheet A4 Container -->
    <div id="letter-content" class="a4-container max-w-[210mm] mx-auto bg-white px-10 sm:px-14 py-8 rounded-sm shadow-md border border-slate-300 text-black text-[10pt] leading-[1.6]">
        
        <!-- ==========================================
             1. KOP SURAT RESMI
        =========================================== -->
        <div class="relative pb-1">
            <!-- Logo Sleman di Kiri -->
            <div class="absolute left-1 top-0">
                <img src="{{ asset('images/logo-sleman.png') }}" alt="Logo Sleman" class="w-16 h-20 sm:w-20 sm:h-24 object-contain">
            </div>

            <!-- Teks Kop Surat Rata Tengah -->
            <div class="text-center pl-16 pr-2">
                <h4 class="text-[12pt] font-bold tracking-wide uppercase leading-tight">
                    PEMERINTAH KABUPATEN SLEMAN
                </h4>
                <h4 class="text-[12pt] font-bold tracking-wide uppercase leading-tight mt-0.5">
                    KAPANEWON PAKEM
                </h4>
                <h3 class="text-[14pt] font-extrabold tracking-wide uppercase leading-tight mt-0.5">
                    PEMERINTAH KALURAHAN PURWOBINANGUN
                </h3>
                <div class="font-javanese text-[11pt] leading-tight my-0.5">
                    ꦥꦩꦫꦺꦤ꧀ꦠꦃꦏꦭꦸꦫꦲꦤ꧀ꦥꦸꦂꦮꦧꦶꦤꦔꦸꦤ꧀
                </div>
                <p class="text-[8pt] leading-tight text-slate-800">
                    Alamat : Jl. Pakem-Turi Km.04 Watuadeg Purwobinangun Pakem Sleman Yogyakarta. 55582
                </p>
                <p class="text-[8pt] leading-tight mt-0.5 text-slate-800">
                    Laman : <span class="underline">www.purwobinangun.slemankab.go.id</span> &nbsp; Surel : desapurwobinangun@slemankab.go.id
                </p>
            </div>

            <!-- Garis Ganda Pembatas Kop Surat -->
            <div class="mt-2.5" style="border-top: 3px solid #000; border-bottom: 1px solid #000; height: 3px;"></div>
        </div>

        <!-- ==========================================
             2. JUDUL SURAT & NOMOR
        =========================================== -->
        <div class="text-center my-4">
            <h2 class="text-[13pt] font-extrabold tracking-wide uppercase">
                SURAT KETERANGAN KEMATIAN
            </h2>
            <p class="text-[10pt] font-bold tracking-wide mt-0.5">
                No. 474.3/{{ $letterNumberSeq }}/{{ $monthRoman }}/{{ $letterYear }}
            </p>
        </div>

        <!-- Data Kepala Keluarga & No KK -->
        <div class="mb-4 text-[10pt]">
            <table class="w-full">
                <tr>
                    <td class="w-48 py-0.5">Nama Kepala Keluarga</td>
                    <td class="w-4 py-0.5">:</td>
                    <td class="py-0.5 uppercase font-medium">{{ $death->head_of_family_name ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="w-48 py-0.5">Nomor Kartu Keluarga</td>
                    <td class="w-4 py-0.5">:</td>
                    <td class="py-0.5 font-medium">{{ $death->family_card_no ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- ==========================================
             3. SECTION: JENAZAH
        =========================================== -->
        <div class="section-block mb-4">
            <h4 class="font-extrabold uppercase tracking-wider text-[10.5pt] mb-1">
                JENAZAH
            </h4>

            <table class="w-full text-[9.5pt]">
                <tr>
                    <td class="w-6 py-0.5 align-top">1</td>
                    <td class="w-56 py-0.5 align-top">NIK</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">{{ $death->deceased_nik }}</td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">2</td>
                    <td class="w-56 py-0.5 align-top">Nama Lengkap</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-bold uppercase">{{ $death->deceased_name }}</td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">3</td>
                    <td class="w-56 py-0.5 align-top">Jenis Kelamin / Anak ke</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">
                        <div class="flex items-center justify-between pr-8">
                            <span>{{ $genderText }}</span>
                            <span>Anak ke &nbsp; {{ $death->deceased_child_order ?: '-' }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">4</td>
                    <td class="w-56 py-0.5 align-top">Tempat dan Tanggal Lahir / Umur</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">
                        <div class="flex items-center justify-between pr-8">
                            <span class="uppercase">
                                {{ $death->deceased_birth_place ?: 'SLEMAN' }}, {{ $death->birth_date ? $death->birth_date->format('d-m-Y') : '-' }}
                            </span>
                            <span>Umur {{ $deceasedAge !== null ? $deceasedAge . ' th' : '-' }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">5</td>
                    <td class="w-56 py-0.5 align-top">Agama</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top uppercase font-medium">{{ $death->religion }}</td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">6</td>
                    <td class="w-56 py-0.5 align-top">Pekerjaan</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top uppercase font-medium">{{ $death->deceased_job ?: 'PENSIUNAN' }}</td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">7</td>
                    <td class="w-56 py-0.5 align-top">Alamat</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top uppercase font-medium">
                        {{ $death->deceased_address ?: ('PADUKUHAN ' . $death->padukuhan . ', RT ' . $death->rt . ' / RW ' . $death->rw . ', PURWOBINANGUN, PAKEM') }}
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">8</td>
                    <td class="w-56 py-0.5 align-top">Tanggal dan Jam Kematian</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">
                        <div class="flex items-center justify-between pr-8">
                            <span>{{ $death->death_date ? $death->death_date->format('d-m-Y') : '-' }}</span>
                            <span>Jam: {{ $death->death_time ?: '-' }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">9</td>
                    <td class="w-56 py-0.5 align-top">Sebab Kematian</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top uppercase font-medium">{{ $death->cause_of_death }}</td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">10</td>
                    <td class="w-56 py-0.5 align-top">Tempat Kematian</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top uppercase font-medium">{{ $death->death_location_detail ?: ($death->death_place ?: 'KABUPATEN SLEMAN') }}</td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">11</td>
                    <td class="w-56 py-0.5 align-top">Yang Menerangkan</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top uppercase font-medium">{{ $death->reported_by_title }}</td>
                </tr>
            </table>
        </div>

        <!-- ==========================================
             4. SECTION: IBU
        =========================================== -->
        <div class="section-block mb-4">
            <h4 class="font-extrabold uppercase tracking-wider text-[10.5pt] mb-1">
                IBU
            </h4>

            <table class="w-full text-[9.5pt]">
                <tr>
                    <td class="w-6 py-0.5 align-top">1</td>
                    <td class="w-56 py-0.5 align-top">NIK</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">
                        @if(!empty($death->mother_nik))
                            {{ $death->mother_nik }}
                        @else
                            <span class="dots-line">....................................................................................</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">2</td>
                    <td class="w-56 py-0.5 align-top">Nama Lengkap</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        @if(!empty($death->mother_name))
                            {{ $death->mother_name }}
                        @else
                            <span class="dots-line">....................................................................................</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">3</td>
                    <td class="w-56 py-0.5 align-top">Tempat dan Tanggal Lahir / Umur</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">
                        @if(!empty($death->mother_birth_place) || !empty($death->mother_birth_date))
                            <span class="uppercase">{{ $death->mother_birth_place ?: '-' }}, {{ $death->mother_birth_date ? $death->mother_birth_date->format('d-m-Y') : '-' }}</span>
                            @if($motherAge !== null)
                                &nbsp;&bull;&nbsp; Umur {{ $motherAge }} th
                            @endif
                        @else
                            <span class="dots-line">....................................................................................</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">4</td>
                    <td class="w-56 py-0.5 align-top">Alamat</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        @if(!empty($death->mother_address))
                            {{ $death->mother_address }}
                        @else
                            <span class="dots-line">....................................................................................</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- ==========================================
             5. SECTION: AYAH
        =========================================== -->
        <div class="section-block mb-4">
            <h4 class="font-extrabold uppercase tracking-wider text-[10.5pt] mb-1">
                AYAH
            </h4>

            <table class="w-full text-[9.5pt]">
                <tr>
                    <td class="w-6 py-0.5 align-top">1</td>
                    <td class="w-56 py-0.5 align-top">NIK</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">
                        @if(!empty($death->father_nik))
                            {{ $death->father_nik }}
                        @else
                            <span class="dots-line">....................................................................................</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">2</td>
                    <td class="w-56 py-0.5 align-top">Nama Lengkap</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        @if(!empty($death->father_name))
                            {{ $death->father_name }}
                        @else
                            <span class="dots-line">....................................................................................</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">3</td>
                    <td class="w-56 py-0.5 align-top">Tempat dan Tanggal Lahir / Umur</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">
                        @if(!empty($death->father_birth_place) || !empty($death->father_birth_date))
                            <span class="uppercase">{{ $death->father_birth_place ?: '-' }}, {{ $death->father_birth_date ? $death->father_birth_date->format('d-m-Y') : '-' }}</span>
                            @if($fatherAge !== null)
                                &nbsp;&bull;&nbsp; Umur {{ $fatherAge }} th
                            @endif
                        @else
                            <span class="dots-line">....................................................................................</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">4</td>
                    <td class="w-56 py-0.5 align-top">Alamat</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        @if(!empty($death->father_address))
                            {{ $death->father_address }}
                        @else
                            <span class="dots-line">....................................................................................</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- ==========================================
             6. SECTION: PELAPOR
        =========================================== -->
        <div class="section-block mb-4">
            <h4 class="font-extrabold uppercase tracking-wider text-[10.5pt] mb-1">
                PELAPOR
            </h4>

            <table class="w-full text-[9.5pt]">
                <tr>
                    <td class="w-6 py-0.5 align-top">1</td>
                    <td class="w-56 py-0.5 align-top">NIK</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">{{ $death->applicant_nik }}</td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">2</td>
                    <td class="w-56 py-0.5 align-top">Nama Lengkap</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-bold uppercase">{{ $death->applicant_name }}</td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">3</td>
                    <td class="w-56 py-0.5 align-top">Tempat dan Tanggal Lahir / Umur</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        {{ $death->applicant_birth_place ?: 'SLEMAN' }}, {{ $death->applicant_birth_date ? $death->applicant_birth_date->format('d-m-Y') : '-' }}
                        @if(!empty($death->applicant_age))
                            &nbsp;&bull;&nbsp; Umur {{ $death->applicant_age }} th
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">4</td>
                    <td class="w-56 py-0.5 align-top">Alamat</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">{{ $death->applicant_address ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- ==========================================
             7. SECTION: SAKSI 1
        =========================================== -->
        <div class="section-block mb-4">
            <h4 class="font-extrabold uppercase tracking-wider text-[10.5pt] mb-1">
                SAKSI 1
            </h4>

            <table class="w-full text-[9.5pt]">
                <tr>
                    <td class="w-6 py-0.5 align-top">1</td>
                    <td class="w-56 py-0.5 align-top">NIK</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">
                        {{ $death->witness1_nik ?: ($death->witness_nik ?: '...................................................') }}
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">2</td>
                    <td class="w-56 py-0.5 align-top">Nama Lengkap</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        {{ $death->witness1_name ?: ($death->witness_name ?: '...................................................') }}
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">3</td>
                    <td class="w-56 py-0.5 align-top">Tempat dan Tanggal Lahir / Umur</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        @if($death->witness1_birth_place || $death->witness1_birth_date)
                            {{ $death->witness1_birth_place ?: 'SLEMAN' }}, {{ $death->witness1_birth_date ? $death->witness1_birth_date->format('d-m-Y') : '-' }}
                            @if($death->witness1_age)
                                &nbsp;&bull;&nbsp; Umur {{ $death->witness1_age }} th
                            @endif
                        @elseif($death->witness1_age)
                            SLEMAN, 23-03-1987 &nbsp;&bull;&nbsp; Umur {{ $death->witness1_age }} th
                        @else
                            ...................................................
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">4</td>
                    <td class="w-56 py-0.5 align-top">Alamat</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        {{ $death->witness1_address ?: '...................................................' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- ==========================================
             8. SECTION: SAKSI 2
        =========================================== -->
        <div class="section-block mb-6">
            <h4 class="font-extrabold uppercase tracking-wider text-[10.5pt] mb-1">
                SAKSI 2
            </h4>

            <table class="w-full text-[9.5pt]">
                <tr>
                    <td class="w-6 py-0.5 align-top">1</td>
                    <td class="w-56 py-0.5 align-top">NIK</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium">
                        {{ $death->witness2_nik ?: '...................................................' }}
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">2</td>
                    <td class="w-56 py-0.5 align-top">Nama Lengkap</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        {{ $death->witness2_name ?: '...................................................' }}
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">3</td>
                    <td class="w-56 py-0.5 align-top">Tempat dan Tanggal Lahir / Umur</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        @if($death->witness2_birth_place || $death->witness2_birth_date)
                            {{ $death->witness2_birth_place ?: 'SLEMAN' }}, {{ $death->witness2_birth_date ? $death->witness2_birth_date->format('d-m-Y') : '-' }}
                            @if($death->witness2_age)
                                &nbsp;&bull;&nbsp; Umur {{ $death->witness2_age }} th
                            @endif
                        @elseif($death->witness2_age)
                            SLEMAN, 07-04-1976 &nbsp;&bull;&nbsp; Umur {{ $death->witness2_age }} th
                        @else
                            ...................................................
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-6 py-0.5 align-top">4</td>
                    <td class="w-56 py-0.5 align-top">Alamat</td>
                    <td class="w-4 py-0.5 align-top">:</td>
                    <td class="py-0.5 align-top font-medium uppercase">
                        {{ $death->witness2_address ?: '...................................................' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- ==========================================
             9. TANDA TANGAN (SIGNATURE BLOCK)
        =========================================== -->
        <div class="section-block pt-4 flex justify-end text-[10pt]">
            <div class="text-left w-72 pr-4">
                <p>Sleman, {{ $death->created_at ? $death->created_at->translatedFormat('d F Y') : date('d F Y') }}</p>
                <p class="mt-1">Mengetahui</p>
                <p>a.n Lurah</p>
                <p>Kaur Tata Laksana</p>
                
                <!-- Ruang tanda tangan & cap stempel -->
                <div class="h-24"></div>

                <p class="font-bold underline text-[10.5pt]">
                    {{ $death->officer_name ?: 'Teguh Kurniawan, S.Pi.' }}
                </p>
            </div>
        </div>

    </div>

    <!-- Script Download PDF menggunakan html2pdf -->
    <script>
        function downloadPDF() {
            const btn = document.getElementById('btn-download-pdf');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyiapkan PDF...';

            const element = document.getElementById('letter-content');
            const safeName = "{{ preg_replace('/[^A-Za-z0-9]/', '_', $death->deceased_name) }}";
            const regNo = "{{ $death->registration_no }}";

            const opt = {
                margin: [10, 15, 12, 15],
                filename: `Surat_Keterangan_Kematian_${safeName}_${regNo}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, letterRendering: true, scrollY: 0 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
            };

            html2pdf().set(opt).from(element).save().then(function() {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }).catch(function(err) {
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = originalText;
                alert('Gagal membuat PDF otomatis. Mengalihkan ke opsi cetak/simpan PDF browser...');
                window.print();
            });
        }
    </script>

</body>
</html>
