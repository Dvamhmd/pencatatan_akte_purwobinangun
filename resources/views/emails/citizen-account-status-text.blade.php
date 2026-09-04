PEMBERITAHUAN STATUS AKUN WARGA
PEMERINTAH KALURAHAN PURWOBINANGUN
============================================================

Yth. Bapak/Ibu {{ $citizen->name }},

Status Akun: {{ $statusLabel }}

@if($actionType === 'approved')
Selamat! Akun Anda telah berhasil diverifikasi oleh petugas pelayanan. Anda sekarang dapat masuk dan mengakses seluruh layanan pengajuan dokumen kependudukan secara online.
@elseif($actionType === 'deactivated')
Pemberitahuan: Akun warga Anda telah dinonaktifkan oleh petugas pelayanan Kalurahan Purwobinangun.
@elseif($actionType === 'rejected')
Mohon maaf, pendaftaran akun warga Anda belum dapat disetujui oleh petugas pelayanan karena data belum sesuai atau belum lengkap.
@endif

RINCIAN AKUN:
- NIK: {{ $citizen->nik }}
- Nomor KK: {{ $citizen->family_card_no }}
- Nama Lengkap: {{ $citizen->name }}
- Status Akun: {{ $actionType === 'approved' ? 'Aktif / Terverifikasi' : ($actionType === 'deactivated' ? 'Dinonaktifkan' : 'Ditolak') }}
- Petugas: {{ $processedBy }}

@if(!empty($reason))
CATATAN / ALASAN PETUGAS:
"{{ $reason }}"
@endif

@if($actionType === 'approved')
Tautan Masuk (Login):
{{ $loginUrl }}
@else
Jika membutuhkan informasi atau klarifikasi lebih lanjut, silakan hubungi Kantor Kalurahan Purwobinangun pada hari dan jam kerja.
@endif

============================================================
Pemerintah Kalurahan Purwobinangun
Jl. Boyong No. 1, Purwobinangun, Pakem, Sleman, D.I. Yogyakarta 55582
Pesan ini dikirim secara otomatis oleh Sistem Pelayanan Kependudukan.
