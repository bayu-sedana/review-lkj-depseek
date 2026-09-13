# PRODUCT REQUIREMENTS DOCUMENT (PRD)
**Nama Produk:** Aplikasi Review Laporan Kinerja (LKj) Satker
**Versi Dokumen:** 2.3 (Final dengan Database & Tech Stack)
**Tanggal:** 11 September 2026
**Disusun Oleh:** I Gede Bayu Sedana, S.Kom.

---

## 1. Ringkasan Eksekutif & Tujuan Produk
Aplikasi ini dikembangkan untuk mendigitalisasi dan menyederhanakan proses *monitoring* dan evaluasi (monev) Laporan Kinerja (LKj) tingkat Satuan Kerja (Satker). 

**Tujuan Utama:**
*   Menyediakan platform terpusat untuk review LKj dengan siklus tahunan.
*   Menjaga rekam jejak (*versioning history*) dokumen LKj dari versi awal hingga final tanpa menghapus riwayat perbaikan sebelumnya.
*   Menciptakan alur komunikasi dua arah antara Tim Monev (pemberi catatan) dan Operator Satker (pengisi tanggapan perbaikan).
*   Mengukur progres penyelesaian review secara *real-time* dan dinamis berdasarkan beban indikator masing-masing Satker.
*   Mengotomatisasi pembuatan dokumen Berita Acara Selesai Review (.docx) dan pengarsipan versi final yang telah ditandatangani (.pdf).

## 2. Pengguna dan Hak Akses (Roles)
1.  **Admin (Super User):** Mengelola konfigurasi sistem, membuka periode tahunan, mengelola master data, mengatur penugasan Tim Monev, dan memantau *dashboard* progres global.
2.  **Operator Satker:** Menginput Sasaran & Indikator Kinerja, mengunggah dokumen LKj, menerima notifikasi revisi, memberikan tanggapan perbaikan, dan mengunggah dokumen revisi.
3.  **Tim Monev:** Mereview dokumen LKj berdasarkan 3 aspek, memberikan status dan catatan, mencetak Berita Acara (BA), dan mengunggah kembali BA fisik hasil *scan*.

## 3. Alur Kerja Utama (Core User Flow)
1.  **Inisiasi (Admin):** Admin membuka periode review, menetapkan batas waktu (*soft-deadline*), dan menetapkan penugasan Tim Monev per Satker.
2.  **Input & Upload Awal (Satker):** Operator Satker mengisi form Sasaran Kegiatan dan Indikator Kinerja, lalu mengunggah LKj (V1).
3.  **Proses Review (Tim Monev):** Tim Monev melakukan evaluasi melalui 3 Aspek Review. Sistem menghitung persentase progres secara *real-time* seiring bertambahnya status "Sesuai/Sinkron".
4.  **Notifikasi & Revisi (Sistem & Satker):** Jika ada status "Belum/Perlu Perbaikan", progres akan tertahan. Sistem mengirim notifikasi *Email* ke Satker. Satker wajib mengisi kolom tanggapan lalu mengunggah LKj revisi (V2).
5.  **Looping (Iterasi):** Langkah 3 dan 4 berulang. Jika melewati *deadline*, sistem menampilkan peringatan visual merah di *dashboard* tanpa memblokir akses unggah.
6.  **Finalisasi & Berita Acara (Tim Monev):** Saat progres mencapai 100%, Tim Monev menandai review "Selesai". Sistem meng-*generate* Berita Acara (.docx). Tim Monev mencetak, menandatangani, men-*scan*, dan mengunggahnya kembali (.pdf).

---

## 4. Rincian Modul Review (The 3 Aspects)

### Aspek 1: Kesesuaian Format Pelaporan (Evaluasi Per Dokumen)
Tim Monev mereview dokumen secara keseluruhan berdasarkan **8 bagian baku** (Kata Pengantar, Ringkasan Eksekutif, Bab 1, Bab 2, Bab 3A, Bab 3B, Bab 4, Lampiran).
*   **Parameter Penilaian:**
    *   `Minimum Informasi`: Teks statis panduan.
    *   `Uraian Hasil Review`: Teks bebas, diisi Tim Monev.
    *   `Status`: Pilihan **[Sesuai]** atau **[Belum]**.
    *   `Catatan untuk perbaikan`: Wajib diisi jika status **[Belum]**.
    *   `Perbaikan telah dilakukan`: Wajib diisi Operator Satker saat revisi.

### Aspek 2: Kesesuaian Data Capaian Kinerja (Evaluasi Per Indikator)
Sistem memvalidasi konsistensi angka secara otomatis (*auto-compare*). Diulang untuk **setiap Indikator Kinerja**.
*   **Parameter Penilaian:**
    *   Tim Monev memasukkan angka capaian di 5 tempat: `Executive Summary`, `Bab III`, `Bab IV`, `Aplikasi Kinerjaku`, dan `Data Dukung`.
    *   **Logika Auto-Compare:** Jika kelima angka SAMA, status indikator otomatis **[Sinkron]**. Jika berbeda, status menjadi **[Belum]** dan memunculkan notifikasi merah.
    *   `Catatan & Tanggapan Perbaikan`: Berlaku seperti Aspek 1.

### Aspek 3: Pengungkapan Informasi Kinerja (Evaluasi Per Indikator)
Evaluasi kualitatif dan analitis yang diulang untuk **setiap Indikator Kinerja**.
*   **7 Kriteria Evaluasi:** (3.1) Target vs Realisasi thn ini, (3.2) Realisasi thn ini vs historis, (3.3) Realisasi vs Target Renstra, (3.4) Realisasi vs Standar Nasional, (3.5) Analisis keberhasilan/kegagalan, (3.6) Analisis efisiensi, (3.7) Analisis program penunjang.
*   **Parameter Penilaian:** Sama dengan Aspek 1 (Uraian, Status Sesuai/Belum, Catatan perbaikan, Tanggapan Satker).

---

## 5. Logika Perhitungan Progres Penilaian (Dinamis 0-100%)
*   **Total Poin (Target 100%):** `8 + (8 x Jumlah Indikator Kinerja)`
*   **Progres Berjalan:** `(Jumlah Poin berstatus 'Sesuai/Sinkron' / Total Poin) x 100`
*   **Antarmuka:** *Progress bar* dengan persentase utuh (contoh: 75%). Tombol *"Generate Berita Acara"* terkunci hingga progres bernilai 100%.

---

## 6. Arsitektur Database (ERD Lengkap)
Struktur relasional ini dinormalisasi untuk menjaga riwayat revisi dokumen (*versioning*).

### 6.1. Kelompok Master Data & Pengguna
*   **Tabel `users`**: `id` (PK), `name`, `email`, `password`, `role` (admin, monev, satker), `satker_id` (FK, Nullable).
*   **Tabel `satkers`**: `id` (PK), `kode_satker`, `nama_satker`.
*   **Tabel `rubrik_reviews`**: `id` (PK), `aspek` (Format Pelaporan / Pengungkapan), `tipe_evaluasi` (per_dokumen / per_indikator), `bagian_laporan`, `minimum_informasi`, `urutan`.

### 6.2. Kelompok Periode & Penugasan
*   **Tabel `periode_reviews`**: `id` (PK), `tahun_lkj`, `tahun_review`, `deadline_revisi`, `status` (aktif/ditutup).
*   **Tabel `penugasan_monev`**: `id` (PK), `periode_id` (FK), `satker_id` (FK), `monev_user_id` (FK).

### 6.3. Kelompok Kinerja (Input dari Operator Satker)
*   **Tabel `sasaran_kegiatans`**: `id` (PK), `periode_id` (FK), `satker_id` (FK), `sasaran_kegiatan`.
*   **Tabel `indikator_kinerjas`**: `id` (PK), `sasaran_kegiatan_id` (FK), `indikator_kinerja`.

### 6.4. Kelompok Transaksi Utama (Unggah & Versioning)
*   **Tabel `lkj_submissions`**: `id` (PK), `periode_id` (FK), `satker_id` (FK), `status_keseluruhan`, `tanggal_selesai`.
*   **Tabel `lkj_dokumens`**: `id` (PK), `lkj_submission_id` (FK), `versi` (int: 1, 2, dst), `file_path`, `diupload_oleh` (FK).

### 6.5. Kelompok Penilaian & Review
*   **Tabel `hasil_reviews`** (Untuk Aspek 1 & 3): `id` (PK), `lkj_dokumen_id` (FK), `rubrik_id` (FK), `indikator_kinerja_id` (FK, Nullable), `uraian_hasil_review`, `status` (sesuai/belum), `catatan_perbaikan`, `tanggapan_perbaikan_satker`, `direview_oleh` (FK).
*   **Tabel `review_capaian_kinerjas`** (Untuk Aspek 2): `id` (PK), `lkj_dokumen_id` (FK), `indikator_kinerja_id` (FK), `nilai_exec_summary`, `nilai_bab_3`, `nilai_bab_4`, `nilai_aplikasi_kinerjaku`, `nilai_data_dukung`, `is_sinkron` (Boolean), `catatan_perbaikan`, `tanggapan_perbaikan_satker`, `direview_oleh` (FK).

### 6.6. Kelompok Output & Notifikasi
*   **Tabel `berita_acaras`**: `id` (PK), `lkj_submission_id` (FK), `file_word_path` (hasil generate), `file_pdf_path` (hasil scan), `diupload_oleh` (FK).
*   **Tabel `notifications`**: `id` (PK), `user_id` (FK), `title`, `message`, `is_read`.

---

## 7. Technology Stack (Tumpukan Teknologi)
Aplikasi ini akan dibangun dengan arsitektur *Monolithic* web modern yang mengutamakan kecepatan pengembangan, kemudahan *maintenance*, dan reliabilitas *server*.

### A. Backend & Database
*   **Framework Utama:** Laravel (PHP)
*   **Database Relasional:** MySQL
*   **Otorisasi & Manajemen Peran:** Spatie Laravel Permission (untuk mengelola hak akses Admin, Tim Monev, dan Operator Satker).
*   **Document Generator:** PHPWord (`phpoffice/phpword`) untuk memanipulasi *template* `.docx` dan mengisi variabel Berita Acara secara otomatis.

### B. Frontend (Antarmuka Pengguna)
*   **Templating Engine:** Laravel Blade
*   **CSS Framework:** Bootstrap 5 atau Tailwind CSS (untuk desain UI yang responsif dan pembuatan *dashboard*).
*   **Client-side Scripting:** Vanilla JavaScript atau Alpine.js (difokuskan untuk logika *real-time auto-compare* warna merah/hijau pada form input Aspek 2, sehingga sistem tidak perlu terus *refresh* halaman).

### C. Server & Infrastruktur Deployment
*   **Sistem Operasi Server:** Ubuntu 24.04 LTS
*   **Web Server:** Apache2 (dikonfigurasi dengan *VirtualHosts* untuk *hosting* aplikasi).
*   **Keamanan (SSL/TLS):** Let's Encrypt (Certbot) untuk mengaktifkan koneksi HTTPS yang aman.
*   **Email Gateway:** SMTP Relay via Gmail (menggunakan otentikasi *App Password*) untuk pengiriman notifikasi otomatis.

---

## 8. Kebutuhan Non-Fungsional (Teknis Tambahan)
1.  **Manajemen File (Storage):** Penyimpanan terstruktur per Satker/Tahun dengan penamaan *file* sistematis (contoh: `2025_SatkerA_LKj_V1.pdf`).
2.  **Keamanan Data:** Satker hanya dapat melihat datanya sendiri. Tim Monev dibatasi aksesnya hanya pada Satker yang ditugaskan (*Tenant Isolation/Row Level Security*).
3.  **Kapasitas Server:** Direkomendasikan menyediakan alokasi *storage disk* yang memadai, mengingat setiap perbaikan dokumen LKj (V1, V2, dst.) tidak menimpa dokumen sebelumnya demi keperluan jejak audit (*audit trail*).