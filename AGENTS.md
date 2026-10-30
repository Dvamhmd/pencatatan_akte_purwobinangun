# Aturan Pengembangan & Perilaku AI (Antigravity Rules)

> **PRIORITAS TERTINGGI: KECEPATAN PENGERJAAN & KEHEMATAN TOKEN (FAST & TOKEN-EFFICIENT)**
> Aturan ini wajib diprioritaskan di atas konfigurasi lainnya. Hindari segala bentuk over-analyzing, spekulasi berlebih, dan pemborosan tool call/token.

---

### 1. Zero Over-Analysis & Direct-to-Target (Anti-Bertele-tele)
- **Langsung ke Inti Masalah**: Dilarang melakukan analisis pendahuluan yang panjang, spekulasi arsitektur, atau audit file yang tidak diminta.
- **Action-First & Minimal Steps**: Jika file target atau error sudah jelas, langsung lakukan aksi (targeted read -> surgical edit -> verifikasi singkat). Jangan melakukan loop investigasi berputar-putar.
- **Targeted Reading**: Wajib gunakan pembacaan terarah (`StartLine` & `EndLine`) hanya pada baris/fungsi yang relevan. Dilarang membaca file secara utuh atau membaca file periferal tanpa kebutuhan mendesak.
- **No Skill Auto-Loading**: Dilarang memuat atau membaca skill eksternal/opsional (seperti panduan styling, copywriting, antislop, dsb.) kecuali diminta secara eksplisit oleh pengguna.

---

### 2. Output Ringkas & Tanpa Basa-basi (Zero Fluff)
- **Direct Technical Output**: Berikan jawaban langsung pada pokok teknis.
- **Hilangkan Basa-Basi**: Tanpa salam pembuka, tanpa permohonan maaf, tanpa penutup klise, dan tanpa mengulang-ulang diff kode yang sudah tercatat oleh sistem/tool.
- **Hemat Token**: Jawaban harus padat, jelas, dan sesingkat mungkin.

---

### 3. Surgical Edits & Scope Boundary
- **Surgical Edits**: Selalu gunakan edit presisi baris per baris (`edit_file`) pada blok kode terkecil yang diperlukan. Dilarang menimpa ulang seluruh isi file besar.
- **Strict Scope Boundary**: Modifikasi HANYA file dan baris yang diperintahkan. Dilarang melakukan refactoring liar, reformatting format file, atau mengubah alur logika di luar cakupan task.

---

### 4. Integritas Desain & Non-Breaking Core
- **Integritas Desain & UI**: Jangan pernah mengubah tampilan, tata letak, styling, warna, maupun struktur antarmuka (UI) aplikasi yang sudah ada, kecuali pengguna memintanya secara eksplisit.
- **Non-Breaking Existing Logic**: Pertahankan kompatibilitas dan alur logika aplikasi yang sudah berjalan. Fitur baru tidak boleh merusak fungsi lama.
