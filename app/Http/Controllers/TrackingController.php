<?php

namespace App\Http\Controllers;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));
        $result = null;
        $type = null;

        if (!empty($keyword)) {
            // Cek di Akte Kelahiran
            $birth = BirthCertificate::where('registration_no', $keyword)
                ->orWhere('applicant_nik', $keyword)
                ->orWhere('father_nik', $keyword)
                ->orWhere('mother_nik', $keyword)
                ->latest()
                ->first();

            if ($birth) {
                $result = $birth;
                $type = 'birth';
            } else {
                // Cek di Akte Kematian
                $death = DeathCertificate::where('registration_no', $keyword)
                    ->orWhere('deceased_nik', $keyword)
                    ->orWhere('applicant_nik', $keyword)
                    ->latest()
                    ->first();

                if ($death) {
                    $result = $death;
                    $type = 'death';
                }
            }
        }

        return view('tracking.index', compact('keyword', 'result', 'type'));
    }

    public function show(string $type, string $registrationNo)
    {
        if ($type === 'birth') {
            $data = BirthCertificate::where('registration_no', $registrationNo)->firstOrFail();
        } else {
            $data = DeathCertificate::where('registration_no', $registrationNo)->firstOrFail();
        }

        return view('tracking.show', compact('data', 'type'));
    }

    public function printReceipt(string $type, string $registrationNo)
    {
        if ($type === 'birth') {
            $data = BirthCertificate::where('registration_no', $registrationNo)->firstOrFail();
        } else {
            $data = DeathCertificate::where('registration_no', $registrationNo)->firstOrFail();
        }

        return view('tracking.print-receipt', compact('data', 'type'));
    }
}
