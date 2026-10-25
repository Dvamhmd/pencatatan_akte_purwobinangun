<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Kalurahan Purwobinangun
        User::updateOrCreate(
            ['email' => 'admin@purwobinangun.desa.id'],
            [
                'name' => 'Petugas Pelayanan Kalurahan',
                'email' => 'admin@purwobinangun.desa.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => 'Sistem',
            ]
        );

        // 2. Akun Warga Terverifikasi
        User::updateOrCreate(
            ['nik' => '3404051205900001'],
            [
                'name' => 'Bambang Nugroho',
                'role' => 'warga',
                'nik' => '3404051205900001',
                'family_card_no' => '3404050101900001',
                'birth_place' => 'Sleman',
                'birth_date' => '1990-05-12',
                'gender' => 'L',
                'address' => 'Kadilobo, Purwobinangun, Pakem, Sleman',
                'rt' => '02',
                'rw' => '05',
                'phone' => '081234567890',
                'email' => 'warga@purwobinangun.desa.id',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => 'Petugas Pelayanan Kalurahan',
            ]
        );
    }
}

