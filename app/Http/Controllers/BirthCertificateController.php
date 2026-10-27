<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\AdminNotificationService;

class BirthCertificateController extends Controller
{
    public function create(Request $request)
    {
        $padukuhanList = [
            'Babadan', 'Beneran', 'Donolayan', 'Gadingan', 'Jamblangan',
            'Kadilobo', 'Kadisobo', 'Karanggeneng', 'Kemiri', 'Ngepring',
            'Potrowangsan', 'Surodadi', 'Tawangrejo', 'Turgo', 'Watuadeg', 'Somokaton'
        ];

        // Standar Nasional Indonesia Kategori Pekerjaan (Dukcapil / BPS)
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
            'Nelayan / Perikanan',
            'Notaris',
            'Pegawai Negeri Sipil (PNS)',
            'Pelajar / Mahasiswa',
            'Pelaut',
            'Pembantu Rumah Tangga',
            'Penata Busana',
            'Penata Rias',
            'Peneliti',
            'Pengacara',
            'Pensiunan',
            'Perangkat Desa',
            'Perdagangan / Pedagang',
            'Perawat',
            'Petani / Pekebun',
            'Peternak',
            'Pilot',
            'Psikiater / Psikolog',
            'Seniman',
            'Sopir',
            'Tentara Nasional Indonesia (TNI)',
            'Tokoh Agama / Rohaniwan',
            'Transportasi',
            'Tukang Batu',
            'Tukang Cukur / Penata Rambut',
            'Tukang Jahit',
            'Tukang Kayu',
            'Tukang Las / Pandai Besi',
            'Tukang Listrik',
            'Wartawan',
            'Wiraswasta',
        ];
        sort($pekerjaanList);

        $user = Auth::user();

        $revisionNo = $request->query('revision');
        $revisionBirth = null;
        $revisionData = null;

        if ($revisionNo) {
            $revisionBirth = BirthCertificate::where('registration_no', $revisionNo)->first();
            if ($revisionBirth && $user && $user->isWarga()) {
                if ($revisionBirth->family_card_no && $revisionBirth->family_card_no !== $user->family_card_no && $revisionBirth->user_id !== $user->id) {
                    abort(403, 'Akses Ditolak: Anda tidak berhak merevisi permohonan dari Kartu Keluarga lain.');
                }
            }
        }

        $defaultFather = null;
        $defaultMother = null;

        if ($user) {
            $candidates = collect();

            // 1. Data akun pemohon (User yang sedang login)
            $candidates->push((object)[
                'name' => $user->name,
                'nik' => $user->nik,
                'birth_date' => $user->birth_date ? ($user->birth_date instanceof \DateTimeInterface ? $user->birth_date->format('Y-m-d') : substr((string)$user->birth_date, 0, 10)) : null,
                'gender' => $user->gender,
                'family_relationship' => $user->family_relationship,
            ]);

            // 2. Data anggota keluarga dari tabel family_members
            $familyMembersQuery = \App\Models\FamilyMember::where('user_id', $user->id);
            if (!empty($user->family_card_no)) {
                $familyMembersQuery->orWhere('family_card_no', $user->family_card_no);
            }
            foreach ($familyMembersQuery->get() as $fm) {
                $candidates->push((object)[
                    'name' => $fm->name,
                    'nik' => $fm->nik,
                    'birth_date' => $fm->birth_date ? ($fm->birth_date instanceof \DateTimeInterface ? $fm->birth_date->format('Y-m-d') : substr((string)$fm->birth_date, 0, 10)) : null,
                    'gender' => $fm->gender,
                    'family_relationship' => $fm->family_relationship,
                ]);
            }

            // 3. Data warga lain yang memiliki Nomor KK sama (jika ada)
            if (!empty($user->family_card_no)) {
                $otherUsers = \App\Models\User::where('family_card_no', $user->family_card_no)
                    ->where('id', '!=', $user->id)
                    ->where('role', 'warga')
                    ->get();
                foreach ($otherUsers as $ou) {
                    $candidates->push((object)[
                        'name' => $ou->name,
                        'nik' => $ou->nik,
                        'birth_date' => $ou->birth_date ? ($ou->birth_date instanceof \DateTimeInterface ? $ou->birth_date->format('Y-m-d') : substr((string)$ou->birth_date, 0, 10)) : null,
                        'gender' => $ou->gender,
                        'family_relationship' => $ou->family_relationship,
                    ]);
                }
            }

            // Unikkan berdasarkan NIK agar tidak ada duplikasi data
            $candidates = $candidates->unique(function ($item) {
                return !empty($item->nik) ? $item->nik : spl_object_hash($item);
            })->values();

            // Logika Auto-Fill Data Ayah:
            // hubungan_dalam_kk = "Kepala Keluarga" AND jenis_kelamin = "Laki-laki"
            $fatherCandidate = $candidates->first(function ($item) {
                $rel = strtolower(trim($item->family_relationship ?? ''));
                $gender = strtoupper(trim($item->gender ?? ''));
                $isKepalaKeluarga = ($rel === 'kepala keluarga');
                $isLakiLaki = in_array($gender, ['L', 'LAKI-LAKI']);

                return $isKepalaKeluarga && $isLakiLaki;
            });

            if ($fatherCandidate) {
                $defaultFather = [
                    'name' => $fatherCandidate->name,
                    'nik' => $fatherCandidate->nik,
                    'birth_date' => $fatherCandidate->birth_date,
                    'address' => $user->address ?? '',
                    'rt' => $user->rt ?? '',
                    'rw' => $user->rw ?? '',
                    'village' => 'Purwobinangun',
                    'district' => 'Pakem',
                    'city' => 'Sleman',
                    'province' => 'D.I. Yogyakarta',
                    'citizenship' => 'WNI',
                    'nationality' => 'INDONESIA',
                ];
            }

            // Logika Auto-Fill Data Ibu:
            // hubungan_dalam_kk = "Istri" AND jenis_kelamin = "Perempuan"
            $motherCandidate = $candidates->first(function ($item) {
                $rel = strtolower(trim($item->family_relationship ?? ''));
                $gender = strtoupper(trim($item->gender ?? ''));
                $isIstri = ($rel === 'istri');
                $isPerempuan = in_array($gender, ['P', 'PEREMPUAN']);

                return $isIstri && $isPerempuan;
            });

            if ($motherCandidate) {
                $defaultMother = [
                    'name' => $motherCandidate->name,
                    'nik' => $motherCandidate->nik,
                    'birth_date' => $motherCandidate->birth_date,
                    'address' => $user->address ?? '',
                    'rt' => $user->rt ?? '',
                    'rw' => $user->rw ?? '',
                    'village' => 'Purwobinangun',
                    'district' => 'Pakem',
                    'city' => 'Sleman',
                    'province' => 'D.I. Yogyakarta',
                    'citizenship' => 'WNI',
                    'nationality' => 'INDONESIA',
                ];
            }
        }

        $defaultApplicant = [
            'name' => $user->name ?? '',
            'nik' => $user->nik ?? '',
            'birth_date' => $user && $user->birth_date ? ($user->birth_date instanceof \DateTimeInterface ? $user->birth_date->format('Y-m-d') : substr((string)$user->birth_date, 0, 10)) : '',
            'gender' => ($user && in_array(strtoupper(trim($user->gender ?? '')), ['P', 'PEREMPUAN'])) ? 'P' : 'L',
            'job' => '',
            'address' => $user->address ?? '',
            'rt' => $user->rt ?? '01',
            'rw' => $user->rw ?? '01',
            'village' => 'Purwobinangun',
            'district' => 'Pakem',
            'city' => 'Sleman',
            'province' => 'D.I. Yogyakarta',
            'phone' => $user->phone ?? '',
            'relation' => ($user && in_array(strtoupper(trim($user->gender ?? '')), ['P', 'PEREMPUAN'])) ? 'Ibu Kandung' : 'Ayah Kandung',
        ];

        if ($revisionBirth) {
            $formatDate = function ($date) {
                if (!$date) return '';
                if ($date instanceof \DateTimeInterface) return $date->format('Y-m-d');
                return substr((string)$date, 0, 10);
            };

            $defaultFather = [
                'name' => $revisionBirth->father_name ?? '',
                'nik' => $revisionBirth->father_nik ?? '',
                'birth_date' => $formatDate($revisionBirth->father_birth_date),
                'job' => $revisionBirth->father_job ?? '',
                'address' => $revisionBirth->father_address ?? '',
                'rt' => $revisionBirth->father_rt ?? '01',
                'rw' => $revisionBirth->father_rw ?? '01',
                'village' => $revisionBirth->father_village ?? 'Purwobinangun',
                'district' => $revisionBirth->father_district ?? 'Pakem',
                'city' => $revisionBirth->father_city ?? 'Sleman',
                'province' => $revisionBirth->father_province ?? 'D.I. Yogyakarta',
                'citizenship' => $revisionBirth->father_citizenship ?? 'WNI',
                'nationality' => $revisionBirth->father_nationality ?? 'INDONESIA',
            ];

            $defaultMother = [
                'name' => $revisionBirth->mother_name ?? '',
                'nik' => $revisionBirth->mother_nik ?? '',
                'birth_date' => $formatDate($revisionBirth->mother_birth_date),
                'job' => $revisionBirth->mother_job ?? '',
                'address' => $revisionBirth->mother_address ?? '',
                'rt' => $revisionBirth->mother_rt ?? '01',
                'rw' => $revisionBirth->mother_rw ?? '01',
                'village' => $revisionBirth->mother_village ?? 'Purwobinangun',
                'district' => $revisionBirth->mother_district ?? 'Pakem',
                'city' => $revisionBirth->mother_city ?? 'Sleman',
                'province' => $revisionBirth->mother_province ?? 'D.I. Yogyakarta',
                'citizenship' => $revisionBirth->mother_citizenship ?? 'WNI',
                'nationality' => $revisionBirth->mother_nationality ?? 'INDONESIA',
            ];

            $defaultApplicant = [
                'name' => $revisionBirth->applicant_name ?? '',
                'nik' => $revisionBirth->applicant_nik ?? '',
                'birth_date' => $formatDate($revisionBirth->applicant_birth_date),
                'gender' => $revisionBirth->applicant_gender ?? 'L',
                'job' => $revisionBirth->applicant_job ?? '',
                'address' => $revisionBirth->address ?? '',
                'rt' => $revisionBirth->rt ?? '01',
                'rw' => $revisionBirth->rw ?? '01',
                'village' => $revisionBirth->applicant_village ?? 'Purwobinangun',
                'district' => $revisionBirth->applicant_district ?? 'Pakem',
                'city' => $revisionBirth->applicant_city ?? 'Sleman',
                'province' => $revisionBirth->applicant_province ?? 'D.I. Yogyakarta',
                'phone' => $revisionBirth->applicant_phone ?? '',
                'relation' => $revisionBirth->applicant_relation ?? 'Pelapor',
            ];

            $revisionData = [
                'registration_no' => $revisionBirth->registration_no,
                'rejection_note' => $revisionBirth->rejection_note,
                'child_name' => $revisionBirth->child_name,
                'child_nik' => $revisionBirth->child_nik,
                'gender' => $revisionBirth->gender,
                'birth_place_type' => $revisionBirth->birth_place_type,
                'birth_place' => $revisionBirth->birth_place,
                'birth_date' => $formatDate($revisionBirth->birth_date),
                'birth_time' => $revisionBirth->birth_time,
                'birth_type' => $revisionBirth->birth_type,
                'birth_order' => $revisionBirth->birth_order,
                'birth_helper' => $revisionBirth->birth_helper,
                'weight_gram' => $revisionBirth->weight_gram,
                'weight_kg' => $revisionBirth->weight_kg,
                'length_cm' => $revisionBirth->length_cm,

                'father_nik' => $revisionBirth->father_nik,
                'father_name' => $revisionBirth->father_name,
                'father_birth_date' => $formatDate($revisionBirth->father_birth_date),
                'father_age' => $revisionBirth->father_age,
                'father_job' => $revisionBirth->father_job,
                'father_address' => $revisionBirth->father_address,
                'father_rt' => $revisionBirth->father_rt,
                'father_rw' => $revisionBirth->father_rw,
                'father_village' => $revisionBirth->father_village,
                'father_district' => $revisionBirth->father_district,
                'father_city' => $revisionBirth->father_city,
                'father_province' => $revisionBirth->father_province,
                'father_citizenship' => $revisionBirth->father_citizenship,
                'father_nationality' => $revisionBirth->father_nationality,

                'mother_nik' => $revisionBirth->mother_nik,
                'mother_name' => $revisionBirth->mother_name,
                'mother_birth_date' => $formatDate($revisionBirth->mother_birth_date),
                'mother_age' => $revisionBirth->mother_age,
                'mother_job' => $revisionBirth->mother_job,
                'mother_address' => $revisionBirth->mother_address,
                'mother_rt' => $revisionBirth->mother_rt,
                'mother_rw' => $revisionBirth->mother_rw,
                'mother_village' => $revisionBirth->mother_village,
                'mother_district' => $revisionBirth->mother_district,
                'mother_city' => $revisionBirth->mother_city,
                'mother_province' => $revisionBirth->mother_province,
                'mother_citizenship' => $revisionBirth->mother_citizenship,
                'mother_nationality' => $revisionBirth->mother_nationality,

                'applicant_name' => $revisionBirth->applicant_name,
                'applicant_nik' => $revisionBirth->applicant_nik,
                'applicant_birth_date' => $formatDate($revisionBirth->applicant_birth_date),
                'applicant_age' => $revisionBirth->applicant_age,
                'applicant_gender' => $revisionBirth->applicant_gender,
                'applicant_job' => $revisionBirth->applicant_job,
                'address' => $revisionBirth->address,
                'padukuhan' => $revisionBirth->padukuhan,
                'rt' => $revisionBirth->rt,
                'rw' => $revisionBirth->rw,
                'applicant_village' => $revisionBirth->applicant_village,
                'applicant_district' => $revisionBirth->applicant_district,
                'applicant_city' => $revisionBirth->applicant_city,
                'applicant_province' => $revisionBirth->applicant_province,
                'applicant_phone' => $revisionBirth->applicant_phone,
                'applicant_relation' => $revisionBirth->applicant_relation,

                'witness_1_nik' => $revisionBirth->witness_1_nik,
                'witness_1_name' => $revisionBirth->witness_1_name,
                'witness_1_birth_date' => $formatDate($revisionBirth->witness_1_birth_date),
                'witness_1_age' => $revisionBirth->witness_1_age,
                'witness_1_gender' => $revisionBirth->witness_1_gender,
                'witness_1_job' => $revisionBirth->witness_1_job,
                'witness_1_address' => $revisionBirth->witness_1_address,
                'witness_1_rt' => $revisionBirth->witness_1_rt,
                'witness_1_rw' => $revisionBirth->witness_1_rw,
                'witness_1_village' => $revisionBirth->witness_1_village,
                'witness_1_district' => $revisionBirth->witness_1_district,
                'witness_1_city' => $revisionBirth->witness_1_city,
                'witness_1_province' => $revisionBirth->witness_1_province,

                'witness_2_nik' => $revisionBirth->witness_2_nik,
                'witness_2_name' => $revisionBirth->witness_2_name,
                'witness_2_birth_date' => $formatDate($revisionBirth->witness_2_birth_date),
                'witness_2_age' => $revisionBirth->witness_2_age,
                'witness_2_gender' => $revisionBirth->witness_2_gender,
                'witness_2_job' => $revisionBirth->witness_2_job,
                'witness_2_address' => $revisionBirth->witness_2_address,
                'witness_2_rt' => $revisionBirth->witness_2_rt,
                'witness_2_rw' => $revisionBirth->witness_2_rw,
                'witness_2_village' => $revisionBirth->witness_2_village,
                'witness_2_district' => $revisionBirth->witness_2_district,
                'witness_2_city' => $revisionBirth->witness_2_city,
                'witness_2_province' => $revisionBirth->witness_2_province,

                'doc_parents_ktp' => $revisionBirth->doc_parents_ktp ? asset('storage/' . $revisionBirth->doc_parents_ktp) : null,
                'doc_family_card' => $revisionBirth->doc_family_card ? asset('storage/' . $revisionBirth->doc_family_card) : null,
                'doc_birth_cert' => $revisionBirth->doc_birth_cert ? asset('storage/' . $revisionBirth->doc_birth_cert) : null,
                'doc_marriage_cert' => $revisionBirth->doc_marriage_cert ? asset('storage/' . $revisionBirth->doc_marriage_cert) : null,
                'doc_witness_ktp' => $revisionBirth->doc_witness_ktp ? asset('storage/' . $revisionBirth->doc_witness_ktp) : null,
                'applicant_signature' => $revisionBirth->applicant_signature ? (str_starts_with($revisionBirth->applicant_signature, 'data:image') ? $revisionBirth->applicant_signature : asset('storage/' . $revisionBirth->applicant_signature)) : null,
            ];
        }

        return view('birth.create', compact('padukuhanList', 'pekerjaanList', 'user', 'defaultFather', 'defaultMother', 'defaultApplicant', 'revisionBirth', 'revisionData'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $isRevision = $request->filled('revision_registration_no');
        $existingBirth = null;

        if ($isRevision) {
            $existingBirth = BirthCertificate::where('registration_no', $request->input('revision_registration_no'))->first();
            if ($existingBirth && $user && $user->isWarga()) {
                if ($existingBirth->family_card_no && $existingBirth->family_card_no !== $user->family_card_no && $existingBirth->user_id !== $user->id) {
                    abort(403, 'Akses Ditolak: Anda tidak berhak merevisi permohonan ini.');
                }
            }
        }

        $validated = $request->validate([
            // Data Pelapor
            'applicant_name' => 'required|string|max:255',
            'applicant_nik' => 'required|digits:16',
            'applicant_birth_date' => 'nullable|date',
            'applicant_age' => 'nullable|integer|min:1|max:130',
            'applicant_gender' => 'nullable|string|max:20',
            'applicant_job' => 'nullable|string|max:100',
            'address' => 'required|string|max:500',
            'applicant_phone' => 'nullable|string|max:20',
            'applicant_relation' => 'nullable|string|max:50',
            'padukuhan' => 'nullable|string|max:100',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'applicant_village' => 'nullable|string|max:100',
            'applicant_district' => 'nullable|string|max:100',
            'applicant_city' => 'nullable|string|max:100',
            'applicant_province' => 'nullable|string|max:100',

            // Data Orang Tua
            'father_name' => 'required|string|max:255',
            'father_nik' => 'required|digits:16',
            'father_birth_date' => 'required|date',
            'father_age' => 'nullable|integer|min:1|max:130',
            'father_job' => 'required|string|max:100',
            'father_address' => 'nullable|string|max:500',
            'father_rt' => 'nullable|string|max:5',
            'father_rw' => 'nullable|string|max:5',
            'father_village' => 'nullable|string|max:100',
            'father_district' => 'nullable|string|max:100',
            'father_city' => 'nullable|string|max:100',
            'father_province' => 'nullable|string|max:100',
            'father_citizenship' => 'nullable|string|max:20',
            'father_nationality' => 'nullable|string|max:50',

            'mother_name' => 'required|string|max:255',
            'mother_nik' => 'required|digits:16',
            'mother_birth_date' => 'required|date',
            'mother_age' => 'nullable|integer|min:1|max:130',
            'mother_job' => 'required|string|max:100',
            'mother_address' => 'nullable|string|max:500',
            'mother_rt' => 'nullable|string|max:5',
            'mother_rw' => 'nullable|string|max:5',
            'mother_village' => 'nullable|string|max:100',
            'mother_district' => 'nullable|string|max:100',
            'mother_city' => 'nullable|string|max:100',
            'mother_province' => 'nullable|string|max:100',
            'mother_citizenship' => 'nullable|string|max:20',
            'mother_nationality' => 'nullable|string|max:50',
            'marriage_date' => 'nullable|date',

            // Data Saksi 1 & 2
            'witness_1_nik' => 'nullable|digits:16',
            'witness_1_name' => 'nullable|string|max:255',
            'witness_1_birth_date' => 'nullable|date',
            'witness_1_age' => 'nullable|integer|min:1|max:130',
            'witness_1_gender' => 'nullable|in:L,P',
            'witness_1_job' => 'nullable|string|max:100',
            'witness_1_address' => 'nullable|string|max:500',
            'witness_1_rt' => 'nullable|string|max:5',
            'witness_1_rw' => 'nullable|string|max:5',
            'witness_1_village' => 'nullable|string|max:100',
            'witness_1_district' => 'nullable|string|max:100',
            'witness_1_city' => 'nullable|string|max:100',
            'witness_1_province' => 'nullable|string|max:100',

            'witness_2_nik' => 'nullable|digits:16',
            'witness_2_name' => 'nullable|string|max:255',
            'witness_2_birth_date' => 'nullable|date',
            'witness_2_age' => 'nullable|integer|min:1|max:130',
            'witness_2_gender' => 'nullable|in:L,P',
            'witness_2_job' => 'nullable|string|max:100',
            'witness_2_address' => 'nullable|string|max:500',
            'witness_2_rt' => 'nullable|string|max:5',
            'witness_2_rw' => 'nullable|string|max:5',
            'witness_2_village' => 'nullable|string|max:100',
            'witness_2_district' => 'nullable|string|max:100',
            'witness_2_city' => 'nullable|string|max:100',
            'witness_2_province' => 'nullable|string|max:100',

            // Dokumen Unggahan
            'doc_parents_ktp' => ($isRevision && $existingBirth && $existingBirth->doc_parents_ktp) ? 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072' : 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_family_card' => ($isRevision && $existingBirth && $existingBirth->doc_family_card) ? 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072' : 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_birth_cert' => ($isRevision && $existingBirth && $existingBirth->doc_birth_cert) ? 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072' : 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_marriage_cert' => ($isRevision && $existingBirth && $existingBirth->doc_marriage_cert) ? 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072' : 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_witness_ktp' => ($isRevision && $existingBirth && $existingBirth->doc_witness_ktp) ? 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072' : 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'applicant_signature' => 'nullable|string',

            // Biodata Bayi
            'child_name' => 'required|string|max:255',
            'child_nik' => 'nullable|digits:16',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string|max:150',
            'birth_date' => 'required|date',
            'birth_day' => 'nullable|string|max:20',
            'birth_time' => 'nullable|string|max:50',
            'birth_order' => 'required|integer|min:1|max:30',
            'birth_helper' => 'nullable|string|max:100',
            'birth_helper_other' => 'nullable|string|max:100',
            'weight_gram' => 'nullable|numeric|min:100|max:10000',
            'weight_kg' => 'nullable|numeric|min:0.1|max:20',
            'length_cm' => 'nullable|numeric|min:10|max:150',
            'birth_type' => 'required|string|max:100',
            'birth_place_type' => 'required|string|max:100',
            'birth_place_other' => 'nullable|string|max:150',
            'birth_type_custom' => 'nullable|string|max:50',
        ], [
            'required' => ':attribute wajib diisi.',
            'digits' => ':attribute harus berisi tepat :digits digit angka.',
            'mimes' => ':attribute harus berformat JPG, PNG, atau PDF.',
            'max' => ':attribute ukuran file maksimal 3MB.',
            'in' => 'Pilihan :attribute tidak valid.',
        ], [
            'applicant_name' => 'Nama Lengkap Pelapor',
            'applicant_nik' => 'NIK Pelapor',
            'applicant_birth_date' => 'Tanggal Lahir Pelapor',
            'applicant_age' => 'Umur Pelapor',
            'applicant_gender' => 'Jenis Kelamin Pelapor',
            'applicant_job' => 'Pekerjaan Pelapor',
            'address' => 'Alamat Pelapor',
            'applicant_phone' => 'No. HP / WhatsApp Pelapor',
            'father_name' => 'Nama Lengkap Ayah',
            'father_nik' => 'NIK Ayah',
            'father_birth_date' => 'Tanggal Lahir Ayah',
            'father_job' => 'Pekerjaan Ayah',
            'mother_name' => 'Nama Lengkap Ibu',
            'mother_nik' => 'NIK Ibu',
            'mother_birth_date' => 'Tanggal Lahir Ibu',
            'mother_job' => 'Pekerjaan Ibu',
            'doc_parents_ktp' => 'Dokumen KTP',
            'doc_family_card' => 'Dokumen Kartu Keluarga',
            'doc_birth_cert' => 'Surat Kelahiran RS/Bidan',
            'doc_marriage_cert' => 'Buku Nikah / Akta Perkawinan',
            'doc_witness_ktp' => 'Surat Pengantar RT/RW',
            'child_name' => 'Nama Lengkap Anak',
            'child_nik' => 'NIK Anak',
            'gender' => 'Jenis Kelamin',
            'birth_place' => 'Tempat Kelahiran',
            'birth_date' => 'Tanggal Lahir',
            'birth_day' => 'Hari Lahir',
            'birth_time' => 'Jam Kelahiran',
            'birth_order' => 'Kelahiran Anak ke-',
            'birth_type' => 'Jenis Kelahiran',
            'birth_helper' => 'Penolong Kelahiran',
            'weight_gram' => 'Berat Bayi',
            'length_cm' => 'Panjang Bayi',
            'witness_1_nik' => 'NIK Saksi 1',
            'witness_1_name' => 'Nama Lengkap Saksi 1',
            'witness_1_birth_date' => 'Tanggal Lahir Saksi 1',
            'witness_1_age' => 'Umur Saksi 1',
            'witness_1_gender' => 'Jenis Kelamin Saksi 1',
            'witness_1_job' => 'Pekerjaan Saksi 1',
            'witness_1_address' => 'Alamat Saksi 1',
            'witness_1_rt' => 'RT Saksi 1',
            'witness_1_rw' => 'RW Saksi 1',
            'witness_2_nik' => 'NIK Saksi 2',
            'witness_2_name' => 'Nama Lengkap Saksi 2',
            'witness_2_birth_date' => 'Tanggal Lahir Saksi 2',
            'witness_2_age' => 'Umur Saksi 2',
            'witness_2_gender' => 'Jenis Kelamin Saksi 2',
            'witness_2_job' => 'Pekerjaan Saksi 2',
            'witness_2_address' => 'Alamat Saksi 2',
            'witness_2_rt' => 'RT Saksi 2',
            'witness_2_rw' => 'RW Saksi 2',
            'birth_place_type' => 'Tempat Dilahirkan',
        ]);

        $data = $validated;
        if ($isRevision && $existingBirth) {
            $data['registration_no'] = $existingBirth->registration_no;
            $data['status'] = 'pending';
        } else {
            $data['registration_no'] = BirthCertificate::generateRegistrationNo();
            $data['status'] = 'pending';
        }

        if (empty($data['applicant_age']) && !empty($data['applicant_birth_date'])) {
            $data['applicant_age'] = \Carbon\Carbon::parse($data['applicant_birth_date'])->age;
        }

        if (empty($data['applicant_phone'])) {
            $data['applicant_phone'] = $user ? ($user->phone ?? '-') : '-';
        }
        if (empty($data['applicant_relation'])) {
            $data['applicant_relation'] = 'Pelapor';
        }
        if (empty($data['padukuhan'])) {
            $data['padukuhan'] = $user ? ($user->sub_village ?? 'Purwobinangun') : 'Purwobinangun';
        }

        // Bind data KK dan Akun Warga yang login
        $data['user_id'] = $user ? $user->id : null;
        $data['family_card_no'] = $user ? $user->family_card_no : ($request->input('family_card_no') ?? null);

        // Jika Jenis Kelahiran kustom diisi
        if ($request->filled('birth_type_custom') && $request->input('birth_type') === 'Input Jumlah') {
            $data['birth_type'] = 'Kembar ' . $request->input('birth_type_custom');
        }

        // Tempat Dilahirkan
        if ($request->filled('birth_place_other') && $request->input('birth_place_type') === 'Lainnya') {
            $data['birth_place_type'] = $request->input('birth_place_other');
        }

        // Penolong Kelahiran
        if ($request->filled('birth_helper_other') && $request->input('birth_helper') === 'Lainnya') {
            $data['birth_helper'] = $request->input('birth_helper_other');
        } elseif ($request->filled('birth_helper')) {
            $data['birth_helper'] = $request->input('birth_helper');
        } else {
            $data['birth_helper'] = $request->input('birth_place_type') ?: 'Bidan';
        }

        // Sinkronisasi Berat Bayi (Gram & Kg)
        if ($request->filled('weight_gram')) {
            $data['weight_gram'] = (int) $request->input('weight_gram');
            $data['weight_kg'] = round($data['weight_gram'] / 1000, 2);
        } elseif ($request->filled('weight_kg')) {
            $data['weight_kg'] = (float) $request->input('weight_kg');
            $data['weight_gram'] = (int) round($data['weight_kg'] * 1000);
        }

        // Otomatisasi Hari Lahir jika belum terisi
        if (empty($data['birth_day']) && !empty($data['birth_date'])) {
            $days = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];
            $dayNameEn = date('l', strtotime($data['birth_date']));
            $data['birth_day'] = $days[$dayNameEn] ?? null;
        }

        // Otomatisasi Umur Orang Tua jika belum terisi namun tanggal lahir ada
        if (empty($data['father_age']) && !empty($data['father_birth_date'])) {
            try {
                $data['father_age'] = \Carbon\Carbon::parse($data['father_birth_date'])->age;
            } catch (\Exception $e) {
                // Abaikan jika format error
            }
        }
        if (empty($data['mother_age']) && !empty($data['mother_birth_date'])) {
            try {
                $data['mother_age'] = \Carbon\Carbon::parse($data['mother_birth_date'])->age;
            } catch (\Exception $e) {
                // Abaikan jika format error
            }
        }

        // Otomatisasi Umur Saksi jika belum terisi namun tanggal lahir ada
        if (empty($data['witness_1_age']) && !empty($data['witness_1_birth_date'])) {
            try {
                $data['witness_1_age'] = \Carbon\Carbon::parse($data['witness_1_birth_date'])->age;
            } catch (\Exception $e) {
                // Abaikan jika format error
            }
        }
        if (empty($data['witness_2_age']) && !empty($data['witness_2_birth_date'])) {
            try {
                $data['witness_2_age'] = \Carbon\Carbon::parse($data['witness_2_birth_date'])->age;
            } catch (\Exception $e) {
                // Abaikan jika format error
            }
        }

        // Default Kewarganegaraan & Kebangsaan jika kosong
        $data['father_citizenship'] = $data['father_citizenship'] ?? 'WNI';
        $data['father_nationality'] = $data['father_nationality'] ?? 'INDONESIA';
        $data['mother_citizenship'] = $data['mother_citizenship'] ?? 'WNI';
        $data['mother_nationality'] = $data['mother_nationality'] ?? 'INDONESIA';

        // Simpan File Dokumen
        $fileFields = ['doc_birth_cert', 'doc_family_card', 'doc_marriage_cert', 'doc_parents_ktp', 'doc_witness_ktp'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('uploads/birth_certs', 'public');
                $data[$field] = $path;
            } elseif ($isRevision && $existingBirth && !empty($existingBirth->$field)) {
                $data[$field] = $existingBirth->$field;
            }
        }

        // Simpan Tanda Tangan Digital Pemohon (Base64 PNG atau File)
        if ($request->filled('applicant_signature')) {
            $sigInput = $request->input('applicant_signature');
            if (str_starts_with($sigInput, 'data:image')) {
                try {
                    $imageParts = explode(';base64,', $sigInput);
                    if (count($imageParts) === 2) {
                        $imageTypeAux = explode('image/', $imageParts[0]);
                        $imageType = $imageTypeAux[1] ?? 'png';
                        $imageBase64 = base64_decode($imageParts[1]);
                        $fileName = 'signatures/birth_' . $data['registration_no'] . '_' . time() . '.' . $imageType;
                        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);
                        $data['applicant_signature'] = $fileName;
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Gagal menyimpan file tanda tangan digital: ' . $e->getMessage());
                    $data['applicant_signature'] = $sigInput;
                }
            } elseif ($request->hasFile('applicant_signature')) {
                $data['applicant_signature'] = $request->file('applicant_signature')->store('signatures', 'public');
            }
        } elseif ($isRevision && $existingBirth && !empty($existingBirth->applicant_signature)) {
            $data['applicant_signature'] = $existingBirth->applicant_signature;
        }

        if ($isRevision && $existingBirth) {
            $existingBirth->update($data);
            $birth = $existingBirth;
        } else {
            $birth = BirthCertificate::create($data);
        }

        // Notifikasi email otomatis ke admin/petugas kalurahan
        AdminNotificationService::notifyNewBirthCertificate($birth);

        ActivityLog::record([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? $birth->applicant_name,
            'user_role' => 'warga',
            'action_type' => $isRevision ? 'update' : 'create',
            'module' => 'birth_certificate',
            'title' => $isRevision ? 'Pengiriman Revisi Akte Kelahiran' : 'Pengajuan Akte Kelahiran Baru',
            'description' => $isRevision ? 'Pengiriman revisi berkas permohonan Akte Kelahiran.' : 'Pengajuan permohonan Akte Kelahiran baru.',
            'reference_no' => $birth->registration_no,
            'subject_type' => BirthCertificate::class,
            'subject_id' => $birth->id,
        ]);

        $successMessage = $isRevision
            ? 'Revisi berkas permohonan Akte Kelahiran berhasil dikirimkan!'
            : 'Permohonan Akte Kelahiran berhasil diajukan!';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'registration_no' => $birth->registration_no,
                'birth' => [
                    'registration_no' => $birth->registration_no,
                    'child_name' => $birth->child_name,
                    'applicant_name' => $birth->applicant_name,
                    'applicant_phone' => $birth->applicant_phone,
                    'gender' => $birth->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                    'birth_date' => $birth->birth_date ? $birth->birth_date->translatedFormat('d F Y') : '',
                    'status' => $birth->status,
                    'status_label' => $birth->status_label,
                    'status_badge_class' => $birth->status_badge_class,
                    'created_at' => $birth->created_at->translatedFormat('d F Y, H:i') . ' WIB',
                ],
                'receipt_url' => route('tracking.print_receipt', ['type' => 'birth', 'registrationNo' => $birth->registration_no]),
                'tracking_url' => route('tracking.show', ['type' => 'birth', 'registrationNo' => $birth->registration_no]),
                'list_url' => route('submissions.index'),
            ]);
        }

        return redirect()->route('birth.success', ['registration_no' => $birth->registration_no])
            ->with('success', $successMessage);
    }

    public function success(Request $request)
    {
        $registrationNo = $request->query('registration_no');
        $birth = BirthCertificate::where('registration_no', $registrationNo)->firstOrFail();

        // Otorisasi: Warga hanya boleh melihat sukses dari KK miliknya
        if (Auth::check() && Auth::user()->isWarga()) {
            if ($birth->family_card_no && $birth->family_card_no !== Auth::user()->family_card_no) {
                abort(403, 'Anda tidak memiliki hak akses ke data permohonan ini.');
            }
        }

        return view('birth.success', compact('birth'));
    }

    public function list(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        $type = $request->input('type');
        $user = Auth::user();

        // Default type logic: jika dibuka dari route death.list tanpa parameter type eksplisit, set 'death', selain itu 'all'
        if (!$type) {
            if ($request->routeIs('death.list')) {
                $type = 'death';
            } else {
                $type = 'all';
            }
        }

        // 1. Query Akte Kelahiran (STRICT SCOPING: KK Warga yang Login)
        $birthQuery = BirthCertificate::query();
        if ($user && $user->isWarga()) {
            $birthQuery->where('family_card_no', $user->family_card_no);
        }

        if ($search) {
            $birthQuery->where(function ($q) use ($search) {
                $q->where('registration_no', 'like', "%{$search}%")
                  ->orWhere('applicant_nik', 'like', "%{$search}%")
                  ->orWhere('applicant_name', 'like', "%{$search}%")
                  ->orWhere('child_name', 'like', "%{$search}%")
                  ->orWhere('applicant_phone', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['pending', 'in_process', 'revision', 'rejected', 'ready_for_pickup', 'picked_up', 'verified', 'completed', 'archived'])) {
            if ($status === 'in_process') {
                $birthQuery->whereIn('status', ['in_process', 'verified']);
            } elseif ($status === 'ready_for_pickup') {
                $birthQuery->whereIn('status', ['ready_for_pickup', 'completed']);
            } elseif ($status === 'picked_up') {
                $birthQuery->whereIn('status', ['picked_up', 'archived']);
            } else {
                $birthQuery->where('status', $status);
            }
        }

        // 2. Query Akte Kematian (STRICT SCOPING: KK Warga yang Login)
        $deathQuery = DeathCertificate::query();
        if ($user && $user->isWarga()) {
            $deathQuery->where('family_card_no', $user->family_card_no);
        }

        if ($search) {
            $deathQuery->where(function ($q) use ($search) {
                $q->where('registration_no', 'like', "%{$search}%")
                  ->orWhere('applicant_nik', 'like', "%{$search}%")
                  ->orWhere('applicant_name', 'like', "%{$search}%")
                  ->orWhere('deceased_name', 'like', "%{$search}%")
                  ->orWhere('deceased_nik', 'like', "%{$search}%")
                  ->orWhere('applicant_phone', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['pending', 'in_process', 'revision', 'rejected', 'ready_for_pickup', 'picked_up', 'verified', 'completed', 'archived'])) {
            if ($status === 'in_process') {
                $deathQuery->whereIn('status', ['in_process', 'verified']);
            } elseif ($status === 'ready_for_pickup') {
                $deathQuery->whereIn('status', ['ready_for_pickup', 'completed']);
            } elseif ($status === 'picked_up') {
                $deathQuery->whereIn('status', ['picked_up', 'archived']);
            } else {
                $deathQuery->where('status', $status);
            }
        }

        // 3. Pagination & Hasil Sesuai Filter Type
        $perPage = 10;
        if ($type === 'birth') {
            $submissions = $birthQuery->latest()->paginate($perPage)->withQueryString();
        } elseif ($type === 'death') {
            $submissions = $deathQuery->latest()->paginate($perPage)->withQueryString();
        } else {
            $births = $birthQuery->latest()->get();
            $deaths = $deathQuery->latest()->get();
            $merged = $births->concat($deaths)->sortByDesc('created_at')->values();

            $page = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $merged->slice(($page - 1) * $perPage, $perPage)->values();

            $submissions = new LengthAwarePaginator(
                $currentItems,
                $merged->count(),
                $perPage,
                $page,
                ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
            );
        }

        // 4. Statistik Dihitung Khusus Berdasarkan KK Warga yang Login
        $baseBirthStats = BirthCertificate::query();
        $baseDeathStats = DeathCertificate::query();
        if ($user && $user->isWarga()) {
            $baseBirthStats->where('family_card_no', $user->family_card_no);
            $baseDeathStats->where('family_card_no', $user->family_card_no);
        }

        $birthCount = (clone $baseBirthStats)->count();
        $deathCount = (clone $baseDeathStats)->count();
        $totalCount = $birthCount + $deathCount;

        $pendingCount = (clone $baseBirthStats)->where('status', 'pending')->count()
                      + (clone $baseDeathStats)->where('status', 'pending')->count();

        $inProcessCount = (clone $baseBirthStats)->whereIn('status', ['in_process', 'verified'])->count()
                        + (clone $baseDeathStats)->whereIn('status', ['in_process', 'verified'])->count();

        $readyCount = (clone $baseBirthStats)->whereIn('status', ['ready_for_pickup', 'completed'])->count()
                    + (clone $baseDeathStats)->whereIn('status', ['ready_for_pickup', 'completed'])->count();

        $pickedUpCount = (clone $baseBirthStats)->whereIn('status', ['picked_up', 'archived'])->count()
                       + (clone $baseDeathStats)->whereIn('status', ['picked_up', 'archived'])->count();

        return view('birth.list', compact(
            'submissions',
            'status',
            'search',
            'type',
            'totalCount',
            'birthCount',
            'deathCount',
            'pendingCount',
            'inProcessCount',
            'readyCount',
            'pickedUpCount',
            'user'
        ));
    }
}
