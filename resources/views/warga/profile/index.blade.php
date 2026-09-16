@extends('layouts.app')

@section('title', 'Profil & Data Warga - ' . $warga->name)

@section('content')
<div class="space-y-6">

    <!-- Header & Hero Profile Card -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden dissolve-card">
        <div class="bg-gradient-to-r from-[#095b8c] via-[#0b7c89] to-[#059cb8] p-6 text-white relative">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-5 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 backdrop-blur border border-white/30 flex items-center justify-center text-white text-3xl font-extrabold shadow-inner shrink-0">
                        <i class="fa-solid fa-user text-amber-300"></i>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="bg-black/25 text-teal-100 text-[11px] font-bold px-2.5 py-0.5 rounded border border-white/10 uppercase tracking-wider">
                                Akun Warga Terdaftar
                            </span>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $warga->status_badge_class }}">
                                {{ $warga->status_label }}
                            </span>
                            @if($pendingRequest)
                                <span class="bg-amber-400 text-slate-950 text-[11px] font-extrabold px-2.5 py-0.5 rounded-full border border-amber-300 flex items-center gap-1">
                                    <i class="fa-solid fa-clock-rotate-left"></i> Ada Pengajuan Perubahan Data
                                </span>
                            @endif
                        </div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">{{ $warga->name }}</h1>
                        <div class="text-xs text-teal-100 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                            <span class="inline-flex items-center gap-1.5 font-mono">
                                <i class="fa-solid fa-id-card text-amber-300"></i> NIK: {{ $warga->nik }}
                            </span>
                            <span class="text-teal-300/60 hidden sm:inline">•</span>
                            <span class="inline-flex items-center gap-1.5 font-mono">
                                <i class="fa-solid fa-people-roof text-teal-300"></i> No. KK: {{ $warga->family_card_no }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-black/20 backdrop-blur rounded-xl p-3 border border-white/15 text-xs text-teal-50 max-w-xs shrink-0 w-full md:w-auto">
                    <p class="font-bold text-amber-300 flex items-center gap-1.5 mb-1">
                        <i class="fa-solid fa-shield-halved"></i> Verifikasi Perubahan Data
                    </p>
                    <p class="text-[11px] leading-relaxed text-teal-100">
                        Setiap perubahan data profil dan anggota keluarga akan dikirimkan ke petugas kelurahan terlebih dahulu untuk diverifikasi sebelum diterapkan.
                    </p>
                </div>
            </div>

            <!-- Background subtle pattern -->
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        </div>
    </div>

    <!-- Banner Notifikasi Status Pengajuan Perubahan Data -->
    @if($pendingRequest)
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-5 border border-amber-300 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 border border-amber-300 text-lg">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-bold text-slate-800 text-sm">Permohonan Perubahan Data Sedang Menunggu Verifikasi Admin</h3>
                        <span class="bg-amber-200 text-amber-900 text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            Menunggu Verifikasi
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        Anda telah mengajukan perubahan data akun dan keluarga pada <strong>{{ $pendingRequest->created_at->translatedFormat('d F Y, H:i') }} WIB</strong>. Perubahan akan berlaku pada sistem setelah disetujui oleh admin kelurahan. Anda tetap dapat memperbarui formulir di bawah jika ingin merevisi pengajuan.
                    </p>
                </div>
            </div>
            <form action="{{ route('profile.cancel', $pendingRequest) }}" method="POST" onsubmit="try { localStorage.removeItem('purwobinangun_warga_profile_draft_{{ Auth::id() }}'); if (window.indexedDB) { clearDraftFilesFromDB('profile_doc_family_card_'); } } catch(e){} return confirm('Apakah Anda yakin ingin membatalkan permohonan perubahan data yang sedang menunggu verifikasi ini?');" class="shrink-0 m-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-300 px-3.5 py-2 rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow-2xs">
                    <i class="fa-solid fa-xmark"></i> Batalkan Pengajuan
                </button>
            </form>
        </div>
    @elseif($latestRequest && $latestRequest->isRejected())
        <div id="profile-rejected-banner-{{ $latestRequest->id }}" class="bg-rose-50 rounded-xl p-4 sm:p-5 border border-rose-300 shadow-xs flex items-start justify-between gap-3.5 transition-all duration-300">
            <div class="flex items-start gap-3.5 flex-1">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 border border-rose-200 text-lg">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-bold text-rose-900 text-sm">Permohonan Perubahan Data Sebelumnya Ditolak</h3>
                        <span class="text-[10px] text-slate-500 font-mono">({{ $latestRequest->updated_at->translatedFormat('d F Y, H:i') }} WIB)</span>
                    </div>
                    <div class="mt-2 p-3 bg-white/90 rounded-lg border border-rose-200 text-xs text-rose-800">
                        <p class="font-bold mb-0.5"><i class="fa-solid fa-comment-dots text-rose-600 mr-1"></i> Catatan dari Petugas Kelurahan:</p>
                        <p class="italic text-[11px]">{{ $latestRequest->admin_notes ?: 'Data tidak sesuai atau berkas persyaratan belum lengkap.' }}</p>
                    </div>
                    <p class="text-xs text-slate-600 mt-2">
                        Silakan perbaiki data pada formulir di bawah ini dan kirimkan kembali untuk diverifikasi ulang oleh petugas.
                    </p>
                </div>
            </div>
            <button type="button" onclick="dismissProfileNotification('profile-rejected-banner-{{ $latestRequest->id }}', 'purwobinangun_dismissed_profile_rejected_{{ Auth::id() }}_{{ $latestRequest->id }}')" class="shrink-0 text-rose-400 hover:text-rose-700 hover:bg-rose-100/80 w-8 h-8 rounded-lg transition flex items-center justify-center cursor-pointer -mt-1 -mr-1" title="Tutup Notifikasi" aria-label="Tutup Notifikasi">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>
    @elseif($latestRequest && $latestRequest->isApproved())
        <div id="profile-approved-banner-{{ $latestRequest->id }}" class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4 sm:p-5 border border-emerald-300 shadow-xs flex items-start justify-between gap-3.5 transition-all duration-300">
            <div class="flex items-start gap-3.5 flex-1">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200 text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-bold text-emerald-900 text-sm">Permohonan Perubahan Data Telah Disetujui</h3>
                        <span class="bg-emerald-200 text-emerald-900 text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            Disetujui
                        </span>
                        <span class="text-[10px] text-slate-500 font-mono">({{ $latestRequest->updated_at->translatedFormat('d F Y, H:i') }} WIB)</span>
                    </div>
                    <p class="text-xs text-slate-700 mt-1.5 leading-relaxed">
                        Permohonan perubahan data akun dan anggota keluarga Anda telah diverifikasi dan disetujui oleh petugas kelurahan <strong>({{ $latestRequest->processed_by ?: 'Petugas Kelurahan' }})</strong>. Seluruh data terbaru Anda telah berhasil diperbarui dan aktif di sistem.
                    </p>
                </div>
            </div>
            <button type="button" onclick="dismissProfileNotification('profile-approved-banner-{{ $latestRequest->id }}', 'purwobinangun_dismissed_profile_approved_{{ Auth::id() }}_{{ $latestRequest->id }}')" class="shrink-0 text-emerald-400 hover:text-emerald-700 hover:bg-emerald-100/80 w-8 h-8 rounded-lg transition flex items-center justify-center cursor-pointer -mt-1 -mr-1" title="Tutup Notifikasi" aria-label="Tutup Notifikasi">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>
    @endif

    <!-- Data Form Inisialisasi: Pilih data dari pendingRequest jika ada, lalu fallback ke data warga aktif -->
    @php
        $activeNik = old('nik', $pendingRequest->nik ?? $warga->nik);
        $activeKk = old('family_card_no', $pendingRequest->family_card_no ?? $warga->family_card_no);
        $activeName = old('name', $pendingRequest->name ?? $warga->name);
        $activeBirthPlace = old('birth_place', $pendingRequest->birth_place ?? $warga->birth_place);
        $activeBirthDate = old('birth_date', isset($pendingRequest->birth_date) ? $pendingRequest->birth_date->format('Y-m-d') : ($warga->birth_date ? $warga->birth_date->format('Y-m-d') : ''));
        $activeGender = old('gender', $pendingRequest->gender ?? $warga->gender);
        $activeFamilyRel = old('family_relationship', $pendingRequest->family_relationship ?? ($warga->family_relationship ?: 'Kepala Keluarga'));
        $activePhone = old('phone', $pendingRequest->phone ?? $warga->phone);
        $activeEmail = old('email', $pendingRequest->email ?? $warga->email);
        $activeAddress = old('address', $pendingRequest->address ?? $warga->address);
        $activeRt = old('rt', $pendingRequest->rt ?? $warga->rt);
        $activeRw = old('rw', $pendingRequest->rw ?? $warga->rw);
        $activeDocKk = $pendingRequest->doc_family_card ?? $warga->doc_family_card;
        $hasDocKk = !empty($activeDocKk);
        $isPdfDocKk = $hasDocKk && \Illuminate\Support\Str::endsWith(strtolower($activeDocKk), '.pdf');
        $docKkUrl = $hasDocKk ? (\Illuminate\Support\Str::startsWith($activeDocKk, 'http') || \Illuminate\Support\Str::startsWith($activeDocKk, 'storage/') ? asset($activeDocKk) : asset('storage/' . $activeDocKk)) : '';

        // Data anggota keluarga untuk form:
        $serverFamilyMembers = old('family_members', []);
        if (empty($serverFamilyMembers)) {
            if ($pendingRequest && !empty($pendingRequest->family_members_data)) {
                $serverFamilyMembers = $pendingRequest->family_members_data;
            } elseif ($warga->familyMembers && $warga->familyMembers->isNotEmpty()) {
                $serverFamilyMembers = $warga->familyMembers->map(function($m) {
                    return [
                        'family_card_no' => $m->family_card_no,
                        'nik' => $m->nik,
                        'name' => $m->name,
                        'birth_place' => $m->birth_place,
                        'birth_date' => $m->birth_date ? $m->birth_date->format('Y-m-d') : '',
                        'gender' => $m->gender,
                        'family_relationship' => $m->family_relationship,
                    ];
                })->values()->toArray();
            }
        }
    @endphp

    <!-- Grid Konten: Form Ubah Profil (Kiri 8 Col) & Form Password + Info KK (Kanan 4 Col) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Kolom Kiri: Form Ubah Data Profil & Anggota Keluarga (8 Col) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-user-pen text-[#059cb8]"></i> Formulir Pengajuan Perubahan Data
                    </h2>
                    <span class="text-[11px] text-slate-400"><i class="fa-solid fa-asterisk text-rose-500 text-[9px]"></i> Wajib diisi</span>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-6 text-xs" id="form-profile-update">
                    @csrf
                    @method('PUT')

                    <!-- BAGIAN 1: IDENTITAS UTAMA (NIK & KK) -->
                    <div class="space-y-4">
                        <div class="pb-2.5 border-b border-slate-200 flex items-center justify-between">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                                <i class="fa-solid fa-id-card text-sm"></i> 1. Identitas Akun & Kartu Keluarga
                            </h3>
                            <span class="text-[11px] text-slate-400 font-medium hidden sm:inline">Data Pemohon Utama</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nik" class="block font-bold text-slate-700 mb-1">
                                    Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nik" id="nik" value="{{ $activeNik }}" required maxlength="16" placeholder="16 digit NIK"
                                    class="w-full text-xs font-mono rounded-lg border {{ $errors->has('nik') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                    style="padding: 0.625rem 0.875rem;">
                                @error('nik')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="family_card_no" class="block font-bold text-slate-700 mb-1">
                                    Nomor Kartu Keluarga (KK) <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="family_card_no" id="family_card_no" value="{{ $activeKk }}" required maxlength="16" placeholder="16 digit Nomor KK"
                                    class="w-full text-xs font-mono rounded-lg border {{ $errors->has('family_card_no') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                    style="padding: 0.625rem 0.875rem;">
                                @error('family_card_no')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Upload Berkas Kartu Keluarga Fisik -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">
                                Dokumen Kartu Keluarga (KK) Fisik <span class="text-slate-400 font-normal">(Opsional jika tidak ada perubahan berkas)</span>
                            </label>

                            <!-- Kotak Preview Berkas Tersimpan / Dipilih -->
                            <div id="preview-box-kk" class="{{ $hasDocKk ? '' : 'hidden' }} p-3.5 bg-teal-50/50 rounded-xl border border-teal-200/80 mb-3 flex items-center justify-between gap-3 flex-wrap sm:flex-nowrap">
                                <div class="flex items-center gap-3 min-w-0 cursor-pointer group" onclick="openDocModal();" title="Klik untuk Pratinjau Dokumen">
                                    <div class="w-10 h-10 rounded-lg bg-teal-100/80 border border-teal-200 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                                        <div id="img-preview-wrap-kk" class="{{ ($hasDocKk && !$isPdfDocKk) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-file-image text-[#095b8c] text-xl"></i>
                                        </div>
                                        <div id="pdf-preview-wrap-kk" class="{{ ($hasDocKk && $isPdfDocKk) ? '' : 'hidden' }}">
                                            <i class="fa-solid fa-file-pdf text-rose-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="truncate">
                                        <p id="file-name-kk" class="font-bold text-slate-800 text-xs truncate group-hover:text-[#095b8c] transition">
                                            {{ $hasDocKk ? basename($activeDocKk) : 'Berkas Kartu Keluarga' }}
                                        </p>
                                        <p id="file-size-kk" class="text-[10px] text-teal-700 font-medium">
                                            {{ $hasDocKk ? 'Dokumen terlampir di sistem (Klik untuk lihat)' : 'Berkas siap diunggah (Klik untuk lihat)' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" id="btn-preview-lightbox" class="text-xs font-bold text-[#095b8c] hover:text-[#074a73] bg-teal-50 hover:bg-teal-100 px-3.5 py-1.5 rounded-lg border border-teal-200 transition flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                                        <i class="fa-solid fa-magnifying-glass"></i> Lihat
                                    </button>
                                    <label for="doc_family_card" class="text-xs font-bold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg border border-slate-300 transition flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                                        <i class="fa-solid fa-upload"></i> {{ $hasDocKk ? 'Ganti Berkas' : 'Pilih Berkas' }}
                                    </label>
                                </div>
                            </div>

                            <!-- Input File Hidden / Upload Trigger -->
                            <div id="placeholder-kk" class="{{ $hasDocKk ? 'hidden' : '' }} border-2 border-dashed border-slate-300 hover:border-[#095b8c] rounded-xl p-4 text-center cursor-pointer transition bg-slate-50/50 hover:bg-teal-50/20" onclick="document.getElementById('doc_family_card').click();">
                                <i class="fa-solid fa-cloud-arrow-up text-slate-400 text-2xl mb-1.5 block"></i>
                                <span class="font-bold text-slate-700 text-xs block">Unggah Foto / Scan Dokumen Kartu Keluarga</span>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">Format JPG, JPEG, PNG, atau PDF (Maksimal 3MB)</span>
                            </div>

                            <input type="file" name="doc_family_card" id="doc_family_card" accept=".jpg,.jpeg,.png,.pdf" class="hidden">

                            @error('doc_family_card')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Lengkap Sesuai KK -->
                        <div>
                            <label for="name" class="block font-bold text-slate-700 mb-1">
                                Nama Lengkap Sesuai KTP / KK <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ $activeName }}" required
                                class="w-full text-xs rounded-lg border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding: 0.625rem 0.875rem;">
                            @error('name')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tempat & Tanggal Lahir -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="birth_place" class="block font-bold text-slate-700 mb-1">
                                    Tempat Lahir <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="birth_place" id="birth_place" value="{{ $activeBirthPlace }}" required placeholder="Contoh: Sleman"
                                    class="w-full text-xs rounded-lg border {{ $errors->has('birth_place') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                    style="padding: 0.625rem 0.875rem;">
                                @error('birth_place')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="birth_date" class="block font-bold text-slate-700 mb-1">
                                    Tanggal Lahir <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="birth_date" id="birth_date" value="{{ $activeBirthDate }}" required
                                    class="w-full text-xs rounded-lg border {{ $errors->has('birth_date') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                    style="padding: 0.625rem 0.875rem;">
                                @error('birth_date')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Jenis Kelamin & Posisi dalam Keluarga -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Jenis Kelamin <span class="text-rose-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border cursor-pointer transition {{ $activeGender === 'L' ? 'bg-teal-50/80 border-[#059cb8] text-[#095b8c] font-bold' : 'border-slate-200 hover:bg-slate-50' }}">
                                        <input type="radio" name="gender" value="L" {{ $activeGender === 'L' ? 'checked' : '' }} required class="text-[#095b8c] focus:ring-[#059cb8]">
                                        <span class="text-xs whitespace-nowrap"><i class="fa-solid fa-mars text-blue-500 mr-1"></i> Laki-laki</span>
                                    </label>
                                    <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border cursor-pointer transition {{ $activeGender === 'P' ? 'bg-pink-50/80 border-pink-400 text-pink-700 font-bold' : 'border-slate-200 hover:bg-slate-50' }}">
                                        <input type="radio" name="gender" value="P" {{ $activeGender === 'P' ? 'checked' : '' }} required class="text-pink-600 focus:ring-pink-500">
                                        <span class="text-xs whitespace-nowrap"><i class="fa-solid fa-venus text-pink-500 mr-1"></i> Perempuan</span>
                                    </label>
                                </div>
                                @error('gender')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="family_relationship" class="block font-bold text-slate-700 mb-1">
                                    Posisi dalam Keluarga <span class="text-rose-500">*</span>
                                </label>
                                <select name="family_relationship" id="family_relationship" required
                                    class="w-full text-xs rounded-lg border {{ $errors->has('family_relationship') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition bg-white"
                                    style="padding: 0.625rem 0.875rem;">
                                    <option value="Kepala Keluarga" {{ $activeFamilyRel === 'Kepala Keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                    <option value="Suami" {{ $activeFamilyRel === 'Suami' ? 'selected' : '' }}>Suami</option>
                                    <option value="Istri" {{ $activeFamilyRel === 'Istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="Anak" {{ $activeFamilyRel === 'Anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="Menantu" {{ $activeFamilyRel === 'Menantu' ? 'selected' : '' }}>Menantu</option>
                                    <option value="Cucu" {{ $activeFamilyRel === 'Cucu' ? 'selected' : '' }}>Cucu</option>
                                    <option value="Orang Tua" {{ $activeFamilyRel === 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="Mertua" {{ $activeFamilyRel === 'Mertua' ? 'selected' : '' }}>Mertua</option>
                                    <option value="Famili Lain" {{ $activeFamilyRel === 'Famili Lain' ? 'selected' : '' }}>Famili Lain</option>
                                    <option value="Lainnya" {{ $activeFamilyRel === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('family_relationship')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kontak: No HP & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block font-bold text-slate-700 mb-1">
                                    Nomor HP / WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="phone" id="phone" value="{{ $activePhone }}" required placeholder="Contoh: 081234567890"
                                        class="w-full text-xs rounded-lg border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                        style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                                    <span class="text-emerald-600 pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                    </span>
                                </div>
                                @error('phone')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block font-bold text-slate-700 mb-1">
                                    Alamat Email <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" name="email" id="email" value="{{ $activeEmail }}" required placeholder="nama@email.com"
                                        class="w-full text-xs rounded-lg border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                        style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                                    <span class="text-slate-400 pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center">
                                        <i class="fa-regular fa-envelope text-xs"></i>
                                    </span>
                                </div>
                                @error('email')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label for="address" class="block font-bold text-slate-700 mb-1">
                                Alamat Lengkap (Padukuhan / Jalan / Dusun) <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="address" id="address" rows="2" required placeholder="Contoh: Watuadeg, Purwobinangun, Pakem, Sleman"
                                class="w-full text-xs rounded-lg border {{ $errors->has('address') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding: 0.625rem 0.875rem;">{{ $activeAddress }}</textarea>
                            @error('address')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- RT & RW -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="rt" class="block font-bold text-slate-700 mb-1">
                                    RT <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="rt" id="rt" value="{{ $activeRt }}" required placeholder="Contoh: 01" maxlength="5"
                                    class="w-full text-xs rounded-lg border {{ $errors->has('rt') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                    style="padding: 0.625rem 0.875rem;">
                                @error('rt')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="rw" class="block font-bold text-slate-700 mb-1">
                                    RW <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="rw" id="rw" value="{{ $activeRw }}" required placeholder="Contoh: 05" maxlength="5"
                                    class="w-full text-xs rounded-lg border {{ $errors->has('rw') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                    style="padding: 0.625rem 0.875rem;">
                                @error('rw')
                                    <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 2: ANGGOTA KELUARGA (TAMBAH / EDIT / KURANGI) -->
                    <div class="space-y-4 pt-4 border-t border-slate-200">
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-200 flex-wrap gap-2">
                            <div>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                                    <i class="fa-solid fa-people-roof text-sm"></i> 2. Data Anggota Keluarga Satu KK
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    Kelola daftar anggota keluarga lain yang tercantum di KK. Anda dapat menambah, mengedit, atau menghapus anggota.
                                </p>
                            </div>
                            <button type="button" id="btn-add-family-member" class="text-xs font-bold bg-[#095b8c] hover:bg-[#074a73] text-white px-3.5 py-2 rounded-lg transition flex items-center gap-1.5 shadow-2xs cursor-pointer shrink-0 whitespace-nowrap">
                                <i class="fa-solid fa-user-plus text-xs"></i> Tambah Anggota
                            </button>
                        </div>

                        <!-- Empty State Placeholder -->
                        <div id="empty-family-state" class="p-6 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-center text-slate-500 text-xs">
                            <i class="fa-solid fa-users text-slate-400 text-2xl mb-1.5 block"></i>
                            <p class="font-medium text-slate-700">Belum ada anggota keluarga tambahan.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Klik tombol <strong>Tambah Anggota</strong> di atas untuk menambahkan anggota keluarga lain yang tercatat dalam KK.</p>
                        </div>

                        <!-- Container Kartu Anggota Keluarga Dinamis -->
                        <div id="family-members-container" class="space-y-3.5"></div>
                    </div>

                    <!-- Tombol Simpan & Info Verifikasi -->
                    <div class="pt-4 border-t border-slate-200 space-y-3.5">
                        <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 text-slate-700 text-xs flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0 text-sm"></i>
                            <div class="text-[11px] leading-relaxed">
                                <strong>Perhatian:</strong> Perubahan data profil dan penyesuaian anggota keluarga akan dikirimkan sebagai <strong>permohonan perubahan data</strong> kepada petugas kelurahan dan akan aktif setelah diverifikasi oleh admin.
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="w-full sm:w-auto bg-[#095b8c] hover:bg-[#059cb8] active:bg-[#074a73] text-white font-bold text-xs py-3 px-6 rounded-xl shadow-xs hover:shadow transition flex items-center justify-center cursor-pointer whitespace-nowrap">
                                <span>Kirim Permohonan Perubahan Data ke Admin</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Form Ganti Kata Sandi & Anggota Keluarga Tercatat (4 Col) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Card Ganti Password -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-4 sm:px-5 py-3.5 border-b border-slate-200">
                    <h2 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-key text-amber-500"></i> <span class="whitespace-nowrap">Ganti Kata Sandi Akun</span>
                    </h2>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="p-4 sm:p-5 space-y-3.5 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block font-bold text-slate-700 mb-1 whitespace-nowrap">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password" required placeholder="Masukkan kata sandi lama"
                                class="w-full text-xs rounded-lg border {{ $errors->has('current_password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            <span class="text-slate-400 pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                        </div>
                        @error('current_password')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password" class="block font-bold text-slate-700 mb-1 whitespace-nowrap">
                            Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="new_password" required minlength="6" placeholder="Minimal 6 karakter"
                                class="w-full text-xs rounded-lg border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            <span class="text-slate-400 pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center">
                                <i class="fa-solid fa-key text-xs"></i>
                            </span>
                        </div>
                        @error('password')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-bold text-slate-700 mb-1 whitespace-nowrap">
                            Ulangi Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6" placeholder="Ketik ulang kata sandi baru"
                                class="w-full text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] transition"
                                style="padding-left: 0.875rem; padding-right: 2.75rem; padding-top: 0.625rem; padding-bottom: 0.625rem;">
                            <span class="text-slate-400 pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center">
                                <i class="fa-solid fa-shield-check text-xs"></i>
                            </span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 active:bg-black text-white font-bold text-xs py-2.5 px-4 rounded-lg shadow-xs hover:shadow transition flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Anggota Keluarga Tercatat Saat Ini (Live Data) -->
            <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-4 sm:px-5 py-3.5 border-b border-slate-200 flex items-center justify-between gap-2 flex-wrap">
                    <h2 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-people-roof text-[#095b8c]"></i> <span class="whitespace-nowrap">Data KK Aktif</span>
                    </h2>
                    <span class="text-[10px] font-mono bg-teal-100/70 text-[#095b8c] font-bold px-2 py-0.5 rounded shrink-0 whitespace-nowrap">
                        KK: {{ $warga->family_card_no }}
                    </span>
                </div>

                <div class="p-4 sm:p-5 space-y-4 text-xs">
                    @if($warga->familyMembers->count() > 0)
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <p class="font-bold text-slate-700 text-[11px]">Anggota Keluarga di KK:</p>
                                <span class="text-[10px] font-bold text-[#095b8c] bg-teal-50 px-2 py-0.5 rounded border border-teal-200 shrink-0 whitespace-nowrap">
                                    {{ $warga->familyMembers->count() }} Orang
                                </span>
                            </div>
                            <div class="space-y-2">
                                @foreach($warga->familyMembers as $fm)
                                    <div class="p-2.5 bg-slate-50 hover:bg-teal-50/40 rounded-lg border border-slate-200/80 hover:border-teal-200 transition">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="font-bold text-slate-800 text-xs truncate">{{ $fm->name }}</p>
                                            <span class="text-[9px] font-bold text-[#095b8c] bg-teal-100 px-1.5 py-0.5 rounded shrink-0 whitespace-nowrap">
                                                {{ $fm->family_relationship ?: 'Anggota' }}
                                            </span>
                                        </div>
                                        <div class="text-[10px] text-slate-500 font-mono mt-1 space-y-0.5">
                                            <p class="truncate"><span class="text-slate-400">NIK:</span> {{ $fm->nik ?: '-' }} &bull; {{ $fm->gender === 'L' ? 'Laki-laki' : ($fm->gender === 'P' ? 'Perempuan' : '-') }}</p>
                                            @if($fm->birth_place || $fm->birth_date)
                                                <p class="truncate text-[10px]"><span class="text-slate-400">Lahir:</span> {{ $fm->birth_place }}{{ ($fm->birth_place && $fm->birth_date) ? ', ' : '' }}{{ $fm->birth_date ? $fm->birth_date->format('d/m/Y') : '' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-3 bg-slate-50 rounded-lg border border-dashed border-slate-200 text-center text-slate-400 italic text-[11px]">
                            Belum ada anggota keluarga yang tercatat di sistem. Silakan tambahkan pada formulir di sebelah kiri.
                        </div>
                    @endif

                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-slate-500 text-[11px] leading-relaxed mb-2 font-medium">
                            Akun warga lain yang terdaftar dalam satu KK:
                        </p>

                        @if($familyMembers->count() > 0)
                            <div class="space-y-2">
                                @foreach($familyMembers as $member)
                                    <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-200/80 flex items-center justify-between gap-2">
                                        <div class="min-w-0 flex-1">
                                            <p class="font-bold text-slate-800 truncate text-xs">{{ $member->name }}</p>
                                            <p class="text-[10px] text-slate-500 font-mono mt-0.5 truncate">NIK: {{ $member->nik }}</p>
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border shrink-0 whitespace-nowrap {{ $member->status_badge_class }}">
                                            {{ $member->status_label }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-3 bg-slate-50 rounded-lg border border-dashed border-slate-200 text-center text-slate-400 italic text-[11px]">
                                Belum ada akun warga lain yang terdaftar dalam KK ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- MODAL PREVIEW DOKUMEN KK (FULL VIEW, ZOOM & DRAG/GESER) -->
<div id="modal-preview-kk" class="fixed inset-0 z-50 bg-slate-950/85 backdrop-blur-sm flex items-center justify-center p-2 sm:p-4 hidden transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-700/50 max-w-5xl w-full overflow-hidden my-auto flex flex-col h-[90vh] max-h-[92vh]">
        <!-- Header Modal -->
        <div class="bg-gradient-to-r from-[#095b8c] to-[#059cb8] text-white px-4 sm:px-6 py-3 flex items-center justify-between shrink-0 shadow-md">
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
                <h5 class="text-base font-bold text-white" id="modal-pdf-name">{{ $hasDocKk ? basename($activeDocKk) : 'Dokumen Kartu Keluarga (PDF)' }}</h5>
                <p class="text-xs text-slate-400 mt-1.5 max-w-sm mx-auto">Dokumen Kartu Keluarga berformat PDF dapat dilihat dan dibuka melalui tautan berikut.</p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                    <a id="modal-pdf-link" href="{{ ($hasDocKk && $isPdfDocKk) ? $docKkUrl : '#' }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-white bg-[#095b8c] hover:bg-[#074a73] px-4 py-2.5 rounded-xl shadow-md transition">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Dokumen PDF di Tab Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2 overflow-hidden mr-3">
                <i class="fa-solid fa-file-circle-check text-emerald-600 shrink-0"></i>
                <span class="text-xs text-slate-700 truncate font-medium" id="modal-file-info">{{ $hasDocKk ? basename($activeDocKk) : '-' }}</span>
            </div>
            <button type="button" id="btn-close-modal-kk-footer" class="text-xs font-bold bg-[#095b8c] hover:bg-[#074a73] text-white px-4 py-2 rounded-xl shadow-xs transition cursor-pointer">
                Tutup Pratinjau
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const CURRENT_USER_ID = '{{ Auth::id() ?? "guest" }}';
    const PROFILE_DRAFT_KEY = 'purwobinangun_warga_profile_draft_' + CURRENT_USER_ID;
    const PROFILE_KK_KEY = 'profile_doc_family_card_' + CURRENT_USER_ID;
    const DB_NAME = 'PurwobinangunFormDB';
    const STORE_NAME = 'draft_files';

    const familyContainer = document.getElementById('family-members-container');
    const emptyFamilyState = document.getElementById('empty-family-state');
    const btnAddFamily = document.getElementById('btn-add-family-member');
    const applicantKkInput = document.getElementById('family_card_no');
    const formProfileUpdate = document.getElementById('form-profile-update');

    const profileDraftFields = [
        'nik',
        'family_card_no',
        'name',
        'birth_place',
        'birth_date',
        'family_relationship',
        'phone',
        'email',
        'address',
        'rt',
        'rw'
    ];

    let memberCounter = 0;
    const initialMembers = @json($serverFamilyMembers ?? []);

    function updateEmptyState() {
        if (!familyContainer || !emptyFamilyState) return;
        const memberCards = familyContainer.querySelectorAll('.family-member-card');
        if (memberCards.length === 0) {
            emptyFamilyState.classList.remove('hidden');
        } else {
            emptyFamilyState.classList.add('hidden');
        }
    }

    function renumberMembers() {
        if (!familyContainer) return;
        const memberCards = familyContainer.querySelectorAll('.family-member-card');
        memberCards.forEach(function(card, idx) {
            const badge = card.querySelector('.member-badge');
            if (badge) {
                badge.innerHTML = '<i class="fa-solid fa-user text-[10px] mr-1"></i> Anggota Keluarga #' + (idx + 1);
            }
        });
        updateEmptyState();
    }

    function createMemberCard(data = {}) {
        if (!familyContainer) return;
        memberCounter++;
        const index = memberCounter;
        const currentApplicantKk = applicantKkInput ? applicantKkInput.value.trim() : '';
        const memberKk = data.family_card_no !== undefined ? data.family_card_no : currentApplicantKk;
        const memberNik = data.nik || '';
        const memberName = data.name || '';
        const memberBirthPlace = data.birth_place || '';
        const memberBirthDate = data.birth_date || '';
        const memberGender = data.gender || '';
        const memberRel = data.family_relationship || 'Anak';

        const card = document.createElement('div');
        card.className = 'family-member-card bg-slate-50/80 border border-slate-200 rounded-xl p-4 sm:p-5 space-y-3.5 shadow-2xs transition hover:border-[#095b8c]/40';
        card.dataset.index = index;

        card.innerHTML = `
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/80 gap-2">
                <span class="member-badge text-[11px] font-bold text-[#095b8c] bg-teal-50 border border-teal-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                    <i class="fa-solid fa-user text-[10px] mr-1"></i> Anggota Keluarga #${index}
                </span>
                <button type="button" class="btn-remove-member text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200 transition flex items-center gap-1 cursor-pointer shrink-0 whitespace-nowrap">
                    <i class="fa-solid fa-trash-can text-[11px]"></i> Hapus
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- No KK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 whitespace-nowrap">
                        No KK <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="family_members[${index}][family_card_no]" value="${memberKk}" maxlength="16" required placeholder="16 digit Nomor KK" class="member-kk-input w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] bg-white font-mono" style="padding: 0.625rem 0.875rem;">
                </div>

                <!-- NIK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 whitespace-nowrap">
                        NIK <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="family_members[${index}][nik]" value="${memberNik}" maxlength="16" required placeholder="16 digit NIK anggota" class="member-nik-input w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] bg-white font-mono" style="padding: 0.625rem 0.875rem;">
                </div>
            </div>

            <!-- Nama Lengkap Sesuai KK -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Nama Lengkap Sesuai KK <span class="text-rose-600">*</span>
                </label>
                <input type="text" name="family_members[${index}][name]" value="${memberName}" required placeholder="Nama lengkap sesuai KK" class="member-name-input w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] bg-white" style="padding: 0.625rem 0.875rem;">
            </div>

            <!-- Tempat Lahir, Tanggal Lahir, Jenis Kelamin, Posisi dalam Keluarga -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 whitespace-nowrap">
                        Tempat Lahir <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="family_members[${index}][birth_place]" value="${memberBirthPlace}" required placeholder="Kota/Kab. Lahir" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] bg-white" style="padding: 0.625rem 0.875rem;">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 whitespace-nowrap">
                        Tanggal Lahir <span class="text-rose-600">*</span>
                    </label>
                    <input type="date" name="family_members[${index}][birth_date]" value="${memberBirthDate}" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] bg-white" style="padding: 0.625rem 0.875rem;">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 whitespace-nowrap">
                        Jenis Kelamin <span class="text-rose-600">*</span>
                    </label>
                    <select name="family_members[${index}][gender]" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] bg-white" style="padding: 0.625rem 0.875rem;">
                        <option value="">-- Pilih --</option>
                        <option value="L" ${memberGender === 'L' ? 'selected' : ''}>Laki-laki</option>
                        <option value="P" ${memberGender === 'P' ? 'selected' : ''}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 whitespace-nowrap">
                        Posisi Keluarga <span class="text-rose-600">*</span>
                    </label>
                    <select name="family_members[${index}][family_relationship]" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#059cb8]/20 focus:border-[#059cb8] bg-white" style="padding: 0.625rem 0.875rem;">
                        <option value="Istri" ${memberRel === 'Istri' ? 'selected' : ''}>Istri</option>
                        <option value="Suami" ${memberRel === 'Suami' ? 'selected' : ''}>Suami</option>
                        <option value="Anak" ${memberRel === 'Anak' ? 'selected' : ''}>Anak</option>
                        <option value="Menantu" ${memberRel === 'Menantu' ? 'selected' : ''}>Menantu</option>
                        <option value="Cucu" ${memberRel === 'Cucu' ? 'selected' : ''}>Cucu</option>
                        <option value="Orang Tua" ${memberRel === 'Orang Tua' ? 'selected' : ''}>Orang Tua</option>
                        <option value="Mertua" ${memberRel === 'Mertua' ? 'selected' : ''}>Mertua</option>
                        <option value="Famili Lain" ${memberRel === 'Famili Lain' ? 'selected' : ''}>Famili Lain</option>
                        <option value="Lainnya" ${memberRel === 'Lainnya' ? 'selected' : ''}>Lainnya</option>
                    </select>
                </div>
            </div>
        `;

        card.querySelector('.btn-remove-member').addEventListener('click', function() {
            card.remove();
            renumberMembers();
            saveProfileDraft();
        });

        card.querySelectorAll('input, select').forEach(function(inp) {
            inp.addEventListener('input', saveProfileDraft);
            inp.addEventListener('change', saveProfileDraft);
        });

        familyContainer.appendChild(card);
        renumberMembers();
        return card;
    }

    if (btnAddFamily) {
        btnAddFamily.addEventListener('click', function() {
            const card = createMemberCard();
            const nameInput = card.querySelector('.member-name-input');
            if (nameInput) nameInput.focus();
            saveProfileDraft();
        });
    }

    if (applicantKkInput) {
        applicantKkInput.addEventListener('input', function() {
            const newKk = applicantKkInput.value.trim();
            const memberKks = familyContainer.querySelectorAll('.member-kk-input');
            memberKks.forEach(function(input) {
                if (!input.value || input.value.length < 16) {
                    input.value = newKk;
                }
            });
            saveProfileDraft();
        });
    }

    // ==========================================
    // IndexedDB Helper untuk Penyimpanan Berkas Draft KK
    // ==========================================
    function openDraftDB() {
        return new Promise((resolve, reject) => {
            if (!window.indexedDB) return reject(new Error('IndexedDB not supported'));
            const request = indexedDB.open(DB_NAME, 1);
            request.onupgradeneeded = function (e) {
                const db = e.target.result;
                if (!db.objectStoreNames.contains(STORE_NAME)) {
                    db.createObjectStore(STORE_NAME, { keyPath: 'id' });
                }
            };
            request.onsuccess = function (e) { resolve(e.target.result); };
            request.onerror = function (e) { reject(e.target.error); };
        });
    }

    async function saveFileToDB(key, file) {
        try {
            const db = await openDraftDB();
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            store.put({
                id: key,
                file: file,
                name: file.name,
                type: file.type,
                size: file.size,
                lastModified: file.lastModified
            });
            return new Promise((resolve, reject) => {
                tx.oncomplete = () => resolve();
                tx.onerror = () => reject(tx.error);
            });
        } catch (err) {
            console.warn('Gagal menyimpan file ke DB:', err);
        }
    }

    async function getFileFromDB(key) {
        try {
            const db = await openDraftDB();
            const tx = db.transaction(STORE_NAME, 'readonly');
            const store = tx.objectStore(STORE_NAME);
            const req = store.get(key);
            return new Promise((resolve) => {
                req.onsuccess = () => resolve(req.result);
                req.onerror = () => resolve(null);
            });
        } catch (err) {
            console.warn('Gagal membaca file dari DB:', err);
            return null;
        }
    }

    async function clearDraftFilesFromDB(prefix = '') {
        try {
            if (!window.indexedDB) return;
            const db = await openDraftDB();
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            if (!prefix) {
                store.clear();
            } else {
                const req = store.openCursor();
                req.onsuccess = function (e) {
                    const cursor = e.target.result;
                    if (cursor) {
                        if (String(cursor.key).startsWith(prefix)) {
                            cursor.delete();
                        }
                        cursor.continue();
                    }
                };
            }
        } catch (err) {
            console.warn('Gagal membersihkan draft DB:', err);
        }
    }

    async function restoreProfileDraftFile() {
        if (!docKkInput) return;
        const record = await getFileFromDB(PROFILE_KK_KEY);
        if (record && record.file) {
            try {
                const dt = new DataTransfer();
                const restoredFile = new File([record.file], record.name, {
                    type: record.type,
                    lastModified: record.lastModified || Date.now()
                });
                dt.items.add(restoredFile);
                docKkInput.files = dt.files;

                currentFileName = restoredFile.name;
                if (fileNameKk) fileNameKk.textContent = restoredFile.name;
                if (fileSizeKk) fileSizeKk.textContent = (restoredFile.size / 1024).toFixed(1) + ' KB (Draft tersimpan - Siap diunggah)';

                if (restoredFile.type === 'application/pdf' || restoredFile.name.toLowerCase().endsWith('.pdf')) {
                    currentFileIsPdf = true;
                    currentPreviewUrl = URL.createObjectURL(restoredFile);
                    if (modalPdfLink) modalPdfLink.href = currentPreviewUrl;
                    if (imgPreviewWrapKk) imgPreviewWrapKk.classList.add('hidden');
                    if (pdfPreviewWrapKk) pdfPreviewWrapKk.classList.remove('hidden');
                } else {
                    currentFileIsPdf = false;
                    currentPreviewUrl = URL.createObjectURL(restoredFile);
                    if (modalPreviewImg) {
                        modalPreviewImg.src = currentPreviewUrl;
                        modalPreviewImg.style.display = 'block';
                    }
                    if (imgPreviewWrapKk) imgPreviewWrapKk.classList.remove('hidden');
                    if (pdfPreviewWrapKk) pdfPreviewWrapKk.classList.add('hidden');
                }

                if (placeholderKk) placeholderKk.classList.add('hidden');
                if (previewBoxKk) previewBoxKk.classList.remove('hidden');
            } catch (e) {
                console.warn('Gagal memulihkan draft KK profil:', e);
            }
        }
    }

    // ==========================================
    // LocalStorage Draft Management
    // ==========================================
    function saveProfileDraft() {
        try {
            const draft = {};
            let hasContent = false;

            profileDraftFields.forEach(function(fieldId) {
                const el = document.getElementById(fieldId);
                if (el) {
                    draft[fieldId] = el.value || '';
                    if (el.value && el.value.trim() !== '') {
                        hasContent = true;
                    }
                }
            });

            const checkedGender = document.querySelector('input[name="gender"]:checked');
            if (checkedGender) {
                draft['gender'] = checkedGender.value;
            }

            // Simpan anggota keluarga
            const members = [];
            const memberCards = familyContainer.querySelectorAll('.family-member-card');
            memberCards.forEach(function(card) {
                const kkInp = card.querySelector('input[name*="[family_card_no]"]');
                const nikInp = card.querySelector('input[name*="[nik]"]');
                const nameInp = card.querySelector('input[name*="[name]"]');
                const birthPlaceInp = card.querySelector('input[name*="[birth_place]"]');
                const birthDateInp = card.querySelector('input[name*="[birth_date]"]');
                const genderInp = card.querySelector('select[name*="[gender]"]');
                const relInp = card.querySelector('select[name*="[family_relationship]"]');

                const mData = {
                    family_card_no: kkInp ? kkInp.value : '',
                    nik: nikInp ? nikInp.value : '',
                    name: nameInp ? nameInp.value : '',
                    birth_place: birthPlaceInp ? birthPlaceInp.value : '',
                    birth_date: birthDateInp ? birthDateInp.value : '',
                    gender: genderInp ? genderInp.value : '',
                    family_relationship: relInp ? relInp.value : ''
                };

                if (mData.name || mData.nik) {
                    hasContent = true;
                }
                members.push(mData);
            });

            draft.family_members = members;

            if (hasContent || members.length > 0) {
                localStorage.setItem(PROFILE_DRAFT_KEY, JSON.stringify(draft));
            } else {
                localStorage.removeItem(PROFILE_DRAFT_KEY);
            }
        } catch (e) {
            console.warn('Gagal menyimpan draft profil ke localStorage:', e);
        }
    }

    function loadProfileDraft() {
        let restoredMembers = false;
        try {
            const rawDraft = localStorage.getItem(PROFILE_DRAFT_KEY);
            if (rawDraft) {
                const draft = JSON.parse(rawDraft);
                if (draft && typeof draft === 'object') {
                    profileDraftFields.forEach(function(fieldId) {
                        const el = document.getElementById(fieldId);
                        if (el && draft[fieldId] !== undefined && draft[fieldId] !== null) {
                            el.value = draft[fieldId];
                        }
                    });

                    if (draft.gender) {
                        const genderRadio = document.querySelector(`input[name="gender"][value="${draft.gender}"]`);
                        if (genderRadio) genderRadio.checked = true;
                    }

                    if (Array.isArray(draft.family_members)) {
                        familyContainer.innerHTML = '';
                        memberCounter = 0;
                        draft.family_members.forEach(function(m) {
                            createMemberCard(m);
                        });
                        restoredMembers = true;
                    }
                }
            }
        } catch (e) {
            console.warn('Gagal memulihkan draft profil dari localStorage:', e);
        }

        // Jika tidak dipulihkan dari draft localStorage, muat initial members dari server
        if (!restoredMembers) {
            if (initialMembers && initialMembers.length > 0) {
                initialMembers.forEach(function(m) {
                    createMemberCard(m);
                });
            } else {
                updateEmptyState();
            }
        } else {
            updateEmptyState();
        }

        // Pulihkan berkas fisik KK dari IndexedDB
        restoreProfileDraftFile();
    }

    // Pasang listeners form input untuk auto-save
    profileDraftFields.forEach(function(fieldId) {
        const el = document.getElementById(fieldId);
        if (el) {
            el.addEventListener('input', saveProfileDraft);
            el.addEventListener('change', saveProfileDraft);
        }
    });

    document.querySelectorAll('input[name="gender"]').forEach(function(radio) {
        radio.addEventListener('change', saveProfileDraft);
    });

    // Modal Preview & Interactive Pan/Zoom Dokumen KK
    const docKkInput = document.getElementById('doc_family_card');
    const placeholderKk = document.getElementById('placeholder-kk');
    const previewBoxKk = document.getElementById('preview-box-kk');
    const imgPreviewWrapKk = document.getElementById('img-preview-wrap-kk');
    const pdfPreviewWrapKk = document.getElementById('pdf-preview-wrap-kk');
    const fileNameKk = document.getElementById('file-name-kk');
    const fileSizeKk = document.getElementById('file-size-kk');
    const btnPreviewLightbox = document.getElementById('btn-preview-lightbox');

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

    let currentFileIsPdf = {{ ($hasDocKk && $isPdfDocKk) ? 'true' : 'false' }};
    let currentPreviewUrl = "{{ ($hasDocKk && !$isPdfDocKk) ? $docKkUrl : '' }}";
    let currentFileName = "{{ ($hasDocKk) ? basename($activeDocKk) : '' }}";

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
            if (modalPdfLink && currentPreviewUrl) modalPdfLink.href = currentPreviewUrl;
        } else if (currentPreviewUrl) {
            if (modalImgContainer) modalImgContainer.classList.remove('hidden');
            if (modalZoomControls) modalZoomControls.classList.remove('hidden');
            if (modalPdfContainer) modalPdfContainer.classList.add('hidden');
            if (modalPreviewImg) {
                modalPreviewImg.src = currentPreviewUrl;
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

    if (btnPreviewLightbox) btnPreviewLightbox.addEventListener('click', window.openDocModal);
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

    if (docKkInput) {
        docKkInput.addEventListener('change', async function() {
            if (!this.files || this.files.length === 0) return;
            const file = this.files[0];

            if (file.size > 3 * 1024 * 1024) {
                alert('Ukuran berkas melebihi 3MB. Silakan pilih file yang lebih kecil.');
                this.value = '';
                return;
            }

            const ext = file.name.split('.').pop().toLowerCase();
            if (!['jpg', 'jpeg', 'png', 'pdf'].includes(ext)) {
                alert('Format berkas harus JPG, JPEG, PNG, atau PDF.');
                this.value = '';
                return;
            }

            currentFileName = file.name;
            if (fileNameKk) fileNameKk.textContent = file.name;
            if (fileSizeKk) fileSizeKk.textContent = (file.size / 1024).toFixed(1) + ' KB (Siap diunggah - Klik untuk Pratinjau)';

            if (file.type === 'application/pdf' || ext === 'pdf') {
                currentFileIsPdf = true;
                currentPreviewUrl = URL.createObjectURL(file);
                if (modalPdfLink) modalPdfLink.href = currentPreviewUrl;
                if (imgPreviewWrapKk) imgPreviewWrapKk.classList.add('hidden');
                if (pdfPreviewWrapKk) pdfPreviewWrapKk.classList.remove('hidden');
            } else {
                currentFileIsPdf = false;
                currentPreviewUrl = URL.createObjectURL(file);
                if (modalPreviewImg) {
                    modalPreviewImg.src = currentPreviewUrl;
                    modalPreviewImg.style.display = 'block';
                }
                if (imgPreviewWrapKk) imgPreviewWrapKk.classList.remove('hidden');
                if (pdfPreviewWrapKk) pdfPreviewWrapKk.classList.add('hidden');
            }

            if (placeholderKk) placeholderKk.classList.add('hidden');
            if (previewBoxKk) previewBoxKk.classList.remove('hidden');

            await saveFileToDB(PROFILE_KK_KEY, file);
        });
    }

    // Reset draft ketika form update profil disubmit
    if (formProfileUpdate) {
        formProfileUpdate.addEventListener('submit', function () {
            setTimeout(function () {
                try {
                    localStorage.removeItem(PROFILE_DRAFT_KEY);
                    clearDraftFilesFromDB('profile_doc_family_card_');
                } catch (e) {}
            }, 500);
        });
    }

    // Restore draft form & file KK pada saat load
    loadProfileDraft();

    // Periksa status dismiss notifikasi banner perubahan profil
    @if($latestRequest && $latestRequest->isRejected())
        try {
            if (localStorage.getItem('purwobinangun_dismissed_profile_rejected_{{ Auth::id() }}_{{ $latestRequest->id }}') === 'true') {
                const rejBanner = document.getElementById('profile-rejected-banner-{{ $latestRequest->id }}');
                if (rejBanner) rejBanner.remove();
            }
        } catch (e) {}
    @elseif($latestRequest && $latestRequest->isApproved())
        try {
            if (localStorage.getItem('purwobinangun_dismissed_profile_approved_{{ Auth::id() }}_{{ $latestRequest->id }}') === 'true') {
                const appBanner = document.getElementById('profile-approved-banner-{{ $latestRequest->id }}');
                if (appBanner) appBanner.remove();
            }
        } catch (e) {}
    @endif
});

// Fungsi penutup notifikasi banner profil secara halus
function dismissProfileNotification(bannerId, storageKey) {
    const banner = document.getElementById(bannerId);
    if (!banner) return;

    banner.style.opacity = '0';
    banner.style.transform = 'translateY(-6px)';
    setTimeout(() => {
        if (banner) banner.remove();
    }, 250);

    if (storageKey) {
        try {
            localStorage.setItem(storageKey, 'true');
        } catch (e) {}
    }
}
</script>
@endsection
