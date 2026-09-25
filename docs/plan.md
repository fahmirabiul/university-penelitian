# 📋 Rencana Eksekusi (Implementation Plan)

Berdasarkan dokumen PRD, TDD, dan Blueprint ERD Anda, kita akan membagi eksekusi proyek ini ke dalam beberapa fase. Pendekatan ini memastikan kita membangun sistem secara bertahap (tidak sekaligus masif) untuk meminimalisir bug pada arsitektur.

## Fase 0: Setup Environment Tambahan (Redis & Database)
- [x] Pastikan database `university_penelitian` sudah terbuat di MySQL.
- [x] Jalankan `php artisan migrate` awal untuk memastikan koneksi lancar.
- [x] **Setup Redis:**
  - [x] Pastikan service/server Redis lokal (Windows/WSL/Docker) sudah berjalan.
  - [x] Update `.env`: Ubah `CACHE_STORE=redis`, `SESSION_DRIVER=redis`, `QUEUE_CONNECTION=redis`.
  - [x] Pastikan ekstensi `phpredis` aktif di `php.ini` atau jalankan `composer require predis/predis`.

## Fase 1: Fondasi Autentikasi & Identitas (SSO OAuth2)
- [x] Instal package Laravel Socialite (`composer require laravel/socialite`).
- [x] Modifikasi migration tabel `users` (hapus password, tambahkan `sso_id`).
- [x] Buat `SsoAuthController` untuk *redirect* & *callback* ke Identity Provider (IdP).
- [x] Konfigurasi mekanisme *Guard/Provider* agar aplikasi mengenali user dari SSO.
- [x] **Redis Caching**: Implementasi cache untuk menyimpan data profil user dari SSO ke Redis.

## Fase 2: Struktur Database Inti (Migrations & Models)

### 2.1 Domain Pengguna & Akses
- [x] Buat Migration & Model `Mahasiswa` (Kolom: nim, nama, program_studi).

### 2.2 Domain Transaksi Inti (Penelitian)
- [x] Buat Migration & Model `Penelitian` (Kolom: judul, abstrak, status_saat_ini, tanggal_pengajuan).
- [x] Buat Migration tabel pivot `penelitian_dosen` (Kolom: penelitian_id, user_id, peran).
- [x] Buat Migration tabel pivot `penelitian_mahasiswa` (Kolom: penelitian_id, mahasiswa_id).
- [x] Buat Migration tabel pivot `penelitian_reviewer` (Kolom: penelitian_id, user_id, nilai_desk_eval, komentar, dll).
- [x] Buat Migration & Model `DokumenPenelitian` (Kolom: documentable_type, documentable_id, tipe_dokumen, file_path, uploaded_by) - Polimorfik.

### 2.3 Domain Luaran & Insentif
- [x] Buat Migration & Model `PeriodeInsentif` (Kolom: nama_periode, tanggal_mulai, tanggal_selesai, status_aktif).
- [x] Buat Migration & Model `PaguInsentif` (Kolom: tingkat_quartil, nominal_base).
- [x] Buat Migration & Model `Publikasi` (Kolom: penelitian_id, judul_publikasi, tingkat_quartil, informasi_jurnal (JSON), status_publikasi).
- [x] Buat Migration & Model `Insentif` (Kolom: publikasi_id, periode_insentif_id, status, total_dana).
- [x] Buat Migration & Model `InsentifDistribusi` (Kolom: insentif_id, user_id, peran, persentase_potongan, nominal_final).

### 2.4 Domain Audit & Log
- [x] Buat Migration & Model `AuditLog` (Kolom: model_type, model_id, user_id, status_sebelum, status_sesudah, catatan_sistem).

### 2.5 Factory & Seeder
- [x] Buat Factory & Seeder `MahasiswaSeeder` (Generate 100 data mahasiswa dummy).
- [x] Buat Seeder `PaguInsentifSeeder` (Isi referensi standar Q1-Q4/Sinta).
- [x] Buat Seeder `PeriodeInsentifSeeder` (Generate 1-2 periode aktif dummy).

## Fase 3: Core Design Patterns (State, Observer, Service)
- [x] **State Pattern**: Buat base class & turunan State (`DraftState`, `DeskEvalState`, `ApprovedState`) untuk kontrol perubahan status proposal yang persisten.
- [x] **Observer Pattern**: Buat `ResearchObserver` untuk otomatis mencatat riwayat ke `audit_logs` saat status penelitian di-update.
- [x] **Service Pattern**: Buat `IncentiveCalculatorService` (Logika matematis 60% ketua & 40% anggota).

## Fase 4: Authorization (RBAC) & Keamanan Dokumen
- [ ] Buat Laravel Policies & Gates (Blokir dosen mereview proposal sendiri, blokir edit jika status `Submitted`).
- [ ] Buat Route & Controller khusus untuk membaca `storage/app/private`.
- [ ] Implementasi fitur *Temporary Signed URLs* untuk proteksi tautan unduh file PDF.

## Fase 5: Pengembangan Fitur Interaktif (UI & Queue Job)
- [ ] **Redis Queue**: Buat `Job` pengiriman Email Notifikasi agar berjalan secara asinkronus (*background worker*).
- [ ] Bangun antarmuka (Blade/Vite) untuk Alur A: Pengajuan Penelitian & Review.
- [ ] Bangun antarmuka untuk Alur B: Pendaftaran Publikasi Luaran.
- [ ] Bangun antarmuka untuk Alur C: Pengajuan & Pencairan Insentif.
