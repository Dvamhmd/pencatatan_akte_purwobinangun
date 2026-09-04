<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Format dan normalisasi nomor WhatsApp ke standar internasional (format 62xxxxxxxxxxx).
     */
    public static function normalizePhoneNumber(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Hapus semua karakter selain angka
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (empty($cleaned)) {
            return null;
        }

        // Ubah awalan 08xxx menjadi 628xxx
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        }

        // Jika diawali 8xxx langsung (tanpa 0 atau 62)
        if (str_starts_with($cleaned, '8')) {
            $cleaned = '62' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Kirim pesan teks umum via Fonnte.
     */
    public static function sendMessage(string $targetPhone, string $message): bool
    {
        $phone = self::normalizePhoneNumber($targetPhone);
        if (!$phone) {
            Log::warning('Gagal kirim WA: Nomor telepon tidak valid.', ['target' => $targetPhone]);
            return false;
        }

        $token = config('services.fonnte.token');
        $url = config('services.fonnte.url', 'https://api.fonnte.com/send');

        if (empty($token)) {
            Log::warning('Gagal kirim WA: FONNTE_TOKEN belum disetel di file .env.');
            return false;
        }

        try {
            $response = Http::timeout(10)
                ->withoutVerifying()
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post($url, [
                    'target' => $phone,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                Log::info('Notifikasi WhatsApp berhasil dikirim via Fonnte.', [
                    'target' => $phone,
                    'response' => $result,
                ]);
                return true;
            }

            Log::error('Fonnte mengembalikan response gagal:', [
                'target' => $phone,
                'status_code' => $response->status(),
                'response' => $result ?? $response->body(),
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('Terjadi pengecualian saat mengirim notifikasi WhatsApp: ' . $e->getMessage(), [
                'target' => $phone,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Kirim pesan notifikasi status permohonan Akte Kelahiran / Kematian secara otomatis.
     */
    public static function sendSubmissionStatusNotification(
        $submission,
        string $type,
        string $status,
        ?string $note = null
    ): bool {
        // Ambil nomor pemohon dari data pengajuan atau profil user
        $rawPhone = $submission->applicant_phone ?? $submission->user?->phone;

        if (empty($rawPhone)) {
            Log::info("WhatsApp tidak dikirim: Nomor telepon tidak ditemukan pada pengajuan {$submission->registration_no}.");
            return false;
        }

        $typeLabel = ($type === 'birth') ? 'Akte Kelahiran' : 'Akte Kematian';
        $subjectName = ($type === 'birth')
            ? ($submission->child_name ?? 'Pemohon')
            : ($submission->deceased_name ?? 'Almarhum/Almarhumah');
        $subjectField = ($type === 'birth') ? 'Nama Anak' : 'Nama Jenazah';

        $statusTitle = match ($status) {
            'ready_for_pickup', 'completed' => 'Siap Diambil di Kantor Kalurahan',
            'revision' => 'Memerlukan Revisi Berkas',
            'rejected' => 'Dibatalkan / Tidak Disetujui',
            'in_process', 'verified' => 'Sedang Diproses',
            'picked_up' => 'Sudah Diambil',
            'archived' => 'Diarsipkan',
            'pending' => 'Menunggu Verifikasi',
            default => 'Pembaruan Status Pengajuan',
        };

        // Buat tracking URL menggunakan named route tracking.show
        $trackingUrl = route('tracking.show', [
            'type' => ($type === 'birth' ? 'kelahiran' : 'kematian'),
            'registrationNo' => $submission->registration_no,
        ]);

        // Format pesan WhatsApp dengan Markdown
        $message = "🏛️ *PEMBERITAHUAN STATUS PENGAJUAN*\n";
        $message .= "*Pemerintah Kalurahan Purwobinangun*\n";
        $message .= "────────────────────────────\n";
        $message .= "Yth. Bpk/Ibu *{$submission->applicant_name}*,\n\n";
        $message .= "Permohonan *{$typeLabel}* Anda telah diperbarui oleh petugas dengan rincian sebagai berikut:\n\n";
        $message .= "📌 *No. Registrasi:* `{$submission->registration_no}`\n";
        $message .= "👤 *{$subjectField}:* {$subjectName}\n";
        $message .= "📊 *Status:* *{$statusTitle}*\n";

        if (!empty($note)) {
            $message .= "📝 *Catatan Petugas:* \n_{$note}_\n";
        }

        if (in_array($status, ['ready_for_pickup', 'completed'])) {
            $message .= "\n📍 *Lokasi Pengambilan:*\n";
            $message .= "Loket Pelayanan Kantor Kalurahan Purwobinangun\n";
            $message .= "Senin - Jumat, pukul 08.00 - 15.00 WIB\n";
            $message .= "_(Harap membawa berkas persyaratan asli / surat pengantar RT/RW)_\n";
        } elseif ($status === 'revision') {
            $message .= "\n⚠️ *Tindakan Diperlukan:*\n";
            $message .= "Silakan login ke website untuk memperbaiki dan mengunggah kembali dokumen yang diminta.\n";
        } elseif (in_array($status, ['in_process', 'verified'])) {
            $message .= "\nℹ️ *Informasi:*\n";
            $message .= "Berkas permohonan Anda sedang dalam tahap verifikasi dan pemrosesan oleh petugas kalurahan.\n";
        } elseif (in_array($status, ['picked_up', 'archived'])) {
            $message .= "\n✅ *Informasi:*\n";
            $message .= "Dokumen fisik telah diserahkan kepada pemohon. Proses pelayanan telah selesai dan berkas diarsipkan.\n";
        } elseif ($status === 'pending') {
            $message .= "\nℹ️ *Informasi:*\n";
            $message .= "Berkas permohonan Anda telah diterima dalam antrean dan menunggu verifikasi petugas.\n";
        }

        $message .= "\n🔍 *Cek Status & Detail:* \n{$trackingUrl}\n";
        $message .= "────────────────────────────\n";
        $message .= "_Pesan ini dikirimkan secara otomatis oleh Sistem Pelayanan Kalurahan Purwobinangun._";

        return self::sendMessage($rawPhone, $message);
    }

    /**
     * Kirim pesan notifikasi status akun warga (disetujui, ditolak, dinonaktifkan).
     */
    public static function sendCitizenAccountStatusNotification(
        $citizen,
        string $actionType,
        ?string $reason = null,
        ?string $adminName = null
    ): bool {
        $rawPhone = $citizen->phone;

        if (empty($rawPhone)) {
            Log::info("WhatsApp tidak dikirim: Nomor telepon tidak ditemukan pada akun warga NIK {$citizen->nik}.");
            return false;
        }

        $loginUrl = route('warga.login');
        $adminOfficer = $adminName ?: 'Petugas Pelayanan Kalurahan';

        $message = "🏛️ *PEMBERITAHUAN STATUS AKUN WARGA*\n";
        $message .= "*Pemerintah Kalurahan Purwobinangun*\n";
        $message .= "────────────────────────────\n";
        $message .= "Yth. Bpk/Ibu *{$citizen->name}*,\n\n";

        if ($actionType === 'approved') {
            $message .= "Pendaftaran Akun Warga Anda di Sistem Pelayanan Kalurahan Purwobinangun telah *DISETUJUI & DIAKTIFKAN* oleh petugas.\n\n";
            $message .= "📌 *NIK:* `{$citizen->nik}`\n";
            $message .= "🏠 *No. KK:* `{$citizen->family_card_no}`\n";
            $message .= "📊 *Status Akun:* *Aktif / Terverifikasi*\n";
            $message .= "👤 *Petugas:* {$adminOfficer}\n";

            if (!empty($reason)) {
                $message .= "\n📝 *Catatan Petugas:* \n_{$reason}_\n";
            }

            $message .= "\n✅ Anda sekarang dapat masuk (login) ke website pelayanan kependudukan untuk mengajukan permohonan Akte Kelahiran maupun Akte Kematian secara online.\n\n";
            $message .= "🔗 *Masuk Akun Warga:* \n{$loginUrl}\n";
        } elseif ($actionType === 'deactivated') {
            $message .= "Pemberitahuan: Akun Warga Anda di Sistem Pelayanan Kalurahan Purwobinangun telah *DINONAKTIFKAN* oleh petugas pelayanan.\n\n";
            $message .= "📌 *NIK:* `{$citizen->nik}`\n";
            $message .= "🏠 *No. KK:* `{$citizen->family_card_no}`\n";
            $message .= "📊 *Status Akun:* *Dinonaktifkan*\n";
            $message .= "👤 *Petugas:* {$adminOfficer}\n";

            if (!empty($reason)) {
                $message .= "\n📝 *Catatan / Alasan Petugas:* \n_{$reason}_\n";
            }

            $message .= "\nℹ️ Jika Anda memerlukan klarifikasi atau bantuan lebih lanjut, silakan hubungi Kantor Kalurahan Purwobinangun pada hari dan jam kerja.\n";
        } else {
            // rejected
            $message .= "Pendaftaran Akun Warga Anda di Sistem Pelayanan Kalurahan Purwobinangun *BELUM DAPAT DISETUJUI / DITOLAK* oleh petugas verifikator.\n\n";
            $message .= "📌 *NIK:* `{$citizen->nik}`\n";
            $message .= "🏠 *No. KK:* `{$citizen->family_card_no}`\n";
            $message .= "📊 *Status Akun:* *Ditolak*\n";
            $message .= "👤 *Petugas:* {$adminOfficer}\n";

            if (!empty($reason)) {
                $message .= "\n📝 *Catatan / Alasan Penolakan:* \n_{$reason}_\n";
            }

            $message .= "\nℹ️ Silakan periksa kembali kelengkapan data atau hubungi Kantor Kalurahan Purwobinangun jika membutuhkan bantuan lebih lanjut.\n";
        }

        $message .= "────────────────────────────\n";
        $message .= "_Pesan ini dikirimkan secara otomatis oleh Sistem Pelayanan Kalurahan Purwobinangun._";

        return self::sendMessage($rawPhone, $message);
    }
}
