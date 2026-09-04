PEMBERITAHUAN STATUS PENGAJUAN DOKUMEN
Pemerintah Kalurahan Purwobinangun
Kapanewon Pakem, Kabupaten Sleman, D.I. Yogyakarta
============================================================

Yth. Bapak/Ibu {{ $submission->applicant_name ?? $submission->user->name ?? 'Warga' }},

Berikut adalah rincian pembaruan status pengajuan dokumen kependudukan Anda:

Nomor Registrasi : {{ $submission->registration_no }}
Jenis Permohonan : {{ $typeLabel }}
Nama Terkait     : {{ $type === 'birth' ? ($submission->child_name ?? '-') : ($submission->deceased_name ?? '-') }}
Status Saat Ini  : {{ $statusLabel }}
Waktu Pembaruan  : {{ date('d F Y, H:i') }} WIB
Petugas          : {{ $processedBy }}

@if(!empty($note))
------------------------------------------------------------
CATATAN / ALASAN PETUGAS:
{{ $note }}
------------------------------------------------------------
@endif

LANGKAH SELANJUTNYA:
@if(in_array($status, ['ready_for_pickup', 'completed']))
- Silakan datang ke Kantor Kalurahan Purwobinangun pada jam pelayanan (Senin - Jumat: 08.00 - 15.00 WIB).
- Bawa dokumen asli untuk verifikasi fisik: KTP Asli Pemohon, Kartu Keluarga (KK) Asli, dan Resi Pendaftaran.
@elseif($status === 'revision')
- Silakan masuk ke akun warga Anda di website pelayanan.
- Periksa rincian data dan unggah ulang dokumen yang diminta sesuai catatan verifikator di atas.
@elseif($status === 'rejected')
- Silakan baca catatan verifikator di atas untuk mengetahui alasan pembatalan.
- Anda dapat berkonsultasi langsung dengan petugas pelayanan Kalurahan jika memerlukan informasi lebih lanjut.
@elseif(in_array($status, ['in_process', 'verified']))
- Berkas pengajuan Anda sedang dalam proses verifikasi dan pengerjaan oleh petugas.
- Anda akan menerima pemberitahuan berikutnya saat dokumen siap diambil atau jika ada perbaikan berkas.
@elseif(in_array($status, ['picked_up', 'archived']))
- Dokumen fisik telah berhasil diserahkan kepada pemohon. Pelayanan permohonan ini telah selesai dan diarsipkan.
@elseif($status === 'pending')
- Berkas pengajuan Anda telah diterima dan masuk dalam antrean verifikasi petugas.
@else
- Pantau perkembangan berkas secara berkala melalui website pelayanan.
@endif

Untuk memeriksa status secara online, silakan masuk ke website pelayanan Kalurahan.

============================================================
Pemerintah Kalurahan Purwobinangun
Jl. Boyong No. 1, Purwobinangun, Pakem, Sleman, D.I. Yogyakarta 55582
Email ini dikirimkan secara otomatis oleh Sistem Pelayanan Akte Kalurahan Purwobinangun.
