<?php

namespace Database\Seeders;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
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
        // Admin Kalurahan Purwobinangun
        User::updateOrCreate(
            ['email' => 'admin@purwobinangun.desa.id'],
            [
                'name' => 'Petugas Pelayanan Kalurahan',
                'email' => 'admin@purwobinangun.desa.id',
                'password' => Hash::make('admin123'),
            ]
        );

        // Dummy Data Akte Kelahiran
        BirthCertificate::create([
            'registration_no' => 'AKL-20260901-0001',
            'child_name' => 'Aditya Pratama Nugraha',
            'gender' => 'L',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-08-15',
            'birth_time' => '08:30',
            'birth_type' => 'Tunggal',
            'birth_order' => 1,
            'birth_helper' => 'Bidan',
            'weight_kg' => 3.25,
            'length_cm' => 49.5,
            'father_nik' => '3404051205900001',
            'father_name' => 'Bambang Nugroho',
            'father_birth_date' => '1990-05-12',
            'father_job' => 'Wiraswasta',
            'mother_nik' => '3404055508930002',
            'mother_name' => 'Siti Nurhaliza',
            'mother_birth_date' => '1993-08-15',
            'mother_job' => 'Ibu Rumah Tangga',
            'applicant_nik' => '3404051205900001',
            'applicant_name' => 'Bambang Nugroho',
            'applicant_phone' => '081234567890',
            'applicant_relation' => 'Ayah',
            'padukuhan' => 'Kadilobo',
            'rt' => '02',
            'rw' => '05',
            'status' => 'completed',
            'processed_by' => 'Petugas Pelayanan Kalurahan',
        ]);

        BirthCertificate::create([
            'registration_no' => 'AKL-20260901-0002',
            'child_name' => 'Kirana Ayudia Putri',
            'gender' => 'P',
            'birth_place' => 'Sleman',
            'birth_date' => '2026-08-28',
            'birth_time' => '14:15',
            'birth_type' => 'Tunggal',
            'birth_order' => 2,
            'birth_helper' => 'Dokter',
            'weight_kg' => 3.10,
            'length_cm' => 48.0,
            'father_nik' => '3404051010880003',
            'father_name' => 'Agus Setiawan',
            'father_birth_date' => '1988-10-10',
            'father_job' => 'PNS',
            'mother_nik' => '3404055203920004',
            'mother_name' => 'Dewi Lestari',
            'mother_birth_date' => '1992-03-12',
            'mother_job' => 'Guru',
            'applicant_nik' => '3404051010880003',
            'applicant_name' => 'Agus Setiawan',
            'applicant_phone' => '081398765432',
            'applicant_relation' => 'Ayah',
            'padukuhan' => 'Babadan',
            'rt' => '01',
            'rw' => '02',
            'status' => 'in_process',
            'processed_by' => 'Petugas Pelayanan Kalurahan',
        ]);

        BirthCertificate::create([
            'registration_no' => 'AKL-20260901-0003',
            'child_name' => 'Muhammad Rizqi Ramadhan',
            'gender' => 'L',
            'birth_place' => 'Yogyakarta',
            'birth_date' => '2026-08-30',
            'birth_time' => '21:00',
            'birth_type' => 'Tunggal',
            'birth_order' => 1,
            'birth_helper' => 'Bidan',
            'weight_kg' => 3.40,
            'length_cm' => 50.0,
            'father_nik' => '3404051506950005',
            'father_name' => 'Rian Hidayat',
            'father_birth_date' => '1995-06-15',
            'father_job' => 'Karyawan Swasta',
            'mother_nik' => '3404054807960006',
            'mother_name' => 'Fitri Anggraini',
            'mother_birth_date' => '1996-07-08',
            'mother_job' => 'Karyawan Swasta',
            'applicant_nik' => '3404054807960006',
            'applicant_name' => 'Fitri Anggraini',
            'applicant_phone' => '082155667788',
            'applicant_relation' => 'Ibu',
            'padukuhan' => 'Surodadi',
            'rt' => '03',
            'rw' => '07',
            'status' => 'pending',
        ]);

        // Dummy Data Akte Kematian
        DeathCertificate::create([
            'registration_no' => 'AKM-20260901-0001',
            'deceased_nik' => '3404050101450001',
            'deceased_name' => 'Mbah Marto Suwito',
            'gender' => 'L',
            'birth_date' => '1945-01-01',
            'religion' => 'Islam',
            'padukuhan' => 'Donolayan',
            'rt' => '02',
            'rw' => '03',
            'death_date' => '2026-08-20',
            'death_time' => '05:45',
            'death_place' => 'Rumah',
            'cause_of_death' => 'Sakit / Usia Tua',
            'reported_by_title' => 'Kepala Dusun / RT',
            'applicant_nik' => '3404051109720002',
            'applicant_name' => 'Suharto',
            'applicant_phone' => '081223344556',
            'applicant_relation' => 'Anak',
            'witness_nik' => '3404051203750003',
            'witness_name' => 'Wagimin',
            'status' => 'completed',
            'processed_by' => 'Petugas Pelayanan Kalurahan',
        ]);

        DeathCertificate::create([
            'registration_no' => 'AKM-20260901-0002',
            'deceased_nik' => '3404054404500004',
            'deceased_name' => 'Ibu Sumirah',
            'gender' => 'P',
            'birth_date' => '1950-04-04',
            'religion' => 'Islam',
            'padukuhan' => 'Gadingan',
            'rt' => '01',
            'rw' => '01',
            'death_date' => '2026-08-29',
            'death_time' => '11:20',
            'death_place' => 'RSUD Sleman',
            'cause_of_death' => 'Sakit',
            'reported_by_title' => 'Dokter',
            'applicant_nik' => '3404052208800005',
            'applicant_name' => 'Gunawan Wibisono',
            'applicant_phone' => '087811223344',
            'applicant_relation' => 'Anak',
            'witness_nik' => '3404051909820006',
            'witness_name' => 'Hartanto',
            'status' => 'verified',
            'processed_by' => 'Petugas Pelayanan Kalurahan',
        ]);
    }
}
