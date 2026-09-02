<?php

namespace Tests\Feature;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificateServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_be_accessed(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('KALURAHAN PURWOBINANGUN');
        $response->assertSee('Akte Kelahiran');
        $response->assertSee('Akte Kematian');
    }

    public function test_guidelines_page_can_be_accessed(): void
    {
        $response = $this->get('/panduan-persyaratan');
        $response->assertStatus(200);
        $response->assertSee('Persyaratan Pembuatan Akte Kelahiran');
    }

    public function test_birth_certificate_can_be_submitted(): void
    {
        Storage::fake('public');

        $response = $this->post('/akte-kelahiran/buat', [
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
            'doc_birth_cert' => UploadedFile::fake()->create('surat_lahir.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_parents_ktp' => UploadedFile::fake()->create('ktp.jpg', 100),
        ]);

        $this->assertDatabaseHas('birth_certificates', [
            'child_name' => 'Bayi Baru Lahir',
            'applicant_name' => 'Bapak Joko',
            'status' => 'pending',
        ]);

        $birth = BirthCertificate::first();
        $response->assertRedirect(route('birth.success', ['registration_no' => $birth->registration_no]));
    }

    public function test_birth_submissions_list_page_can_be_accessed(): void
    {
        $response = $this->get('/akte-kelahiran/daftar-pengajuan');
        $response->assertStatus(200);
        $response->assertSee('DAFTAR PENGAJUAN PERMOHONAN AKTA');
    }

    public function test_death_certificate_can_be_submitted(): void
    {
        Storage::fake('public');

        $response = $this->post('/akte-kematian/buat', [
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
            'applicant_name' => 'Anak Kandung',
            'applicant_phone' => '08198765432',
            'applicant_relation' => 'Anak',
            'doc_death_statement' => UploadedFile::fake()->create('kematian.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_deceased_ktp' => UploadedFile::fake()->create('ktp_jenazah.jpg', 100),
            'doc_applicant_ktp' => UploadedFile::fake()->create('ktp_pelapor.jpg', 100),
        ]);

        $this->assertDatabaseHas('death_certificates', [
            'deceased_name' => 'Almarhum Budi',
            'status' => 'pending',
        ]);

        $death = DeathCertificate::first();
        $response->assertRedirect(route('death.success', ['registration_no' => $death->registration_no]));
    }

    public function test_tracking_page_can_find_registration(): void
    {
        $birth = BirthCertificate::create([
            'registration_no' => 'AKL-20260901-9999',
            'child_name' => 'Anak Uji Coba',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-08-01',
            'father_nik' => '3404051205900001',
            'father_name' => 'Ayah Test',
            'mother_nik' => '3404055508930002',
            'mother_name' => 'Ibu Test',
            'applicant_nik' => '3404051205900001',
            'applicant_name' => 'Ayah Test',
            'applicant_phone' => '08123456789',
            'applicant_relation' => 'Ayah',
            'padukuhan' => 'Kadilobo',
            'rt' => '01',
            'rw' => '02',
            'status' => 'verified',
        ]);

        $response = $this->get('/lacak?keyword=AKL-20260901-9999');
        $response->assertStatus(200);
        $response->assertSee('AKL-20260901-9999');
        $response->assertSee('Anak Uji Coba');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Petugas Kalurahan',
            'email' => 'admin@purwobinangun.desa.id',
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@purwobinangun.desa.id',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $dashResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Dashboard Pelayanan Kependudukan');
    }
}
