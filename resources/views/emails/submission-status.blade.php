<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ $statusLabel }} - Pelayanan Kalurahan Purwobinangun</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b; -webkit-font-smoothing: antialiased; line-height: 1.6;">

    <!-- Preheader Text (Hidden, prevents spam filters from picking raw code and gives clean inbox summary) -->
    <div style="display: none; font-size: 1px; color: #f1f5f9; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all;">
        Pemberitahuan resmi: Permohonan {{ $typeLabel }} ({{ $submission->registration_no }}) berstatus: {{ $statusLabel }}.
    </div>

    <!-- Wrapper -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9; padding: 30px 15px;">
        <tr>
            <td align="center">
                
                <!-- Main Container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #065b65 0%, #0b7c89 100%); padding: 30px 25px; text-align: center; color: #ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center">
                                        <div style="background-color: rgba(255, 255, 255, 0.15); display: inline-block; padding: 8px 16px; border-radius: 50px; font-size: 11px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 12px; border: 1px solid rgba(255, 255, 255, 0.2);">
                                            Sistem Informasi Pelayanan Kependudukan
                                        </div>
                                        <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">KALURAHAN PURWOBINANGUN</h1>
                                        <p style="margin: 4px 0 0 0; font-size: 13px; color: #ccfbf1;">Kapanewon Pakem, Kabupaten Sleman, D.I. Yogyakarta</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Status Hero Alert -->
                    @php
                        $isReady = in_array($status, ['ready_for_pickup', 'completed']);
                        $isRevision = ($status === 'revision');
                        $isRejected = ($status === 'rejected');

                        $statusBg = $isReady ? '#ecfdf5' : ($isRevision ? '#fff7ed' : ($isRejected ? '#fff1f2' : '#f0fdf4'));
                        $statusBorder = $isReady ? '#10b981' : ($isRevision ? '#f97316' : ($isRejected ? '#f43f5e' : '#0b7c89'));
                        $statusTextColor = $isReady ? '#065f46' : ($isRevision ? '#9a3412' : ($isRejected ? '#9f1239' : '#065b65'));
                        $statusBadgeBg = $isReady ? '#10b981' : ($isRevision ? '#f97316' : ($isRejected ? '#f43f5e' : '#0b7c89'));
                    @endphp

                    <tr>
                        <td style="padding: 25px 30px 10px 30px;">
                            <div style="background-color: {{ $statusBg }}; border-left: 5px solid {{ $statusBorder }}; border-radius: 8px; padding: 18px 20px;">
                                <div style="display: inline-block; background-color: {{ $statusBadgeBg }}; color: #ffffff; font-size: 11px; font-weight: bold; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; margin-bottom: 6px;">
                                    Status Baru
                                </div>
                                <h2 style="margin: 0; font-size: 18px; color: {{ $statusTextColor }}; font-weight: 800;">
                                    {{ $statusLabel }}
                                </h2>
                                <p style="margin: 6px 0 0 0; font-size: 13px; color: {{ $statusTextColor }}; opacity: 0.9;">
                                    @if($isReady)
                                        Kabar baik! Permohonan dokumen {{ $typeLabel }} Anda telah selesai diproses dan siap untuk diambil di Kantor Kalurahan.
                                    @elseif($isRevision)
                                        Terdapat catatan atau berkas persyaratan yang perlu diperbaiki sebelum permohonan dapat diproses lebih lanjut.
                                    @elseif($isRejected)
                                        Mohon maaf, permohonan dokumen {{ $typeLabel }} Anda tidak dapat diproses / dibatalkan oleh petugas.
                                    @else
                                        Status permohonan {{ $typeLabel }} Anda telah diperbarui oleh petugas pelayanan.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 15px 30px 25px 30px;">
                            <p style="margin: 0 0 16px 0; font-size: 14px; color: #334155;">
                                Yth. Bapak/Ibu <strong>{{ $submission->applicant_name ?? $submission->user->name ?? 'Warga' }}</strong>,
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 13px; color: #475569; line-height: 1.6;">
                                Berikut adalah rincian informasi terkait perubahan status pengajuan dokumen kependudukan Anda:
                            </p>

                            <!-- Submission Details Table -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; color: #64748b; width: 38%; font-weight: 600;">Nomor Registrasi</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #0f172a; font-weight: 800;">
                                        <span style="font-family: monospace; background-color: #e2e8f0; padding: 2px 6px; border-radius: 4px;">{{ $submission->registration_no }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; color: #64748b; font-weight: 600;">Jenis Permohonan</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #0f172a; font-weight: 700;">{{ $typeLabel }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; color: #64748b; font-weight: 600;">
                                        {{ $type === 'birth' ? 'Nama Anak / Bayi' : 'Nama Jenazah' }}
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #0f172a; font-weight: 700;">
                                        {{ $type === 'birth' ? ($submission->child_name ?? '-') : ($submission->deceased_name ?? '-') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; color: #64748b; font-weight: 600;">Waktu Pembaruan</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; color: #334155;">{{ date('d F Y, H:i') }} WIB</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 12px; color: #64748b; font-weight: 600;">Petugas Verifikator</td>
                                    <td style="padding: 12px 16px; font-size: 12px; color: #334155; font-weight: 600;">{{ $processedBy }}</td>
                                </tr>
                            </table>

                            <!-- Rejection Note / Officer Message Box -->
                            @if(!empty($note))
                            <div style="background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
                                <div style="font-size: 12px; font-weight: bold; color: #334155; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">
                                    📝 Catatan / Alasan dari Petugas:
                                </div>
                                <div style="font-size: 13px; color: #1e293b; background-color: #ffffff; padding: 12px; border-radius: 6px; border-left: 3px solid #0b7c89; line-height: 1.5; white-space: pre-wrap;">
                                    {{ $note }}
                                </div>
                            </div>
                            @endif

                            <!-- Instructions / Next Steps -->
                            <div style="margin-bottom: 24px; padding: 16px; background-color: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                                <div style="font-size: 12px; font-weight: bold; color: #065b65; margin-bottom: 8px;">
                                    📌 Langkah Selanjutnya Bagi Pemohon:
                                </div>
                                
                                @if($isReady)
                                <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #475569; line-height: 1.6;">
                                    <li>Silakan datang ke <strong>Kantor Kalurahan Purwobinangun</strong> pada jam pelayanan (Senin - Jumat: 08.00 - 15.00 WIB).</li>
                                    <li>Bawa dokumen asli untuk verifikasi fisik: <strong>KTP Asli Pemohon, Kartu Keluarga (KK) Asli</strong>, dan <strong>Resi Pendaftaran</strong>.</li>
                                </ul>
                                @elseif($isRevision)
                                <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #475569; line-height: 1.6;">
                                    <li>Silakan masuk ke akun warga Anda di website pelayanan.</li>
                                    <li>Periksa rincian data dan unggah ulang dokumen yang diminta sesuai catatan verifikator di atas.</li>
                                </ul>
                                @elseif($isRejected)
                                <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #475569; line-height: 1.6;">
                                    <li>Silakan baca catatan verifikator di atas untuk mengetahui alasan pembatalan.</li>
                                    <li>Jika terdapat pertanyaan atau ingin berkonsultasi, Anda dapat menghubungi petugas pelayanan Kalurahan.</li>
                                </ul>
                                @else
                                <p style="margin: 0; font-size: 12px; color: #475569;">
                                    Anda dapat memantau perkembangan berkas secara berkala melalui menu Lacak Permohonan di website.
                                </p>
                                @endif
                            </div>

                            <!-- CTA Button -->
                            @if(!empty($trackingUrl))
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 25px 0 10px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $trackingUrl }}" target="_blank" style="display: inline-block; background-color: #0b7c89; color: #ffffff; text-decoration: none; font-size: 13px; font-weight: bold; padding: 12px 28px; border-radius: 8px; box-shadow: 0 2px 5px rgba(11, 124, 137, 0.3);">
                                            Cek Status Permohonan &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @endif

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 30px; text-align: center; color: #64748b; font-size: 11px; line-height: 1.5;">
                            <p style="margin: 0 0 4px 0; font-weight: bold; color: #334155;">Pemerintah Kalurahan Purwobinangun</p>
                            <p style="margin: 0 0 8px 0;">Jl. Boyong No. 1, Purwobinangun, Pakem, Sleman, D.I. Yogyakarta 55582</p>
                            <p style="margin: 0 0 4px 0; color: #64748b; font-size: 10.5px;">
                                Anda menerima pesan ini sebagai notifikasi resmi terkait permohonan <strong>{{ $typeLabel }} ({{ $submission->registration_no }})</strong> di Kalurahan Purwobinangun.
                            </p>
                            <p style="margin: 0; color: #94a3b8; font-size: 10px;">
                                Email ini dikirim secara otomatis oleh Sistem Pelayanan Akte Kalurahan Purwobinangun. Mohon jangan membalas langsung ke alamat ini.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
