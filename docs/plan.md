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
- [ ] Buat Migration & Model: Domain Pengguna (Tabel `mahasiswa`).
- [ ] Buat Migration & Model: Domain Penelitian (`penelitian`, relasi pivot `dosen`, `mahasiswa`, `reviewer`).
- [ ] Buat Migration & Model: Dokumen Polimorfik (`dokumen_penelitian`).
- [ ] Buat Migration & Model: Insentif & Publikasi (`periode_insentif`, `pagu_insentif`, `publikasi`, `insentif`, `insentif_distribusi`).
- [ ] Buat Migration & Model: Audit Trail (`audit_logs`).
- [ ] Buat Factory & Seeder: Isi data dummy/awal (Pagu, Dosen, Mahasiswa).

## Fase 3: Core Design Patterns (State, Observer, Service)
- [ ] **State Pattern**: Buat base class & turunan State (`DraftState`, `DeskEvalState`, `ApprovedState`) untuk kontrol perubahan status proposal yang persisten.
- [ ] **Observer Pattern**: Buat `ResearchObserver` untuk otomatis mencatat riwayat ke `audit_logs` saat status penelitian di-update.
- [ ] **Service Pattern**: Buat `IncentiveCalculatorService` (Logika matematis 60% ketua & 40% anggota).

## Fase 4: Authorization (RBAC) & Keamanan Dokumen
- [ ] Buat Laravel Policies & Gates (Blokir dosen mereview proposal sendiri, blokir edit jika status `Submitted`).
- [ ] Buat Route & Controller khusus untuk membaca `storage/app/private`.
- [ ] Implementasi fitur *Temporary Signed URLs* untuk proteksi tautan unduh file PDF.

## Fase 5: Pengembangan Fitur Interaktif (UI & Queue Job)
- [ ] **Redis Queue**: Buat `Job` pengiriman Email Notifikasi agar berjalan secara asinkronus (*background worker*).
- [ ] Bangun antarmuka (Blade/Vite) untuk Alur A: Pengajuan Penelitian & Review.
- [ ] Bangun antarmuka untuk Alur B: Pendaftaran Publikasi Luaran.
- [ ] Bangun antarmuka untuk Alur C: Pengajuan & Pencairan Insentif.
