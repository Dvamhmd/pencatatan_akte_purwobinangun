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

    public function test_warga_can_register_with_uploaded_doc_family_card()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $file = \Illuminate\Http\UploadedFile::fake()->create('kartu_keluarga_terbaru.jpg', 500, 'image/jpeg');

        $response = $this->post('/warga/daftar', [
            'nik' => '3404051122334455',
            'family_card_no' => '3404055544332211',
            'doc_family_card' => $file,
            'name' => 'Warga Berkas KK',
            'birth_place' => 'Sleman',
            'birth_date' => '1995-08-17',
            'gender' => 'L',
            'address' => 'Plosokuning, Minomartani',
            'rt' => '01',
            'rw' => '02',
            'phone' => '081233445566',
            'email' => 'berkaskk@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/warga/login');
        $response->assertSessionHas('registration_success');

        $user = User::where('nik', '3404051122334455')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->doc_family_card);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($user->doc_family_card);
    }

    public function test_warga_can_register_with_family_relationship_and_family_members()
    {
        $response = $this->post('/warga/daftar', [
            'family_card_no' => '3404057777777777',
            'nik' => '3404058888888888',
            'name' => 'Kepala Keluarga Purwobinangun',
            'birth_place' => 'Sleman',
            'birth_date' => '1985-05-15',
            'gender' => 'L',
            'family_relationship' => 'Kepala Keluarga',
            'address' => 'Kadilobo, Purwobinangun, Pakem',
            'rt' => '03',
            'rw' => '05',
            'phone' => '081234567899',
            'email' => 'kk.purwo@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'family_members' => [
                [
                    'family_card_no' => '3404057777777777',
                    'nik' => '3404058888888889',
                    'name' => 'Istri Purwobinangun',
                    'birth_place' => 'Sleman',
                    'birth_date' => '1987-07-20',
                    'gender' => 'P',
                    'family_relationship' => 'Istri',
                ],
                [
                    'family_card_no' => '3404057777777777',
                    'nik' => '3404058888888890',
                    'name' => 'Anak Purwobinangun',
                    'birth_place' => 'Sleman',
                    'birth_date' => '2015-10-10',
                    'gender' => 'L',
                    'family_relationship' => 'Anak',
                ]
            ]
        ]);

        $response->assertRedirect('/warga/login');
        $response->assertSessionHas('registration_success');

        $this->assertDatabaseHas('users', [
            'nik' => '3404058888888888',
            'family_card_no' => '3404057777777777',
            'name' => 'Kepala Keluarga Purwobinangun',
            'family_relationship' => 'Kepala Keluarga',
        ]);

        $this->assertDatabaseHas('family_members', [
            'nik' => '3404058888888889',
            'name' => 'Istri Purwobinangun',
            'family_relationship' => 'Istri',
        ]);

        $this->assertDatabaseHas('family_members', [
            'nik' => '3404058888888890',
            'name' => 'Anak Purwobinangun',
            'family_relationship' => 'Anak',
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

        // Cek halaman pending menampilkan tombol "Tolak Pendaftaran Akun" dan TIDAK menampilkan "Nonaktifkan Akun"
        $responseShowPending = $this->actingAs($admin)->get('/admin/warga/' . $pendingCitizen->id);
        $responseShowPending->assertStatus(200);
        $responseShowPending->assertSee('Tolak Pendaftaran Akun');
        $responseShowPending->assertDontSee('Nonaktifkan Akun');

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

        // Cek halaman aktif menampilkan tombol "Nonaktifkan Akun" dan TIDAK menampilkan "Tolak Pendaftaran Akun"
        $responseShowActive = $this->actingAs($admin)->get('/admin/warga/' . $pendingCitizen->id);
        $responseShowActive->assertStatus(200);
        $responseShowActive->assertSee('Nonaktifkan Akun');
        $responseShowActive->assertDontSee('Tolak Pendaftaran Akun');

        // Admin deactivates/rejects citizen with reason
        $responseReject = $this->actingAs($admin)->post('/admin/warga/' . $pendingCitizen->id . '/verify', [
            'action' => 'reject',
            'rejection_reason' => 'NIK tidak sesuai dengan database KTP Kalurahan.',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $pendingCitizen->id,
            'status' => 'rejected',
            'rejection_reason' => 'NIK tidak sesuai dengan database KTP Kalurahan.',
        ]);

        // Halaman detail akun yang ditolak menampilkan tombol "Arsipkan Akun Warga" dan TIDAK menampilkan tombol "Tolak Pendaftaran Akun" maupun "Nonaktifkan Akun"
        $responseShowRejected = $this->actingAs($admin)->get('/admin/warga/' . $pendingCitizen->id);
        $responseShowRejected->assertStatus(200);
        $responseShowRejected->assertSee('Arsipkan Akun Warga');
        $responseShowRejected->assertDontSee('Tolak Pendaftaran Akun');
        $responseShowRejected->assertDontSee('Nonaktifkan Akun');

        // Admin can archive the citizen
        $responseArchive = $this->actingAs($admin)->post('/admin/warga/' . $pendingCitizen->id . '/verify', [
            'action' => 'archive',
        ]);
        $responseArchive->assertRedirect('/admin/warga');
        $this->assertDatabaseHas('users', [
            'id' => $pendingCitizen->id,
            'status' => 'archived',
        ]);

        // Verifikasi akun warga berstatus 'archived' TIDAK muncul di daftar utama /admin/warga
        $this->flushSession();
        $responseCitizenIndex = $this->actingAs($admin)->get('/admin/warga');
        $responseCitizenIndex->assertStatus(200);
        $responseCitizenIndex->assertDontSee($pendingCitizen->name);
    }

    public function test_admin_can_access_archive_menu_and_restore_items()
    {
        $admin = User::where('email', 'admin@purwobinangun.desa.id')->first();
        $citizen = User::where('role', 'warga')->first();
        $citizen->update(['status' => 'archived', 'rejection_reason' => 'Berkas belum lengkap']);

        // Akses menu arsip tab akun warga
        $responseArchive = $this->actingAs($admin)->get('/admin/arsip?tab=citizens');
        $responseArchive->assertStatus(200);
        $responseArchive->assertSee('Arsip Pengajuan &amp; Akun Nonaktif', false);
        $responseArchive->assertSee($citizen->name);

        // Pulihkan akun warga dari arsip
        $responseRestore = $this->actingAs($admin)->post('/admin/arsip/warga/' . $citizen->id . '/restore');
        $responseRestore->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $citizen->id,
            'status' => 'active',
        ]);

        // Akses menu arsip tab akte kelahiran & kematian
        $responseBirthArchive = $this->actingAs($admin)->get('/admin/arsip?tab=birth');
        $responseBirthArchive->assertStatus(200);

        $responseDeathArchive = $this->actingAs($admin)->get('/admin/arsip?tab=death');
        $responseDeathArchive->assertStatus(200);
    }

    public function test_citizen_verification_triggers_email_and_whatsapp_notifications()
    {
        \Illuminate\Support\Facades\Mail::fake();
        \Illuminate\Support\Facades\Http::fake([
            'api.fonnte.com/*' => \Illuminate\Support\Facades\Http::response(['status' => true, 'target' => '6281234567890'], 200),
        ]);

        $admin = User::where('email', 'admin@purwobinangun.desa.id')->first();
        $pendingCitizen = User::where('status', 'pending')->first();

        // 1. Cek halaman menampilkan pop up konfirmasi notifikasi dan toggle
        $responseShow = $this->actingAs($admin)->get('/admin/warga/' . $pendingCitizen->id);
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Konfirmasi Pengiriman Notifikasi');
        $responseShow->assertSee('Email Warga');
        $responseShow->assertSee('WhatsApp');

        // 2. Admin menyetujui akun dengan notifikasi email & whatsapp aktif
        $responseApprove = $this->actingAs($admin)->post('/admin/warga/' . $pendingCitizen->id . '/verify', [
            'action' => 'approve',
            'send_email' => '1',
            'send_whatsapp' => '1',
        ]);

        $responseApprove->assertRedirect('/admin/warga/' . $pendingCitizen->id);
        $this->assertDatabaseHas('users', [
            'id' => $pendingCitizen->id,
            'status' => 'active',
        ]);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\CitizenAccountStatusNotification::class, function ($mail) use ($pendingCitizen) {
            return $mail->citizen->id === $pendingCitizen->id && $mail->actionType === 'approved';
        });

        // 3. Admin menonaktifkan akun dengan notifikasi email & whatsapp aktif
        $responseDeactivate = $this->actingAs($admin)->post('/admin/warga/' . $pendingCitizen->id . '/verify', [
            'action' => 'reject',
            'rejection_reason' => 'Data NIK ganda atau perlu verifikasi ulang.',
            'send_email' => '1',
            'send_whatsapp' => '1',
        ]);

        $responseDeactivate->assertRedirect('/admin/warga/' . $pendingCitizen->id);
        $this->assertDatabaseHas('users', [
            'id' => $pendingCitizen->id,
            'status' => 'rejected',
            'rejection_reason' => 'Data NIK ganda atau perlu verifikasi ulang.',
        ]);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\CitizenAccountStatusNotification::class, function ($mail) use ($pendingCitizen) {
            return $mail->citizen->id === $pendingCitizen->id && $mail->actionType === 'deactivated';
        });
    }
}
