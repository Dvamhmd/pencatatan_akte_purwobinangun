@extends('layouts.admin')

@section('title', 'Koreksi Data Warga: ' . $citizen->name)
@section('page_title', 'Koreksi & Edit Data Akun Warga')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.citizens.show', $citizen) }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#0b7c89] hover:underline bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Warga
        </a>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500">ID Warga:</span>
            <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">#{{ $citizen->id }}</span>
        </div>
    </div>

    <!-- Form Edit Data Warga -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#074a73]" style="background: linear-gradient(135deg, #065b65 0%, #0b7c89 100%); background-color: #065b65; color: #ffffff;">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-xl font-bold shadow-inner shrink-0" style="background-color: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.3);">
                    <i class="fa-solid fa-user-pen text-amber-300"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-base sm:text-lg tracking-tight" style="color: #ffffff;">
                        Form Koreksi & Pembaruan Data Warga
                    </h2>
                    <p class="text-xs mt-0.5 leading-relaxed font-medium" style="color: #ccfbf1;">
                        Perbarui informasi identitas kependudukan atau status akun warga jika terdapat kesalahan input
                    </p>
                </div>
            </div>
            <div class="shrink-0">
                <span class="inline-block text-xs font-bold px-3 py-1.5 rounded-full border shadow-2xs {{ $citizen->status_badge_class }}">
                    {{ $citizen->status_label }}
                </span>
            </div>
        </div>

        <form action="{{ route('admin.citizens.update', $citizen) }}" method="POST" class="p-6 space-y-6 text-xs">
            @csrf
            @method('PUT')

            <!-- Bagian 1: Data Identitas Kependudukan -->
            <div>
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-id-card text-[#0b7c89]"></i> 1. Identitas Kependudukan (NIK & KK)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nik" class="block font-bold text-slate-700 mb-1">
                            Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik', $citizen->nik) }}" required maxlength="16" minlength="16"
                            class="w-full text-xs font-mono px-3.5 py-2.5 rounded-xl border {{ $errors->has('nik') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('nik')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="family_card_no" class="block font-bold text-slate-700 mb-1">
                            Nomor Kartu Keluarga (KK) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="family_card_no" id="family_card_no" value="{{ old('family_card_no', $citizen->family_card_no) }}" required maxlength="16" minlength="16"
                            class="w-full text-xs font-mono px-3.5 py-2.5 rounded-xl border {{ $errors->has('family_card_no') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('family_card_no')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Data Pribadi & Kontak -->
            <div>
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-user text-[#0b7c89]"></i> 2. Data Pribadi & Kontak
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block font-bold text-slate-700 mb-1">
                            Nama Lengkap Sesuai KTP <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $citizen->name) }}" required
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('name')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block font-bold text-slate-700 mb-1">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <select name="gender" id="gender" required
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('gender') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                            <option value="L" {{ old('gender', $citizen->gender) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('gender', $citizen->gender) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>
                        @error('gender')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="birth_place" class="block font-bold text-slate-700 mb-1">
                            Tempat Lahir <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $citizen->birth_place) }}" required
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('birth_place') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('birth_place')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block font-bold text-slate-700 mb-1">
                            Tanggal Lahir <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $citizen->birth_date ? $citizen->birth_date->format('Y-m-d') : '') }}" required
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('birth_date') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('birth_date')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block font-bold text-slate-700 mb-1">
                            Nomor HP / WhatsApp <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $citizen->phone) }}" required
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('phone')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-bold text-slate-700 mb-1">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $citizen->email) }}" required
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('email')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Alamat Domisili KTP -->
            <div>
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-location-dot text-[#0b7c89]"></i> 3. Alamat Domisili Sesuai KTP/KK
                </h3>

                <div class="mb-4">
                    <label for="address" class="block font-bold text-slate-700 mb-1">
                        Alamat Lengkap (Padukuhan / Dusun / Jalan) <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="address" id="address" rows="2" required
                        class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('address') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">{{ old('address', $citizen->address) }}</textarea>
                    @error('address')
                        <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="rt" class="block font-bold text-slate-700 mb-1">
                            RT <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="rt" id="rt" value="{{ old('rt', $citizen->rt) }}" required maxlength="5"
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('rt') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('rt')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rw" class="block font-bold text-slate-700 mb-1">
                            RW <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="rw" id="rw" value="{{ old('rw', $citizen->rw) }}" required maxlength="5"
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('rw') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('rw')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bagian 4: Status Akun & Opsi Keamanan -->
            <div>
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-sliders text-[#0b7c89]"></i> 4. Status Akun & Pengaturan Kata Sandi
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="status" class="block font-bold text-slate-700 mb-1">
                            Status Verifikasi Akun <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('status') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                            <option value="active" {{ old('status', $citizen->status) === 'active' ? 'selected' : '' }}>Aktif / Terverifikasi</option>
                            <option value="pending" {{ old('status', $citizen->status) === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi (Pending)</option>
                            <option value="rejected" {{ old('status', $citizen->status) === 'rejected' ? 'selected' : '' }}>Ditolak / Dinonaktifkan</option>
                            <option value="archived" {{ old('status', $citizen->status) === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                        @error('status')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block font-bold text-slate-700 mb-1">
                            Reset Password Warga <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        <input type="password" name="password" id="password" minlength="6" placeholder="Masukkan password baru jika ingin mereset"
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">
                        @error('password')
                            <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="rejection_reason_container" class="{{ old('status', $citizen->status) === 'rejected' ? '' : 'hidden' }}">
                    <label for="rejection_reason" class="block font-bold text-slate-700 mb-1">
                        Catatan / Alasan Penolakan
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="2" placeholder="Tuliskan catatan perbaikan untuk warga..."
                        class="w-full text-xs px-3.5 py-2.5 rounded-xl border {{ $errors->has('rejection_reason') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-300' }} focus:outline-none focus:ring-2 focus:ring-[#0b7c89]/20 focus:border-[#0b7c89] transition">{{ old('rejection_reason', $citizen->rejection_reason) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.citizens.show', $citizen) }}" class="text-slate-600 hover:text-slate-900 font-bold px-4 py-2.5 rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="bg-[#0b7c89] hover:bg-[#08636e] active:bg-[#065059] text-white font-bold text-xs py-2.5 px-6 rounded-xl shadow-sm hover:shadow transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan Data Warga</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const reasonContainer = document.getElementById('rejection_reason_container');

    if (statusSelect && reasonContainer) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'rejected') {
                reasonContainer.classList.remove('hidden');
            } else {
                reasonContainer.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection
