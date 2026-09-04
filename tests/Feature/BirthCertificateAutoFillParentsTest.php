<?php

namespace Tests\Feature;

use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BirthCertificateAutoFillParentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_autofills_father_and_mother_from_kk_when_warga_is_kepala_keluarga()
    {
        $wargaAyah = User::create([
            'name' => 'Budi Santoso',
            'nik' => '3404051001800001',
            'family_card_no' => '3404050101800001',
            'birth_place' => 'Sleman',
            'birth_date' => '1980-01-10',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        FamilyMember::create([
            'user_id' => $wargaAyah->id,
            'family_card_no' => $wargaAyah->family_card_no,
            'name' => 'Siti Aminah',
            'nik' => '3404055202820002',
            'birth_place' => 'Sleman',
            'birth_date' => '1982-02-12',
            'gender' => 'P',
            'family_relationship' => 'Istri',
        ]);

        $response = $this->actingAs($wargaAyah)->get('/akte-kelahiran/buat');

        $response->assertStatus(200);

        // Ayah terisi otomatis
        $response->assertSee('value="3404051001800001"', false);
        $response->assertSee('value="Budi Santoso"', false);
        $response->assertSee('value="1980-01-10"', false);

        // Ibu terisi otomatis
        $response->assertSee('value="3404055202820002"', false);
        $response->assertSee('value="Siti Aminah"', false);
        $response->assertSee('value="1982-02-12"', false);

        // Informasi keterangan di bawah section
        $response->assertSee('Data orang tua diisi otomatis berdasarkan data KK. Silakan periksa dan ubah apabila data tidak sesuai.');
    }

    public function test_autofills_father_and_mother_from_kk_when_warga_is_istri()
    {
        $wargaIbu = User::create([
            'name' => 'Dewi Lestari',
            'nik' => '3404056003850003',
            'family_card_no' => '3404050101850002',
            'birth_place' => 'Sleman',
            'birth_date' => '1985-03-20',
            'gender' => 'P',
            'family_relationship' => 'Istri',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        FamilyMember::create([
            'user_id' => $wargaIbu->id,
            'family_card_no' => $wargaIbu->family_card_no,
            'name' => 'Agus Wijaya',
            'nik' => '3404051504830004',
            'birth_place' => 'Sleman',
            'birth_date' => '1983-04-15',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
        ]);

        $response = $this->actingAs($wargaIbu)->get('/akte-kelahiran/buat');

        $response->assertStatus(200);

        // Ayah terisi dari data suami di KK
        $response->assertSee('value="3404051504830004"', false);
        $response->assertSee('value="Agus Wijaya"', false);
        $response->assertSee('value="1983-04-15"', false);

        // Ibu terisi dari data warga yang login
        $response->assertSee('value="3404056003850003"', false);
        $response->assertSee('value="Dewi Lestari"', false);
        $response->assertSee('value="1985-03-20"', false);
    }

    public function test_does_not_autofill_father_if_kepala_keluarga_not_laki_laki()
    {
        // Kepala Keluarga berjenis kelamin Perempuan (misal single mother/janda kepala keluarga)
        $wargaIbuKepala = User::create([
            'name' => 'Ibu Mandiri',
            'nik' => '3404055005800005',
            'family_card_no' => '3404050101800003',
            'birth_place' => 'Sleman',
            'birth_date' => '1980-05-10',
            'gender' => 'P',
            'family_relationship' => 'Kepala Keluarga',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($wargaIbuKepala)->get('/akte-kelahiran/buat');

        $response->assertStatus(200);

        // Field Ayah harus kosong (tidak memaksakan nilai)
        $response->assertSee('id="father_nik" name="father_nik" value=""', false);
        $response->assertSee('id="father_name" name="father_name" value=""', false);
    }

    public function test_does_not_autofill_mother_if_no_istri_perempuan()
    {
        // Kepala Keluarga Laki-laki belum menikah / duda tanpa istri
        $wargaDuda = User::create([
            'name' => 'Bapak Duda',
            'nik' => '3404051006780006',
            'family_card_no' => '3404050101780004',
            'birth_place' => 'Sleman',
            'birth_date' => '1978-06-10',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($wargaDuda)->get('/akte-kelahiran/buat');

        $response->assertStatus(200);

        // Ayah terisi
        $response->assertSee('value="3404051006780006"', false);
        $response->assertSee('value="Bapak Duda"', false);

        // Field NIK Ibu harus kosong (value="")
        $response->assertSee('id="mother_nik" name="mother_nik" value=""', false);
        $response->assertSee('id="mother_name" name="mother_name" value=""', false);
    }

    public function test_warga_can_override_autofilled_parent_values_on_submission()
    {
        $warga = User::create([
            'name' => 'Budi Santoso',
            'nik' => '3404051001800001',
            'family_card_no' => '3404050101800001',
            'birth_place' => 'Sleman',
            'birth_date' => '1980-01-10',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'role' => 'warga',
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);

        FamilyMember::create([
            'user_id' => $warga->id,
            'family_card_no' => $warga->family_card_no,
            'name' => 'Siti Aminah',
            'nik' => '3404055202820002',
            'birth_place' => 'Sleman',
            'birth_date' => '1982-02-12',
            'gender' => 'P',
            'family_relationship' => 'Istri',
        ]);

        $response = $this->actingAs($warga)->get('/akte-kelahiran/buat');
        $response->assertStatus(200);

        // Nilai default awal ada di form
        $response->assertSee('value="3404051001800001"', false);

        // Input tetap bertipe editable normal tanpa atribut disabled atau readonly
        $response->assertDontSee('id="father_name" name="father_name" readonly', false);
        $response->assertDontSee('id="father_name" name="father_name" disabled', false);
        $response->assertDontSee('id="mother_name" name="mother_name" readonly', false);
        $response->assertDontSee('id="mother_name" name="mother_name" disabled', false);
    }
}
