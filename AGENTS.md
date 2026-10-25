# Aturan Pengembangan & Perilaku AI (Antigravity Rules)

1. **Integritas Desain & Tampilan (UI):**
   - Jangan pernah mengubah tampilan, tata letak, styling, maupun struktur antarmuka (UI) aplikasi yang sudah ada, kecuali pengguna memintanya secara eksplisit.

2. **Penambahan Fitur Baru (Non-Breaking):**
   - Saat mengimplementasikan atau menambahkan fitur baru, jangan mengubah atau merusak tampilan antarmuka (UI) dan alur logika aplikasi yang sudah berjalan sebelumnya.
   - Selalu pertahankan kompatibilitas dan stabilitas fungsi yang telah ada.

3. **Token Economy & Direct Execution Protocol (Efisiensi & Fokus):**
   - **Strict Scope Boundary**: Modifikasi HANYA file dan baris yang diperintahkan secara spesifik. Dilarang melakukan refactoring liar, reformatting menyeluruh, atau mengubah logika di luar instruksi user.
   - **Direct & Focused Output**: Langsung ke inti teknis (on-point). Hilangkan basa-basi pembuka/penutup, permohonan maaf, atau pengulangan kode/diff yang sudah terlihat di tool.
   - **Surgical Edits**: Selalu gunakan edit bedah baris per baris (`replace_file_content`) alih-alih menimpa/menulis ulang seluruh file besar.
   - **Targeted Reading**: Saat membaca kode (`view_file`), gunakan `StartLine` dan `EndLine` untuk melihat blok yang relevan saja. Hindari membaca ratusan baris file yang tidak bersangkutan.
   - **No Over-Engineering**: Terapkan apa yang diminta secara presisi tanpa berspekulasi atau membuat abstraksi berlebih yang tidak dibutuhkan saat ini.
