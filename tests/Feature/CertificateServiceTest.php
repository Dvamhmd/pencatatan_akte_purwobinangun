<?php

namespace Tests\Feature;

use App\Mail\SubmissionStatusNotification;
use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
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
            'doc_witness_ktp' => UploadedFile::fake()->create('pengantar_rt_rw.pdf', 100),
        ]);

        $this->assertDatabaseHas('birth_certificates', [
            'child_name' => 'Bayi Baru Lahir',
            'applicant_name' => 'Bapak Joko',
            'status' => 'pending',
        ]);

        $birth = BirthCertificate::first();
        $response->assertRedirect(route('birth.success', ['registration_no' => $birth->registration_no]));
    }

    public function test_birth_certificate_can_be_submitted_with_all_12_kelurahan_template_fields(): void
    {
        Storage::fake('public');

        $warga = User::create([
            'name' => 'Bapak Joko Template',
            'nik' => '3404051205900099',
            'family_card_no' => '3404050101900099',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($warga)->post('/akte-kelahiran/buat', [
            // 1. Nama Lengkap
            'child_name' => 'Muhammad Bintang Pratama',
            // 2. NIK
            'child_nik' => '3404050101260001',
            // 3. Jenis Kelamin
            'gender' => 'L',
            // 4. Tempat Dilahirkan
            'birth_place_type' => 'RS/SB',
            // 5. Tempat Kelahiran
            'birth_place' => 'Sleman',
            // 6. Hari dan Tanggal Lahir
            'birth_day' => 'Senin',
            'birth_date' => '2026-08-03',
            // 7. Pukul
            'birth_time' => '08:45',
            // 8. Jenis Kelahiran
            'birth_type' => 'Tunggal',
            // 9. Kelahiran Anak Ke
            'birth_order' => 1,
            // 10. Penolong Kelahiran
            'birth_helper' => 'Dokter',
            // 11. Berat Bayi
            'weight_gram' => 3450,
            // 12. Panjang Bayi
            'length_cm' => 50.2,

            'applicant_nik' => '3404051205900099',
            'applicant_name' => 'Bapak Joko Template',
            'address' => 'Padukuhan Kadilobo RT 01 / RW 02',
            'applicant_phone' => '081234567890',
            'father_name' => 'Bapak Joko Template',
            'father_nik' => '3404051205900099',
            'father_birth_date' => '1990-05-12',
            'father_job' => 'Wiraswasta',
            'mother_name' => 'Ibu Siti Template',
            'mother_nik' => '3404051205900098',
            'mother_birth_date' => '1992-06-15',
            'mother_job' => 'PNS',
            'doc_birth_cert' => UploadedFile::fake()->create('surat_lahir.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_marriage_cert' => UploadedFile::fake()->create('buku_nikah.pdf', 100),
            'doc_parents_ktp' => UploadedFile::fake()->create('ktp.jpg', 100),
            'doc_witness_ktp' => UploadedFile::fake()->create('pengantar_rt_rw.pdf', 100),
        ]);

        $this->assertDatabaseHas('birth_certificates', [
            'child_name' => 'Muhammad Bintang Pratama',
            'child_nik' => '3404050101260001',
            'gender' => 'L',
            'birth_place_type' => 'RS/SB',
            'birth_place' => 'Sleman',
            'birth_day' => 'Senin',
            'birth_time' => '08:45',
            'birth_type' => 'Tunggal',
            'birth_order' => 1,
            'birth_helper' => 'Dokter',
            'weight_gram' => 3450,
            'weight_kg' => 3.45,
            'length_cm' => 50.2,
            'status' => 'pending',
        ]);

        $birth = BirthCertificate::where('child_nik', '3404050101260001')->first();
        $this->assertNotNull($birth);
        $response->assertRedirect(route('birth.success', ['registration_no' => $birth->registration_no]));
    }

    public function test_birth_certificate_can_be_submitted_with_witnesses_details(): void
    {
        $warga = User::create([
            'name' => 'Keluarga Saksi Test',
            'nik' => '3404051205900888',
            'family_card_no' => '3404050101900888',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($warga)->post('/akte-kelahiran/buat', [
            'child_name' => 'Bayi Dengan Saksi',
            'child_nik' => '3404050101260888',
            'gender' => 'L',
            'birth_place_type' => 'RS/SB',
            'birth_place' => 'Sleman',
            'birth_day' => 'Senin',
            'birth_date' => '2026-08-03',
            'birth_time' => '08:45',
            'birth_type' => 'Tunggal',
            'birth_order' => 1,
            'birth_helper' => 'Dokter',
            'weight_gram' => 3200,
            'length_cm' => 49.0,

            'applicant_nik' => '3404051205900888',
            'applicant_name' => 'Keluarga Saksi Test',
            'address' => 'Kemiri Kemiri',
            'applicant_phone' => '081234567890',
            'father_name' => 'Ayah Test',
            'father_nik' => '3404051205900777',
            'father_birth_date' => '1985-01-01',
            'father_job' => 'Wiraswasta',
            'mother_name' => 'Ibu Test',
            'mother_nik' => '3404051205900666',
            'mother_birth_date' => '1988-02-02',
            'mother_job' => 'PNS',

            // Saksi 1 (Sesuai Lampiran F-2.01)
            'witness_1_nik' => '3404162303870002',
            'witness_1_name' => 'PURNOMO RAHAYU',
            'witness_1_birth_date' => '1987-03-23',
            'witness_1_age' => 37,
            'witness_1_gender' => 'L',
            'witness_1_job' => 'Perangkat Desa',
            'witness_1_address' => 'Kemiri Kemiri',
            'witness_1_rt' => '3',
            'witness_1_rw' => '8',
            'witness_1_village' => 'Purwobinangun',
            'witness_1_district' => 'Pakem',
            'witness_1_city' => 'Sleman',
            'witness_1_province' => 'Di Yogyakarta',

            // Saksi 2 (Sesuai Lampiran F-2.01)
            'witness_2_nik' => '3404160704760001',
            'witness_2_name' => 'HARI PUJIANTO',
            'witness_2_birth_date' => '1976-04-07',
            'witness_2_age' => 48,
            'witness_2_gender' => 'L',
            'witness_2_job' => 'Perangkat Desa',
            'witness_2_address' => 'Sudimoro Wringin',
            'witness_2_rt' => '1',
            'witness_2_rw' => '15',
            'witness_2_village' => 'Purwobinangun',
            'witness_2_district' => 'Pakem',
            'witness_2_city' => 'Sleman',
            'witness_2_province' => 'Di Yogyakarta',

            'doc_birth_cert' => UploadedFile::fake()->create('surat_lahir.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_marriage_cert' => UploadedFile::fake()->create('buku_nikah.pdf', 100),
            'doc_parents_ktp' => UploadedFile::fake()->create('ktp.jpg', 100),
            'doc_witness_ktp' => UploadedFile::fake()->create('pengantar_rt_rw.pdf', 100),
        ]);

        $this->assertDatabaseHas('birth_certificates', [
            'child_nik' => '3404050101260888',
            'witness_1_nik' => '3404162303870002',
            'witness_1_name' => 'PURNOMO RAHAYU',
            'witness_1_gender' => 'L',
            'witness_1_rt' => '3',
            'witness_1_rw' => '8',
            'witness_2_nik' => '3404160704760001',
            'witness_2_name' => 'HARI PUJIANTO',
            'witness_2_gender' => 'L',
            'witness_2_rt' => '1',
            'witness_2_rw' => '15',
        ]);

        $birth = BirthCertificate::where('child_nik', '3404050101260888')->first();
        $this->assertNotNull($birth);
        $this->assertEquals(37, $birth->witness_1_age);
        $this->assertEquals(48, $birth->witness_2_age);

        $response->assertRedirect(route('birth.success', ['registration_no' => $birth->registration_no]));
    }

    public function test_birth_certificate_can_be_submitted_with_applicant_signature(): void
    {
        Storage::fake('public');

        $warga = User::create([
            'name' => 'Pemohon Tanda Tangan',
            'nik' => '3404051001950009',
            'family_card_no' => '3404050101950009',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        // 1x1 transparent PNG data URI
        $dummySignatureDataUri = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

        $response = $this->actingAs($warga)->post('/akte-kelahiran/buat', [
            'applicant_name' => 'Pemohon Tanda Tangan',
            'applicant_nik' => '3404051001950009',
            'applicant_phone' => '081234567899',
            'address' => 'Padukuhan Kemiri RT 01 / RW 02',
            'child_name' => 'Bayi Bertanda Tangan',
            'child_nik' => '3404050101260999',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_place_type' => 'Rumah Sakit',
            'birth_date' => '2026-02-15',
            'birth_time' => '10:00',
            'birth_order' => 1,
            'birth_type' => 'Tunggal',
            'birth_helper' => 'Dokter',
            'father_name' => 'Ayah Test',
            'father_nik' => '3404051001850777',
            'father_birth_date' => '1985-01-10',
            'father_job' => 'PNS',
            'mother_name' => 'Ibu Test',
            'mother_nik' => '3404051205900777',
            'mother_birth_date' => '1990-05-12',
            'mother_job' => 'PNS',
            'applicant_signature' => $dummySignatureDataUri,
            'doc_birth_cert' => UploadedFile::fake()->create('surat_lahir.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_marriage_cert' => UploadedFile::fake()->create('buku_nikah.pdf', 100),
            'doc_parents_ktp' => UploadedFile::fake()->create('ktp.jpg', 100),
            'doc_witness_ktp' => UploadedFile::fake()->create('pengantar_rt_rw.pdf', 100),
        ]);

        $birth = BirthCertificate::where('child_nik', '3404050101260999')->first();
        $this->assertNotNull($birth);
        $this->assertNotNull($birth->applicant_signature);
        Storage::disk('public')->assertExists($birth->applicant_signature);
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

        $response = $this->actingAs($warga)->followingRedirects()->get('/akte-kelahiran/daftar-pengajuan');
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

        // 4. Ubah ke Siap diambil dengan toggle notifikasi (hanya WhatsApp aktif, email nonaktif)
        $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'ready_for_pickup',
            'rejection_note' => 'Akte kelahiran telah selesai dicetak dan siap diambil.',
            'send_email' => '0',
            'send_whatsapp' => '1',
        ]);
        $this->assertEquals('Siap diambil', $birth->fresh()->status_label);

        // Halaman verifikasi menampilkan pop-up card konfirmasi notifikasi beserta toggle email dan whatsapp
        $responseShowReady = $this->actingAs($admin)->get('/admin/akte-kelahiran/' . $birth->id);
        $responseShowReady->assertStatus(200);
        $responseShowReady->assertSee('Konfirmasi Pengiriman Notifikasi');
        $responseShowReady->assertSee('Email Warga');
        $responseShowReady->assertSee('WhatsApp');

        // 5. Ubah ke Sudah diambil (otomatis masuk ke arsip)
        $responsePickedUp = $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'picked_up',
            'rejection_note' => 'Dokumen telah diserahkan dan diambil oleh pemohon.',
            'send_email' => '1',
            'send_whatsapp' => '1',
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

    public function test_status_updates_trigger_email_and_whatsapp_notifications_based_on_toggles(): void
    {
        Mail::fake();
        Http::fake([
            'api.fonnte.com/*' => Http::response(['status' => true, 'detail' => 'Pesan terkirim'], 200),
        ]);

        $admin = User::create([
            'name' => 'Petugas Validasi',
            'email' => 'petugas@purwobinangun.desa.id',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $warga = User::create([
            'name' => 'Warga Notifikasi',
            'email' => 'warga.notif@example.com',
            'phone' => '081234567890',
            'nik' => '3404051111222233',
            'family_card_no' => '3404059999888877',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $birth = BirthCertificate::create([
            'user_id' => $warga->id,
            'registration_no' => 'AKL-20260904-7777',
            'child_name' => 'Anak Validasi',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-08-01',
            'applicant_nik' => $warga->nik,
            'applicant_name' => $warga->name,
            'applicant_phone' => $warga->phone,
            'padukuhan' => 'Kadilobo',
            'status' => 'pending',
        ]);

        // 1. Akte Kelahiran: Ubah status ke 'in_process' dengan toggle email dan WA aktif
        $resBirthInProcess = $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'in_process',
            'rejection_note' => 'Berkas pendaftaran sedang diverifikasi oleh staf pelayanan.',
            'send_email' => '1',
            'send_whatsapp' => '1',
        ]);

        $resBirthInProcess->assertRedirect(route('admin.birth.show', $birth));
        $resBirthInProcess->assertSessionHas('success');
        $this->assertEquals('Sedang Diproses', $birth->fresh()->status_label);

        // Pastikan Mailable terkirim untuk status in_process
        Mail::assertSent(SubmissionStatusNotification::class, function ($mail) use ($warga, $birth) {
            return $mail->hasTo($warga->email)
                && $mail->status === 'in_process'
                && $mail->submission->id === $birth->id;
        });

        // Pastikan WhatsApp Fonnte dipanggil
        Http::assertSent(function ($request) use ($warga) {
            return str_contains($request->url(), 'api.fonnte.com/send')
                && str_contains($request['message'], 'Sedang Diproses')
                && $request['target'] === '6281234567890';
        });

        // 2. Akte Kelahiran: Ubah status ke 'revision' dengan hanya WA aktif (email nonaktif)
        Mail::fake();
        Http::fake([
            'api.fonnte.com/*' => Http::response(['status' => true], 200),
        ]);

        $resBirthRevision = $this->actingAs($admin)->put('/admin/akte-kelahiran/' . $birth->id . '/status', [
            'status' => 'revision',
            'rejection_note' => 'Harap unggah ulang KTP orang tua.',
            'send_email' => '0',
            'send_whatsapp' => '1',
        ]);

        $resBirthRevision->assertRedirect(route('admin.birth.show', $birth));
        $this->assertEquals('Revisi Berkas', $birth->fresh()->status_label);
        Mail::assertNothingSent();
        Http::assertSent(function ($request) {
            return str_contains($request['message'], 'Revisi Berkas');
        });

        // 3. Akte Kematian: Buat pengajuan dan ubah ke 'ready_for_pickup' dengan email aktif dan WA nonaktif
        $death = DeathCertificate::create([
            'user_id' => $warga->id,
            'registration_no' => 'AKM-20260904-8888',
            'deceased_nik' => '3404051111999900',
            'deceased_name' => 'Jenazah Validasi',
            'gender' => 'L',
            'birth_date' => '1950-01-01',
            'death_date' => '2026-09-01',
            'applicant_nik' => $warga->nik,
            'applicant_name' => $warga->name,
            'applicant_phone' => $warga->phone,
            'applicant_relation' => 'Anak Kandung',
            'padukuhan' => 'Kadilobo',
            'status' => 'pending',
        ]);

        Mail::fake();
        Http::fake([
            'api.fonnte.com/*' => Http::response(['status' => true], 200),
        ]);

        $resDeathReady = $this->actingAs($admin)->put('/admin/akte-kematian/' . $death->id . '/status', [
            'status' => 'ready_for_pickup',
            'rejection_note' => 'Akte kematian sudah dicetak dan siap diambil.',
            'send_email' => '1',
            'send_whatsapp' => '0',
        ]);

        $resDeathReady->assertRedirect(route('admin.death.show', $death));
        $this->assertEquals('Siap Diambil', $death->fresh()->status_label);

        Mail::assertSent(SubmissionStatusNotification::class, function ($mail) use ($warga, $death) {
            return $mail->hasTo($warga->email)
                && $mail->status === 'ready_for_pickup'
                && $mail->submission->id === $death->id;
        });
        Http::assertNothingSent();

        // 4. Akte Kematian: Ubah status ke 'picked_up' dengan kedua toggle nonaktif
        Mail::fake();
        Http::fake();

        $resDeathPickedUp = $this->actingAs($admin)->put('/admin/akte-kematian/' . $death->id . '/status', [
            'status' => 'picked_up',
            'rejection_note' => 'Dokumen telah diserahkan kepada keluarga pelapor.',
            'send_email' => '0',
            'send_whatsapp' => '0',
        ]);

        $resDeathPickedUp->assertRedirect(route('admin.death.show', $death));
        $this->assertEquals('Sudah Diambil', $death->fresh()->status_label);
        Mail::assertNothingSent();
        Http::assertNothingSent();
    }

    public function test_birth_certificate_revision_flow(): void
    {
        Storage::fake('public');

        $warga = User::create([
            'name' => 'Warga Revisi',
            'email' => 'warga.revisi@gmail.com',
            'nik' => '3404051205900010',
            'family_card_no' => '3404050101900010',
            'role' => 'warga',
            'password' => bcrypt('password123'),
        ]);

        $birth = BirthCertificate::create([
            'user_id' => $warga->id,
            'registration_no' => 'AKL-20261027-0001',
            'family_card_no' => $warga->family_card_no,
            'applicant_name' => 'Warga Revisi',
            'applicant_nik' => '3404051205900010',
            'applicant_phone' => '081234567890',
            'applicant_relation' => 'Ayah Kandung',
            'child_name' => 'Bayi Perlu Revisi',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-10-01',
            'birth_order' => 1,
            'birth_type' => 'Tunggal',
            'birth_place_type' => 'RS/SB',
            'father_name' => 'Ayah Lama',
            'father_nik' => '3404051205900010',
            'father_birth_date' => '1990-05-12',
            'father_job' => 'Wiraswasta',
            'mother_name' => 'Ibu Lama',
            'mother_nik' => '3404055205920002',
            'mother_birth_date' => '1992-05-12',
            'mother_job' => 'Ibu Rumah Tangga',
            'address' => 'Kadilobo RT 01 RW 01',
            'status' => 'revision',
            'rejection_note' => 'Mohon perbaiki nama lengkap anak dan perbarui foto KTP.',
            'doc_parents_ktp' => 'uploads/birth_certs/old_ktp.jpg',
            'doc_family_card' => 'uploads/birth_certs/old_kk.jpg',
            'doc_birth_cert' => 'uploads/birth_certs/old_lahir.jpg',
            'doc_marriage_cert' => 'uploads/birth_certs/old_nikah.jpg',
            'doc_witness_ktp' => 'uploads/birth_certs/old_pengantar.jpg',
            'applicant_signature' => 'signatures/old_sig.png',
        ]);

        // 1. Pada halaman lacak, pastikan tombol 'Revisi Berkas' muncul dan tombol Cetak Tanda Terima di-disable
        $trackingResponse = $this->actingAs($warga)->get('/lacak/birth/' . $birth->registration_no);
        $trackingResponse->assertStatus(200);
        $trackingResponse->assertSee('Revisi Berkas');
        $trackingResponse->assertSee(route('birth.create', ['revision' => $birth->registration_no]));
        $trackingResponse->assertSee('disabled');
        $trackingResponse->assertSee('Cetak Tanda Terima Permohonan');

        // Akses langsung ke cetak tanda terima saat status permohonan 'revision' dilarang dan diredirect
        $printAttempt = $this->actingAs($warga)->get(route('tracking.print_receipt', ['type' => 'birth', 'registrationNo' => $birth->registration_no]));
        $printAttempt->assertRedirect(route('tracking.show', ['type' => 'birth', 'registrationNo' => $birth->registration_no]));
        $printAttempt->assertSessionHas('error');

        // 2. Pada halaman /akte-kelahiran/buat?revision=AKL-20261027-0001
        $createResponse = $this->actingAs($warga)->get(route('birth.create', ['revision' => $birth->registration_no]));
        $createResponse->assertStatus(200);
        $createResponse->assertDontSee('Mode Revisi Berkas Permohonan');
        $createResponse->assertSee('Catatan Perbaikan dari Petugas / Admin Kalurahan');
        $createResponse->assertSee('Mohon perbaiki nama lengkap anak dan perbarui foto KTP.');
        $createResponse->assertSee('revision_registration_no');
        $createResponse->assertSee('AKL-20261027-0001');

        // 3. Warga mengirim form revisi (perbaikan nama anak) tanpa harus mengunggah ulang dokumen yang sudah ada
        $revisionPayload = [
            'revision_registration_no' => 'AKL-20261027-0001',
            'applicant_name' => 'Warga Revisi',
            'applicant_nik' => '3404051205900010',
            'applicant_phone' => '081234567890',
            'applicant_relation' => 'Ayah Kandung',
            'address' => 'Kadilobo RT 01 RW 01',
            'child_name' => 'Bayi Sudah Direvisi',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-10-01',
            'birth_order' => 1,
            'birth_type' => 'Tunggal',
            'birth_place_type' => 'RS/SB',
            'father_name' => 'Ayah Lama',
            'father_nik' => '3404051205900010',
            'father_birth_date' => '1990-05-12',
            'father_job' => 'Wiraswasta',
            'mother_name' => 'Ibu Lama',
            'mother_nik' => '3404055205920002',
            'mother_birth_date' => '1992-05-12',
            'mother_job' => 'Ibu Rumah Tangga',
        ];

        $submitResponse = $this->actingAs($warga)->post(route('birth.store'), $revisionPayload);
        $submitResponse->assertRedirect(route('birth.success', ['registration_no' => 'AKL-20261027-0001']));

        // Pastikan tidak ada data duplikat yang dibuat
        $this->assertEquals(1, BirthCertificate::where('registration_no', 'AKL-20261027-0001')->count());

        $refreshed = $birth->fresh();
        $this->assertEquals('Bayi Sudah Direvisi', $refreshed->child_name);
        $this->assertEquals('pending', $refreshed->status);
        $this->assertEquals('uploads/birth_certs/old_ktp.jpg', $refreshed->doc_parents_ktp);
        $this->assertEquals('signatures/old_sig.png', $refreshed->applicant_signature);
    }

    public function test_birth_certificate_letter_can_be_printed_in_a4_format(): void
    {
        $warga = User::create([
            'name' => 'Warga Purwobinangun',
            'nik' => '3404051101900001',
            'family_card_no' => '3404050101900002',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $birth = BirthCertificate::create([
            'user_id' => $warga->id,
            'registration_no' => 'AKL-20261030-0099',
            'family_card_no' => '3404050101900002',
            'child_name' => 'Ananda Putri',
            'gender' => 'P',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-10-01',
            'applicant_name' => 'Warga Purwobinangun',
            'applicant_nik' => '3404051101900001',
            'applicant_phone' => '081234567890',
            'applicant_job' => 'Wiraswasta',
            'padukuhan' => 'Watuadeg',
            'rt' => '02',
            'rw' => '05',
            'status' => 'pending',
        ]);

        // Warga dapat mencetak surat akte kelahiran miliknya
        $response = $this->actingAs($warga)->get(route('birth.print_letter', $birth));
        $response->assertStatus(200);
        $response->assertSee('SURAT KETERANGAN');
        $response->assertSee('PEMERINTAH KALURAHAN PURWOBINANGUN');
        $response->assertSee('R. Heri Suasana');
        $response->assertSee('Lurah Purwobinangun');
        $response->assertSee('Mohon Akta Kelahiran untuk ANANDA PUTRI');
        $response->assertSee('Dinas Kependudukan dan Catatan Sipil');

        // Admin juga dapat mencetak surat akte kelahiran
        $admin = User::create([
            'name' => 'Petugas Kalurahan',
            'email' => 'petugas_print@purwobinangun.desa.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $adminResponse = $this->actingAs($admin)->get(route('admin.birth.print_letter', $birth));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('SURAT KETERANGAN');
        $adminResponse->assertSee('R. Heri Suasana');
    }
}
