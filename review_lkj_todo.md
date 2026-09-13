# TODO List: Aplikasi Review LKj Satker (Laravel)

## Fase 1: Setup Proyek & Konfigurasi Dasar
- [x] Inisialisasi proyek Laravel baru dan atur koneksi database MySQL di file `.env`.
- [x] Atur konfigurasi SMTP Gmail untuk pengiriman email di file `.env`.
- [x] Jalankan perintah `php artisan storage:link` untuk setup direktori penyimpanan file publik.
- [x] Install package `spatie/laravel-permission` untuk manajemen Role & Permission, lalu jalankan instalasi dan migrasinya.
- [x] Install package `phpoffice/phpword` untuk kebutuhan generate Berita Acara (.docx) nantinya.
- [x] Setup sistem Autentikasi dasar (login/logout) menggunakan Laravel Breeze atau Jetstream (pilih stack Blade/Alpine.js).

## Fase 2: Migrasi Database & Relasi (Master Data)
- [x] Buat file migrasi dan model untuk `satkers` (id, kode_satker, nama_satker).
- [x] Ubah migrasi tabel `users`, tambahkan kolom `role` (enum: admin, monev, satker) dan `satker_id` (foreign key ke satkers, nullable).
- [x] Buat file migrasi dan model untuk `rubrik_reviews` (id, aspek, tipe_evaluasi, bagian_laporan, minimum_informasi, urutan).
- [x] Buat Seeder untuk tabel `roles` (Spatie), akun Admin pertama (Super User), dan data statis panduan di `rubrik_reviews` (untuk Aspek 1 dan Aspek 3).
- [x] Definisikan relasi Eloquent pada model `User`, `Satker`, dan `RubrikReview`.

## Fase 3: Modul Admin - Master Data & Autentikasi
- [x] Buat layout dashboard dasar (template Blade) dengan navigasi sidebar yang menyesuaikan role pengguna (Admin/Monev/Satker).
- [x] Buat Route, Controller, dan View (CRUD) untuk manajemen Master Data `satkers` (Hanya dapat diakses role Admin).
- [x] Buat Route, Controller, dan View (CRUD) untuk manajemen `users` (Hanya dapat diakses role Admin). Pada saat create/edit user dengan role 'satker', berikan input pilihan untuk assign `satker_id`.
- [x] Terapkan middleware otorisasi (role-based) untuk memproteksi rute Admin, Monev, dan Satker.

## Fase 4: Modul Admin - Periode & Penugasan
- [x] Buat file migrasi dan model untuk `periode_reviews` (id, tahun_lkj, tahun_review, deadline_revisi, status: aktif/ditutup).
- [x] Buat file migrasi dan model untuk `penugasan_monev` (id, periode_id, satker_id, monev_user_id).
- [x] Buat Route, Controller, dan View (CRUD) untuk `periode_reviews` (Admin).
- [x] Buat fitur (UI dan Logika) pada halaman Admin untuk menetapkan penugasan Tim Monev: Memilih periode aktif, memilih Satker, dan menetapkan User (role Monev) ke Satker tersebut.
- [x] Definisikan relasi Eloquent untuk `PeriodeReview` dan `PenugasanMonev`.

## Fase 5: Modul Satker - Input Target Kinerja
- [ ] Buat file migrasi dan model untuk `sasaran_kegiatans` (id, periode_id, satker_id, sasaran_kegiatan).
- [ ] Buat file migrasi dan model untuk `indikator_kinerjas` (id, sasaran_kegiatan_id, indikator_kinerja).
- [ ] Definisikan relasi Eloquent (`SasaranKegiatan` hasMany `IndikatorKinerja`).
- [ ] Buat halaman Dashboard Satker yang menampilkan periode review yang sedang aktif.
- [ ] Buat Form Input (UI & Controller) bagi Operator Satker untuk menambah/mengedit `sasaran_kegiatans` milik satker-nya pada periode aktif.
- [ ] Buat Form Input (UI & Controller) bagi Operator Satker untuk menambah `indikator_kinerjas` di bawah setiap sasaran kegiatan.

## Fase 6: Modul Satker - Upload LKj (Versioning V1)
- [ ] Buat file migrasi dan model untuk `lkj_submissions` (id, periode_id, satker_id, status_keseluruhan, tanggal_selesai).
- [ ] Buat file migrasi dan model untuk `lkj_dokumens` (id, lkj_submission_id, versi, file_path, diupload_oleh).
- [ ] Definisikan relasi Eloquent terkait transaksi submission dan dokumen LKj.
- [ ] Buat fitur Upload Dokumen (UI & Logic) untuk Operator Satker: Jika belum pernah upload, buat record di `lkj_submissions` (status: proses_review) dan simpan file fisik di storage, catat di `lkj_dokumens` sebagai `versi = 1`.

## Fase 7: Modul Monev - Persiapan Engine Review
- [ ] Buat Dashboard Tim Monev (UI & Controller) yang menampilkan daftar Satker yang ditugaskan kepada Monev tersebut pada periode aktif (ambil dari `penugasan_monev`).
- [ ] Buat Layout halaman "Detail Review" untuk Satker yang dipilih, lengkapi dengan UI 3 Tab: "Aspek 1: Format", "Aspek 2: Capaian", dan "Aspek 3: Pengungkapan".
- [ ] Buat Service/Helper (Backend) untuk menghitung kalkulasi persentase progress dinamis (Total Poin = 8 + (8 x Jumlah Indikator)). Tampilkan progress bar pada halaman detail review ini.

## Fase 8: Modul Monev - Engine Review Aspek 1 & 3
- [ ] Buat file migrasi dan model untuk `hasil_reviews` (id, lkj_dokumen_id, rubrik_id, indikator_kinerja_id [nullable], uraian, status, catatan_perbaikan, tanggapan_satker, direview_oleh).
- [ ] Implementasi Tab Aspek 1 (UI & Controller): Tampilkan 8 form evaluasi (berdasarkan data `rubrik_reviews` per_dokumen). Sediakan input radio Sesuai/Belum dan textarea Catatan (wajib jika Belum). Simpan data ke `hasil_reviews`.
- [ ] Implementasi Tab Aspek 3 (UI & Controller): Tampilkan daftar Indikator Kinerja Satker. Di dalam tiap indikator, tampilkan 7 form evaluasi (berdasarkan data `rubrik_reviews` per_indikator). Simpan data ke `hasil_reviews` (sertakan `indikator_kinerja_id`).

## Fase 9: Modul Monev - Engine Review Aspek 2 (Auto-Compare)
- [ ] Buat file migrasi dan model untuk `review_capaian_kinerjas` (id, lkj_dokumen_id, indikator_kinerja_id, nilai_exec, nilai_bab3, nilai_bab4, nilai_aplikasi, nilai_datadukung, is_sinkron, catatan_perbaikan, tanggapan_satker, direview_oleh).
- [ ] Implementasi Tab Aspek 2 (UI): Tampilkan daftar Indikator Kinerja. Di bawah setiap indikator, buat 5 input teks (number/decimal) untuk memasukkan nilai capaian.
- [ ] Tambahkan Vanilla JS / Alpine.js pada UI Aspek 2 untuk logika *real-time auto-compare*: Jika 5 input bernilai sama, set warna indikator menjadi hijau (Sinkron). Jika beda, set merah dan tampilkan textarea "Catatan Perbaikan" sebagai field wajib (required).
- [ ] Buat route dan controller untuk menyimpan data inputan Aspek 2 ke tabel `review_capaian_kinerjas`.

## Fase 10: Sistem Revisi & Notifikasi Email
- [ ] Buat class Mailable Laravel (misal: `RevisionNotificationMail`) yang mendefinisikan template email pemberitahuan revisi.
- [ ] Integrasikan pengiriman email: Saat Tim Monev menyimpan review (Aspek 1, 2, atau 3) yang mengandung status "Belum/Tidak Sinkron", ubah status `lkj_submissions` menjadi `perlu_revisi` dan kirim email ke Operator Satker bersangkutan.
- [ ] Buat antarmuka (UI) khusus bagi Operator Satker untuk melihat daftar item yang "Perlu Perbaikan" (menggabungkan catatan dari Aspek 1, 2, dan 3).
- [ ] Wajibkan Operator Satker untuk mengisi input form `tanggapan_perbaikan_satker` pada setiap catatan perbaikan tersebut sebelum mengaktifkan tombol Upload Revisi.
- [ ] Implementasi logika Upload Revisi (V2, V3, dst): Simpan file PDF/DOCX yang baru di storage, insert record baru ke `lkj_dokumens` dengan nomor versi di-increment (+1). Ubah status `lkj_submissions` kembali menjadi `proses_review`.

## Fase 11: Finalisasi & Generate Berita Acara
- [ ] Buat file migrasi dan model untuk `berita_acaras` (id, lkj_submission_id, file_word_path, file_pdf_path, diupload_oleh).
- [ ] Buat logika validasi pada halaman Monev: Tombol "Tandai Selesai" HANYA bisa diklik (tidak disabled) jika persentase Progress = 100%.
- [ ] Buat Controller Method untuk menangani "Tandai Selesai": Update `lkj_submissions.tanggal_selesai` menjadi current timestamp dan status menjadi `selesai`.
- [ ] Buat fitur Generate BA: Gunakan library PHPWord untuk membuka template `.docx`, ubah variabel teks (`[Nama Satker]`, `[Tanggal]`, dll), simpan file output ke storage, dan catat path-nya di `berita_acaras.file_word_path`. Sediakan tombol Download file tersebut di UI Monev.
- [ ] Buat form (UI & Controller) bagi Tim Monev untuk mengunggah file hasil scan Berita Acara fisik (.pdf) dan catat di `berita_acaras.file_pdf_path`.

## Fase 12: Dashboard Global (Admin) & Polishing Akhir
- [ ] Buat file migrasi dan model untuk `notifications` (log in-app notifications).
- [ ] Sempurnakan Dashboard Admin: Tampilkan statistik jumlah Satker yang (Belum Upload, Sedang Review, Proses Revisi, Selesai).
- [ ] Tambahkan logika peringatan deadline pada Dashboard Admin dan Monev: Bandingkan `date.now()` dengan `periode_reviews.deadline_revisi`. Berikan indikator visual warna merah jika lewat waktu dan belum berstatus selesai.
- [ ] Lakukan refactoring kode, pengecekan celah keamanan (pastikan Tenant Isolation pada Controller: Satker hanya bisa akses data Satkernya sendiri), dan pastikan UI responsif.
