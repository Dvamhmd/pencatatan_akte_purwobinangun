<?php

namespace Tests\Feature;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use App\Services\CitizenNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitizenNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizen_notification_service_aggregates_all_warga_events(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'status' => 'active',
            'nik' => '3404010101010001',
            'family_card_no' => '3404010101010002',
            'name' => 'Budi Santoso',
            'verified_at' => now(),
        ]);

        // 1. Birth certificate submission
        $birth = BirthCertificate::create([
            'user_id' => $warga->id,
            'registration_no' => 'AKL-20260916-0001',
            'family_card_no' => $warga->family_card_no,
            'child_name' => 'Ahmad Budi',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-09-01',
            'birth_time' => '08:00:00',
            'birth_type' => 'Tunggal',
            'birth_order' => 1,
            'birth_helper' => 'Bidan',
            'birth_place_type' => 'RS/RB',
            'weight_kg' => 3.2,
            'length_cm' => 49,
            'father_nik' => '3404010101010001',
            'father_name' => 'Budi Santoso',
            'father_birth_date' => '1990-01-01',
            'father_job' => 'Wiraswasta',
            'mother_nik' => '3404010101010003',
            'mother_name' => 'Siti Aminah',
            'mother_birth_date' => '1992-02-02',
            'mother_job' => 'Ibu Rumah Tangga',
            'applicant_nik' => '3404010101010001',
            'applicant_name' => 'Budi Santoso',
            'applicant_phone' => '081234567890',
            'applicant_relation' => 'Ayah',
            'address' => 'Purwobinangun RT 01 RW 02',
            'padukuhan' => 'Babadan',
            'rt' => '01',
            'rw' => '02',
            'status' => 'ready_for_pickup',
        ]);

        // 2. Death certificate submission
        $death = DeathCertificate::create([
            'user_id' => $warga->id,
            'registration_no' => 'AKM-20260916-0001',
            'family_card_no' => $warga->family_card_no,
            'deceased_nik' => '3404010101010099',
            'deceased_name' => 'Siti Rahayu',
            'gender' => 'P',
            'death_date' => '2026-09-05',
            'death_time' => '10:00:00',
            'death_place' => 'Rumah Sakit',
            'cause_of_death' => 'Sakit',
            'determining_party' => 'Dokter',
            'applicant_nik' => '3404010101010001',
            'applicant_name' => 'Budi Santoso',
            'applicant_phone' => '081234567890',
            'applicant_relation' => 'Anak',
            'address' => 'Purwobinangun RT 01 RW 02',
            'padukuhan' => 'Babadan',
            'rt' => '01',
            'rw' => '02',
            'status' => 'rejected',
            'rejection_note' => 'Surat keterangan dokter belum dilampirkan',
        ]);

        // 3. Profile update request
        $profileReq = ProfileUpdateRequest::create([
            'user_id' => $warga->id,
            'nik' => $warga->nik,
            'family_card_no' => $warga->family_card_no,
            'name' => 'Budi Santoso, S.T.',
            'birth_place' => 'Sleman',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'address' => 'Purwobinangun',
            'rt' => '01',
            'rw' => '02',
            'phone' => '081234567890',
            'status' => 'approved',
            'admin_notes' => 'Disetujui oleh admin kelurahan',
            'processed_by' => 'Petugas Admin',
            'processed_at' => now(),
        ]);

        $notifs = CitizenNotificationService::getNotificationsForUser($warga);

        $this->assertGreaterThanOrEqual(4, $notifs->count());

        // Check account active notif
        $this->assertTrue($notifs->contains('type', 'account'));
        
        // Check birth notif
        $birthNotif = $notifs->firstWhere('type', 'birth');
        $this->assertNotNull($birthNotif);
        $this->assertStringContainsString('Ahmad Budi', $birthNotif['message']);
        $this->assertStringContainsString('SIAP DIAMBIL', $birthNotif['message']);

        // Check death notif
        $deathNotif = $notifs->firstWhere('type', 'death');
        $this->assertNotNull($deathNotif);
        $this->assertStringContainsString('Siti Rahayu', $deathNotif['message']);
        $this->assertEquals('Surat keterangan dokter belum dilampirkan', $deathNotif['admin_note']);

        // Check profile update notif
        $profileNotif = $notifs->firstWhere('type', 'profile');
        $this->assertNotNull($profileNotif);
        $this->assertStringContainsString('Disetujui', $profileNotif['title']);
    }

    public function test_headbar_displays_notification_bell_when_warga_logged_in(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'status' => 'active',
            'nik' => '3404010101010001',
            'family_card_no' => '3404010101010002',
            'name' => 'Budi Santoso',
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($warga)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('id="warga-notif-btn"', false);
        $response->assertSee('id="warga-notif-dropdown"', false);
        $response->assertSee('fa-solid fa-bell', false);
        $response->assertSee('Pengaduan');
        $response->assertSee('Notifikasi Masuk');
    }

    public function test_headbar_does_not_display_warga_notification_bell_for_guest(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertDontSee('id="warga-notif-btn"', false);
    }
}
