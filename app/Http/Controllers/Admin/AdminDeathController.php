<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeathCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDeathController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        // Pengajuan yang sudah diambil / diarsipkan hanya muncul di menu Arsip
        $query = DeathCertificate::where('is_archived', false)->whereNotIn('status', ['picked_up', 'archived']);

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
                  ->orWhere('deceased_name', 'like', "%{$search}%")
                  ->orWhere('deceased_nik', 'like', "%{$search}%")
                  ->orWhere('applicant_name', 'like', "%{$search}%")
                  ->orWhere('applicant_nik', 'like', "%{$search}%");
            });
        }

        $deaths = $query->latest()->paginate(10)->withQueryString();

        return view('admin.death.index', compact('deaths', 'status', 'search'));
    }

    public function show(DeathCertificate $death)
    {
        return view('admin.death.show', compact('death'));
    }

    public function updateStatus(Request $request, DeathCertificate $death)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_process,revision,rejected,ready_for_pickup,picked_up,verified,completed,archived',
            'rejection_note' => 'required|string|min:3',
        ], [
            'rejection_note.required' => 'Catatan verifikator / alasan perubahan status wajib diisi.',
            'rejection_note.min' => 'Catatan verifikator minimal berisi 3 karakter.',
        ]);

        $death->status = $validated['status'];
        $death->is_archived = in_array($validated['status'], ['picked_up', 'archived']);
        $death->rejection_note = $validated['rejection_note'];
        $death->processed_by = Auth::user()->name;
        $death->save();

        $message = $death->isPickedUp()
            ? 'Status permohonan Akte Kematian berhasil diperbarui menjadi Sudah Diambil dan telah otomatis masuk ke arsip.'
            : 'Status permohonan Akte Kematian berhasil diperbarui menjadi ' . $death->status_label . '.';

        return redirect()->route('admin.death.show', $death)
            ->with('success', $message);
    }

    public function printLetter(DeathCertificate $death)
    {
        return view('admin.death.print-letter', compact('death'));
    }
}
