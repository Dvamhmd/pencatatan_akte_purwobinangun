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
            default => 'Pembaruan Status Pengajuan',
        };

        // Buat tracking URL jika tersedia
        $trackingUrl = url('/tracking/' . ($type === 'birth' ? 'kelahiran' : 'kematian') . '/' . $submission->registration_no);

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
        }

        $message .= "\n🔍 *Cek Status & Detail:* \n{$trackingUrl}\n";
        $message .= "────────────────────────────\n";
        $message .= "_Pesan ini dikirimkan secara otomatis oleh Sistem Pelayanan Kalurahan Purwobinangun._";

        return self::sendMessage($rawPhone, $message);
    }
}
