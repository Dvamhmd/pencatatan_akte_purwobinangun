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
        $warga = User::create([
            'name' => 'Warga Test',
            'nik' => '3404051205900002',
            'family_card_no' => '3404050101900002',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($warga)->get('/akte-kelahiran/daftar-pengajuan');
        $response->assertStatus(200);
        $response->assertSee('DAFTAR PENGAJUAN PERMOHONAN AKTA');
    }

    public function test_death_certificate_can_be_submitted(): void
    {
        Storage::fake('public');

        $warga = User::create([
            'name' => 'Anak Kandung',
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
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@purwobinangun.desa.id',
            'password' => 'admin123',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        // Saat admin yang sudah login/diingat mengakses /admin/login, diarahkan otomatis ke dashboard
        $loginPageResponse = $this->actingAs($admin)->get('/admin/login');
        $loginPageResponse->assertRedirect(route('admin.dashboard'));

        $dashResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Dashboard Pelayanan Kependudukan');
    }

    public function test_admin_can_update_birth_certificate_through_all_six_statuses(): void
    {
        $admin = User::create([
            'name' => 'Petugas Kalurahan',
            'email' => 'admin@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $birth = BirthCertificate::create([
            'registration_no' => 'AKL-20260903-0001',
            'child_name' => 'Bayi Sukses',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-08-01',
            'applicant_nik' => '3404051205900001',
            'applicant_name' => 'Ayah Test',
            'applicant_phone' => '08123456789',
            'padukuhan' => 'Kadilobo',
            'status' => 'pending',
        ]);

        // Validasi gagal jika catatan verifikator kosong
        $responseFailed = $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'in_process',
            'rejection_note' => '',
        ]);
        $responseFailed->assertSessionHasErrors('rejection_note');

        // 1. Ubah ke Sedang Diproses
        $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'in_process',
            'rejection_note' => 'Berkas terverifikasi lengkap dan sedang diproses.',
        ])->assertRedirect(route('admin.birth.show', $birth));
        $this->assertEquals('Sedang Diproses', $birth->fresh()->status_label);

        // 2. Ubah ke Revisi Berkas
        $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'revision',
            'rejection_note' => 'Mohon unggah ulang foto KTP saksi yang lebih jelas.',
        ]);
        $this->assertEquals('Revisi Berkas', $birth->fresh()->status_label);

        // Validasi gagal jika status diubah tapi pesan/catatan belum diganti dari pesan sebelumnya
        $responseUnchangedNote = $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'rejected',
            'rejection_note' => 'Mohon unggah ulang foto KTP saksi yang lebih jelas.',
        ]);
        $responseUnchangedNote->assertSessionHasErrors('rejection_note');

        // 3. Ubah ke Dibatalkan (rejected) dengan catatan yang sudah diperbarui
        $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'rejected',
            'rejection_note' => 'Data tidak sesuai database kependudukan.',
        ]);
        $this->assertEquals('Dibatalkan', $birth->fresh()->status_label);

        // Halaman detail menampilkan tombol "Arsipkan Pengajuan" saat status Dibatalkan
        $responseShowRejected = $this->actingAs($admin)->get('/admin/akte-kelahiran/' . $birth->id);
        $responseShowRejected->assertStatus(200);
        $responseShowRejected->assertSee('Arsipkan Pengajuan');

        // 4. Ubah ke Siap diambil
        $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'ready_for_pickup',
            'rejection_note' => 'Akte kelahiran telah selesai dicetak dan siap diambil.',
        ]);
        $this->assertEquals('Siap diambil', $birth->fresh()->status_label);

        // 5. Ubah ke Sudah diambil (otomatis masuk ke arsip)
        $responsePickedUp = $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'picked_up',
            'rejection_note' => 'Dokumen telah diserahkan dan diambil oleh pemohon.',
        ]);
        $responsePickedUp->assertSessionHas('success');
        $this->assertEquals('Sudah diambil', $birth->fresh()->status_label);

        // Halaman detail menampilkan tombol "Arsipkan Pengajuan" saat status Sudah diambil serta email warga pemohon
        $responseShowPickedUp = $this->actingAs($admin)->get('/admin/akte-kelahiran/' . $birth->id);
        $responseShowPickedUp->assertStatus(200);
        $responseShowPickedUp->assertSee('Email Warga');
        $responseShowPickedUp->assertSee('Arsipkan Pengajuan');

        // Manual archive test (redirect ke halaman kelola dan status tetap terjaga)
        $responseArchiveManual = $this->actingAs($admin)->post('/admin/arsip/akte-kelahiran/' . $birth->id . '/archive');
        $responseArchiveManual->assertRedirect(route('admin.birth.index'));
        $responseArchiveManual->assertSessionHas('success');
        $this->assertTrue($birth->fresh()->is_archived);

        // Verifikasi TIDAK muncul di daftar utama /admin/akte-kelahiran
        $this->flushSession();
        $responseIndex = $this->actingAs($admin)->get('/admin/akte-kelahiran');
        $responseIndex->assertStatus(200);
        $responseIndex->assertDontSee('AKL-20260903-0001');
        $responseIndex->assertDontSee('Bayi Sukses');

        // Verifikasi HANYA muncul di menu Arsip tab birth dan status tetap terlihat sesuai aslinya
        $responseArchive = $this->actingAs($admin)->get('/admin/arsip?tab=birth');
        $responseArchive->assertStatus(200);
        $responseArchive->assertSee('AKL-20260903-0001');
        $responseArchive->assertSee('Bayi Sukses');
    }

    public function test_admin_can_update_death_certificate_through_all_six_statuses(): void
    {
        $admin = User::create([
            'name' => 'Petugas Kalurahan',
            'email' => 'admin@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $death = DeathCertificate::create([
            'registration_no' => 'AKM-20260903-0001',
            'deceased_nik' => '3404050101450001',
            'deceased_name' => 'Almarhum Uji Coba',
            'gender' => 'L',
            'religion' => 'Islam',
            'padukuhan' => 'Kadilobo',
            'rt' => '01',
            'rw' => '02',
            'death_date' => '2026-08-10',
            'death_place' => 'Rumah',
            'cause_of_death' => 'Sakit Usia Lanjut',
            'reported_by_title' => 'Dokter',
            'applicant_nik' => '3404051109720002',
            'applicant_name' => 'Anak Uji Coba',
            'applicant_phone' => '08198765432',
            'applicant_relation' => 'Anak Kandung',
            'status' => 'pending',
        ]);

        // Validasi gagal jika catatan verifikator kosong
        $responseFailed = $this->actingAs($admin)->put('/admin/akte-kematian/' . $death->id . '/status', [
            'status' => 'in_process',
            'rejection_note' => '',
        ]);
        $responseFailed->assertSessionHasErrors('rejection_note');

        // 1. Ubah ke Sedang Diproses
        $this->actingAs($admin)->put('/admin/akte-kematian/' . $death->id . '/status', [
            'status' => 'in_process',
            'rejection_note' => 'Berkas terverifikasi lengkap dan sedang diproses.',
        ])->assertRedirect(route('admin.death.show', $death));
        $this->assertEquals('Sedang Diproses', $death->fresh()->status_label);

        // 2. Ubah ke Revisi Berkas
        $this->actingAs($admin)->put('/admin/akte-kematian/' . $death->id . '/status', [
            'status' => 'revision',
            'rejection_note' => 'Mohon unggah surat keterangan kematian yang lebih jelas.',
        ]);
        $this->assertEquals('Revisi Berkas', $death->fresh()->status_label);

        // 3. Ubah ke Dibatalkan (rejected)
        $this->actingAs($admin)->put('/admin/akte-kematian/' . $death->id . '/status', [
            'status' => 'rejected',
            'rejection_note' => 'Data kematian tidak valid atau dibatalkan oleh pelapor.',
        ]);
        $this->assertEquals('Dibatalkan', $death->fresh()->status_label);

        // Halaman detail menampilkan tombol "Arsipkan Pengajuan" saat status Dibatalkan
        $responseShowRejected = $this->actingAs($admin)->get('/admin/akte-kematian/' . $death->id);
        $responseShowRejected->assertStatus(200);
        $responseShowRejected->assertSee('Arsipkan Pengajuan');

        // Saat status Dibatalkan lalu diarsipkan: status tetap 'rejected' (Dibatalkan) dan redirect ke halaman kelola /admin/akte-kematian
        $responseArchiveRejected = $this->actingAs($admin)->post('/admin/arsip/akte-kematian/' . $death->id . '/archive');
        $responseArchiveRejected->assertRedirect(route('admin.death.index'));
        $this->assertEquals('rejected', $death->fresh()->status);
        $this->assertEquals('Dibatalkan', $death->fresh()->status_label);
        $this->assertTrue($death->fresh()->is_archived);

        // 4. Ubah ke Siap Diambil
        $this->actingAs($admin)->put('/admin/akte-kematian/' . $death->id . '/status', [
            'status' => 'ready_for_pickup',
            'rejection_note' => 'Surat keterangan / akte kematian telah selesai dicetak dan siap diambil.',
        ]);
        $this->assertEquals('Siap Diambil', $death->fresh()->status_label);

        // 5. Ubah ke Sudah Diambil (otomatis masuk ke arsip)
        $responsePickedUp = $this->actingAs($admin)->put('/admin/akte-kematian/' . $death->id . '/status', [
            'status' => 'picked_up',
            'rejection_note' => 'Dokumen telah diserahkan dan diambil oleh ahli waris / pelapor.',
        ]);
        $responsePickedUp->assertSessionHas('success');
        $this->assertEquals('Sudah Diambil', $death->fresh()->status_label);

        // Halaman detail menampilkan tombol "Arsipkan Pengajuan" saat status Sudah diambil
        $responseShowPickedUp = $this->actingAs($admin)->get('/admin/akte-kematian/' . $death->id);
        $responseShowPickedUp->assertStatus(200);
        $responseShowPickedUp->assertSee('Arsipkan Pengajuan');

        // Manual archive test (redirect ke halaman kelola /admin/akte-kematian)
        $responseArchiveManual = $this->actingAs($admin)->post('/admin/arsip/akte-kematian/' . $death->id . '/archive');
        $responseArchiveManual->assertRedirect(route('admin.death.index'));
        $responseArchiveManual->assertSessionHas('success');
        $this->assertTrue($death->fresh()->is_archived);

        // Verifikasi TIDAK muncul di daftar utama /admin/akte-kematian
        $this->flushSession();
        $responseIndex = $this->actingAs($admin)->get('/admin/akte-kematian');
        $responseIndex->assertStatus(200);
        $responseIndex->assertDontSee('AKM-20260903-0001');
        $responseIndex->assertDontSee('Almarhum Uji Coba');

        // Verifikasi HANYA muncul di menu Arsip tab death
        $responseArchive = $this->actingAs($admin)->get('/admin/arsip?tab=death');
        $responseArchive->assertStatus(200);
        $responseArchive->assertSee('AKM-20260903-0001');
        $responseArchive->assertSee('Almarhum Uji Coba');
    }
}
