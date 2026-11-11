<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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

        // Normalisasi status awal dan status baru untuk perbandingan
        $statusMap = [
            'verified' => 'in_process',
            'completed' => 'ready_for_pickup',
            'archived' => 'picked_up',
        ];
        $currentNormalizedStatus = $statusMap[$death->status] ?? $death->status;
        $newNormalizedStatus = $statusMap[$validated['status']] ?? $validated['status'];

        $isStatusChanged = $currentNormalizedStatus !== $newNormalizedStatus;
        $initialNote = trim((string)$death->rejection_note);
        $newNote = trim((string)$validated['rejection_note']);

        if ($isStatusChanged && $initialNote !== '' && $initialNote === $newNote) {
            return back()->withErrors([
                'rejection_note' => 'Status pengajuan telah diubah. Silakan perbarui Catatan Verifikator / Pesan terlebih dahulu sesuai status yang baru.'
            ])->withInput();
        }

        $death->status = $validated['status'];
        $death->is_archived = in_array($validated['status'], ['picked_up', 'archived']);
        $death->rejection_note = $validated['rejection_note'];
        $death->processed_by = Auth::user()->name;
        $death->save();

        ActivityLog::record([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_role' => 'admin',
            'action_type' => in_array($validated['status'], ['picked_up', 'completed', 'ready_for_pickup']) ? 'approve' : ($validated['status'] === 'rejected' ? 'reject' : 'update'),
            'module' => 'death_certificate',
            'title' => 'Pembaruan Status Akte Kematian',
            'description' => 'Pembaruan status permohonan menjadi "' . $death->status_label . '".' . (!empty($validated['rejection_note']) ? ' Catatan: ' . $validated['rejection_note'] : ''),
            'reference_no' => $death->registration_no,
            'subject_type' => DeathCertificate::class,
            'subject_id' => $death->id,
        ]);

        $message = $death->isPickedUp()
            ? 'Status permohonan Akte Kematian berhasil diperbarui menjadi Sudah Diambil dan telah otomatis masuk ke arsip. Pemberitahuan telah ditampilkan di portal notifikasi warga.'
            : 'Status permohonan Akte Kematian berhasil diperbarui menjadi ' . $death->status_label . '. Pemberitahuan telah ditampilkan di portal notifikasi warga.';

        return redirect()->route('admin.death.show', $death)
            ->with('success', $message);
    }

    public function printLetter(DeathCertificate $death)
    {
        return view('admin.death.print-letter', compact('death'));
    }
}
