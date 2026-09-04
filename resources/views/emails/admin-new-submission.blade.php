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
    <title>Pemberitahuan Pengajuan Baru - Pelayanan Kalurahan Purwobinangun</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b; -webkit-font-smoothing: antialiased; line-height: 1.6;">

    <!-- Preheader Text -->
    <div style="display: none; max-height: 0px; overflow: hidden; font-size: 1px; line-height: 1px; max-width: 0px; mso-hide: all;">
        Pemberitahuan resmi pengajuan {{ $typeTitle }} baru masuk yang menunggu verifikasi petugas.
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
                                        <div style="background-color: rgba(255, 255, 255, 0.15); display: inline-block; padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 12px; border: 1px solid rgba(255, 255, 255, 0.2);">
                                            Pemberitahuan Petugas Pelayanan
                                        </div>
                                        <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">KALURAHAN PURWOBINANGUN</h1>
                                        <p style="margin: 4px 0 0 0; font-size: 13px; color: #ccfbf1;">Kapanewon Pakem, Kabupaten Sleman, D.I. Yogyakarta</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Status Hero Alert -->
                    <tr>
                        <td style="padding: 25px 30px 10px 30px;">
                            <div style="background-color: #fefce8; border-left: 5px solid #eab308; border-radius: 8px; padding: 18px 20px;">
                                <div style="display: inline-block; background-color: #ca8a04; color: #ffffff; font-size: 11px; font-weight: bold; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; margin-bottom: 6px;">
                                    Menunggu Verifikasi
                                </div>
                                <h2 style="margin: 0; font-size: 18px; color: #854d0e; font-weight: 800;">
                                    Pengajuan Baru: {{ $typeTitle }}
                                </h2>
                                <p style="margin: 6px 0 0 0; font-size: 13px; color: #713f12; opacity: 0.95;">
                                    Terdapat pengajuan baru yang masuk ke dalam sistem dan saat ini menunggu proses verifikasi dan validasi dari Petugas Pelayanan Kalurahan Purwobinangun.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 15px 30px 25px 30px;">
                            <p style="margin: 0 0 16px 0; font-size: 14px; color: #334155;">
                                Yth. <strong>Bapak/Ibu Petugas & Admin Pelayanan</strong>,
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 13px; color: #475569; line-height: 1.6;">
                                Berikut adalah rincian data permohonan baru yang baru saja diajukan oleh pemohon/warga:
                            </p>

                            <!-- Submission Details Table -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
                                <tbody>
                                    @foreach($details as $label => $val)
                                    <tr>
                                        <td width="38%" style="padding: 11px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; font-weight: 600; color: #64748b;">
                                            {{ $label }}
                                        </td>
                                        <td width="62%" style="padding: 11px 16px; border-bottom: 1px solid #e2e8f0; font-size: 13px; font-weight: 700; color: #1e293b;">
                                            {{ $val }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Call to Action Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $actionUrl }}" target="_blank" style="display: inline-block; background-color: #0b7c89; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: bold; padding: 14px 28px; border-radius: 8px; box-shadow: 0 2px 6px rgba(11, 124, 137, 0.35); text-align: center;">
                                            Buka & Verifikasi Berkas di Panel Admin &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Instructions -->
                            <div style="background-color: #f0fdf4; border-radius: 8px; padding: 14px 18px; border: 1px dashed #86efac; margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 12px; color: #166534; line-height: 1.5;">
                                    <strong>Petunjuk Tindak Lanjut:</strong> Silakan login ke panel admin, periksa kelengkapan berkas fisik/unggahan pemohon, lalu perbarui status menjadi disetujui, diproses, atau revisi sesuai ketentuan pelayanan yang berlaku.
                                </p>
                            </div>

                            <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                                * Jika tombol di atas tidak dapat diklik, salin dan tempel tautan berikut di peramban Anda:<br>
                                <a href="{{ $actionUrl }}" style="color: #0b7c89; word-break: break-all; font-size: 11px;">{{ $actionUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: bold; color: #475569;">
                                Pelayanan Kependudukan Kalurahan Purwobinangun
                            </p>
                            <p style="margin: 0 0 10px 0; font-size: 11px; color: #64748b;">
                                Jl. Kalurang Km. 18, Pakem, Sleman, D.I. Yogyakarta 55582
                            </p>
                            <p style="margin: 0; font-size: 10px; color: #94a3b8;">
                                Pesan ini dikirim secara otomatis oleh sistem notifikasi permohonan.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
