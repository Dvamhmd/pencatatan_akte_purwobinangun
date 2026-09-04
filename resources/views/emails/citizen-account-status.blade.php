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

    <!-- Preheader Text -->
    <div style="display: none; max-height: 0px; overflow: hidden; font-size: 1px; line-height: 1px; max-width: 0px; mso-hide: all;">
        Pemberitahuan resmi status verifikasi akun warga pada Sistem Informasi Pelayanan Kependudukan Kalurahan Purwobinangun.
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
                        $isApproved = ($actionType === 'approved');
                        $isDeactivated = ($actionType === 'deactivated');
                        $isRejected = ($actionType === 'rejected');

                        $statusBg = $isApproved ? '#ecfdf5' : '#fff1f2';
                        $statusBorder = $isApproved ? '#10b981' : '#f43f5e';
                        $statusTextColor = $isApproved ? '#065f46' : '#9f1239';
                        $statusBadgeBg = $isApproved ? '#10b981' : '#f43f5e';
                    @endphp

                    <tr>
                        <td style="padding: 25px 30px 10px 30px;">
                            <div style="background-color: {{ $statusBg }}; border-left: 5px solid {{ $statusBorder }}; border-radius: 8px; padding: 18px 20px;">
                                <div style="display: inline-block; background-color: {{ $statusBadgeBg }}; color: #ffffff; font-size: 11px; font-weight: bold; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; margin-bottom: 6px;">
                                    Status Akun Warga
                                </div>
                                <h2 style="margin: 0; font-size: 18px; color: {{ $statusTextColor }}; font-weight: 800;">
                                    {{ $statusLabel }}
                                </h2>
                                <p style="margin: 6px 0 0 0; font-size: 13px; color: {{ $statusTextColor }}; opacity: 0.9;">
                                    @if($isApproved)
                                        Selamat! Akun Anda telah berhasil diverifikasi oleh petugas pelayanan. Anda sekarang dapat masuk dan mengakses seluruh layanan pengajuan dokumen kependudukan secara online.
                                    @elseif($isDeactivated)
                                        Pemberitahuan: Akun warga Anda telah dinonaktifkan oleh petugas pelayanan Kalurahan Purwobinangun.
                                    @elseif($isRejected)
                                        Mohon maaf, pendaftaran akun warga Anda belum dapat disetujui oleh petugas pelayanan karena data belum sesuai atau belum lengkap.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 15px 30px 25px 30px;">
                            <p style="margin: 0 0 16px 0; font-size: 14px; color: #334155;">
                                Yth. Bapak/Ibu <strong>{{ $citizen->name }}</strong>,
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 13px; color: #475569; line-height: 1.6;">
                                Berikut adalah rincian informasi data akun kependudukan Anda pada portal pelayanan Kalurahan Purwobinangun:
                            </p>

                            <!-- Account Details Table -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; color: #64748b; width: 38%; font-weight: 600;">Nomor Induk Kependudukan (NIK)</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #0f172a; font-weight: 800;">
                                        <span style="font-family: monospace; background-color: #e2e8f0; padding: 2px 6px; border-radius: 4px;">{{ $citizen->nik }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; color: #64748b; font-weight: 600;">Nomor Kartu Keluarga (KK)</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #0f172a; font-weight: 700; font-family: monospace;">
                                        {{ $citizen->family_card_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; color: #64748b; font-weight: 600;">Nama Lengkap</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #0f172a; font-weight: 700;">
                                        {{ $citizen->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; color: #64748b; font-weight: 600;">Status Akun Saat Ini</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 13px; font-weight: 800; color: {{ $isApproved ? '#059669' : '#e11d48' }};">
                                        {{ $isApproved ? 'Aktif / Terverifikasi' : ($isDeactivated ? 'Dinonaktifkan' : 'Ditolak') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 12px; color: #64748b; font-weight: 600;">Petugas Verifikator</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: #334155;">
                                        {{ $processedBy }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Petugas Note Alert -->
                            @if(!empty($reason))
                                <div style="background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 16px 18px; margin-bottom: 24px;">
                                    <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: bold; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px;">
                                        Catatan / Alasan dari Petugas:
                                    </p>
                                    <p style="margin: 0; font-size: 13px; color: #78350f; font-style: italic; line-height: 1.5;">
                                        "{{ $reason }}"
                                    </p>
                                </div>
                            @endif

                            @if($isApproved)
                                <!-- Action Button CTA -->
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 25px;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $loginUrl }}" target="_blank" style="display: inline-block; background-color: #0b7c89; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: bold; padding: 14px 28px; border-radius: 8px; box-shadow: 0 4px 6px rgba(11, 124, 137, 0.25);">
                                                Masuk ke Akun Warga &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px;">
                                    <p style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
                                        Jika Anda membutuhkan bantuan atau klarifikasi lebih lanjut mengenai data kependudukan, silakan hubungi bagian Pelayanan Umum Kantor Kalurahan Purwobinangun pada hari dan jam kerja.
                                    </p>
                                </div>
                            @endif

                            <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.5;">
                                Terima kasih atas partisipasi Anda dalam menggunakan layanan digital kependudukan Kalurahan Purwobinangun.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 30px; text-align: center; color: #64748b; font-size: 11px;">
                            <p style="margin: 0 0 6px 0; font-weight: bold; color: #334155; font-size: 12px;">
                                Pemerintah Kalurahan Purwobinangun
                            </p>
                            <p style="margin: 0 0 10px 0; color: #64748b;">
                                Jl. Boyong No. 1, Purwobinangun, Pakem, Sleman, D.I. Yogyakarta 55582
                            </p>
                            <p style="margin: 0; color: #94a3b8; font-size: 10px;">
                                &copy; {{ date('Y') }} Sistem Pelayanan Kependudukan Kalurahan Purwobinangun. Email ini dikirimkan secara otomatis oleh sistem.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
