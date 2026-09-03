<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBirthController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = BirthCertificate::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('registration_no', 'like', "%{$search}%")
                  ->orWhere('child_name', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('mother_name', 'like', "%{$search}%")
                  ->orWhere('applicant_nik', 'like', "%{$search}%");
            });
        }

        $births = $query->latest()->paginate(10)->withQueryString();

        return view('admin.birth.index', compact('births', 'status', 'search'));
    }

    public function show(BirthCertificate $birth)
    {
        return view('admin.birth.show', compact('birth'));
    }

    public function updateStatus(Request $request, BirthCertificate $birth)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,verified,in_process,completed,rejected,archived',
            'rejection_note' => 'nullable|string',
        ]);

        $birth->status = $validated['status'];
        $birth->rejection_note = $validated['rejection_note'] ?? null;
        $birth->processed_by = Auth::user()->name;
        $birth->save();

        return redirect()->route('admin.birth.show', $birth)
            ->with('success', 'Status permohonan Akte Kelahiran berhasil diperbarui.');
    }

    public function printLetter(BirthCertificate $birth)
    {
        return view('admin.birth.print-letter', compact('birth'));
    }
}
