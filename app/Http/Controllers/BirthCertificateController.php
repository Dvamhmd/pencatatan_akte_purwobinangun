<?php

namespace App\Http\Controllers;

use App\Models\BirthCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BirthCertificateController extends Controller
{
    public function create()
    {
        $padukuhanList = [
            'Babadan', 'Beneran', 'Donolayan', 'Gadingan', 'Jamblangan',
            'Kadilobo', 'Kadisobo', 'Karanggeneng', 'Kemiri', 'Ngepring',
            'Potrowangsan', 'Surodadi', 'Tawangrejo', 'Turgo', 'Watuadeg', 'Somokaton'
        ];

        return view('birth.create', compact('padukuhanList'));
    }

    public function store(Request $request)
    {
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

            // Dokumen Unggahan
            'doc_parents_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_family_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_birth_cert' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_marriage_cert' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
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
            'doc_parents_ktp' => 'Dokumen KTP',
            'doc_family_card' => 'Dokumen Kartu Keluarga',
            'doc_birth_cert' => 'Surat Kelahiran RS/Bidan',
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
                'list_url' => route('birth.list'),
            ]);
        }

        return redirect()->route('birth.success', ['registration_no' => $birth->registration_no])
            ->with('success', 'Permohonan Akte Kelahiran berhasil diajukan!');
    }

    public function success(Request $request)
    {
        $registrationNo = $request->query('registration_no');
        $birth = BirthCertificate::where('registration_no', $registrationNo)->firstOrFail();

        return view('birth.success', compact('birth'));
    }

    public function list(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = BirthCertificate::query();

        if ($status && in_array($status, ['pending', 'verified', 'in_process', 'completed', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('registration_no', 'like', "%{$search}%")
                  ->orWhere('applicant_nik', 'like', "%{$search}%")
                  ->orWhere('applicant_name', 'like', "%{$search}%")
                  ->orWhere('child_name', 'like', "%{$search}%")
                  ->orWhere('applicant_phone', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate(10)->withQueryString();
        
        $totalCount = BirthCertificate::count();
        $pendingCount = BirthCertificate::where('status', 'pending')->count();
        $verifiedCount = BirthCertificate::where('status', 'verified')->count();
        $completedCount = BirthCertificate::where('status', 'completed')->count();

        return view('birth.list', compact(
            'submissions',
            'status',
            'search',
            'totalCount',
            'pendingCount',
            'verifiedCount',
            'completedCount'
        ));
    }
}

