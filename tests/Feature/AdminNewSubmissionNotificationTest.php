<?php

namespace Tests\Feature;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use App\Services\AdminNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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

    public function test_birth_certificate_submission_appears_in_admin_website_notifications_without_email(): void
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

        $this->actingAs($warga)->post('/akte-kelahiran/buat', [
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
            'doc_witness_ktp' => UploadedFile::fake()->create('saksi.jpg', 100),
        ]);

        $birth = BirthCertificate::first();
        $this->assertNotNull($birth);
        Mail::assertNothingSent();

        $adminNotifs = AdminNotificationService::getNotificationsForAdmin();
        $this->assertTrue($adminNotifs->contains(fn ($item) => $item['reference_no'] === $birth->registration_no && $item['type'] === 'akte'));

        $responseAdmin = $this->actingAs($admin)->get('/admin/dashboard');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('id="admin-notif-btn"', false);
        $responseAdmin->assertSee('id="admin-notif-dropdown"', false);
        $responseAdmin->assertSee($birth->registration_no);
        $responseAdmin->assertSee('Bayi Baru Lahir');
    }

    public function test_death_certificate_submission_appears_in_admin_website_notifications_without_email(): void
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

        $this->actingAs($warga)->post('/akte-kematian/buat', [
            'family_card_no' => '3404050101900003',
            'head_of_family_name' => 'Almarhum Budi',
            'deceased_nik' => '3404050101450001',
            'deceased_name' => 'Almarhum Budi',
            'gender' => 'L',
            'deceased_birth_place' => 'Sleman',
            'birth_date' => '1945-01-01',
            'religion' => 'Islam',
            'padukuhan' => 'Donolayan',
            'rt' => '01',
            'rw' => '02',
            'death_day' => 'Senin',
            'death_date' => '2026-08-10',
            'death_place' => 'Rumah',
            'cause_of_death' => 'Sakit',
            'reported_by_title' => 'Dokter',
            'applicant_nik' => '3404051109720002',
            'applicant_name' => 'Pelapor Kematian',
            'applicant_phone' => '08198765432',
            'applicant_relation' => 'Anak',
            'doc_death_statement' => UploadedFile::fake()->create('kematian.pdf', 100),
            'doc_rt_rw_statement' => UploadedFile::fake()->create('rt_rw.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_deceased_ktp' => UploadedFile::fake()->create('ktp_jenazah.jpg', 100),
            'doc_heir_family_card' => UploadedFile::fake()->create('kk_waris.jpg', 100),
            'doc_heir_ktp' => UploadedFile::fake()->create('ktp_pelapor.jpg', 100),
        ]);

        $death = DeathCertificate::first();
        $this->assertNotNull($death);
        Mail::assertNothingSent();

        $adminNotifs = AdminNotificationService::getNotificationsForAdmin();
        $this->assertTrue($adminNotifs->contains(fn ($item) => $item['reference_no'] === $death->registration_no && $item['type'] === 'akte'));

        $responseAdmin = $this->actingAs($admin)->get('/admin/dashboard');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee($death->registration_no);
        $responseAdmin->assertSee('Almarhum Budi');
    }

    public function test_warga_registration_appears_in_admin_website_notifications_without_email(): void
    {
        Mail::fake();

        User::create([
            'name' => 'Admin Petugas',
            'email' => 'petugas@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/warga/daftar', [
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
        Mail::assertNothingSent();

        $adminNotifs = AdminNotificationService::getNotificationsForAdmin();
        $this->assertTrue($adminNotifs->contains(fn ($item) => $item['reference_no'] === $newWarga->nik && $item['type'] === 'citizen'));
    }

    public function test_profile_update_request_appears_in_admin_website_notifications_without_email(): void
    {
        Mail::fake();

        User::create([
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

        $this->actingAs($warga)->put('/profil', [
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
        Mail::assertNothingSent();

        $adminNotifs = AdminNotificationService::getNotificationsForAdmin();
        $this->assertTrue($adminNotifs->contains(fn ($item) => $item['type'] === 'profile' && str_contains($item['message'], 'Warga Profil Baru')));
    }

    public function test_admin_profile_page_no_longer_has_email_notification_settings(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.utama@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $responseGet = $this->actingAs($admin)->get('/admin/profil');
        $responseGet->assertStatus(200);
        $responseGet->assertSee('Sistem Pemberitahuan Website');
        $responseGet->assertDontSee('Pengaturan Email Notifikasi Sistem & Pengajuan Masuk', false);
        $responseGet->assertDontSee('Panduan Konfigurasi SMTP');

        $responsePut = $this->actingAs($admin)->put('/admin/profil/email-settings', []);
        $responsePut->assertStatus(404);
    }
}
