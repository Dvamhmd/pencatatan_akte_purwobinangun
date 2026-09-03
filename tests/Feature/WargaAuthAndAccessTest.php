<?php

namespace Tests\Feature;

use App\Models\BirthCertificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WargaAuthAndAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_warga_login_when_accessing_sensitive_menu()
    {
        $response = $this->get('/akte-kelahiran/buat');
        $response->assertRedirect('/warga/login');
        $response->assertSessionHas('info');

        $responseList = $this->get('/daftar-pengajuan');
        $responseList->assertRedirect('/warga/login');
    }

    public function test_warga_can_register_with_valid_data()
    {
        $response = $this->post('/warga/daftar', [
            'nik' => '3404051234567890',
            'family_card_no' => '3404050987654321',
            'name' => 'Warga Baru Sleman',
            'birth_place' => 'Sleman',
            'birth_date' => '1998-04-10',
            'gender' => 'L',
            'address' => 'Turgo, Purwobinangun, Pakem',
            'rt' => '02',
            'rw' => '04',
            'phone' => '081299887766',
            'email' => 'wargabaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/warga/login');
        $response->assertSessionHas('registration_success');

        $this->assertDatabaseHas('users', [
            'nik' => '3404051234567890',
            'family_card_no' => '3404050987654321',
            'name' => 'Warga Baru Sleman',
            'status' => 'pending',
            'role' => 'warga',
        ]);
    }

    public function test_warga_registration_fails_if_email_already_exists()
    {
        // Buat user pertama dengan email tertentu
        $this->post('/warga/daftar', [
            'nik' => '3404051111111111',
            'family_card_no' => '3404052222222222',
            'name' => 'User Pertama',
            'birth_place' => 'Sleman',
            'birth_date' => '1995-01-01',
            'gender' => 'L',
            'address' => 'Pakem',
            'rt' => '01',
            'rw' => '01',
            'phone' => '081234567800',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Pendaftaran kedua dengan NIK berbeda tapi email sama
        $response = $this->post('/warga/daftar', [
            'nik' => '3404053333333333',
            'family_card_no' => '3404054444444444',
            'name' => 'User Kedua',
            'birth_place' => 'Sleman',
            'birth_date' => '1996-02-02',
            'gender' => 'P',
            'address' => 'Pakem',
            'rt' => '02',
            'rw' => '02',
            'phone' => '081234567801',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_pending_warga_cannot_login_and_gets_notice()
    {
        // KK C is pending in seeder (Fitri Anggraini, NIK 3404054807960006)
        $response = $this->post('/warga/login', [
            'nik' => '3404054807960006',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('pending_notice');
        $this->assertGuest();
    }

    public function test_rejected_warga_cannot_login_and_gets_rejection_reason()
    {
        // KK D is rejected in seeder (Gunawan Wibisono, NIK 3404052208800005)
        $response = $this->post('/warga/login', [
            'nik' => '3404052208800005',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('rejected_notice');
        $this->assertGuest();
    }

    public function test_active_warga_can_login_and_access_own_kk_submissions_only()
    {
        $wargaA = User::where('nik', '3404051205900001')->first(); // KK 3404050101900001
        $wargaB = User::where('nik', '3404051010880003')->first(); // KK 3404050101880002

        // Login as Warga A
        $response = $this->post('/warga/login', [
            'nik' => '3404051205900001',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($wargaA);

        // List pengajuan harus hanya menampilkan data KK A
        $responseList = $this->actingAs($wargaA)->get('/daftar-pengajuan');
        $responseList->assertStatus(200);
        $responseList->assertSee('Aditya Pratama Nugraha'); // KK A
        $responseList->assertDontSee('Kirana Ayudia Putri'); // KK B

        // Warga A mencoba melihat detail pengajuan milik KK B via URL -> harus 403 Forbidden
        $subB = BirthCertificate::where('family_card_no', $wargaB->family_card_no)->first();
        $responseForbidden = $this->actingAs($wargaA)->get('/lacak/birth/' . $subB->registration_no);
        $responseForbidden->assertStatus(403);

        // Warga A melihat pengajuan miliknya sendiri -> 200 OK
        $subA = BirthCertificate::where('family_card_no', $wargaA->family_card_no)->first();
        $responseAllowed = $this->actingAs($wargaA)->get('/lacak/birth/' . $subA->registration_no);
        $responseAllowed->assertStatus(200);
        $responseAllowed->assertSee('Aditya Pratama Nugraha');
    }

    public function test_admin_can_verify_and_reject_citizens()
    {
        $admin = User::where('email', 'admin@purwobinangun.desa.id')->first();
        $pendingCitizen = User::where('status', 'pending')->first();

        // Admin approves citizen
        $responseApprove = $this->actingAs($admin)->post('/admin/warga/' . $pendingCitizen->id . '/verify', [
            'action' => 'approve',
        ]);

        $responseApprove->assertRedirect('/admin/warga/' . $pendingCitizen->id);
        $this->assertDatabaseHas('users', [
            'id' => $pendingCitizen->id,
            'status' => 'active',
            'verified_by' => 'Petugas Pelayanan Kalurahan',
        ]);

        // Admin rejects citizen with reason
        $responseReject = $this->actingAs($admin)->post('/admin/warga/' . $pendingCitizen->id . '/verify', [
            'action' => 'reject',
            'rejection_reason' => 'NIK tidak sesuai dengan database KTP Kalurahan.',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $pendingCitizen->id,
            'status' => 'rejected',
            'rejection_reason' => 'NIK tidak sesuai dengan database KTP Kalurahan.',
        ]);
    }
}
