<?php

namespace Tests\Feature;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\FamilyMember;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WargaProfileUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function createActiveWarga(): User
    {
        $warga = User::create([
            'role' => 'warga',
            'nik' => '3404010101900001',
            'family_card_no' => '3404010101000001',
            'name' => 'Budi Santoso',
            'birth_place' => 'Sleman',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'address' => 'Watuadeg RT 01 RW 05',
            'rt' => '01',
            'rw' => '05',
            'phone' => '081234567890',
            'email' => 'budi@example.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'verified_at' => now(),
        ]);

        FamilyMember::create([
            'user_id' => $warga->id,
            'family_card_no' => $warga->family_card_no,
            'nik' => '3404010101920002',
            'name' => 'Siti Aminah',
            'birth_place' => 'Sleman',
            'birth_date' => '1992-05-10',
            'gender' => 'P',
            'family_relationship' => 'Istri',
        ]);

        return $warga;
    }

    protected function createAdmin(): User
    {
        return User::create([
            'role' => 'admin',
            'name' => 'Admin Kalurahan',
            'email' => 'admin@purwobinangun.desa.id',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);
    }

    public function test_warga_can_access_profile_page()
    {
        $warga = $this->createActiveWarga();

        $response = $this->actingAs($warga)->get(route('profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Siti Aminah');
        $response->assertSee('Formulir Pengajuan Perubahan Data');
    }

    public function test_warga_submitting_update_does_not_modify_user_directly_and_creates_pending_request()
    {
        $warga = $this->createActiveWarga();

        $postData = [
            'nik' => $warga->nik,
            'family_card_no' => $warga->family_card_no,
            'name' => 'Budi Santoso, S.Kom', // Diubah
            'birth_place' => 'Sleman',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'phone' => '089999999999', // Diubah
            'email' => 'budi.baru@example.com', // Diubah
            'address' => 'Watuadeg RT 02 RW 05 Baru', // Diubah
            'rt' => '02',
            'rw' => '05',
            'family_members' => [
                1 => [
                    'family_card_no' => $warga->family_card_no,
                    'nik' => '3404010101920002',
                    'name' => 'Siti Aminah',
                    'birth_place' => 'Sleman',
                    'birth_date' => '1992-05-10',
                    'gender' => 'P',
                    'family_relationship' => 'Istri',
                ],
                2 => [
                    'family_card_no' => $warga->family_card_no,
                    'nik' => '3404010101230003',
                    'name' => 'Ananda Santoso', // Anggota baru ditambahkan
                    'birth_place' => 'Sleman',
                    'birth_date' => '2023-01-15',
                    'gender' => 'L',
                    'family_relationship' => 'Anak',
                ],
            ],
        ];

        $response = $this->actingAs($warga)->put(route('profile.update'), $postData);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHas('success');

        // Pastikan tabel users ASLI BELUM BERUBAH
        $wargaFresh = $warga->fresh();
        $this->assertEquals('Budi Santoso', $wargaFresh->name);
        $this->assertEquals('budi@example.com', $wargaFresh->email);
        $this->assertEquals('081234567890', $wargaFresh->phone);

        // Pastikan family_members ASLI BELUM BERUBAH (masih 1)
        $this->assertEquals(1, $wargaFresh->familyMembers()->count());

        // Pastikan ada record di profile_update_requests berstatus pending
        $this->assertDatabaseHas('profile_update_requests', [
            'user_id' => $warga->id,
            'name' => 'Budi Santoso, S.Kom',
            'email' => 'budi.baru@example.com',
            'phone' => '089999999999',
            'status' => 'pending',
        ]);

        $profileReq = ProfileUpdateRequest::where('user_id', $warga->id)->first();
        $this->assertNotNull($profileReq);
        $this->assertCount(2, $profileReq->family_members_data);

        // Buka kembali halaman profil, pastikan banner pending muncul
        $viewResponse = $this->actingAs($warga)->get(route('profile.index'));
        $viewResponse->assertSee('Permohonan Perubahan Data Sedang Menunggu Verifikasi Admin');
        $viewResponse->assertSee('Budi Santoso, S.Kom');
    }

    public function test_warga_can_cancel_pending_profile_update_request()
    {
        $warga = $this->createActiveWarga();

        $req = ProfileUpdateRequest::create([
            'user_id' => $warga->id,
            'nik' => $warga->nik,
            'family_card_no' => $warga->family_card_no,
            'name' => 'Budi Update',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($warga)->delete(route('profile.cancel', $req));

        $response->assertRedirect(route('profile.index'));
        $this->assertDatabaseMissing('profile_update_requests', ['id' => $req->id]);
    }

    public function test_admin_can_view_profile_requests_index_and_show()
    {
        $admin = $this->createAdmin();
        $warga = $this->createActiveWarga();

        $profileReq = ProfileUpdateRequest::create([
            'user_id' => $warga->id,
            'nik' => $warga->nik,
            'family_card_no' => $warga->family_card_no,
            'name' => 'Budi Santoso Baru',
            'birth_place' => 'Sleman',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'phone' => '089999999999',
            'email' => 'budi.baru@example.com',
            'address' => 'Watuadeg Baru',
            'rt' => '02',
            'rw' => '05',
            'family_members_data' => [
                [
                    'family_card_no' => $warga->family_card_no,
                    'nik' => '3404010101920002',
                    'name' => 'Siti Aminah Baru',
                    'birth_place' => 'Sleman',
                    'birth_date' => '1992-05-10',
                    'gender' => 'P',
                    'family_relationship' => 'Istri',
                ],
            ],
            'status' => 'pending',
        ]);

        $indexRes = $this->actingAs($admin)->get(route('admin.profile_requests.index'));
        $indexRes->assertStatus(200);
        $indexRes->assertSee('Budi Santoso Baru');

        $showRes = $this->actingAs($admin)->get(route('admin.profile_requests.show', $profileReq));
        $showRes->assertStatus(200);
        $showRes->assertSee('Data Saat Ini (Tersimpan)');
        $showRes->assertSee('Data yang Diajukan (Baru)');
        $showRes->assertSee('Siti Aminah Baru');
    }

    public function test_admin_approving_request_updates_user_and_family_members()
    {
        $admin = $this->createAdmin();
        $warga = $this->createActiveWarga();

        // Buat pengajuan akta kelahiran terkait untuk verifikasi sinkronisasi kontak
        $birth = BirthCertificate::create([
            'user_id' => $warga->id,
            'registration_no' => 'REG-BIRTH-001',
            'family_card_no' => $warga->family_card_no,
            'applicant_nik' => $warga->nik,
            'applicant_name' => $warga->name,
            'applicant_phone' => $warga->phone,
            'child_name' => 'Ananda Santoso',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_date' => '2023-01-01',
            'birth_time' => '08:00',
            'birth_order' => 1,
            'birth_type' => 'Tunggal',
            'birth_helper' => 'Bidan',
            'father_nik' => $warga->nik,
            'father_name' => $warga->name,
            'mother_nik' => '3404010101920002',
            'mother_name' => 'Siti Aminah',
            'status' => 'pending',
        ]);

        $profileReq = ProfileUpdateRequest::create([
            'user_id' => $warga->id,
            'nik' => $warga->nik,
            'family_card_no' => $warga->family_card_no,
            'name' => 'Budi Santoso, M.Kom',
            'birth_place' => 'Yogyakarta',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'phone' => '081299998888',
            'email' => 'budi.mkom@example.com',
            'address' => 'Watuadeg No 10',
            'rt' => '03',
            'rw' => '05',
            'family_members_data' => [
                [
                    'family_card_no' => $warga->family_card_no,
                    'nik' => '3404010101920002',
                    'name' => 'Siti Aminah, S.Pd',
                    'birth_place' => 'Sleman',
                    'birth_date' => '1992-05-10',
                    'gender' => 'P',
                    'family_relationship' => 'Istri',
                ],
                [
                    'family_card_no' => $warga->family_card_no,
                    'nik' => '3404010101230003',
                    'name' => 'Ananda Santoso',
                    'birth_place' => 'Sleman',
                    'birth_date' => '2023-01-15',
                    'gender' => 'L',
                    'family_relationship' => 'Anak',
                ],
            ],
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.profile_requests.verify', $profileReq), [
            'action' => 'approve',
        ]);

        $response->assertRedirect(route('admin.profile_requests.show', $profileReq));
        $response->assertSessionHas('success');

        // Verifikasi User sekarang telah diperbarui
        $wargaFresh = $warga->fresh();
        $this->assertEquals('Budi Santoso, M.Kom', $wargaFresh->name);
        $this->assertEquals('Yogyakarta', $wargaFresh->birth_place);
        $this->assertEquals('budi.mkom@example.com', $wargaFresh->email);
        $this->assertEquals('081299998888', $wargaFresh->phone);
        $this->assertEquals('03', $wargaFresh->rt);

        // Verifikasi Anggota Keluarga sekarang ada 2
        $this->assertEquals(2, $wargaFresh->familyMembers()->count());
        $this->assertDatabaseHas('family_members', [
            'user_id' => $warga->id,
            'name' => 'Siti Aminah, S.Pd',
        ]);
        $this->assertDatabaseHas('family_members', [
            'user_id' => $warga->id,
            'name' => 'Ananda Santoso',
        ]);

        // Verifikasi sinkronisasi ke birth certificate
        $this->assertEquals('Budi Santoso, M.Kom', $birth->fresh()->applicant_name);
        $this->assertEquals('081299998888', $birth->fresh()->applicant_phone);

        // Verifikasi status request menjadi approved
        $this->assertEquals('approved', $profileReq->fresh()->status);
        $this->assertEquals('Admin Kalurahan', $profileReq->fresh()->processed_by);
    }

    public function test_admin_rejecting_request_leaves_user_unchanged_and_stores_rejection_reason()
    {
        $admin = $this->createAdmin();
        $warga = $this->createActiveWarga();

        $profileReq = ProfileUpdateRequest::create([
            'user_id' => $warga->id,
            'nik' => $warga->nik,
            'family_card_no' => $warga->family_card_no,
            'name' => 'Nama Ngawur',
            'birth_place' => 'Sleman',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'phone' => '081299998888',
            'email' => 'ngawur@example.com',
            'address' => 'Watuadeg',
            'rt' => '01',
            'rw' => '05',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.profile_requests.verify', $profileReq), [
            'action' => 'reject',
            'rejection_reason' => 'Nama tidak sesuai dengan KTP fisik dan KK.',
        ]);

        $response->assertRedirect(route('admin.profile_requests.show', $profileReq));

        // Verifikasi User TETAP tidak berubah
        $wargaFresh = $warga->fresh();
        $this->assertEquals('Budi Santoso', $wargaFresh->name);
        $this->assertEquals('budi@example.com', $wargaFresh->email);

        // Verifikasi request berstatus rejected
        $this->assertEquals('rejected', $profileReq->fresh()->status);
        $this->assertEquals('Nama tidak sesuai dengan KTP fisik dan KK.', $profileReq->fresh()->admin_notes);

        // Verifikasi warga melihat catatan penolakan saat buka profil
        $viewRes = $this->actingAs($warga)->get(route('profile.index'));
        $viewRes->assertSee('Permohonan Perubahan Data Sebelumnya Ditolak');
        $viewRes->assertSee('Nama tidak sesuai dengan KTP fisik dan KK.');
    }
}
