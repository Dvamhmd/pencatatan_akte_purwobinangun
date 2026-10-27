<?php

namespace Database\Seeders;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use App\Models\FamilyMember;
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
        // 1. Admin Kalurahan Purwobinangun
        $admin = User::updateOrCreate(
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

        // 2. Akun Warga KK A (Bambang Nugroho - Aktif)
        $wargaA = User::updateOrCreate(
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
                'email' => 'bambang.nugroho@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => 'Petugas Pelayanan Kalurahan',
            ]
        );

        // 3. Akun Warga KK B (Agus Setiawan - Aktif)
        $wargaB = User::updateOrCreate(
            ['nik' => '3404051010880003'],
            [
                'name' => 'Agus Setiawan',
                'role' => 'warga',
                'nik' => '3404051010880003',
                'family_card_no' => '3404050101880002',
                'birth_place' => 'Sleman',
                'birth_date' => '1988-10-10',
                'gender' => 'L',
                'address' => 'Babadan, Purwobinangun, Pakem, Sleman',
                'rt' => '01',
                'rw' => '02',
                'phone' => '081398765432',
                'email' => 'agus.setiawan@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => 'Petugas Pelayanan Kalurahan',
            ]
        );

        // 4. Akun Warga KK C (Fitri Anggraini - Menunggu Verifikasi)
        $wargaC = User::updateOrCreate(
            ['nik' => '3404054807960006'],
            [
                'name' => 'Fitri Anggraini',
                'role' => 'warga',
                'nik' => '3404054807960006',
                'family_card_no' => '3404050101960003',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '1996-07-08',
                'gender' => 'P',
                'address' => 'Surodadi, Purwobinangun, Pakem, Sleman',
                'rt' => '03',
                'rw' => '07',
                'phone' => '082155667788',
                'email' => 'fitri.anggraini@example.com',
                'password' => Hash::make('password123'),
                'status' => 'pending',
            ]
        );

        // 5. Akun Warga KK D (Gunawan Wibisono - Ditolak)
        $wargaD = User::updateOrCreate(
            ['nik' => '3404052208800005'],
            [
                'name' => 'Gunawan Wibisono',
                'role' => 'warga',
                'nik' => '3404052208800005',
                'family_card_no' => '3404050101800004',
                'birth_place' => 'Sleman',
                'birth_date' => '1980-08-22',
                'gender' => 'L',
                'address' => 'Gadingan, Purwobinangun, Pakem, Sleman',
                'rt' => '01',
                'rw' => '01',
                'phone' => '087811223344',
                'email' => 'gunawan.wibisono@example.com',
                'password' => Hash::make('password123'),
                'status' => 'rejected',
                'rejection_reason' => 'Nomor KK tidak sesuai dengan data kependudukan Kalurahan. Silakan periksa kembali dan upload data yang valid.',
                'verified_at' => now(),
                'verified_by' => 'Petugas Pelayanan Kalurahan',
            ]
        );

        // 6. Akun Warga KK E (Eko Prasetyo - Aktif)
        $wargaE = User::updateOrCreate(
            ['nik' => '3404051402910001'],
            [
                'name' => 'Eko Prasetyo',
                'role' => 'warga',
                'nik' => '3404051402910001',
                'family_card_no' => '3404050101910005',
                'family_relationship' => 'Kepala Keluarga',
                'birth_place' => 'Sleman',
                'birth_date' => '1991-02-14',
                'gender' => 'L',
                'address' => 'Turgo, Purwobinangun, Pakem, Sleman',
                'rt' => '01',
                'rw' => '01',
                'phone' => '081223344551',
                'email' => 'eko.prasetyo@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => 'Petugas Pelayanan Kalurahan',
            ]
        );

        FamilyMember::updateOrCreate(
            ['user_id' => $wargaE->id, 'nik' => '3404055208930005'],
            [
                'family_card_no' => $wargaE->family_card_no,
                'name' => 'Ratna Sari',
                'birth_place' => 'Sleman',
                'birth_date' => '1993-08-12',
                'gender' => 'P',
                'family_relationship' => 'Istri',
            ]
        );

        FamilyMember::updateOrCreate(
            ['user_id' => $wargaE->id, 'nik' => '3404051004180006'],
            [
                'family_card_no' => $wargaE->family_card_no,
                'name' => 'Dimas Aditya Pratama',
                'birth_place' => 'Sleman',
                'birth_date' => '2018-04-10',
                'gender' => 'L',
                'family_relationship' => 'Anak',
            ]
        );

        // 7. Akun Warga KK F (Siti Rahmawati - Aktif)
        $wargaF = User::updateOrCreate(
            ['nik' => '3404055506930002'],
            [
                'name' => 'Siti Rahmawati',
                'role' => 'warga',
                'nik' => '3404055506930002',
                'family_card_no' => '3404050101930006',
                'family_relationship' => 'Kepala Keluarga',
                'birth_place' => 'Sleman',
                'birth_date' => '1993-06-15',
                'gender' => 'P',
                'address' => 'Kemiri, Purwobinangun, Pakem, Sleman',
                'rt' => '02',
                'rw' => '03',
                'phone' => '081223344552',
                'email' => 'siti.rahmawati@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => 'Petugas Pelayanan Kalurahan',
            ]
        );

        FamilyMember::updateOrCreate(
            ['user_id' => $wargaF->id, 'nik' => '3404051103900007'],
            [
                'family_card_no' => $wargaF->family_card_no,
                'name' => 'Joko Susilo',
                'birth_place' => 'Sleman',
                'birth_date' => '1990-03-11',
                'gender' => 'L',
                'family_relationship' => 'Suami',
            ]
        );

        FamilyMember::updateOrCreate(
            ['user_id' => $wargaF->id, 'nik' => '3404054509200008'],
            [
                'family_card_no' => $wargaF->family_card_no,
                'name' => 'Anindita Putri',
                'birth_place' => 'Sleman',
                'birth_date' => '2020-09-05',
                'gender' => 'P',
                'family_relationship' => 'Anak',
            ]
        );

        // 8. Akun Warga KK G (Hendra Kurniawan - Aktif)
        $wargaG = User::updateOrCreate(
            ['nik' => '3404051809860003'],
            [
                'name' => 'Hendra Kurniawan',
                'role' => 'warga',
                'nik' => '3404051809860003',
                'family_card_no' => '3404050101860007',
                'family_relationship' => 'Kepala Keluarga',
                'birth_place' => 'Sleman',
                'birth_date' => '1986-09-18',
                'gender' => 'L',
                'address' => 'Nganggring, Purwobinangun, Pakem, Sleman',
                'rt' => '03',
                'rw' => '04',
                'phone' => '081223344553',
                'email' => 'hendra.kurniawan@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => 'Petugas Pelayanan Kalurahan',
            ]
        );

        FamilyMember::updateOrCreate(
            ['user_id' => $wargaG->id, 'nik' => '3404055001880009'],
            [
                'family_card_no' => $wargaG->family_card_no,
                'name' => 'Maya Indah',
                'birth_place' => 'Sleman',
                'birth_date' => '1988-01-10',
                'gender' => 'P',
                'family_relationship' => 'Istri',
            ]
        );

        FamilyMember::updateOrCreate(
            ['user_id' => $wargaG->id, 'nik' => '3404051207160010'],
            [
                'family_card_no' => $wargaG->family_card_no,
                'name' => 'Bagas Kurnia',
                'birth_place' => 'Sleman',
                'birth_date' => '2016-07-12',
                'gender' => 'L',
                'family_relationship' => 'Anak',
            ]
        );

        // 9. Akun Warga KK H (Dewi Sartika - Aktif)
        $wargaH = User::updateOrCreate(
            ['nik' => '3404056211950004'],
            [
                'name' => 'Dewi Sartika',
                'role' => 'warga',
                'nik' => '3404056211950004',
                'family_card_no' => '3404050101950008',
                'family_relationship' => 'Kepala Keluarga',
                'birth_place' => 'Sleman',
                'birth_date' => '1995-11-22',
                'gender' => 'P',
                'address' => 'Gatep, Purwobinangun, Pakem, Sleman',
                'rt' => '01',
                'rw' => '02',
                'phone' => '081223344554',
                'email' => 'dewi.sartika@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => 'Petugas Pelayanan Kalurahan',
            ]
        );

        FamilyMember::updateOrCreate(
            ['user_id' => $wargaH->id, 'nik' => '3404052504930011'],
            [
                'family_card_no' => $wargaH->family_card_no,
                'name' => 'Fajar Ramadhan',
                'birth_place' => 'Sleman',
                'birth_date' => '1993-04-25',
                'gender' => 'L',
                'family_relationship' => 'Suami',
            ]
        );

        FamilyMember::updateOrCreate(
            ['user_id' => $wargaH->id, 'nik' => '3404056102210012'],
            [
                'family_card_no' => $wargaH->family_card_no,
                'name' => 'Alika Nayla',
                'birth_place' => 'Sleman',
                'birth_date' => '2021-02-21',
                'gender' => 'P',
                'family_relationship' => 'Anak',
            ]
        );

        // Dummy Data Akte Kelahiran KK A (Bambang Nugroho)
        BirthCertificate::create([
            'user_id' => $wargaA->id,
            'registration_no' => 'AKL-20260901-0001',
            'family_card_no' => '3404050101900001',
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
            'address' => 'Kadilobo, Purwobinangun, Pakem, Sleman',
            'padukuhan' => 'Kadilobo',
            'rt' => '02',
            'rw' => '05',
            'status' => 'completed',
            'processed_by' => 'Petugas Pelayanan Kalurahan',
        ]);

        // Dummy Data Akte Kelahiran KK B (Agus Setiawan)
        BirthCertificate::create([
            'user_id' => $wargaB->id,
            'registration_no' => 'AKL-20260901-0002',
            'family_card_no' => '3404050101880002',
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
            'address' => 'Babadan, Purwobinangun, Pakem, Sleman',
            'padukuhan' => 'Babadan',
            'rt' => '01',
            'rw' => '02',
            'status' => 'in_process',
            'processed_by' => 'Petugas Pelayanan Kalurahan',
        ]);

        // Dummy Data Akte Kelahiran KK C (Fitri Anggraini)
        BirthCertificate::create([
            'user_id' => $wargaC->id,
            'registration_no' => 'AKL-20260901-0003',
            'family_card_no' => '3404050101960003',
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
            'address' => 'Surodadi, Purwobinangun, Pakem, Sleman',
            'padukuhan' => 'Surodadi',
            'rt' => '03',
            'rw' => '07',
            'status' => 'pending',
        ]);

        // Dummy Data Akte Kematian KK A (Bambang Nugroho)
        DeathCertificate::create([
            'user_id' => $wargaA->id,
            'registration_no' => 'AKM-20260901-0001',
            'family_card_no' => '3404050101900001',
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
            'applicant_nik' => '3404051205900001',
            'applicant_name' => 'Bambang Nugroho',
            'applicant_phone' => '081234567890',
            'applicant_relation' => 'Cucu',
            'witness_nik' => '3404051203750003',
            'witness_name' => 'Wagimin',
            'status' => 'completed',
            'processed_by' => 'Petugas Pelayanan Kalurahan',
        ]);

        // Dummy Data Akte Kematian KK D (Gunawan Wibisono)
        DeathCertificate::create([
            'user_id' => $wargaD->id,
            'registration_no' => 'AKM-20260901-0002',
            'family_card_no' => '3404050101800004',
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
