## 1. Tujuan Produk

Membangun sistem terpusat untuk mengelola seluruh siklus penelitian kampus—mulai dari pengajuan proposal, publikasi luaran, hingga pencairan insentif. Sistem ini dirancang untuk memastikan integritas data, transparansi proses melalui *audit trail*, serta keamanan tingkat tinggi (RBAC dan proteksi file).

## 2. Target Pengguna (User Personas & Roles)

- **Dosen (Pengusul):** Mengajukan penelitian, mengunggah laporan (kemajuan & akhir), mendaftarkan publikasi, dan mengajukan insentif.
- **Lembaga Penelitian (Admin/Verifikator):** Menyetujui/menolak pengajuan pada berbagai tahap, menunjuk *reviewer*, membuka/menutup periode insentif, dan mengatur pagu.
- **Dosen (Reviewer):** Memberikan nilai dan komentar pada tahap *desk evaluation* dan presentasi. Sistem harus memblokir dosen untuk mereview penelitiannya sendiri.
- **Mahasiswa (Pasif):** Data mahasiswa (nama, NIM) hanya direferensikan dalam sistem sebagai anggota penelitian, tanpa memiliki akses login. (Data dosen dan mahasiswa di-generate melalui *seeder*).

## 3. Fitur Utama & Batasan Teknis (Technical Requirements)

Sistem ini akan mendemonstrasikan kapabilitas *backend enterprise*:

1. **Arsitektur Single Sign-On (SSO):** Database autentikasi dipisah dari database operasional penelitian.
2. **Keamanan Penyimpanan Dokumen:** Semua file unggahan (proposal, laporan) tidak dapat diakses langsung via URL publik. Harus melewati pengecekan *middleware/controller* (misal: *Signed URL* atau *Private Storage*).
3. **Manajemen State & Flag:** Status pengajuan harus linier dan persisten.
4. **Audit Trail:** Setiap perubahan status (*approval/rejection*) wajib dicatat dalam log aktivitas (menyimpan ID *user*, *timestamp*, *action*).
5. **Asynchronous Email Notifications:** Pengiriman notifikasi terkait perubahan status proposal dikelola menggunakan *Queue* (antrean tugas latar belakang).
6. **Validasi Kalkulasi Matematis:**
    - *Skor Reviewer:* Jika ada 2 *reviewer*, sistem otomatis melakukan kalkulasi rata-rata.
    - *Pagu Insentif:* Sistem otomatis menghitung pembagian: Penulis Pertama (60%) dan sisanya (40%) dibagi rata kepada jumlah anggota dosen lainnya berdasarkan base nilai dari level jurnal.

## 4. Alur Kerja Sistem (User Flows)

### A. Alur Penelitian (Non-Periodik)

*Aturan ketat: Jika terjadi penolakan di salah satu tahap approval, status terkunci pada "Ditolak" dan siklus berhenti (tidak ada revisi).*

1. Dosen mengajukan proposal penelitian.
2. Lembaga Penelitian memberikan *approval* awal sembari menunjuk 1-2 *reviewer*.
3. Reviewer melakukan *Desk Evaluation*.
4. Dosen melakukan presentasi usulan proposal.
5. Lembaga Penelitian memberikan *approval* dan menerbitkan Surat Tugas.
6. Dosen mengunggah Laporan Kemajuan -> Presentasi Kemajuan -> Penilaian.
7. Dosen mengunggah Laporan Akhir -> Presentasi Akhir -> Penilaian.
8. Lembaga Penelitian memberikan *approval* final dan menerbitkan Surat Keterangan Selesai.

### B. Alur Publikasi (Non-Periodik)

*Syarat: Hanya dapat dilakukan jika penelitian terkait sudah memiliki Surat Keterangan Selesai.*

1. Dosen mendaftarkan luaran penelitian dengan form:
    - Nama penelitian terkait.
    - Tanggal publish dan informasi publikasi (Nama, Edisi, Penerbit, URL, ISSN, DOI, Akreditasi, Impact Factor, Skor SJR, Quartil).
    - Daftar anggota beserta perannya (Penulis Pertama, Penulis Pertama & Korespondensi, Penulis Korespondensi, Penulis Pendamping).
2. Lembaga Penelitian melakukan *approval* publikasi.

### C. Alur Insentif (Terkait Periode)

*Aturan ketat: Pengajuan hanya bisa dilakukan saat periode insentif dibuka. Jika ditolak, pengajuan dikembalikan dengan status "Revisi".*

1. Dosen mengajukan insentif untuk publikasi yang sudah di-*approve*.
2. Lembaga Penelitian melakukan *approval* insentif.
3. Sistem melakukan kalkulasi besaran dana sesuai pagu dan proporsi (60/40) secara otomatis.