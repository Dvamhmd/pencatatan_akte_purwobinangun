<?php

namespace App\Http\Controllers;

use App\Models\DeathCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeathCertificateController extends Controller
{
    public function create()
    {
        $padukuhanList = [
            'Babadan', 'Beneran', 'Donolayan', 'Gadingan', 'Jamblangan',
            'Kadilobo', 'Kadisobo', 'Karanggeneng', 'Kemiri', 'Ngepring',
            'Potrowangsan', 'Surodadi', 'Tawangrejo', 'Turgo', 'Watuadeg', 'Somokaton'
        ];

        return view('death.create', compact('padukuhanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Data Almarhum
            'deceased_nik' => 'required|digits:16',
            'deceased_name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'religion' => 'required|string|max:50',
            'padukuhan' => 'required|string|max:100',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',

            // Data Kematian
            'death_date' => 'required|date',
            'death_time' => 'nullable|string|max:20',
            'death_place' => 'required|string|max:100',
            'cause_of_death' => 'required|string|max:100',
            'reported_by_title' => 'required|string|max:100',

            // Data Pelapor & Saksi
            'applicant_nik' => 'required|digits:16',
            'applicant_name' => 'required|string|max:255',
            'applicant_phone' => 'required|string|max:20',
            'applicant_relation' => 'required|string|max:50',
            'witness_nik' => 'nullable|digits:16',
            'witness_name' => 'nullable|string|max:255',

            // Upload Berkas
            'doc_death_statement' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_family_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_deceased_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'doc_applicant_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ], [
            'required' => ':attribute wajib diisi.',
            'digits' => ':attribute harus berisi persis :digits digit angka.',
            'mimes' => ':attribute harus berformat JPG, PNG, atau PDF.',
            'max' => ':attribute ukuran file maksimal 3MB.',
        ]);

        $data = $validated;
        $data['registration_no'] = DeathCertificate::generateRegistrationNo();
        $data['status'] = 'pending';

        // Simpan File
        $fileFields = ['doc_death_statement', 'doc_family_card', 'doc_deceased_ktp', 'doc_applicant_ktp'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('uploads/death_certs', 'public');
                $data[$field] = $path;
            }
        }

        $death = DeathCertificate::create($data);

        return redirect()->route('death.success', ['registration_no' => $death->registration_no])
            ->with('success', 'Permohonan Akte Kematian berhasil diajukan!');
    }

    public function success(Request $request)
    {
        $registrationNo = $request->query('registration_no');
        $death = DeathCertificate::where('registration_no', $registrationNo)->firstOrFail();

        return view('death.success', compact('death'));
    }
}
