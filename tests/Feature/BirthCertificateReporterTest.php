<?php

namespace Tests\Feature;

use App\Models\BirthCertificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BirthCertificateReporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_step_3_displays_pelapor_and_autofills_user_profile(): void
    {
        $warga = User::create([
            'name' => 'Bambang Pamungkas',
            'email' => 'bambang@example.com',
            'nik' => '3404051508850001',
            'family_card_no' => '3404050101010001',
            'birth_date' => '1985-08-15',
            'gender' => 'L',
            'phone' => '081234567899',
            'address' => 'Jl. Kaliurang KM 18',
            'rt' => '02',
            'rw' => '05',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($warga)->get('/akte-kelahiran/buat');

        $response->assertStatus(200);

        // Stepper dan Judul Bagian
        $response->assertSee('id="step-label-3">Pelapor</span>', false);
        $response->assertSee('Identitas Diri Pelapor');
        $response->assertDontSee('Bagian PELAPOR');

        // Nomor & Form Sesuai Formulir Gambar
        $response->assertSee('1. NIK Pelapor (16 Digit)');
        $response->assertSee('value="3404051508850001"', false);

        $response->assertSee('2. Nama Lengkap Pelapor');
        $response->assertSee('value="Bambang Pamungkas"', false);

        $response->assertSee('3. Tanggal Lahir / Umur Pelapor');
        $response->assertSee('value="1985-08-15"', false);

        $response->assertSee('4. Jenis Kelamin Pelapor');
        $response->assertSee('1. LAKI-LAKI');
        $response->assertSee('2. PEREMPUAN');

        $response->assertSee('5. Pekerjaan Pelapor');

        $response->assertSee('6. Alamat Pelapor');
        $response->assertSee('value="Jl. Kaliurang KM 18"', false);
        $response->assertSee('a. Desa / Kelurahan');
        $response->assertSee('b. Kecamatan');
        $response->assertSee('c. Kabupaten / Kota');
        $response->assertSee('d. Propinsi');

        // Field Kontak HP, Hubungan, dan Padukuhan telah dihilangkan dari form Langkah 3
        $response->assertDontSee('No. HP / WhatsApp');
        $response->assertDontSee('Hubungan dengan Bayi');
        $response->assertDontSee('Padukuhan Domisili');
    }

    public function test_submitting_birth_certificate_saves_all_reporter_fields(): void
    {
        $warga = User::create([
            'name' => 'Bambang Pamungkas',
            'email' => 'bambang@example.com',
            'nik' => '3404051508850001',
            'family_card_no' => '3404050101010001',
            'birth_date' => '1985-08-15',
            'gender' => 'L',
            'phone' => '081234567899',
            'address' => 'Jl. Kaliurang KM 18',
            'rt' => '02',
            'rw' => '05',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($warga)->post('/akte-kelahiran/buat', [
            // Bayi
            'child_name' => 'Gita Gutawa',
            'gender' => 'P',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-08-10',
            'birth_time' => '09:00',
            'birth_type' => 'Tunggal',
            'birth_order' => 1,
            'birth_place_type' => 'Rumah Sakit',

            // Orang Tua
            'father_name' => 'Bambang Pamungkas',
            'father_nik' => '3404051508850001',
            'father_birth_date' => '1985-08-15',
            'father_job' => 'PNS',
            'mother_name' => 'Tribun Pamungkas',
            'mother_nik' => '3404055508870002',
            'mother_birth_date' => '1987-08-15',
            'mother_job' => 'Karyawan Swasta',

            // Pelapor (Langkah 3)
            'applicant_name' => 'Bambang Pamungkas',
            'applicant_nik' => '3404051508850001',
            'applicant_birth_date' => '1985-08-15',
            'applicant_gender' => 'L',
            'applicant_job' => 'PNS',
            'address' => 'Jl. Kaliurang KM 18',
            'rt' => '02',
            'rw' => '05',
            'applicant_village' => 'Purwobinangun',
            'applicant_district' => 'Pakem',
            'applicant_city' => 'Sleman',
            'applicant_province' => 'D.I. Yogyakarta',
            'applicant_phone' => '081234567899',
            'applicant_relation' => 'Ayah Kandung',

            // Dokumen
            'doc_birth_cert' => UploadedFile::fake()->create('surat_lahir.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_marriage_cert' => UploadedFile::fake()->create('buku_nikah.pdf', 100),
            'doc_parents_ktp' => UploadedFile::fake()->create('ktp.jpg', 100),
            'doc_witness_ktp' => UploadedFile::fake()->create('surat_pengantar.pdf', 100),
        ]);

        $this->assertDatabaseHas('birth_certificates', [
            'child_name' => 'Gita Gutawa',
            'applicant_name' => 'Bambang Pamungkas',
            'applicant_nik' => '3404051508850001',
            'applicant_gender' => 'L',
            'applicant_job' => 'PNS',
            'applicant_village' => 'Purwobinangun',
            'applicant_district' => 'Pakem',
            'applicant_city' => 'Sleman',
            'applicant_province' => 'D.I. Yogyakarta',
            'applicant_phone' => '081234567899',
            'applicant_relation' => 'Ayah Kandung',
        ]);

        $birth = BirthCertificate::first();
        $this->assertEquals('1985-08-15', $birth->applicant_birth_date->format('Y-m-d'));
        $this->assertNotNull($birth->applicant_age);
        $this->assertGreaterThan(0, $birth->applicant_age);
        $response->assertRedirect(route('birth.success', ['registration_no' => $birth->registration_no]));
    }

    public function test_submitting_birth_certificate_without_phone_and_relation_uses_user_defaults(): void
    {
        $warga = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'nik' => '3404055508880002',
            'birth_date' => '1988-08-15',
            'gender' => 'P',
            'phone' => '087711223344',
            'sub_village' => 'Watuadeg',
            'address' => 'Watuadeg RT 01 RW 02',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($warga)->post('/akte-kelahiran/buat', [
            'child_name' => 'Bayi Siti',
            'gender' => 'P',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-10-10',
            'birth_order' => 1,
            'birth_type' => 'Tunggal',
            'birth_place_type' => 'Rumah Bersalin',

            'father_name' => 'Suami Siti',
            'father_nik' => '3404051101850003',
            'father_birth_date' => '1985-01-11',
            'father_job' => 'Wiraswasta',

            'mother_name' => 'Siti Nurhaliza',
            'mother_nik' => '3404055508880002',
            'mother_birth_date' => '1988-08-15',
            'mother_job' => 'Ibu Rumah Tangga',

            'applicant_name' => 'Siti Nurhaliza',
            'applicant_nik' => '3404055508880002',
            'applicant_birth_date' => '1988-08-15',
            'applicant_gender' => 'P',
            'applicant_job' => 'Ibu Rumah Tangga',
            'address' => 'Watuadeg RT 01 RW 02',
            // applicant_phone, applicant_relation, and padukuhan omitted!

            'doc_birth_cert' => UploadedFile::fake()->create('surat_lahir.pdf', 100),
            'doc_family_card' => UploadedFile::fake()->create('kk.jpg', 100),
            'doc_marriage_cert' => UploadedFile::fake()->create('buku_nikah.pdf', 100),
            'doc_parents_ktp' => UploadedFile::fake()->create('ktp.jpg', 100),
            'doc_witness_ktp' => UploadedFile::fake()->create('surat_pengantar.pdf', 100),
        ]);

        $response->assertStatus(302);
        $birth = BirthCertificate::where('child_name', 'Bayi Siti')->first();
        $this->assertNotNull($birth);
        $this->assertEquals('087711223344', $birth->applicant_phone);
        $this->assertEquals('Pelapor', $birth->applicant_relation);
        $this->assertEquals('Purwobinangun', $birth->padukuhan);
    }
}
