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
                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-people-roof text-[#0b7c89]"></i> 2. Susunan Anggota Keluarga yang Diajukan
                    </h3>
                    @php
                        $proposedMembers = is_array($profileRequest->family_members_data) ? $profileRequest->family_members_data : [];
                    @endphp
                    <span class="text-[10px] font-bold bg-teal-50 text-[#0b7c89] border border-teal-200 px-2 py-0.5 rounded">
                        {{ count($proposedMembers) }} Anggota Baru Diajukan
                    </span>
                </div>

                <div class="p-5 space-y-4 text-xs">
                    
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
                                    <div class="p-2 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between text-[11px]">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-800">{{ $oldMember->name }}</span>
                                            <span class="text-[9px] bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded">
                                                {{ $oldMember->family_relationship ?: 'Anggota' }}
                                            </span>
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
                    @endphp

                    @if($targetDoc)
                        <div class="p-3.5 bg-teal-50/50 rounded-xl border border-teal-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-teal-100 text-[#0b7c89] flex items-center justify-center text-xl shrink-0">
                                    @if(\Illuminate\Support\Str::endsWith(strtolower($targetDoc), '.pdf'))
                                        <i class="fa-solid fa-file-pdf text-rose-600"></i>
                                    @else
                                        <i class="fa-solid fa-file-image text-teal-700"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-xs">
                                        {{ $profileRequest->doc_family_card ? 'Dokumen KK Baru Diunggah' : 'Dokumen KK Saat Ini' }}
                                    </p>
                                    <p class="text-[10px] text-slate-500">{{ basename($targetDoc) }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $targetDoc) }}" target="_blank" class="bg-[#0b7c89] hover:bg-[#065b65] text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-eye"></i> Buka Dokumen KK
                            </a>
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

                </div>
            </div>

        </div>

    </div>

</div>
@endsection
