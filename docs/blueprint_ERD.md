### 1. Domain Pengguna & Akses (Local Mirror)

Karena otentikasi diurus oleh Proyek SSO, tabel pengguna di Proyek Penelitian hanya bertindak sebagai "cermin" (*mirror*) untuk relasi data lokal.

- **Tabel `users` (Local Mirror):** Hanya berisi `sso_id` (berelasi dengan ID di sistem SSO), dan `role_lokal` (Dosen, Admin, dsb).
- `mahasiswa`: Tabel pasif berisi `nim`, `nama`, `program_studi`. (Sesuai PRD, diisi dari *seeder*).

| **Tabel** | **Kolom** | **Tipe Data** | **Keterangan** |
| --- | --- | --- | --- |
| **users** | `id` | BigInt (PK) | Primary Key lokal. |
|  | `sso_id` | UUID / String | Unique Key. ID relasi ke database SSO. |
|  | `nama` | String | *Cached data* dari SSO. |
|  | `email` | String | *Cached data* dari SSO. |
|  | `role_lokal` | Enum / String | 'dosen', 'admin_lembaga'. |
| **mahasiswa** | `id` | BigInt (PK) |  |
|  | `nim` | String | Unique Key. |
|  | `nama` | String |  |
|  | `program_studi` | String |  |

### 2. Domain Transaksi Inti (Penelitian)

Pusat dari aplikasi Anda. Kita akan memecah data entitas dari data relasi agar normalisasi database (3NF) terjaga.

- `penelitian`: Berisi detail proposal (judul, abstrak, status_saat_ini, tanggal_pengajuan). Kolom `status_saat_ini` akan menjadi pijakan untuk *State Pattern*.
- `penelitian_dosen` (Pivot): Menghubungkan penelitian dan pengguna (dosen). Menyimpan `peran` (Ketua, Anggota).
- `penelitian_mahasiswa` (Pivot): Menghubungkan mahasiswa pasif yang ikut membantu penelitian.
- `penelitian_reviewer` (Pivot): Menghubungkan penelitian dengan dosen penilai. Menyimpan kolom `nilai_desk_eval`, `komentar`, `nilai_presentasi`. (Pemisahan ini mencegah tabel *penelitian* menjadi gemuk).
- `dokumen_penelitian`: Tabel polimorfik atau berelasi langsung yang menyimpan *path* file (proposal, laporan kemajuan, laporan akhir) dengan atribut `tipe_dokumen` agar file bisa divalidasi keamanannya.

| **Tabel** | **Kolom** | **Tipe Data** | **Keterangan** |
| --- | --- | --- | --- |
| **penelitian** | `id` | BigInt (PK) |  |
|  | `judul` | String |  |
|  | `abstrak` | Text |  |
|  | `status_saat_ini` | String | *State Machine flag* (misal: 'draft', 'desk_eval'). |
|  | `tanggal_pengajuan` | Date / DateTime |  |
| **penelitian_dosen** | `penelitian_id` | BigInt (FK) | Berelasi ke tabel penelitian. |
|  | `user_id` | BigInt (FK) | Berelasi ke tabel users. |
|  | `peran` | Enum | 'ketua', 'anggota'. |
| **penelitian_mahasiswa** | `penelitian_id` | BigInt (FK) |  |
|  | `mahasiswa_id` | BigInt (FK) | Berelasi ke tabel mahasiswa. |
| **penelitian_reviewer** | `penelitian_id` | BigInt (FK) |  |
|  | `user_id` | BigInt (FK) | Dosen yang menjadi reviewer. |
|  | `nilai_desk_eval` | Decimal / Int | Nullable (diisi saat evaluasi). |
|  | `komentar_desk_eval` | Text | Nullable. |
|  | `nilai_presentasi` | Decimal / Int | Nullable. |
|  | `komentar_presentasi` | Text | Nullable. |
| **dokumen_penelitian** | `id` | BigInt (PK) |  |
| *(Polymorphic)* | `documentable_type` | String | Menyimpan nama Model (Penelitian / Publikasi). |
|  | `documentable_id` | BigInt | ID dari Model terkait. |
|  | `tipe_dokumen` | Enum | 'proposal', 'laporan_kemajuan', 'laporan_akhir'. |
|  | `file_path` | String | Path S3/lokal. |
|  | `uploaded_by` | BigInt (FK) | User yang mengunggah. |

### 3. Domain Luaran & Insentif

Ini adalah domain di mana operasi matematis Anda bekerja.

- `publikasi`: Berelasi dengan `penelitian_id`. Berisi metadata jurnal (judul, Q-level, DOI, dsb) dan status persetujuan publikasinya.
- `pagu_insentif` (Master Data): Menyimpan level jurnal dan nominal base (misal: Q1 = Rp10.000.000).
- `insentif`: Berelasi dengan `publikasi_id` dan `pagu_insentif_id`. Mencatat periode pengajuan, status, dan total dana yang disetujui.
- **Tabel `insentif_distribusi`:** Akan mencatat histori definitif (id, insentif_id, user_id, peran, persentase_potongan, nominal_final) untuk keperluan audit keuangan.
- **Tabel `periode_insentif`:** Dibuat sebagai master data (id, nama_periode, tanggal_mulai, tanggal_selesai, status_aktif).

| **Tabel** | **Kolom** | **Tipe Data** | **Keterangan** |
| --- | --- | --- | --- |
| **periode_insentif** | `id` | BigInt (PK) |  |
|  | `nama_periode` | String | Misal: "Gelombang 1 2026". |
|  | `tanggal_mulai` | Date |  |
|  | `tanggal_selesai` | Date |  |
|  | `status_aktif` | Boolean | True jika sedang dibuka. |
| **pagu_insentif** | `id` | BigInt (PK) |  |
|  | `tingkat_quartil` | String | 'Q1', 'Q2', 'SINTA 1', dll. |
|  | `nominal_base` | BigInteger | Nilai rupiah pagu utama. |
| **publikasi** | `id` | BigInt (PK) |  |
|  | `penelitian_id` | BigInt (FK) |  |
|  | `judul_publikasi` | String |  |
|  | `tingkat_quartil` | String | Mencocokkan dengan pagu_insentif. |
|  | `informasi_jurnal` | JSON / Text | Menyimpan ISSN, DOI, URL, penerbit. |
|  | `status_publikasi` | String | 'pending', 'approved', 'rejected'. |
| **insentif** | `id` | BigInt (PK) |  |
|  | `publikasi_id` | BigInt (FK) |  |
|  | `periode_insentif_id` | BigInt (FK) |  |
|  | `status` | String | 'diajukan', 'revisi', 'disetujui'. |
|  | `total_dana` | BigInteger | Diisi setelah kalkulasi disetujui. |
| **insentif_distribusi** | `id` | BigInt (PK) |  |
|  | `insentif_id` | BigInt (FK) |  |
|  | `user_id` | BigInt (FK) | Dosen penerima. |
|  | `peran` | String | 'ketua', 'anggota'. |
|  | `persentase_potongan` | Int | '60', '40', atau hasil pembagian anggota. |
|  | `nominal_final` | BigInteger | Nilai riil yang diterima (Rupiah). |

### 4. Domain Audit & Log

Pilar utama untuk menunjukkan sistem berstandar *enterprise*.

- `audit_logs`: Menyimpan riwayat perubahan status menggunakan *Observer*. Kolomnya: `model_type`, `model_id`, `user_id` (aktor), `status_sebelum`, `status_sesudah`, `timestamp`, `catatan_sistem`.

| **Tabel** | **Kolom** | **Tipe Data** | **Keterangan** |
| --- | --- | --- | --- |
| **audit_logs** | `id` | BigInt (PK) |  |
|  | `model_type` | String | (Indexed) Nama Model yang berubah. |
|  | `model_id` | BigInt | (Indexed) ID model. |
|  | `user_id` | BigInt (FK) | Aktor yang mengubah (nullable jika oleh sistem). |
|  | `status_sebelum` | String | State sebelumnya. |
|  | `status_sesudah` | String | State terbaru. |
|  | `catatan_sistem` | Text | Misal: "Ditolak karena format salah". |
|  | `created_at` | Timestamp | Waktu perubahan. |