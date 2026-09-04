<?php

namespace App\Services;

use App\Mail\AdminNewSubmissionNotification;
use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminNotificationService
{
    /**
     * Dapatkan daftar alamat email admin/petugas penerima notifikasi.
     * Mengambil dari konfigurasi mail.admin_notification_email / env ADMIN_NOTIFICATION_EMAIL
     * dan akun user dengan role admin di database.
     *
     * @return array<string>
     */
    public static function getAdminRecipients(): array
    {
        $recipients = [];

        // 1. Ambil dari config / environment jika ada
        $configEmail = config('mail.admin_notification_email') ?: env('ADMIN_NOTIFICATION_EMAIL');
        if (!empty($configEmail)) {
            $split = array_map('trim', explode(',', (string) $configEmail));
            foreach ($split as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $recipients[] = strtolower($email);
                }
            }
        }

        // 2. Ambil dari database user yang memiliki role admin
        try {
            $adminUsers = User::where('role', 'admin')
                ->whereNotNull('email')
                ->pluck('email')
                ->toArray();

            foreach ($adminUsers as $email) {
                $trimmed = strtolower(trim((string) $email));
                if (filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
                    $recipients[] = $trimmed;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengambil email admin dari database: ' . $e->getMessage());
        }

        // 3. Fallback jika tidak ada admin ditemukan sama sekali
        if (empty($recipients)) {
            $fromAddress = config('mail.from.address');
            if (filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
                $recipients[] = strtolower(trim((string) $fromAddress));
            }
        }

        return array_values(array_unique($recipients));
    }

    /**
     * Kirim Mailable ke semua email admin penerima.
     * Dijalankan secara aman (fail-safe) sehingga kendala koneksi SMTP tidak menggagalkan proses permohonan.
     */
    protected static function sendToAdmins($mailable): bool
    {
        $recipients = self::getAdminRecipients();

        if (empty($recipients)) {
            Log::warning('Tidak ada alamat email admin yang valid untuk menerima notifikasi pengajuan baru.');
            return false;
        }

        $success = false;

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient)->send($mailable);
                $success = true;
            } catch (\Throwable $e) {
                Log::error("Gagal mengirim email notifikasi pengajuan baru ke admin ({$recipient}): " . $e->getMessage());
            }
        }

        return $success;
    }

    /**
     * Kirim notifikasi email ke admin untuk permohonan Akte Kelahiran baru.
     */
    public static function notifyNewBirthCertificate(BirthCertificate $birth): bool
    {
        $birthDateStr = $birth->birth_date
            ? ($birth->birth_date instanceof \DateTimeInterface ? $birth->birth_date->format('d/m/Y') : (string) $birth->birth_date)
            : '-';

        $createdAtStr = $birth->created_at
            ? $birth->created_at->translatedFormat('d F Y, H:i') . ' WIB'
            : now()->translatedFormat('d F Y, H:i') . ' WIB';

        $details = [
            'Nomor Registrasi' => $birth->registration_no,
            'Nama Pemohon' => $birth->applicant_name,
            'NIK Pemohon' => $birth->applicant_nik,
            'No. HP / WhatsApp' => $birth->applicant_phone,
            'Nama Bayi / Anak' => $birth->child_name,
            'Tempat / Tgl Lahir' => ($birth->birth_place ?: '-') . ', ' . $birthDateStr,
            'Jenis Kelamin' => $birth->gender === 'L' ? 'Laki-laki' : ($birth->gender === 'P' ? 'Perempuan' : '-'),
            'Kelahiran Anak ke-' => (string) ($birth->birth_order ?? '-'),
            'Nama Orang Tua' => ($birth->father_name ?: '-') . ' (Ayah) & ' . ($birth->mother_name ?: '-') . ' (Ibu)',
            'Waktu Pengajuan' => $createdAtStr,
        ];

        $actionUrl = route('admin.birth.show', $birth->id);
        $subject = "[Pelayanan Purwobinangun] Pengajuan Baru Akte Kelahiran - {$birth->registration_no}";

        $mailable = new AdminNewSubmissionNotification(
            submissionType: 'birth',
            typeTitle: 'Akte Kelahiran',
            subjectLine: $subject,
            actionUrl: $actionUrl,
            details: $details,
            submission: $birth
        );

        return self::sendToAdmins($mailable);
    }

    /**
     * Kirim notifikasi email ke admin untuk permohonan Akte Kematian baru.
     */
    public static function notifyNewDeathCertificate(DeathCertificate $death): bool
    {
        $deathDateStr = $death->death_date
            ? ($death->death_date instanceof \DateTimeInterface ? $death->death_date->format('d/m/Y') : (string) $death->death_date)
            : '-';

        $createdAtStr = $death->created_at
            ? $death->created_at->translatedFormat('d F Y, H:i') . ' WIB'
            : now()->translatedFormat('d F Y, H:i') . ' WIB';

        $details = [
            'Nomor Registrasi' => $death->registration_no,
            'Nama Pelapor' => $death->applicant_name,
            'NIK Pelapor' => $death->applicant_nik,
            'No. HP / WhatsApp' => $death->applicant_phone,
            'Hubungan dgn Almarhum' => $death->applicant_relation ?: '-',
            'Nama Almarhum / Jenazah' => $death->deceased_name,
            'NIK Almarhum' => $death->deceased_nik ?: '-',
            'Tempat / Tgl Meninggal' => ($death->death_place ?: '-') . ', ' . $deathDateStr,
            'Penyebab Kematian' => $death->cause_of_death ?: '-',
            'Waktu Pengajuan' => $createdAtStr,
        ];

        $actionUrl = route('admin.death.show', $death->id);
        $subject = "[Pelayanan Purwobinangun] Pengajuan Baru Akte Kematian - {$death->registration_no}";

        $mailable = new AdminNewSubmissionNotification(
            submissionType: 'death',
            typeTitle: 'Akte Kematian',
            subjectLine: $subject,
            actionUrl: $actionUrl,
            details: $details,
            submission: $death
        );

        return self::sendToAdmins($mailable);
    }

    /**
     * Kirim notifikasi email ke admin untuk pendaftaran Akun Warga Baru.
     */
    public static function notifyNewCitizenRegistration(User $citizen): bool
    {
        $createdAtStr = $citizen->created_at
            ? $citizen->created_at->translatedFormat('d F Y, H:i') . ' WIB'
            : now()->translatedFormat('d F Y, H:i') . ' WIB';

        $memberCount = $citizen->familyMembers()->count();

        $details = [
            'Nama Lengkap' => $citizen->name,
            'NIK' => $citizen->nik,
            'Nomor Kartu Keluarga (KK)' => $citizen->family_card_no,
            'No. HP / WhatsApp' => $citizen->phone ?: '-',
            'Email' => $citizen->email ?: '-',
            'Alamat Domisili' => trim(($citizen->address ?: '-') . ' (RT ' . ($citizen->rt ?: '0') . ' / RW ' . ($citizen->rw ?: '0') . ')'),
            'Posisi dalam KK' => $citizen->family_relationship ?: 'Kepala Keluarga',
            'Anggota KK Ditambahkan' => $memberCount > 0 ? $memberCount . ' orang' : 'Tidak ada',
            'Waktu Pendaftaran' => $createdAtStr,
        ];

        $actionUrl = route('admin.citizens.show', $citizen->id);
        $subject = "[Pelayanan Purwobinangun] Pendaftaran Akun Warga Baru - {$citizen->name}";

        $mailable = new AdminNewSubmissionNotification(
            submissionType: 'warga_registration',
            typeTitle: 'Pendaftaran Akun Warga Baru',
            subjectLine: $subject,
            actionUrl: $actionUrl,
            details: $details,
            submission: $citizen
        );

        return self::sendToAdmins($mailable);
    }

    /**
     * Kirim notifikasi email ke admin untuk permohonan Perubahan Data Warga.
     */
    public static function notifyNewProfileUpdateRequest(ProfileUpdateRequest $profileRequest): bool
    {
        $createdAtStr = $profileRequest->created_at
            ? $profileRequest->created_at->translatedFormat('d F Y, H:i') . ' WIB'
            : now()->translatedFormat('d F Y, H:i') . ' WIB';

        $memberCount = is_array($profileRequest->family_members_data) ? count($profileRequest->family_members_data) : 0;

        $details = [
            'Nama Pemohon' => $profileRequest->name,
            'NIK' => $profileRequest->nik ?: ($profileRequest->user?->nik ?: '-'),
            'Nomor Kartu Keluarga (KK)' => $profileRequest->family_card_no ?: ($profileRequest->user?->family_card_no ?: '-'),
            'No. HP / WhatsApp' => $profileRequest->phone ?: '-',
            'Email' => $profileRequest->email ?: ($profileRequest->user?->email ?: '-'),
            'Alamat Baru' => trim(($profileRequest->address ?: '-') . ' (RT ' . ($profileRequest->rt ?: '0') . ' / RW ' . ($profileRequest->rw ?: '0') . ')'),
            'Anggota KK Ditautkan' => $memberCount > 0 ? $memberCount . ' orang' : 'Tidak ada',
            'Waktu Pengajuan' => $createdAtStr,
        ];

        $actionUrl = route('admin.profile_requests.show', $profileRequest->id);
        $subject = "[Pelayanan Purwobinangun] Permohonan Perubahan Data Warga - {$profileRequest->name}";

        $mailable = new AdminNewSubmissionNotification(
            submissionType: 'profile_update',
            typeTitle: 'Perubahan Data Profil Warga',
            subjectLine: $subject,
            actionUrl: $actionUrl,
            details: $details,
            submission: $profileRequest
        );

        return self::sendToAdmins($mailable);
    }
}
