<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_activity_logs_page_with_pagination_10()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@purwobinangun.desa.id',
        ]);

        // Buat 15 dummy activity logs
        for ($i = 1; $i <= 15; $i++) {
            ActivityLog::record([
                'actor_id' => $admin->id,
                'actor_name' => $admin->name,
                'actor_role' => 'admin',
                'actor_nik' => '3404010101010001',
                'module' => 'akte_kelahiran',
                'action' => 'setujui',
                'description' => "Admin {$admin->name} menyetujui permohonan ke-$i",
                'reference_no' => "REG-TEST-$i",
                'ip_address' => '127.0.0.1',
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.activity_logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Log Aktivitas Sistem');
        $response->assertSee('Petugas Kelurahan');
        $response->assertSee('Detail');
        $response->assertDontSee('IP & Perangkat');
        $response->assertSee('10 data per halaman');
        $response->assertSee('total riwayat aktivitas');
    }

    public function test_non_admin_cannot_access_activity_logs_page()
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'nik' => '3404010101010002',
        ]);

        $response = $this->actingAs($warga)->get(route('admin.activity_logs.index'));

        // Harus ditolak atau dialihkan karena bukan admin
        $this->assertTrue(in_array($response->status(), [302, 403]));
    }

    public function test_activity_log_filters_work()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        ActivityLog::record([
            'actor_id' => $admin->id,
            'actor_name' => 'Admin Utama',
            'actor_role' => 'admin',
            'module' => 'akte_kelahiran',
            'action' => 'setujui',
            'description' => 'Aktivitas verifikasi akte kelahiran',
            'reference_no' => 'REG-BIRTH-01',
        ]);

        ActivityLog::record([
            'actor_id' => null,
            'actor_name' => 'Warga Budi',
            'actor_role' => 'warga',
            'module' => 'akte_kematian',
            'action' => 'pengajuan',
            'description' => 'Pengajuan akte kematian oleh warga Budi',
            'reference_no' => 'REG-DEATH-01',
        ]);

        // Filter module akte_kematian
        $response = $this->actingAs($admin)->get(route('admin.activity_logs.index', ['module' => 'akte_kematian']));
        $response->assertStatus(200);
        $response->assertSee('REG-DEATH-01');
        $response->assertDontSee('REG-BIRTH-01');

        // Filter search Budi
        $responseSearch = $this->actingAs($admin)->get(route('admin.activity_logs.index', ['search' => 'Budi']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('REG-DEATH-01');
        $responseSearch->assertDontSee('REG-BIRTH-01');
    }
}
