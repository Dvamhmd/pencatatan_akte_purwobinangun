<?php

namespace Tests\Feature;

use App\Mail\AdminNewSubmissionNotification;
use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use App\Services\AdminNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminNewSubmissionNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_admin_recipients_resolution_from_env_and_database(): void
    {
        // 1. Buat admin di database
        User::create([
            'name' => 'Admin Kalurahan',
            'email' => 'admin.db@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        // 2. Set environment/config admin email
        Config::set('mail.admin_notification_email', 'admin.env@example.com');

        $recipients = AdminNotificationService::getAdminRecipients();

        $this->assertContains('admin.env@example.com', $recipients);
        $this->assertContains('admin.db@purwobinangun.desa.id', $recipients);
    }

    public function test_birth_certificate_submission_triggers_admin_email_notification(): void
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Admin Petugas',
            'email' => 'petugas@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $warga = User::create([
            'name' => 'Bapak Joko',
            'nik' => '3404051205900001',
            'family_card_no' => '3404050101900001',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($warga)->post('/akte-kelahiran/buat', [
            'child_name' => 'Bayi Baru Lahir',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-08-01',
            'birth_time' => '07:30',
            'birth_type' => 'Tunggal',
            'birth_order' => 1,
            'birth_place_type' => 'Rumah Sakit',
            'applicant_nik' => '3404051205900001',
            'applicant_name' => 'Bapak Joko',
            'address' => 'Padukuhan Kadilobo RT 01 / RW 02',
            'applicant_phone' => '081234567890',
            'father_name' => 'Bapak Joko',
            'father_nik' => '3404051205900001',
            'father_birth_date' => '1990-05-12',
            'father_job' => 'Wiraswasta',
            'mother_name' => 'Ibu Siti',
            'mother_nik' => '3404051205900002',
            'mother_birth_date' => '1992-06-15',
            'mother_job' => 'PNS',
            'doc_birth_cert' => UploadedFile::fake()->create('surat_lahir.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_marriage_cert' => UploadedFile::fake()->create('buku_nikah.pdf', 100),
            'doc_parents_ktp' => UploadedFile::fake()->create('ktp.jpg', 100),
        ]);

        $birth = BirthCertificate::first();
        $this->assertNotNull($birth);

        Mail::assertSent(AdminNewSubmissionNotification::class, function ($mail) use ($admin, $birth) {
            return $mail->hasTo($admin->email)
                && $mail->submissionType === 'birth'
                && $mail->submission->id === $birth->id
                && str_contains($mail->actionUrl, (string) $birth->id);
        });
    }

    public function test_death_certificate_submission_triggers_admin_email_notification(): void
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Admin Petugas',
            'email' => 'petugas@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $warga = User::create([
            'name' => 'Pelapor Kematian',
            'nik' => '3404051109720002',
            'family_card_no' => '3404050101900003',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($warga)->post('/akte-kematian/buat', [
            'deceased_nik' => '3404050101450001',
            'deceased_name' => 'Almarhum Budi',
            'gender' => 'L',
            'religion' => 'Islam',
            'padukuhan' => 'Donolayan',
            'rt' => '01',
            'rw' => '02',
            'death_date' => '2026-08-10',
            'death_place' => 'Rumah',
            'cause_of_death' => 'Sakit',
            'reported_by_title' => 'Dokter',
            'applicant_nik' => '3404051109720002',
            'applicant_name' => 'Pelapor Kematian',
            'applicant_phone' => '08198765432',
            'applicant_relation' => 'Anak',
            'doc_death_statement' => UploadedFile::fake()->create('kematian.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_deceased_ktp' => UploadedFile::fake()->create('ktp_jenazah.jpg', 100),
            'doc_applicant_ktp' => UploadedFile::fake()->create('ktp_pelapor.jpg', 100),
        ]);

        $death = DeathCertificate::first();
        $this->assertNotNull($death);

        Mail::assertSent(AdminNewSubmissionNotification::class, function ($mail) use ($admin, $death) {
            return $mail->hasTo($admin->email)
                && $mail->submissionType === 'death'
                && $mail->submission->id === $death->id
                && str_contains($mail->actionUrl, (string) $death->id);
        });
    }

    public function test_warga_registration_triggers_admin_email_notification(): void
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Admin Petugas',
            'email' => 'petugas@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/warga/daftar', [
            'nik' => '3404051234560001',
            'family_card_no' => '3404059876540001',
            'name' => 'Warga Pendaftar Baru',
            'birth_place' => 'Sleman',
            'birth_date' => '1995-04-10',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'address' => 'Padukuhan Turgo RT 02 / RW 03',
            'rt' => '02',
            'rw' => '03',
            'phone' => '081234567899',
            'email' => 'warga.baru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $newWarga = User::where('nik', '3404051234560001')->first();
        $this->assertNotNull($newWarga);
        $this->assertEquals('pending', $newWarga->status);

        Mail::assertSent(AdminNewSubmissionNotification::class, function ($mail) use ($admin, $newWarga) {
            return $mail->hasTo($admin->email)
                && $mail->submissionType === 'warga_registration'
                && $mail->submission->id === $newWarga->id
                && str_contains($mail->actionUrl, (string) $newWarga->id);
        });
    }

    public function test_profile_update_request_triggers_admin_email_notification(): void
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Admin Petugas',
            'email' => 'petugas@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $warga = User::create([
            'name' => 'Warga Profil',
            'nik' => '3404055555666677',
            'family_card_no' => '3404059999888877',
            'role' => 'warga',
            'status' => 'active',
            'birth_place' => 'Sleman',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'address' => 'Donolayan',
            'rt' => '01',
            'rw' => '02',
            'phone' => '081234567890',
            'email' => 'warga.profil@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($warga)->put('/profil', [
            'nik' => '3404055555666677',
            'family_card_no' => '3404059999888877',
            'name' => 'Warga Profil Baru',
            'birth_place' => 'Sleman',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'address' => 'Donolayan Baru RT 02 / RW 03',
            'rt' => '02',
            'rw' => '03',
            'phone' => '081299988877',
            'email' => 'warga.profil.baru@example.com',
        ]);

        $profileReq = ProfileUpdateRequest::where('user_id', $warga->id)->first();
        $this->assertNotNull($profileReq);
        $this->assertEquals('pending', $profileReq->status);

        Mail::assertSent(AdminNewSubmissionNotification::class, function ($mail) use ($admin, $profileReq) {
            return $mail->hasTo($admin->email)
                && $mail->submissionType === 'profile_update'
                && $mail->submission->id === $profileReq->id
                && str_contains($mail->actionUrl, (string) $profileReq->id);
        });
    }

    public function test_admin_can_view_and_update_email_settings_from_profile_page(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.utama@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        // 1. Cek tampilan halaman profil menampilkan form pengaturan email dan panduan
        $responseGet = $this->actingAs($admin)->get('/admin/profil');
        $responseGet->assertStatus(200);
        $responseGet->assertSee('Pengaturan Email Notifikasi Sistem & Pengajuan Masuk', false);
        $responseGet->assertSee('Panduan Konfigurasi SMTP');
        $responseGet->assertSee('Email Penerima Pengajuan Baru');
        $responseGet->assertSee('Email Pengirim Notifikasi ke Warga');

        // 2. Kirim update pengaturan email
        $responsePut = $this->actingAs($admin)->put('/admin/profil/email-settings', [
            'admin_notification_email' => 'notif.admin@purwobinangun.desa.id, lurah@purwobinangun.desa.id',
            'mail_from_address' => 'layanan.resmi@purwobinangun.desa.id',
            'mail_from_name' => 'Layanan Resmi Kalurahan Purwobinangun',
            'mail_password' => 'abcdefghijklmnop',
        ]);

        $responsePut->assertRedirect(route('admin.profile.index'));
        $responsePut->assertSessionHas('success');
    }

    public function test_update_email_settings_validates_recipient_email_format(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.utama2@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin)->put('/admin/profil/email-settings', [
            'admin_notification_email' => 'email-tidak-valid',
            'mail_from_address' => 'layanan@example.com',
            'mail_from_name' => 'Layanan',
        ]);

        $response->assertSessionHasErrors(['admin_notification_email']);
    }
}
