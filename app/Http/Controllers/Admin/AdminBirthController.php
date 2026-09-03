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

        // Pengajuan yang sudah diambil / diarsipkan hanya muncul di menu Arsip
        $query = BirthCertificate::whereNotIn('status', ['picked_up', 'archived']);

        if ($status && !in_array($status, ['picked_up', 'archived'])) {
            if ($status === 'in_process') {
                $query->whereIn('status', ['in_process', 'verified']);
            } elseif ($status === 'ready_for_pickup') {
                $query->whereIn('status', ['ready_for_pickup', 'completed']);
            } else {
                $query->where('status', $status);
            }
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
            'status' => 'required|in:pending,in_process,revision,rejected,ready_for_pickup,picked_up,verified,completed,archived',
            'rejection_note' => 'required|string|min:3',
        ], [
            'rejection_note.required' => 'Catatan verifikator / alasan perubahan status wajib diisi.',
            'rejection_note.min' => 'Catatan verifikator minimal berisi 3 karakter.',
        ]);

        $birth->status = $validated['status'];
        $birth->rejection_note = $validated['rejection_note'];
        $birth->processed_by = Auth::user()->name;
        $birth->save();

        $message = $birth->isPickedUp()
            ? 'Status permohonan Akte Kelahiran berhasil diperbarui menjadi Sudah diambil dan telah otomatis masuk ke arsip.'
            : 'Status permohonan Akte Kelahiran berhasil diperbarui menjadi ' . $birth->status_label . '.';

        return redirect()->route('admin.birth.show', $birth)
            ->with('success', $message);
    }

    public function printLetter(BirthCertificate $birth)
    {
        return view('admin.birth.print-letter', compact('birth'));
    }
}
