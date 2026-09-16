@extends('layouts.admin')

@section('title', 'Periksa Perubahan Data: ' . $profileRequest->name)
@section('page_title', 'Pemeriksaan & Verifikasi Perubahan Data Warga')

@section('content')
<div class="space-y-6">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <a href="{{ route('admin.profile_requests.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#0b7c89] hover:underline bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pengajuan
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.citizens.show', $user) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 px-3 py-1.5 rounded-xl shadow-2xs transition">
                <i class="fa-solid fa-user"></i> Lihat Profil Akun Warga
            </a>
            <span class="text-xs text-slate-500">Status Pengajuan:</span>
            <span class="inline-block text-xs font-bold px-3 py-1 rounded-full border {{ $profileRequest->status_badge_class }}">
                {{ $profileRequest->status_label }}
            </span>
        </div>
    </div>

    <!-- Grid: Data Komparasi (8 Col) & Panel Verifikasi (4 Col) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri: Komparasi Data (8 Col) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card Komparasi Data Profil Utama -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-code-compare text-[#0b7c89]"></i> 1. Perbandingan Data Akun Warga
                    </h3>
                    <span class="text-[11px] text-slate-400">Pengajuan #{{ $profileRequest->id }}</span>
                </div>

                <div class="p-5 text-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-200 text-[11px] text-slate-500 uppercase font-bold">
                                    <th class="py-2.5 px-3 w-1/4">Bidang Data</th>
                                    <th class="py-2.5 px-3 w-3/8 bg-slate-50 rounded-tl-lg">Data Saat Ini (Tersimpan)</th>
                                    <th class="py-2.5 px-3 w-3/8 bg-teal-50/50 rounded-tr-lg text-[#0b7c89]">Data yang Diajukan (Baru)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $fields = [
                                        'nik' => ['label' => 'NIK', 'is_mono' => true],
                                        'family_card_no' => ['label' => 'Nomor KK', 'is_mono' => true],
                                        'name' => ['label' => 'Nama Lengkap', 'is_bold' => true],
                                        'birth_place' => ['label' => 'Tempat Lahir'],
                                        'birth_date' => [
                                            'label' => 'Tanggal Lahir',
                                            'format' => fn($val) => $val ? ($val instanceof \Carbon\Carbon ? $val->translatedFormat('d F Y') : \Carbon\Carbon::parse($val)->translatedFormat('d F Y')) : '-'
                                        ],
                                        'gender' => [
                                            'label' => 'Jenis Kelamin',
                                            'format' => fn($val) => $val === 'L' ? 'Laki-laki' : ($val === 'P' ? 'Perempuan' : '-')
                                        ],
                                        'family_relationship' => ['label' => 'Posisi dalam KK'],
                                        'phone' => ['label' => 'No. HP / WA', 'is_wa' => true],
                                        'email' => ['label' => 'Alamat Email'],
                                        'address' => ['label' => 'Alamat Lengkap'],
                                        'rt' => ['label' => 'RT'],
                                        'rw' => ['label' => 'RW'],
                                    ];
                                @endphp

                                @foreach($fields as $key => $meta)
                                    @php
                                        $oldVal = $user->{$key};
                                        $newVal = $profileRequest->{$key};
                                        $isChanged = ($oldVal != $newVal);

                                        $displayOld = isset($meta['format']) ? ($meta['format'])($oldVal) : ($oldVal ?: '-');
                                        $displayNew = isset($meta['format']) ? ($meta['format'])($newVal) : ($newVal ?: '-');
                                    @endphp
                                    <tr class="{{ $isChanged ? 'bg-amber-50/40' : '' }} hover:bg-slate-50 transition">
                                        <td class="py-2.5 px-3 font-semibold text-slate-700">
                                            {{ $meta['label'] }}
                                            @if($isChanged)
                                                <span class="ml-1 text-[9px] bg-amber-100 text-amber-800 font-bold px-1.5 py-0.2 rounded border border-amber-300">
                                                    Berubah
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-3 bg-slate-50/50 {{ !empty($meta['is_mono']) ? 'font-mono' : '' }} {{ !empty($meta['is_bold']) ? 'font-bold' : '' }} text-slate-600">
                                            {{ $displayOld }}
                                        </td>
                                        <td class="py-2.5 px-3 bg-teal-50/30 {{ $isChanged ? 'font-bold text-[#095b8c]' : 'text-slate-700' }} {{ !empty($meta['is_mono']) ? 'font-mono' : '' }}">
                                            {{ $displayNew }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card Perbandingan Anggota Keluarga Satu KK -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                @php
                    $proposedMembers = is_array($profileRequest->family_members_data) ? $profileRequest->family_members_data : [];
                    $existingMembers = $user->familyMembers;

                    $deletedMembers = $existingMembers->filter(function($oldMember) use ($proposedMembers) {
                        foreach ($proposedMembers as $newMember) {
                            if (!empty($oldMember->nik) && !empty($newMember['nik']) && $oldMember->nik === $newMember['nik']) {
                                return false;
                            }
                            if (!empty($oldMember->name) && !empty($newMember['name']) && strtolower(trim($oldMember->name)) === strtolower(trim($newMember['name']))) {
                                return false;
                            }
                        }
                        return true;
                    });
                    $deletedCount = $deletedMembers->count();

                    $addedMembers = collect($proposedMembers)->filter(function($newMember) use ($existingMembers) {
                        foreach ($existingMembers as $oldMember) {
                            if (!empty($oldMember->nik) && !empty($newMember['nik']) && $oldMember->nik === $newMember['nik']) {
                                return false;
                            }
                            if (!empty($oldMember->name) && !empty($newMember['name']) && strtolower(trim($oldMember->name)) === strtolower(trim($newMember['name']))) {
                                return false;
                            }
                        }
                        return true;
                    });
                    $addedCount = $addedMembers->count();
                @endphp

                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between gap-2 flex-wrap">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-people-roof text-[#0b7c89]"></i> 2. Susunan Anggota Keluarga yang Diajukan
                    </h3>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if($deletedCount > 0)
                            <span class="text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 px-2 py-0.5 rounded flex items-center gap-1 shadow-2xs">
                                <i class="fa-solid fa-user-minus text-rose-600"></i> {{ $deletedCount }} anggota keluarga dihapus dari KK
                            </span>
                        @endif
                        @if($addedCount > 0)
                            <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded flex items-center gap-1 shadow-2xs">
                                <i class="fa-solid fa-user-plus text-emerald-600"></i> {{ $addedCount }} anggota baru ditambahkan
                            </span>
                        @endif
                        <span class="text-[10px] font-bold bg-teal-50 text-[#0b7c89] border border-teal-200 px-2 py-0.5 rounded">
                            Total: {{ count($proposedMembers) }} Anggota Diajukan
                        </span>
                    </div>
                </div>

                <div class="p-5 space-y-4 text-xs">
                    
                    @if($deletedCount > 0)
                        <!-- Alert Penjelasan Anggota yang Dihapus dari KK -->
                        <div class="p-3.5 bg-rose-50 rounded-xl border border-rose-200 text-xs text-rose-900 flex items-start gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                            <div>
                                <p class="font-bold text-rose-900">{{ $deletedCount }} anggota keluarga dihapus dari KK pada permohonan ini:</p>
                                <div class="mt-1.5 space-y-1">
                                    @foreach($deletedMembers as $delMember)
                                        <div class="text-[11px] text-rose-800 flex items-center gap-1.5">
                                            <i class="fa-solid fa-user-xmark text-rose-500 text-xs"></i>
                                            <span><strong>{{ $delMember->name }}</strong> ({{ $delMember->family_relationship ?: 'Anggota Keluarga' }} &bull; NIK: {{ $delMember->nik ?: '-' }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-[10px] text-rose-600 mt-1.5 italic">*Jika permohonan ini disetujui, anggota keluarga di atas akan dihapus dari data KK aktif sistem.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Data Baru yang Diajukan -->
                    <div>
                        <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-1.5 text-xs text-[#095b8c]">
                            <i class="fa-solid fa-user-check"></i> Daftar Anggota Keluarga Baru (Hasil Pengajuan Warga):
                        </h4>

                        @if(count($proposedMembers) > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border border-slate-200 rounded-lg overflow-hidden">
                                    <thead class="bg-slate-50 text-slate-700 text-[11px] uppercase font-bold border-b border-slate-200">
                                        <tr>
                                            <th class="px-3 py-2.5">No</th>
                                            <th class="px-3 py-2.5">Nama & Hubungan</th>
                                            <th class="px-3 py-2.5">NIK</th>
                                            <th class="px-3 py-2.5">Tempat & Tanggal Lahir</th>
                                            <th class="px-3 py-2.5">Jenis Kelamin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach($proposedMembers as $idx => $m)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-3 py-2.5 font-bold text-slate-500">{{ $idx + 1 }}</td>
                                                <td class="px-3 py-2.5">
                                                    <p class="font-bold text-slate-900">{{ $m['name'] ?? '-' }}</p>
                                                    <span class="text-[10px] text-[#0b7c89] font-semibold bg-teal-50 px-1.5 py-0.2 rounded border border-teal-200">
                                                        {{ $m['family_relationship'] ?? 'Anggota' }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2.5 font-mono text-slate-700">{{ $m['nik'] ?: '-' }}</td>
                                                <td class="px-3 py-2.5 text-slate-600">
                                                    {{ $m['birth_place'] ?? '-' }}, 
                                                    {{ !empty($m['birth_date']) ? \Carbon\Carbon::parse($m['birth_date'])->translatedFormat('d/m/Y') : '-' }}
                                                </td>
                                                <td class="px-3 py-2.5">
                                                    {{ ($m['gender'] ?? '') === 'L' ? 'Laki-laki' : (($m['gender'] ?? '') === 'P' ? 'Perempuan' : '-') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 text-amber-800 text-[11px]">
                                Warga tidak mendaftarkan anggota keluarga lain dalam permohonan ini (hanya akun kepala/pemohon).
                            </div>
                        @endif
                    </div>

                    <!-- Data Anggota Keluarga Saat Ini di Sistem (Untuk Referensi) -->
                    <div class="pt-3 border-t border-slate-100">
                        <h4 class="font-bold text-slate-600 mb-2 flex items-center gap-1.5 text-[11px]">
                            <i class="fa-solid fa-clock-rotate-left"></i> Anggota Keluarga yang Sedang Tercatat di Sistem Saat Ini:
                        </h4>

                        @if($user->familyMembers->count() > 0)
                            <div class="space-y-1.5">
                                @foreach($user->familyMembers as $oldMember)
                                    @php
                                        $isDeleted = $deletedMembers->contains('id', $oldMember->id);
                                    @endphp
                                    <div class="p-2.5 {{ $isDeleted ? 'bg-rose-50/70 border-rose-300' : 'bg-slate-50 border-slate-200' }} rounded-lg border flex items-center justify-between text-[11px]">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-bold {{ $isDeleted ? 'text-rose-950 line-through' : 'text-slate-800' }}">{{ $oldMember->name }}</span>
                                            <span class="text-[9px] bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded">
                                                {{ $oldMember->family_relationship ?: 'Anggota' }}
                                            </span>
                                            @if($isDeleted)
                                                <span class="text-[9px] bg-rose-100 text-rose-800 font-bold px-1.5 py-0.5 rounded border border-rose-300 flex items-center gap-1">
                                                    <i class="fa-solid fa-user-xmark text-xs"></i> Dihapus dari KK
                                                </span>
                                            @else
                                                <span class="text-[9px] bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded border border-emerald-300 flex items-center gap-1">
                                                    <i class="fa-solid fa-check text-xs"></i> Tetap Ada
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-slate-500 font-mono">
                                            NIK: {{ $oldMember->nik ?: '-' }} &bull; {{ $oldMember->gender === 'L' ? 'L' : 'P' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-slate-400 italic text-[11px]">Sebelumnya belum ada anggota keluarga yang tercatat di sistem.</p>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Card Dokumen Kartu Keluarga -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-file-invoice text-[#0b7c89]"></i> 3. Dokumen Kartu Keluarga (KK)
                    </h3>
                </div>

                <div class="p-5 text-xs">
                    @php
                        $targetDoc = $profileRequest->doc_family_card ?: $user->doc_family_card;
                        $hasDocKk = !empty($targetDoc);
                        $isPdfDocKk = $hasDocKk ? \Illuminate\Support\Str::endsWith(strtolower($targetDoc), '.pdf') : false;
                        $docKkUrl = $hasDocKk ? asset('storage/' . $targetDoc) : '';
                    @endphp

                    @if($hasDocKk)
                        <div class="p-3.5 bg-teal-50/50 rounded-xl border border-teal-200 flex items-center justify-between gap-3 flex-wrap sm:flex-nowrap">
                            <div class="flex items-center gap-3 min-w-0 cursor-pointer group" onclick="openDocModal();" title="Klik untuk Pratinjau Dokumen">
                                <div class="w-10 h-10 rounded-lg bg-teal-100 text-[#0b7c89] flex items-center justify-center text-xl shrink-0 group-hover:scale-105 transition">
                                    @if($isPdfDocKk)
                                        <i class="fa-solid fa-file-pdf text-rose-600"></i>
                                    @else
                                        <i class="fa-solid fa-file-image text-[#0b7c89]"></i>
                                    @endif
                                </div>
                                <div class="truncate">
                                    <p class="font-bold text-slate-800 text-xs truncate group-hover:text-[#0b7c89] transition">
                                        {{ $profileRequest->doc_family_card ? 'Dokumen KK Baru Diunggah (Pengajuan)' : 'Dokumen KK Saat Ini di Sistem' }}
                                    </p>
                                    <p class="text-[10px] text-teal-700 font-medium truncate">
                                        {{ basename($targetDoc) }} (Klik untuk lihat & zoom)
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" id="btn-preview-lightbox" onclick="openDocModal();" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5 shadow-2xs cursor-pointer whitespace-nowrap">
                                    <i class="fa-solid fa-magnifying-glass"></i> Lihat
                                </button>
                                <a href="{{ $docKkUrl }}" target="_blank" class="text-xs font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-300 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 shadow-2xs whitespace-nowrap" title="Buka Dokumen Asli di Tab Baru">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Tab Baru
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-slate-400 italic text-[11px] text-center">
                            Tidak ada berkas fisik Kartu Keluarga yang dilampirkan.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Panel Verifikasi (4 Col) -->
        <div class="lg:col-span-4 space-y-6">

            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden sticky top-6">
                <div class="bg-[#065b65] text-white px-5 py-3.5 flex items-center justify-between">
                    <h3 class="font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-user-shield text-amber-300"></i> Tindakan Verifikasi Petugas
                    </h3>
                </div>

                <div class="p-5 space-y-4 text-xs">

                    <!-- Status Banner -->
                    <div class="p-3.5 rounded-xl border {{ $profileRequest->isPending() ? 'bg-amber-50 border-amber-300 text-amber-900' : ($profileRequest->isApproved() ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-rose-50 border-rose-300 text-rose-900') }}">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid {{ $profileRequest->isPending() ? 'fa-clock text-amber-600' : ($profileRequest->isApproved() ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-rose-600') }} text-base"></i>
                            <span class="font-bold">Status: {{ $profileRequest->status_label }}</span>
                        </div>
                        <p class="text-[11px] mt-1 opacity-80">
                            Diajukan pada: {{ $profileRequest->created_at->translatedFormat('d F Y, H:i') }} WIB
                        </p>
                        @if($profileRequest->processed_at)
                            <p class="text-[11px] mt-0.5 opacity-80">
                                Diproses oleh <strong>{{ $profileRequest->processed_by }}</strong> pada {{ $profileRequest->processed_at->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        @endif
                        @if($profileRequest->admin_notes)
                            <div class="mt-2 p-2 bg-white/80 rounded border border-rose-200 text-rose-900 text-[11px]">
                                <strong>Catatan Penolakan:</strong>
                                <p class="italic mt-0.5">{{ $profileRequest->admin_notes }}</p>
                            </div>
                        @endif
                    </div>

                    @if($profileRequest->isPending())
                        <!-- Form Verifikasi -->
                        <form action="{{ route('admin.profile_requests.verify', $profileRequest) }}" method="POST" class="space-y-4" id="form-verify-request">
                            @csrf

                            <div>
                                <label for="rejection_reason" class="block font-bold text-slate-700 mb-1">
                                    Catatan Petugas <span class="text-slate-400 font-normal">(Wajib jika ditolak)</span>:
                                </label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" placeholder="Tuliskan catatan alasan jika permohonan ditolak..." class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0b7c89]">{{ old('rejection_reason', $profileRequest->admin_notes) }}</textarea>
                                @error('rejection_reason')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <input type="hidden" name="action" id="actionField" value="approve">

                            <!-- Action Buttons -->
                            <div class="space-y-2 pt-2">
                                <button type="submit" onclick="document.getElementById('actionField').value='approve'; return confirm('Apakah Anda yakin ingin MENYETUJUI permohonan perubahan data ini? Data profil dan anggota keluarga warga akan langsung diperbarui di sistem.');" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-check-circle"></i> Setujui & Terapkan Perubahan
                                </button>

                                <button type="submit" onclick="
                                    const reason = document.getElementById('rejection_reason').value.trim();
                                    if(!reason) {
                                        alert('Silakan tuliskan alasan penolakan pada kolom Catatan Petugas terlebih dahulu.');
                                        document.getElementById('rejection_reason').focus();
                                        return false;
                                    }
                                    document.getElementById('actionField').value='reject';
                                    return confirm('Apakah Anda yakin ingin MENOLAK permohonan perubahan data ini?');
                                " class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-xmark-circle"></i> Tolak Permohonan Perubahan
                                </button>
                            </div>
                        </form>
                    @elseif($profileRequest->isApproved())
                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-[11px] flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                            <span>Permohonan perubahan data ini telah <strong>disetujui</strong> dan data profil warga telah berhasil diperbarui di sistem.</span>
                        </div>
                    @else
                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-[11px] flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-xmark text-rose-600 text-base shrink-0"></i>
                            <span>Permohonan perubahan data ini telah <strong>ditolak</strong>.</span>
                        </div>
                    @endif

                </div>
            </div>

        </div>

    </div>

</div>

<!-- MODAL PREVIEW DOKUMEN KK (FULL VIEW, ZOOM & DRAG/GESER) -->
<div id="modal-preview-kk" class="fixed inset-0 z-50 bg-slate-950/85 backdrop-blur-sm flex items-center justify-center p-2 sm:p-4 hidden transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-700/50 max-w-5xl w-full overflow-hidden my-auto flex flex-col h-[90vh] max-h-[92vh]">
        <!-- Header Modal -->
        <div class="bg-gradient-to-r from-[#0b7c89] to-[#065b65] text-white px-4 sm:px-6 py-3 flex items-center justify-between shrink-0 shadow-md">
            <div class="flex items-center gap-2.5 min-w-0 mr-2">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center text-teal-200 shrink-0">
                    <i class="fa-solid fa-file-shield text-base"></i>
                </div>
                <div class="truncate">
                    <h4 class="text-xs sm:text-sm font-bold truncate">Pratinjau Dokumen Kartu Keluarga (KK)</h4>
                    <p class="text-[10px] sm:text-[11px] text-teal-100 truncate" id="modal-preview-subtitle">Scroll untuk Zoom • Drag untuk Menggeser Posisi</p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <!-- Toolbar Zoom Gambar -->
                <div id="modal-zoom-controls" class="flex items-center bg-black/25 backdrop-blur-md rounded-xl p-1 border border-white/20">
                    <button type="button" id="btn-zoom-out" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer" title="Perkecil (Zoom Out)">
                        <i class="fa-solid fa-minus text-xs"></i>
                    </button>
                    <span id="zoom-level-label" class="text-xs font-mono font-bold px-2 text-teal-200 min-w-[46px] text-center select-none">100%</span>
                    <button type="button" id="btn-zoom-in" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer" title="Perbesar (Zoom In)">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                    <div class="h-4 w-px bg-white/20 mx-1"></div>
                    <button type="button" id="btn-zoom-rotate" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer" title="Putar 90°">
                        <i class="fa-solid fa-rotate-right text-xs"></i>
                    </button>
                    <button type="button" id="btn-zoom-reset" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer" title="Reset Posisi & Zoom">
                        <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    </button>
                </div>

                <button type="button" id="btn-close-modal-kk" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/30 text-teal-100 hover:text-white flex items-center justify-center transition cursor-pointer" title="Tutup">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body Modal Viewport Interaktif -->
        <div class="relative flex-1 bg-slate-950 overflow-hidden flex items-center justify-center select-none">
            <!-- Image Pan & Zoom Stage -->
            <div id="modal-img-container" class="w-full h-full relative overflow-hidden flex items-center justify-center cursor-grab active:cursor-grabbing bg-slate-900/95" style="touch-action: none; min-height: 250px;">
                <div class="w-full h-full flex items-center justify-center pointer-events-none p-4">
                    <img id="modal-preview-img" 
                         src="{{ ($hasDocKk && !$isPdfDocKk) ? $docKkUrl : '' }}" 
                         alt="Pratinjau Dokumen KK" 
                         class="max-h-[72vh] max-w-[88%] w-auto h-auto rounded-md shadow-2xl object-contain block border border-slate-700 bg-white pointer-events-none select-none transition-transform duration-75 ease-out"
                         style="transform-origin: center center;">
                </div>
            </div>

            <!-- PDF Container -->
            <div id="modal-pdf-container" class="hidden w-full h-full flex flex-col items-center justify-center p-6 bg-slate-900 text-center">
                <div class="w-20 h-20 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-4 border border-rose-500/30">
                    <i class="fa-solid fa-file-pdf text-4xl"></i>
                </div>
                <h5 class="text-base font-bold text-white" id="modal-pdf-name">{{ $hasDocKk ? basename($targetDoc) : 'Dokumen Kartu Keluarga (PDF)' }}</h5>
                <p class="text-xs text-slate-400 mt-1.5 max-w-sm mx-auto">Dokumen Kartu Keluarga berformat PDF dapat dilihat dan dibuka melalui tautan berikut.</p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                    <a id="modal-pdf-link" href="{{ ($hasDocKk && $isPdfDocKk) ? $docKkUrl : '#' }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-white bg-[#0b7c89] hover:bg-[#065b65] px-4 py-2.5 rounded-xl shadow-md transition">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Dokumen PDF di Tab Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2 overflow-hidden mr-3">
                <i class="fa-solid fa-file-circle-check text-emerald-600 shrink-0"></i>
                <span class="text-xs text-slate-700 truncate font-medium" id="modal-file-info">{{ $hasDocKk ? basename($targetDoc) : '-' }}</span>
            </div>
            <button type="button" id="btn-close-modal-kk-footer" class="text-xs font-bold bg-[#0b7c89] hover:bg-[#065b65] text-white px-4 py-2 rounded-xl shadow-xs transition cursor-pointer">
                Tutup Pratinjau
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalPreviewKk = document.getElementById('modal-preview-kk');
    const modalPreviewImg = document.getElementById('modal-preview-img');
    const modalImgContainer = document.getElementById('modal-img-container');
    const modalZoomControls = document.getElementById('modal-zoom-controls');
    const zoomLevelLabel = document.getElementById('zoom-level-label');
    const btnZoomIn = document.getElementById('btn-zoom-in');
    const btnZoomOut = document.getElementById('btn-zoom-out');
    const btnZoomRotate = document.getElementById('btn-zoom-rotate');
    const btnZoomReset = document.getElementById('btn-zoom-reset');

    const modalPdfContainer = document.getElementById('modal-pdf-container');
    const modalPdfName = document.getElementById('modal-pdf-name');
    const modalPdfLink = document.getElementById('modal-pdf-link');
    const modalFileInfo = document.getElementById('modal-file-info');
    const modalPreviewSubtitle = document.getElementById('modal-preview-subtitle');
    const btnCloseModalKk = document.getElementById('btn-close-modal-kk');
    const btnCloseModalKkFooter = document.getElementById('btn-close-modal-kk-footer');

    const currentFileIsPdf = {{ ($hasDocKk && $isPdfDocKk) ? 'true' : 'false' }};
    const currentDocUrl = "{{ $hasDocKk ? $docKkUrl : '' }}";
    const currentFileName = "{{ $hasDocKk ? basename($targetDoc) : '' }}";

    // Zoom & Pan State
    let zoomScale = 1.0;
    let panX = 0;
    let panY = 0;
    let currentRotation = 0;
    let isDraggingImg = false;
    let dragStartX = 0;
    let dragStartY = 0;

    function applyPanZoomTransform() {
        if (!modalPreviewImg) return;
        modalPreviewImg.style.transform = `translate(${panX}px, ${panY}px) scale(${zoomScale}) rotate(${currentRotation}deg)`;
        if (zoomLevelLabel) {
            zoomLevelLabel.textContent = Math.round(zoomScale * 100) + '%';
        }
    }

    function resetPanZoom() {
        zoomScale = 1.0;
        panX = 0;
        panY = 0;
        currentRotation = 0;
        applyPanZoomTransform();
    }

    function zoomIn() {
        zoomScale = Math.min(5.0, Number((zoomScale + 0.25).toFixed(2)));
        applyPanZoomTransform();
    }

    function zoomOut() {
        zoomScale = Math.max(0.4, Number((zoomScale - 0.25).toFixed(2)));
        applyPanZoomTransform();
    }

    function rotateImage() {
        currentRotation = (currentRotation + 90) % 360;
        applyPanZoomTransform();
    }

    if (btnZoomIn) btnZoomIn.addEventListener('click', zoomIn);
    if (btnZoomOut) btnZoomOut.addEventListener('click', zoomOut);
    if (btnZoomReset) btnZoomReset.addEventListener('click', resetPanZoom);
    if (btnZoomRotate) btnZoomRotate.addEventListener('click', rotateImage);

    // Drag & Pan Mouse Interaction
    if (modalImgContainer) {
        modalImgContainer.addEventListener('mousedown', function(e) {
            if (e.button !== 0) return; // Only left click
            isDraggingImg = true;
            dragStartX = e.clientX - panX;
            dragStartY = e.clientY - panY;
            modalImgContainer.classList.add('cursor-grabbing');
            modalImgContainer.classList.remove('cursor-grab');
            e.preventDefault();
        });

        window.addEventListener('mousemove', function(e) {
            if (!isDraggingImg) return;
            panX = e.clientX - dragStartX;
            panY = e.clientY - dragStartY;
            applyPanZoomTransform();
        });

        window.addEventListener('mouseup', function() {
            if (isDraggingImg) {
                isDraggingImg = false;
                if (modalImgContainer) {
                    modalImgContainer.classList.remove('cursor-grabbing');
                    modalImgContainer.classList.add('cursor-grab');
                }
            }
        });

        // Mouse Wheel Zoom
        modalImgContainer.addEventListener('wheel', function(e) {
            e.preventDefault();
            const delta = e.deltaY < 0 ? 0.15 : -0.15;
            zoomScale = Math.min(5.0, Math.max(0.4, Number((zoomScale + delta).toFixed(2))));
            applyPanZoomTransform();
        }, { passive: false });

        // Double Click to Reset or Zoom In
        modalImgContainer.addEventListener('dblclick', function(e) {
            if (zoomScale > 1.2) {
                resetPanZoom();
            } else {
                zoomScale = 2.0;
                applyPanZoomTransform();
            }
        });

        // Touch Interaction (Mobile Pinch & Drag)
        let initialTouchDistance = null;
        modalImgContainer.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                isDraggingImg = true;
                dragStartX = e.touches[0].clientX - panX;
                dragStartY = e.touches[0].clientY - panY;
            } else if (e.touches.length === 2) {
                isDraggingImg = false;
                initialTouchDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
            }
        }, { passive: true });

        modalImgContainer.addEventListener('touchmove', function(e) {
            if (isDraggingImg && e.touches.length === 1) {
                panX = e.touches[0].clientX - dragStartX;
                panY = e.touches[0].clientY - dragStartY;
                applyPanZoomTransform();
            } else if (e.touches.length === 2 && initialTouchDistance) {
                const currentDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                const diff = (currentDist - initialTouchDistance) * 0.005;
                zoomScale = Math.min(5.0, Math.max(0.4, Number((zoomScale + diff).toFixed(2))));
                initialTouchDistance = currentDist;
                applyPanZoomTransform();
            }
        }, { passive: true });

        modalImgContainer.addEventListener('touchend', function(e) {
            if (e.touches.length === 0) {
                isDraggingImg = false;
                initialTouchDistance = null;
            }
        }, { passive: true });
    }

    window.openDocModal = function() {
        if (!modalPreviewKk) return;
        resetPanZoom();
        if (currentFileIsPdf) {
            if (modalImgContainer) modalImgContainer.classList.add('hidden');
            if (modalZoomControls) modalZoomControls.classList.add('hidden');
            if (modalPdfContainer) modalPdfContainer.classList.remove('hidden');
            if (modalPdfName) modalPdfName.textContent = currentFileName || 'Dokumen Kartu Keluarga (PDF)';
            if (modalPreviewSubtitle) modalPreviewSubtitle.textContent = 'Dokumen berformat PDF';
            if (modalFileInfo) modalFileInfo.textContent = currentFileName ? currentFileName + ' (PDF)' : 'Dokumen PDF';
            if (modalPdfLink && currentDocUrl) modalPdfLink.href = currentDocUrl;
        } else if (currentDocUrl) {
            if (modalImgContainer) modalImgContainer.classList.remove('hidden');
            if (modalZoomControls) modalZoomControls.classList.remove('hidden');
            if (modalPdfContainer) modalPdfContainer.classList.add('hidden');
            if (modalPreviewImg) {
                modalPreviewImg.src = currentDocUrl;
                modalPreviewImg.style.display = 'block';
            }
            if (modalPreviewSubtitle) modalPreviewSubtitle.textContent = 'Scroll untuk Zoom • Drag untuk Menggeser Posisi';
            if (modalFileInfo) modalFileInfo.textContent = currentFileName || 'Gambar Kartu Keluarga';
        } else {
            return;
        }
        modalPreviewKk.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    function closeDocModal() {
        if (!modalPreviewKk) return;
        modalPreviewKk.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (btnCloseModalKk) btnCloseModalKk.addEventListener('click', closeDocModal);
    if (btnCloseModalKkFooter) btnCloseModalKkFooter.addEventListener('click', closeDocModal);
    if (modalPreviewKk) {
        modalPreviewKk.addEventListener('click', function(e) {
            if (e.target === modalPreviewKk) closeDocModal();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalPreviewKk && !modalPreviewKk.classList.contains('hidden')) {
            closeDocModal();
        }
    });
});
</script>
@endsection
