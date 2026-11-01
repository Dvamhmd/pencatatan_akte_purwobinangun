<?php

namespace App\Http\Controllers;

use App\Models\DeathCertificate;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\AdminNotificationService;

class DeathCertificateController extends Controller
{
    public function create()
    {
        $padukuhanList = [
            'Babadan', 'Beneran', 'Donolayan', 'Gadingan', 'Jamblangan',
            'Kadilobo', 'Kadisobo', 'Karanggeneng', 'Kemiri', 'Ngepring',
            'Potrowangsan', 'Surodadi', 'Tawangrejo', 'Turgo', 'Watuadeg', 'Somokaton'
        ];

        $pekerjaanList = [
            'Akuntan',
            'Anggota Legislatif (DPR / DPD / DPRD)',
            'Apoteker',
            'Arsitek',
            'Belum / Tidak Bekerja',
            'Bidan',
            'Buruh Harian Lepas',
            'Buruh Nelayan / Perikanan',
            'Buruh Peternakan',
            'Buruh Tani / Perkebunan',
            'Dokter',
            'Dosen',
            'Guru',
            'Industri',
            'Juru Masak',
            'Karyawan BUMD',
            'Karyawan BUMN',
            'Karyawan Honorer',
            'Karyawan Swasta',
            'Kepala Desa',
            'Kepolisian RI (POLRI)',
            'Konstruksi',
            'Konsultan',
            'Lainnya',
            'Mekanik',
            'Mengurus Rumah Tangga',
            'Pedagang',
            'Pegawai Negeri Sipil (PNS)',
            'Pelajar / Mahasiswa',
            'Pelaut',
            'Pensiunan',
            'Perawat',
            'Petani / Pekebun',
            'Peternak',
            'Pilot',
            'Sopir',
            'Tentara Nasional Indonesia (TNI)',
            'Tukang Kayu',
            'Tukang Listrik',
            'Wartawan',
            'Wiraswasta',
        ];
        sort($pekerjaanList);

        $user = Auth::user();

        return view('death.create', compact('padukuhanList', 'pekerjaanList', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            // Header KK
            'family_card_no' => 'required|digits:16',
            'head_of_family_name' => 'required|string|max:255',

            // 1. Data Jenazah
            'deceased_nik' => 'required|digits:16',
            'deceased_name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'deceased_birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'religion' => 'required|string|max:50',
            'deceased_job' => 'nullable|string|max:100',
            'padukuhan' => 'required|string|max:100',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'deceased_address' => 'nullable|string|max:255',
            'deceased_child_order' => 'nullable|string|max:50',
            'death_day' => 'required|string|max:20',
            'death_date' => 'required|date',
            'death_time' => 'nullable|string|max:20',
            'death_place' => 'required|string|max:100',
            'cause_of_death' => 'required|string|max:100',
            'death_location_detail' => 'nullable|string|max:255',
            'reported_by_title' => 'required|string|max:100',

            // 2. Data Ibu
            'mother_nik' => 'nullable|digits:16',
            'mother_name' => 'nullable|string|max:255',
            'mother_birth_place' => 'nullable|string|max:100',
            'mother_birth_date' => 'nullable|date',
            'mother_job' => 'nullable|string|max:100',
            'mother_address' => 'nullable|string|max:255',
            'mother_citizenship' => 'nullable|string|max:50',

            // 2. Data Ayah
            'father_nik' => 'nullable|digits:16',
            'father_name' => 'nullable|string|max:255',
            'father_birth_place' => 'nullable|string|max:100',
            'father_birth_date' => 'nullable|date',
            'father_job' => 'nullable|string|max:100',
            'father_address' => 'nullable|string|max:255',
            'father_citizenship' => 'nullable|string|max:50',

            // 3. Data Pelapor
            'applicant_nik' => 'required|digits:16',
            'applicant_name' => 'required|string|max:255',
            'applicant_birth_place' => 'nullable|string|max:100',
            'applicant_birth_date' => 'nullable|date',
            'applicant_age' => 'nullable|integer|min:1|max:150',
            'applicant_job' => 'nullable|string|max:100',
            'applicant_address' => 'nullable|string|max:255',
            'report_date' => 'nullable|date',
            'applicant_phone' => 'required|string|max:20',
            'applicant_relation' => 'required|string|max:50',

            // 4. Data Saksi I & Saksi II
            'witness1_nik' => 'nullable|digits:16',
            'witness1_name' => 'nullable|string|max:255',
            'witness1_age' => 'nullable|integer|min:1|max:150',
            'witness1_address' => 'nullable|string|max:255',

            'witness2_nik' => 'nullable|digits:16',
            'witness2_name' => 'nullable|string|max:255',
            'witness2_age' => 'nullable|integer|min:1|max:150',
            'witness2_address' => 'nullable|string|max:255',

            // 5. Berkas Unggahan Persyaratan
            'doc_death_statement' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_rt_rw_statement' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_family_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_deceased_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_heir_family_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_heir_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ], [
            'required' => ':attribute wajib diisi.',
            'digits' => ':attribute harus berisi persis :digits digit angka.',
            'mimes' => ':attribute harus berformat JPG, PNG, atau PDF.',
            'max' => ':attribute ukuran file maksimal 3MB.',
        ]);

        $data = $validated;
        $data['registration_no'] = DeathCertificate::generateRegistrationNo();
        $data['status'] = 'pending';

        // Bind data KK dan Akun Warga yang login
        $data['user_id'] = $user ? $user->id : null;
        if ($user && empty($data['family_card_no'])) {
            $data['family_card_no'] = $user->family_card_no;
        }

        // Backward compatibility mappings
        $data['witness_nik'] = $data['witness1_nik'] ?? null;
        $data['witness_name'] = $data['witness1_name'] ?? null;
        $data['doc_applicant_ktp'] = null; // will be set below

        // Simpan File
        $fileFields = [
            'doc_death_statement',
            'doc_rt_rw_statement',
            'doc_family_card',
            'doc_deceased_ktp',
            'doc_heir_family_card',
            'doc_heir_ktp',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('uploads/death_certs', 'public');
                $data[$field] = $path;
            }
        }

        $data['doc_applicant_ktp'] = $data['doc_heir_ktp'] ?? null;

        $death = DeathCertificate::create($data);

        // Catat aktivitas log pengajuan akte kematian oleh warga
        \App\Models\ActivityLog::record([
            'actor_id' => $user?->id,
            'actor_name' => $user ? $user->name : $death->applicant_name,
            'actor_role' => 'warga',
            'actor_nik' => $user?->nik ?? $death->applicant_nik,
            'module' => 'akte_kematian',
            'action' => 'pengajuan',
            'description' => "Pengajuan permohonan Akte Kematian baru (Almarhum/ah: {$death->deceased_name}).",
            'subject_type' => DeathCertificate::class,
            'subject_id' => $death->id,
            'reference_no' => $death->registration_no,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'deceased_name' => $death->deceased_name,
                'deceased_nik' => $death->deceased_nik,
                'applicant_name' => $death->applicant_name,
                'death_date' => $death->death_date,
            ],
        ]);

        // Notifikasi email otomatis ke admin/petugas kalurahan
        AdminNotificationService::notifyNewDeathCertificate($death);

        return redirect()->route('death.success', ['registration_no' => $death->registration_no])
            ->with('success', 'Permohonan Akte Kematian berhasil diajukan!');
    }

    public function success(Request $request)
    {
        $registrationNo = $request->query('registration_no');
        $death = DeathCertificate::where('registration_no', $registrationNo)->firstOrFail();

        // Otorisasi: Warga hanya boleh melihat sukses dari KK miliknya
        if (Auth::check() && Auth::user()->isWarga()) {
            if ($death->family_card_no && $death->family_card_no !== Auth::user()->family_card_no) {
                abort(403, 'Anda tidak memiliki hak akses ke data permohonan ini.');
            }
        }

        return view('death.success', compact('death'));
    }
}
