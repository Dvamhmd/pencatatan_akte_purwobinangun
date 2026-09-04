<?php

namespace App\Http\Controllers;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\AdminNotificationService;

class BirthCertificateController extends Controller
{
    public function create()
    {
        $padukuhanList = [
            'Babadan', 'Beneran', 'Donolayan', 'Gadingan', 'Jamblangan',
            'Kadilobo', 'Kadisobo', 'Karanggeneng', 'Kemiri', 'Ngepring',
            'Potrowangsan', 'Surodadi', 'Tawangrejo', 'Turgo', 'Watuadeg', 'Somokaton'
        ];

        $user = Auth::user();

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
                ];
            }
        }

        return view('birth.create', compact('padukuhanList', 'user', 'defaultFather', 'defaultMother'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            // Data Pemohon
            'applicant_name' => 'required|string|max:255',
            'applicant_nik' => 'required|digits:16',
            'address' => 'required|string|max:500',
            'applicant_phone' => 'required|string|max:20',
            'applicant_relation' => 'nullable|string|max:50',
            'padukuhan' => 'nullable|string|max:100',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',

            // Data Orang Tua
            'father_name' => 'required|string|max:255',
            'father_nik' => 'required|digits:16',
            'father_birth_date' => 'required|date',
            'father_job' => 'required|string|max:100',
            'mother_name' => 'required|string|max:255',
            'mother_nik' => 'required|digits:16',
            'mother_birth_date' => 'required|date',
            'mother_job' => 'required|string|max:100',

            // Dokumen Unggahan
            'doc_parents_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_family_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_birth_cert' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_marriage_cert' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_witness_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',

            // Biodata Bayi
            'child_name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string|max:150',
            'birth_date' => 'required|date',
            'birth_time' => 'nullable|string|max:50',
            'birth_order' => 'required|integer|min:1|max:30',
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
            'applicant_name' => 'Nama Lengkap Pemohon',
            'applicant_nik' => 'NIK Pemohon',
            'address' => 'Alamat',
            'applicant_phone' => 'No. HP / WhatsApp',
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
            'child_name' => 'Nama Lengkap Anak',
            'gender' => 'Jenis Kelamin',
            'birth_place' => 'Tempat Kelahiran',
            'birth_date' => 'Tanggal Lahir',
            'birth_time' => 'Jam Kelahiran',
            'birth_order' => 'Kelahiran Anak ke-',
            'birth_type' => 'Jenis Kelahiran',
            'birth_place_type' => 'Tempat Dilahirkan',
        ]);

        $data = $validated;
        $data['registration_no'] = BirthCertificate::generateRegistrationNo();
        $data['status'] = 'pending';

        // Bind data KK dan Akun Warga yang login
        $data['user_id'] = $user ? $user->id : null;
        $data['family_card_no'] = $user ? $user->family_card_no : ($request->input('family_card_no') ?? null);

        // Jika Jenis Kelahiran kustom diisi
        if ($request->filled('birth_type_custom') && $request->input('birth_type') === 'Input Jumlah') {
            $data['birth_type'] = 'Kembar ' . $request->input('birth_type_custom');
        }

        // Jika Tempat Dilahirkan Lainnya diisi
        if ($request->filled('birth_place_other') && $request->input('birth_place_type') === 'Lainnya') {
            $data['birth_place_type'] = $request->input('birth_place_other');
            $data['birth_helper'] = $request->input('birth_place_other');
        } else {
            $data['birth_helper'] = $request->input('birth_place_type');
        }

        // Simpan File Dokumen
        $fileFields = ['doc_birth_cert', 'doc_family_card', 'doc_marriage_cert', 'doc_parents_ktp', 'doc_witness_ktp'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('uploads/birth_certs', 'public');
                $data[$field] = $path;
            }
        }

        $birth = BirthCertificate::create($data);

        // Notifikasi email otomatis ke admin/petugas kalurahan
        AdminNotificationService::notifyNewBirthCertificate($birth);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permohonan Akte Kelahiran berhasil diajukan!',
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
            ->with('success', 'Permohonan Akte Kelahiran berhasil diajukan!');
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
