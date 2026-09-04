@extends('layouts.app')

@section('title', 'Pendaftaran Akun Warga Baru')

@section('content')
<div class="max-w-3xl mx-auto py-4">

    <!-- Card Registrasi Warga -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Header Banner Toska -->
        <div class="bg-gradient-to-r from-[#095b8c] to-[#059cb8] text-white p-6 sm:p-7 text-center relative overflow-hidden">
            <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur border border-white/30 flex items-center justify-center mx-auto mb-3 shadow-inner">
                <i class="fa-solid fa-user-plus text-2xl text-amber-300"></i>
            </div>
            <span class="text-[11px] font-bold uppercase tracking-widest text-teal-200 bg-black/20 px-3 py-1 rounded-full">
                Formulir Pendaftaran Akun Warga
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold mt-2 tracking-tight">DAFTAR AKUN WARGA</h2>
            <p class="text-xs text-teal-100 max-w-lg mx-auto mt-1 leading-relaxed">
                Daftarkan akun warga untuk mengajukan Akte Kelahiran & Kematian secara online. Data NIK dan KK akan diverifikasi oleh petugas kelurahan.
            </p>
        </div>

        <div class="p-6 sm:p-8 space-y-6">

            <!-- Banner Panduan Verifikasi -->
            <div class="p-4 bg-teal-50 border border-teal-200 rounded-xl flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-[#095b8c] text-lg mt-0.5 shrink-0"></i>
                <div class="text-xs text-slate-700 leading-relaxed">
                    <p class="font-bold text-[#095b8c] mb-1">Ketentuan Pendaftaran & Verifikasi:</p>
                    <ul class="list-disc pl-4 space-y-1 text-slate-600">
                        <li>Pastikan <strong>NIK</strong> dan <strong>Nomor KK</strong> terdiri dari 16 digit angka yang valid sesuai dokumen resmi.</li>
                        <li>Nomor Kartu Keluarga (KK) akan digunakan untuk mengelompokkan dan mengakses seluruh data pengajuan akte keluarga Anda.</li>
                        <li>Setelah pendaftaran berhasil, akun akan berstatus <strong>Menunggu Verifikasi</strong> dan akan diperiksa oleh petugas kelurahan sebelum dapat digunakan.</li>
                    </ul>
                </div>
            </div>

            @if(isset($prefill) && $prefill)
                <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl">
                    <p class="text-xs font-bold text-amber-900">Mode Perbaikan Data (Pendaftaran Ulang)</p>
                    <p class="text-xs text-amber-800 mt-1">
                        Silakan perbaiki data yang sebelumnya belum sesuai. Catatan penolakan sebelumnya: <em>"{{ $prefill->rejection_reason }}"</em>
                    </p>
                </div>
            @endif

            @php
                $hasPrefillKk = isset($prefill) && $prefill && !empty($prefill->doc_family_card);
                $isPrefillPdf = $hasPrefillKk && \Illuminate\Support\Str::endsWith(strtolower($prefill->doc_family_card), '.pdf');
            @endphp

            <!-- Form Registrasi -->
            <form action="{{ route('warga.register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6" autocomplete="off" id="form-register">
                @csrf

                <!-- BAGIAN 1: IDENTITAS PENGAJU -->
                <div class="space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#095b8c] pb-2 border-b border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-user-check"></i> 1. IDENTITAS PENGAJU
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nomor KK -->
                        <div>
                            <label for="family_card_no" class="block text-xs font-bold text-slate-700 mb-1">
                                No. KK <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="family_card_no" id="family_card_no" value="{{ old('family_card_no', $prefill->family_card_no ?? '') }}" maxlength="16" required placeholder="16 digit Nomor KK" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('family_card_no') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                            @error('family_card_no')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block text-xs font-bold text-slate-700 mb-1">
                                NIK <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik', $prefill->nik ?? '') }}" maxlength="16" required placeholder="16 digit NIK sesuai KK/KTP" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('nik') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                            @error('nik')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Upload Dokumen KK Terbaru -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5 flex-wrap gap-1">
                            <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-file-shield text-[#095b8c]"></i> Dokumen Kartu Keluarga (KK) Terbaru
                            </label>
                            @if($hasPrefillKk)
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check"></i> Berkas KK Tersimpan
                                </span>
                            @else
                                <span class="text-[10px] font-medium text-slate-400">
                                    Foto / Scan KK Asli (JPG, PNG, PDF maks. 3MB)
                                </span>
                            @endif
                        </div>

                        <input type="file" id="doc_family_card" name="doc_family_card" accept=".jpg,.jpeg,.png,.pdf" class="hidden">

                        <!-- Placeholder Box (Belum Ada Berkas) -->
                        <label for="doc_family_card" id="placeholder-kk" class="cursor-pointer flex flex-col items-center justify-center text-center bg-slate-50 hover:bg-teal-50/50 text-[#095b8c] border-2 border-dashed {{ $errors->has('doc_family_card') ? 'border-rose-400 bg-rose-50/20' : 'border-teal-300 hover:border-[#095b8c]' }} rounded-xl w-full p-4 transition shadow-2xs {{ $hasPrefillKk ? 'hidden' : '' }}" style="min-height: 125px;">
                            <div class="w-10 h-10 rounded-full bg-teal-50 text-[#095b8c] flex items-center justify-center mb-2 border border-teal-200 shadow-xs">
                                <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                            </div>
                            <span class="text-xs font-bold block">Pilih / Unggah Berkas Dokumen KK</span>
                            <span class="text-[11px] text-slate-400 mt-0.5">Klik di sini untuk memilih foto atau scan Kartu Keluarga terbaru</span>
                        </label>

                        <!-- Box Preview Dokumen KK -->
                        <div id="preview-box-kk" class="{{ $hasPrefillKk ? '' : 'hidden' }} bg-white border border-teal-200 rounded-xl p-3 shadow-xs w-full space-y-2.5">
                            
                            <!-- Thumbnail Gambar -->
                            <div id="img-preview-wrap-kk" class="w-full bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group {{ ($hasPrefillKk && !$isPrefillPdf) ? '' : 'hidden' }}" style="height: 160px; position: relative;">
                                <img id="img-preview-kk" src="{{ ($hasPrefillKk && !$isPrefillPdf) ? asset('storage/' . $prefill->doc_family_card) : '' }}" alt="Preview Kartu Keluarga" class="w-full h-full object-contain object-center block bg-slate-900/5">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                    <button type="button" id="btn-view-modal-kk" class="bg-white text-slate-800 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm hover:bg-slate-100 cursor-pointer flex items-center gap-1.5 transition">
                                        <i class="fa-solid fa-up-right-and-down-left-from-center text-[11px]"></i> Lihat Pratinjau Penuh
                                    </button>
                                    <label for="doc_family_card" class="bg-[#095b8c] text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm hover:bg-[#074a73] cursor-pointer flex items-center gap-1.5 transition">
                                        <i class="fa-solid fa-arrow-rotate-right text-[11px]"></i> Ganti Berkas
                                    </label>
                                </div>
                            </div>

                            <!-- Thumbnail PDF -->
                            <div id="pdf-preview-wrap-kk" class="w-full bg-rose-50 rounded-lg p-4 flex flex-col items-center justify-center border border-rose-200 text-rose-700 {{ ($hasPrefillKk && $isPrefillPdf) ? '' : 'hidden' }}">
                                <i class="fa-solid fa-file-pdf text-3xl mb-1 text-rose-600"></i>
                                <span class="text-xs font-bold uppercase tracking-wider">Dokumen Kartu Keluarga (PDF)</span>
                                <span class="text-[11px] text-rose-600/80 mt-0.5">Berkas PDF berhasil dipilih dan siap diproses</span>
                            </div>

                            <!-- Action & Info Bar -->
                            <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100 flex-wrap">
                                <div class="flex items-center gap-2.5 truncate min-w-0 flex-1">
                                    <div class="w-8 h-8 rounded-lg bg-teal-50 text-[#095b8c] flex items-center justify-center shrink-0 border border-teal-200">
                                        <i class="fa-solid fa-file-shield text-sm"></i>
                                    </div>
                                    <div class="truncate">
                                        <p id="file-name-kk" class="text-xs font-bold text-slate-800 truncate">
                                            {{ $hasPrefillKk ? basename($prefill->doc_family_card) : 'Dokumen KK Terpilih' }}
                                        </p>
                                        <p id="file-size-kk" class="text-[10px] text-slate-500">
                                            {{ $hasPrefillKk ? 'Berkas tersimpan di sistem' : 'Berkas siap diunggah' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <button type="button" id="btn-preview-lightbox" class="text-xs font-bold text-[#095b8c] hover:text-[#074a73] bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg border border-teal-200 transition flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i> Lihat Preview
                                    </button>
                                    <label for="doc_family_card" class="text-xs font-bold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg border border-slate-300 transition flex items-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-pen-to-square"></i> Ganti
                                    </label>
                                    <button type="button" id="btn-clear-kk" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-2.5 py-1.5 rounded-lg border border-rose-200 transition flex items-center gap-1 cursor-pointer">
                                        <i class="fa-solid fa-xmark"></i> Hapus
                                    </button>
                                </div>
                            </div>

                        </div>

                        @error('doc_family_card')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Lengkap Sesuai KK -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                            Nama lengkap Sesuai KK <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $prefill->name ?? '') }}" required placeholder="Nama lengkap sesuai KK tanpa singkatan" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                        @error('name')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir, Tanggal Lahir, Jenis Kelamin, Posisi dalam Keluarga -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="birth_place" class="block text-xs font-bold text-slate-700 mb-1">
                                Tempat Lahir <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $prefill->birth_place ?? '') }}" required placeholder="Kota/Kabupaten Lahir" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('birth_place') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                            @error('birth_place')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birth_date" class="block text-xs font-bold text-slate-700 mb-1">
                                Tanggal Lahir <span class="text-rose-600">*</span>
                            </label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', isset($prefill->birth_date) ? $prefill->birth_date->format('Y-m-d') : '') }}" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('birth_date') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20">
                            @error('birth_date')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="block text-xs font-bold text-slate-700 mb-1">
                                Jenis Kelamin <span class="text-rose-600">*</span>
                            </label>
                            <select name="gender" id="gender" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('gender') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('gender', $prefill->gender ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $prefill->gender ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="family_relationship" class="block text-xs font-bold text-slate-700 mb-1">
                                Posisi dalam Keluarga <span class="text-rose-600">*</span>
                            </label>
                            @php
                                $currentPos = old('family_relationship', $prefill->family_relationship ?? 'Kepala Keluarga');
                            @endphp
                            <select name="family_relationship" id="family_relationship" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('family_relationship') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
                                <option value="Kepala Keluarga" {{ $currentPos === 'Kepala Keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                <option value="Suami" {{ $currentPos === 'Suami' ? 'selected' : '' }}>Suami</option>
                                <option value="Istri" {{ $currentPos === 'Istri' ? 'selected' : '' }}>Istri</option>
                                <option value="Anak" {{ $currentPos === 'Anak' ? 'selected' : '' }}>Anak</option>
                                <option value="Menantu" {{ $currentPos === 'Menantu' ? 'selected' : '' }}>Menantu</option>
                                <option value="Cucu" {{ $currentPos === 'Cucu' ? 'selected' : '' }}>Cucu</option>
                                <option value="Orang Tua" {{ $currentPos === 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                <option value="Mertua" {{ $currentPos === 'Mertua' ? 'selected' : '' }}>Mertua</option>
                                <option value="Famili Lain" {{ $currentPos === 'Famili Lain' ? 'selected' : '' }}>Famili Lain</option>
                                <option value="Lainnya" {{ $currentPos === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('family_relationship')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 2: ANGGOTA KELUARGA -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200 flex-wrap gap-2">
                        <div>
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#095b8c] flex items-center gap-2">
                                <i class="fa-solid fa-people-roof"></i> 2. ANGGOTA KELUARGA
                            </h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Tambahkan data anggota keluarga lain yang tercantum di dalam satu Kartu Keluarga (KK).
                            </p>
                        </div>
                        <button type="button" id="btn-add-family-member" class="text-xs font-bold bg-[#095b8c] hover:bg-[#074a73] text-white px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                            <i class="fa-solid fa-user-plus text-xs"></i> Tambah Anggota Keluarga
                        </button>
                    </div>

                    <!-- Empty State Placeholder -->
                    <div id="empty-family-state" class="p-5 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-center text-slate-500 text-xs">
                        <i class="fa-solid fa-users text-slate-400 text-2xl mb-1.5 block"></i>
                        <p class="font-medium">Belum ada anggota keluarga tambahan.</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Klik tombol <strong>Tambah Anggota Keluarga</strong> di atas jika ingin menambahkan anggota keluarga lain dalam KK.</p>
                    </div>

                    <!-- Container Kartu Anggota Keluarga -->
                    <div id="family-members-container" class="space-y-4"></div>
                </div>

                <!-- BAGIAN 3: ALAMAT & KONTAK -->
                <div class="space-y-4 pt-2">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#095b8c] pb-2 border-b border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot"></i> 3. ALAMAT & KONTAK
                    </h3>

                    <div>
                        <label for="address" class="block text-xs font-bold text-slate-700 mb-1">
                            Alamat Lengkap/Padukuhan <span class="text-rose-600">*</span>
                        </label>
                        <textarea name="address" id="address" rows="2" required placeholder="Contoh: Kadilobo, Purwobinangun, Pakem, Sleman" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('address') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">{{ old('address', $prefill->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <label for="rt" class="block text-xs font-bold text-slate-700 mb-1">
                                RT <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="rt" id="rt" value="{{ old('rt', $prefill->rt ?? '') }}" maxlength="5" required placeholder="01" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('rt') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 text-center">
                            @error('rt')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rw" class="block text-xs font-bold text-slate-700 mb-1">
                                RW <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="rw" id="rw" value="{{ old('rw', $prefill->rw ?? '') }}" maxlength="5" required placeholder="02" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('rw') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 text-center">
                            @error('rw')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">
                                No HP/Whatsapp <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $prefill->phone ?? '') }}" required placeholder="081234567890" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                            @error('phone')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                            Email <span class="text-rose-600">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $prefill->email ?? '') }}" required autocomplete="email" placeholder="email@contoh.com" class="w-full text-xs px-3.5 py-2.5 rounded-lg border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs">
                        <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info text-[#095b8c]"></i> Digunakan untuk menerima notifikasi status pengajuan berkas secara otomatis.
                        </p>
                        @error('email')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- BAGIAN 4: KATA SANDI AKUN -->
                <div class="space-y-4 pt-2">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#095b8c] pb-2 border-b border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i> 4. KATA SANDI AKUN
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                                Kata sandi <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative" style="position: relative;">
                                <input type="password" name="password" id="reg_password" required minlength="6" autocomplete="new-password" placeholder="Minimal 6 karakter" class="w-full text-xs rounded-lg border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs" style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.65rem; padding-bottom: 0.65rem;">
                                <button type="button" onclick="toggleRegPassword('reg_password', 'reg-pwd-icon-1')" class="flex items-center justify-center text-slate-400 hover:text-slate-700 cursor-pointer focus:outline-none" style="position: absolute; right: 0.65rem; top: 50%; transform: translateY(-50%); width: 2rem; height: 2rem;">
                                    <i id="reg-pwd-icon-1" class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                                Konfirmasi kata sandi <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative" style="position: relative;">
                                <input type="password" name="password_confirmation" id="reg_password_confirmation" required minlength="6" autocomplete="new-password" placeholder="Ketik ulang kata sandi" class="w-full text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 placeholder:text-slate-400 placeholder:text-xs" style="padding-left: 1rem; padding-right: 2.75rem; padding-top: 0.65rem; padding-bottom: 0.65rem;">
                                <button type="button" onclick="toggleRegPassword('reg_password_confirmation', 'reg-pwd-icon-2')" class="flex items-center justify-center text-slate-400 hover:text-slate-700 cursor-pointer focus:outline-none" style="position: absolute; right: 0.65rem; top: 50%; transform: translateY(-50%); width: 2rem; height: 2rem;">
                                    <i id="reg-pwd-icon-2" class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#095b8c] hover:bg-[#074a73] text-white font-extrabold text-sm py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center cursor-pointer">
                        Kirim Pendaftaran Akun Warga
                    </button>
                </div>

            </form>

            <!-- Link to Login -->
            <div class="pt-4 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-600">
                    Sudah pernah mendaftar?
                    <a href="{{ route('warga.login') }}" class="font-bold text-[#095b8c] hover:underline ml-1">
                        Masuk ke Akun Warga &rarr;
                    </a>
                </p>
            </div>

        </div>

    </div>

</div>

<!-- MODAL PREVIEW DOKUMEN KK -->
<div id="modal-preview-kk" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-2xl w-full overflow-hidden my-auto flex flex-col max-h-[90vh]">
        <!-- Header Modal -->
        <div class="bg-gradient-to-r from-[#095b8c] to-[#059cb8] text-white px-5 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center text-teal-200">
                    <i class="fa-solid fa-file-shield text-base"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold">Pratinjau Dokumen Kartu Keluarga (KK)</h4>
                    <p class="text-[11px] text-teal-100" id="modal-preview-subtitle">Pratinjau dokumen fisik KK</p>
                </div>
            </div>
            <button type="button" id="btn-close-modal-kk" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-teal-100 hover:text-white flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="p-4 sm:p-6 overflow-y-auto flex-1 flex items-center justify-center bg-slate-100 min-h-[260px]">
            <div id="modal-img-container" class="w-full flex items-center justify-center">
                <img id="modal-preview-img" src="{{ ($hasPrefillKk && !$isPrefillPdf) ? asset('storage/' . $prefill->doc_family_card) : '' }}" alt="Pratinjau Dokumen KK" class="max-h-[65vh] max-w-full rounded-lg shadow-md object-contain border border-slate-200 bg-white">
            </div>
            <div id="modal-pdf-container" class="hidden text-center py-8">
                <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-file-pdf text-3xl"></i>
                </div>
                <h5 class="text-sm font-bold text-slate-800" id="modal-pdf-name">{{ $hasPrefillKk ? basename($prefill->doc_family_card) : 'Dokumen Kartu Keluarga (PDF)' }}</h5>
                <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">Dokumen berformat PDF tidak dapat ditampilkan langsung di sini, namun berkas telah berhasil dipilih dan siap diunggah.</p>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
            <span class="text-xs text-slate-600 truncate font-medium" id="modal-file-info">{{ $hasPrefillKk ? basename($prefill->doc_family_card) : '-' }}</span>
            <button type="button" id="btn-close-modal-kk-footer" class="text-xs font-bold bg-[#095b8c] hover:bg-[#074a73] text-white px-4 py-2 rounded-lg transition cursor-pointer">
                Tutup Pratinjau
            </button>
        </div>
    </div>
</div>

<!-- Data awal anggota keluarga (dari old input atau prefill) -->
@php
    $serverFamilyMembers = old('family_members', []);
    if (empty($serverFamilyMembers) && isset($prefill) && $prefill && $prefill->familyMembers && $prefill->familyMembers->isNotEmpty()) {
        $serverFamilyMembers = $prefill->familyMembers->map(function($m) {
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
@endphp

<script>
function toggleRegPassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const REGISTER_DRAFT_KEY = 'purwobinangun_warga_register_draft';
    const draftFields = [
        'family_card_no',
        'nik',
        'name',
        'birth_place',
        'birth_date',
        'gender',
        'family_relationship',
        'address',
        'rt',
        'rw',
        'phone',
        'email'
    ];

    const familyContainer = document.getElementById('family-members-container');
    const emptyFamilyState = document.getElementById('empty-family-state');
    const btnAddFamily = document.getElementById('btn-add-family-member');
    const applicantKkInput = document.getElementById('family_card_no');

    let memberCounter = 0;

    const initialMembers = @json($serverFamilyMembers ?? []);

    function updateEmptyState() {
        const memberCards = familyContainer.querySelectorAll('.family-member-card');
        if (memberCards.length === 0) {
            emptyFamilyState.classList.remove('hidden');
        } else {
            emptyFamilyState.classList.add('hidden');
        }
    }

    function renumberMembers() {
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
        card.className = 'family-member-card bg-slate-50/70 border border-slate-200 rounded-xl p-4 sm:p-5 space-y-3.5 shadow-xs transition hover:border-[#095b8c]/40';
        card.dataset.index = index;

        card.innerHTML = `
            <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                <span class="member-badge text-[11px] font-bold text-[#095b8c] bg-teal-50 border border-teal-200 px-2.5 py-0.5 rounded-full">
                    <i class="fa-solid fa-user text-[10px] mr-1"></i> Anggota Keluarga #${index}
                </span>
                <button type="button" class="btn-remove-member text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200 transition flex items-center gap-1 cursor-pointer">
                    <i class="fa-solid fa-trash-can text-[11px]"></i> Hapus
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- No KK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        No KK <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="family_members[${index}][family_card_no]" value="${memberKk}" maxlength="16" required placeholder="16 digit Nomor KK" class="member-kk-input w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
                </div>

                <!-- NIK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        NIK <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="family_members[${index}][nik]" value="${memberNik}" maxlength="16" required placeholder="16 digit NIK anggota" class="member-nik-input w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
                </div>
            </div>

            <!-- Nama Lengkap Sesuai KK -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Nama lengkap Sesuai KK <span class="text-rose-600">*</span>
                </label>
                <input type="text" name="family_members[${index}][name]" value="${memberName}" required placeholder="Nama lengkap sesuai KK" class="member-name-input w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
            </div>

            <!-- Tempat Lahir, Tanggal Lahir, Jenis Kelamin, Posisi dalam Keluarga -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Tempat Lahir <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="family_members[${index}][birth_place]" value="${memberBirthPlace}" required placeholder="Kota/Kab. Lahir" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Tanggal Lahir <span class="text-rose-600">*</span>
                    </label>
                    <input type="date" name="family_members[${index}][birth_date]" value="${memberBirthDate}" required class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Jenis Kelamin <span class="text-rose-600">*</span>
                    </label>
                    <select name="family_members[${index}][gender]" required class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
                        <option value="">-- Pilih --</option>
                        <option value="L" ${memberGender === 'L' ? 'selected' : ''}>Laki-laki</option>
                        <option value="P" ${memberGender === 'P' ? 'selected' : ''}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Posisi dalam Keluarga <span class="text-rose-600">*</span>
                    </label>
                    <select name="family_members[${index}][family_relationship]" required class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#095b8c]/20 bg-white">
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

        // Pasang event listener Hapus
        card.querySelector('.btn-remove-member').addEventListener('click', function() {
            card.remove();
            renumberMembers();
            saveRegisterDraft();
        });

        // Pasang auto-save on input
        card.querySelectorAll('input, select').forEach(function(inp) {
            inp.addEventListener('input', saveRegisterDraft);
            inp.addEventListener('change', saveRegisterDraft);
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
            saveRegisterDraft();
        });
    }

    // Auto update member No KK jika No KK Pengaju diubah
    if (applicantKkInput) {
        applicantKkInput.addEventListener('input', function() {
            const newKk = applicantKkInput.value.trim();
            const memberKks = familyContainer.querySelectorAll('.member-kk-input');
            memberKks.forEach(function(input) {
                if (!input.value || input.value.length < 16) {
                    input.value = newKk;
                }
            });
        });
    }

    function loadRegisterDraft() {
        try {
            const rawDraft = localStorage.getItem(REGISTER_DRAFT_KEY);
            if (!rawDraft) return;

            const draft = JSON.parse(rawDraft);
            if (!draft || typeof draft !== 'object') return;

            draftFields.forEach(function(fieldId) {
                const el = document.getElementById(fieldId);
                if (el && (!el.value || el.value.trim() === '')) {
                    if (draft[fieldId] !== undefined && draft[fieldId] !== null) {
                        el.value = draft[fieldId];
                    }
                }
            });

            // Pulihkan anggota keluarga dari draft jika belum ada dari server
            const memberCards = familyContainer.querySelectorAll('.family-member-card');
            if (memberCards.length === 0 && Array.isArray(draft.family_members) && draft.family_members.length > 0) {
                draft.family_members.forEach(function(m) {
                    createMemberCard(m);
                });
            }
        } catch (e) {
            console.warn('Gagal memulihkan draft pendaftaran dari localStorage:', e);
        }
    }

    function saveRegisterDraft() {
        try {
            const draft = {};
            let hasContent = false;

            draftFields.forEach(function(fieldId) {
                const el = document.getElementById(fieldId);
                if (el) {
                    draft[fieldId] = el.value || '';
                    if (el.value && el.value.trim() !== '') {
                        hasContent = true;
                    }
                }
            });

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
                localStorage.setItem(REGISTER_DRAFT_KEY, JSON.stringify(draft));
            } else {
                localStorage.removeItem(REGISTER_DRAFT_KEY);
            }
        } catch (e) {
            console.warn('Gagal menyimpan draft pendaftaran ke localStorage:', e);
        }
    }

    // Pasang listener field pengaju
    draftFields.forEach(function(fieldId) {
        const el = document.getElementById(fieldId);
        if (el) {
            el.addEventListener('input', saveRegisterDraft);
            el.addEventListener('change', saveRegisterDraft);
        }
    });

    // Inisialisasi anggota keluarga dari server jika ada
    if (initialMembers && initialMembers.length > 0) {
        initialMembers.forEach(function(m) {
            createMemberCard(m);
        });
    }

    // Handle Upload Dokumen KK Terbaru & Preview Modal
    const docKkInput = document.getElementById('doc_family_card');
    const placeholderKk = document.getElementById('placeholder-kk');
    const previewBoxKk = document.getElementById('preview-box-kk');
    const imgPreviewWrapKk = document.getElementById('img-preview-wrap-kk');
    const imgPreviewKk = document.getElementById('img-preview-kk');
    const pdfPreviewWrapKk = document.getElementById('pdf-preview-wrap-kk');
    const fileNameKk = document.getElementById('file-name-kk');
    const fileSizeKk = document.getElementById('file-size-kk');
    const btnClearKk = document.getElementById('btn-clear-kk');
    const btnPreviewLightbox = document.getElementById('btn-preview-lightbox');
    const btnViewModalKk = document.getElementById('btn-view-modal-kk');

    const modalPreviewKk = document.getElementById('modal-preview-kk');
    const modalPreviewImg = document.getElementById('modal-preview-img');
    const modalImgContainer = document.getElementById('modal-img-container');
    const modalPdfContainer = document.getElementById('modal-pdf-container');
    const modalPdfName = document.getElementById('modal-pdf-name');
    const modalFileInfo = document.getElementById('modal-file-info');
    const modalPreviewSubtitle = document.getElementById('modal-preview-subtitle');
    const btnCloseModalKk = document.getElementById('btn-close-modal-kk');
    const btnCloseModalKkFooter = document.getElementById('btn-close-modal-kk-footer');

    let currentFileIsPdf = {{ ($hasPrefillKk && $isPrefillPdf) ? 'true' : 'false' }};
    let currentPreviewUrl = "{{ ($hasPrefillKk && !$isPrefillPdf) ? asset('storage/' . $prefill->doc_family_card) : '' }}";
    let currentFileName = "{{ ($hasPrefillKk) ? basename($prefill->doc_family_card) : '' }}";

    function openDocModal() {
        if (!modalPreviewKk) return;
        if (currentFileIsPdf) {
            if (modalImgContainer) modalImgContainer.classList.add('hidden');
            if (modalPdfContainer) modalPdfContainer.classList.remove('hidden');
            if (modalPdfName) modalPdfName.textContent = currentFileName || 'Dokumen Kartu Keluarga (PDF)';
            if (modalPreviewSubtitle) modalPreviewSubtitle.textContent = 'Dokumen berformat PDF';
            if (modalFileInfo) modalFileInfo.textContent = currentFileName ? currentFileName + ' (PDF)' : 'Dokumen PDF';
        } else if (currentPreviewUrl) {
            if (modalImgContainer) modalImgContainer.classList.remove('hidden');
            if (modalPdfContainer) modalPdfContainer.classList.add('hidden');
            if (modalPreviewImg) modalPreviewImg.src = currentPreviewUrl;
            if (modalPreviewSubtitle) modalPreviewSubtitle.textContent = 'Pratinjau gambar Kartu Keluarga';
            if (modalFileInfo) modalFileInfo.textContent = currentFileName || 'Gambar Kartu Keluarga';
        } else {
            return;
        }
        modalPreviewKk.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDocModal() {
        if (!modalPreviewKk) return;
        modalPreviewKk.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (btnPreviewLightbox) btnPreviewLightbox.addEventListener('click', openDocModal);
    if (btnViewModalKk) btnViewModalKk.addEventListener('click', openDocModal);
    if (btnCloseModalKk) btnCloseModalKk.addEventListener('click', closeDocModal);
    if (btnCloseModalKkFooter) btnCloseModalKkFooter.addEventListener('click', closeDocModal);
    if (modalPreviewKk) {
        modalPreviewKk.addEventListener('click', function(e) {
            if (e.target === modalPreviewKk) {
                closeDocModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalPreviewKk && !modalPreviewKk.classList.contains('hidden')) {
            closeDocModal();
        }
    });

    if (docKkInput) {
        docKkInput.addEventListener('change', function() {
            if (!this.files || this.files.length === 0) return;
            const file = this.files[0];

            if (file.size > 3 * 1024 * 1024) {
                alert('Ukuran berkas melebihi batas maksimal 3MB (' + (file.size / 1024 / 1024).toFixed(2) + ' MB). Silakan pilih berkas yang lebih kecil.');
                this.value = '';
                return;
            }

            const ext = file.name.split('.').pop().toLowerCase();
            const allowed = ['jpg', 'jpeg', 'png', 'pdf'];
            if (!allowed.includes(ext)) {
                alert('Format berkas tidak didukung. Harap pilih berkas gambar (JPG, JPEG, PNG) atau PDF.');
                this.value = '';
                return;
            }

            currentFileName = file.name;
            if (fileNameKk) fileNameKk.textContent = file.name;
            if (fileSizeKk) fileSizeKk.textContent = (file.size / 1024).toFixed(1) + ' KB';

            if (file.type === 'application/pdf' || ext === 'pdf') {
                currentFileIsPdf = true;
                currentPreviewUrl = '';
                if (imgPreviewWrapKk) imgPreviewWrapKk.classList.add('hidden');
                if (pdfPreviewWrapKk) pdfPreviewWrapKk.classList.remove('hidden');
            } else {
                currentFileIsPdf = false;
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentPreviewUrl = e.target.result;
                    if (imgPreviewKk) imgPreviewKk.src = currentPreviewUrl;
                    if (imgPreviewWrapKk) imgPreviewWrapKk.classList.remove('hidden');
                    if (pdfPreviewWrapKk) pdfPreviewWrapKk.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }

            if (placeholderKk) placeholderKk.classList.add('hidden');
            if (previewBoxKk) previewBoxKk.classList.remove('hidden');
        });
    }

    if (btnClearKk) {
        btnClearKk.addEventListener('click', function() {
            if (docKkInput) docKkInput.value = '';
            currentPreviewUrl = '';
            currentFileName = '';
            currentFileIsPdf = false;
            if (imgPreviewKk) imgPreviewKk.src = '';
            if (imgPreviewWrapKk) imgPreviewWrapKk.classList.add('hidden');
            if (pdfPreviewWrapKk) pdfPreviewWrapKk.classList.add('hidden');
            if (previewBoxKk) previewBoxKk.classList.add('hidden');
            if (placeholderKk) placeholderKk.classList.remove('hidden');
        });
    }

    // Pulihkan draft jika ada
    loadRegisterDraft();
    updateEmptyState();
});
</script>
@endsection
