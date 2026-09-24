**1. Tech Stack & Environment**

- **Backend:** PHP 8.3, Laravel 13.
- **Frontend:** Blade Template Engine + Vite (Pendekatan MVC Klasik).
- **Database:** MySQL (Menggunakan *InnoDB Engine* untuk mendukung *Database Transactions*).
- **Cache & Queue Driver:** Redis.
- **File Storage:** Flysystem dengan *driver* lokal (direktori non-publik) atau simulasi S3 (MinIO).

**2. Arsitektur Sistem (Distributed - OAuth2)**
Sistem dipecah menjadi dua *service* independen:

- **Identity Provider (IdP):** Proyek Laravel 1 yang diinstal **Laravel Passport**. Bertugas menyimpan data kredensial master, melayani halaman *Login*, dan menerbitkan JWT (*JSON Web Tokens*) melalui alur OAuth2 *Authorization Code Grant*.
- **Client Application (Sistem Penelitian):** Proyek Laravel 2. Tidak memiliki tabel *password*. Menggunakan **Laravel Socialite** (atau HTTP Client) untuk melempar *user* ke IdP, lalu menerima *Access Token*. Token ini digunakan untuk mengidentifikasi *role* (Dosen/Admin/Reviewer) di sistem.

**3. Keamanan & Proteksi**

- **Authorization:** Menggunakan Laravel *Policies* dan *Gates* di setiap lapis *Controller*. Validasi ini mencakup: mencegah Dosen mengedit proposal setelah statusnya *Submitted*, dan mencegah Dosen menjadi *Reviewer* di proposalnya sendiri.
- **Private File Access:** Semua unggahan (PDF proposal, laporan) disimpan di direktori `storage/app/private`. File diunduh melalui *endpoint* khusus yang divalidasi oleh *Middleware* hak akses, atau menggunakan *Temporary Signed URLs* yang otomatis kedaluwarsa dalam sekian menit.

**4. Design Patterns (Core Architecture)**

- **Service Pattern:** Logika bisnis berat dipisah dari *Controller*.
    - *Contoh:* `IncentiveCalculatorService` yang menerima parameter level jurnal, lalu mengembalikan array distribusi dana (60% ketua, 40% dibagi rata ke anggota).
- **State Pattern:** Mengamankan alur persetujuan. Status *database* tidak sekadar diubah via `$model->update(['status' => 'approved'])`. Transisi ditangani oleh kelas State (misal: `DeskEvaluationState`). Jika aplikasi mencoba memindahkan `RejectedState` ke status lain, sistem akan melempar *Exception* secara otomatis sesuai aturan PRD.
- **Observer Pattern:** Memantau model *database*. `ResearchProposalObserver` akan "mendengarkan" *event* `updated` pada model. Jika ada perubahan *flag* status, *Observer* akan otomatis merekam ke tabel `audit_logs` dan memicu *Job* untuk mengirim notifikasi tanpa mengotori *Controller*.

**5. Background Processing (Asynchronous Tasks)**

- **Email Notifications:** Semua pengiriman email (undangan presentasi, hasil persetujuan) dikirim ke antrean (Laravel Queue) dengan *driver* Redis. Hal ini mencegah pengguna mengalami *loading* panjang (HTTP *timeout*) saat mengeklik tombol "Setujui".
1. **Caching Data User:** Ini adalah strategi optimasi. Kita menghindari *N+1 Query API* (HTTP Request berulang ke server SSO) yang bisa mematikan performa aplikasi saat *traffic* tinggi.
2. **Soft Deletes:** Di sistem *enterprise* (terutama yang menyangkut uang dan jejak audit), data tidak boleh benar-benar dihapus dari *database* (`DELETE`). Kita menggunakan `SoftDeletes` bawaan Laravel yang hanya akan mengisi kolom `deleted_at`. Data tidak tampil di aplikasi, tapi masih ada di *database* jika terjadi audit atau kesalahan sistem.
3. **Tipe Data JSON untuk `informasi_jurnal`:** Ini adalah praktik *schema-less* yang baik di dalam *database* relasional. Data seperti URL, ISSN, atau DOI bisa saja bertambah parameternya di masa depan. Menggunakan tipe `JSON` mencegah kita melakukan *Alter Table* terus-menerus. Di Laravel, Anda cukup menggunakan *Attribute Casting* (`protected $casts = ['informasi_jurnal' => 'array'];`), dan data tersebut bisa diperlakukan seperti *array* PHP biasa.