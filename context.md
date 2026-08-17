Percakapan: Perencanaan Pembuatan Marketplace Internal dengan PHP Native MVC
Tanggal: 18 Agustus 2026
Topik: Perencanaan awal pembuatan marketplace internal untuk laboratorium jurusan Sistem Informasi

👤 User (Pertanyaan)
apa hal awal yang harus direncanakan dan diperhatikan dalam pembuatan program menggunakan php native dengan konsep MVC. aku ingin membuat sebuah marketplace internal untuk lab jurusan sistem informasi. marketplace yang dimaksud adalah, jika dosen di jurusan sistem informasi membutuhkan asisten dosen untuk membantunya melaksanakan praktikum atau lain hal. dosen tersebut bisa push kegiatan yang ingin dia laksanakan, dan detail informasi terkait kegiatan tersebut. kemudian asisten dosen yang telah terdaftar bisa melihat marketplace yang isinya kegiatan yang di push oleh dosen tersebut, nantinya akan ada penyeleksian calon asisten dosen untuk kegiatan yang di push dosen yang peminatnya banyak. akan diseleksi langsung oleh dosen terkait

🤖 Assistant (Jawaban)
Membuat marketplace internal dengan PHP native dan konsep MVC adalah proyek yang menantang namun sangat baik untuk belajar. Keberhasilan proyek ini sangat ditentukan oleh perencanaan awal yang matang. Berikut adalah langkah-langkah dan hal-hal penting yang harus Anda rencanakan dan perhatikan.

🎯 1. Analisis Kebutuhan & Perencanaan Fungsional
Langkah pertama yang paling krusial adalah memahami secara mendalam apa yang sistem Anda butuhkan. Jangan mulai menulis kode sebelum ini benar-benar jelas.

Identifikasi Aktor dan Tugasnya:

Dosen (Pengaju Kegiatan): Bisa push kegiatan baru, melihat daftar kegiatan yang dia buat, melihat daftar pendaftar, menyeleksi calon asisten, serta memverifikasi absensi dan bukti pelaksanaan tugas asisten.

Asisten Dosen (Pendaftar & Pelaksana): Bisa melihat marketplace (daftar semua kegiatan yang tersedia), mendaftar pada kegiatan, melihat status pendaftaran, serta mengisi absensi pelaksanaan tugas (upload bukti/foto kegiatan dan deskripsi tugas) pada kegiatan yang telah diterima.

Admin (Pengelola Sistem): Mengelola data master (misalnya, daftar dosen, daftar asisten), dan mengatur konfigurasi sistem.

Definisikan Alur Utama (User Flow):

Alur Dosen: Login → Dashboard → Buat Kegiatan Baru (isi detail: judul, deskripsi, tanggal, kualifikasi) → Lihat daftar pendaftar → Seleksi/Tentukan asisten terpilih → Pantau & Verifikasi absensi pelaksanaan tugas asdos (terima/tolak dengan catatan).

Alur Asisten: Login → Lihat Marketplace → Cari/Filter Kegiatan → Daftar pada kegiatan → Lihat status pendaftaran ("Menunggu", "Diterima", "Ditolak") → Jika Diterima: Melaksanakan tugas & Mengisi form absensi/laporan tugas (tanggal, deskripsi, foto bukti) → Pantau status verifikasi absensi dari dosen.

Tentukan Fitur Minimum (MVP - Minimum Viable Product): Fokus pada fitur inti terlebih dahulu. Jangan langsung membuat fitur yang kompleks.

Modul Autentikasi: Login, Logout, Registrasi (dengan role dosen/asisten).

Modul Dosen: Create, Read, Update, Delete (CRUD) untuk kegiatan yang dia buat.

Modul Asisten: Melihat daftar kegiatan dan mendaftar.

Modul Seleksi: Dosen dapat melihat daftar pendaftar dan mengubah status mereka (misal, dari "menunggu" menjadi "diterima").

Modul Absensi & Verifikasi Tugas: Asisten mengisi absensi pelaksanaan tugas dan mengunggah bukti, lalu dosen memverifikasi absensi tersebut.

🏗️ 2. Perancangan Database
Ini adalah fondasi aplikasi Anda. Struktur tabel yang baik akan memudahkan pengembangan selanjutnya.

Tabel users: Menyimpan data semua pengguna.
id, nama, email, identity_number (NPM/NIDN), no_hp, password (di-hash), role ('dosen' atau 'asisten'), created_at.

Tabel kegiatan: Menyimpan data kegiatan yang di-push dosen.
id, dosen_id (foreign key ke users), nama_kegiatan, periode_mulai, periode_selesai, deskripsi_tugas, insentif, kuota, status ('open', 'closed'), created_at.

Tabel pendaftaran_kegiatan: Menyimpan data pendaftaran asisten pada suatu kegiatan (tabel penghubung antara users dan kegiatan).
id_pendaftaran, kegiatan_id (foreign key ke kegiatan), asdos_id (foreign key ke users), status_pendaftaran ('pending', 'diterima', 'ditolak'), created_at.

Tabel absensi: Menyimpan data pencatatan absensi dan bukti pelaksanaan tugas oleh asisten yang telah diterima.
id_absensi, pendaftaran_id (foreign key ke pendaftaran_kegiatan), tanggal, deskripsi_tugas, foto_kegiatan, foto_selfie, status_verifikasi ('pending', 'disetujui', 'ditolak'), pesan_dosen, created_at.

Tabel log_aktivitas (Opsional): Untuk mencatat aktivitas penting seperti siapa yang mendaftar kapan, siapa yang mengubah status, dll.

🧩 3. Perancangan Arsitektur MVC
Dengan arsitektur MVC, Anda memisahkan kode berdasarkan tanggung jawabnya. Ini membuat kode lebih teratur, mudah dirawat, dan dikembangkan.

Model: Bertanggung jawab untuk semua interaksi dengan database dan logika bisnis.
- UserModel: Untuk operasi terkait pengguna (login, registrasi, profil).
- KegiatanModel: Untuk operasi CRUD kegiatan.
- PendaftaranModel: Untuk operasi pendaftaran dan seleksi calon asisten.
- AbsensiModel: Untuk pencatatan absensi pelaksanaan tugas dan verifikasi dosen.

View: Bagian antarmuka yang dilihat pengguna (HTML/PHP terpisah rapi dengan template Header & Footer).
- Auth: login.php, register.php
- Dosen: profil.php, kegiatan_push.php, seleksi.php, verifikasi.php
- Asdos: marketplace.php, riwayat_pendaftaran.php, absensi_tugas.php
- Templates: HeaderDosen.php, HeaderAsdos.php, Footer.php

Controller: Perantara antara Model dan View.
- AuthController: Menangani login, logout, registrasi.
- DosenController: Menangani profil dosen, CRUD kegiatan, seleksi pelamar, dan verifikasi absensi.
- AsdosController: Menangani tampilan marketplace, aksi pendaftaran kegiatan, dan input absensi tugas.

🔐 4. Keamanan (Prioritas Utama!)
Karena ini sistem internal, keamanan tetap tidak bisa ditawar.

Otentikasi & Otorisasi: Pastikan hanya pengguna yang login yang bisa mengakses fitur. Bedakan akses antara dosen dan asisten melalui session guard (contoh: `requireDosen()` dan `requireAsdos()`).

Proteksi Password: Gunakan fungsi password_hash() dan password_verify() di PHP.

SQL Injection: Selalu gunakan Prepared Statements dengan PDO untuk semua query database.

XSS (Cross-Site Scripting): Bersihkan semua output yang ditampilkan ke user dengan fungsi htmlspecialchars().

CSRF (Cross-Site Request Forgery): Tambahkan token unik pada setiap form untuk mencegah serangan CSRF.

Upload Security: Validasi tipe file (MIME type / ekstensi gambar), batas ukuran upload, dan simpan dengan nama acak/unik untuk bukti absensi.

🛠️ 5. Persiapan Lingkungan Pengembangan
Web Server: Apache atau Nginx (Laragon / XAMPP).
PHP: PHP 8.x.
Database: MySQL / MariaDB.

📂 6. Struktur Folder Proyek
Struktur folder yang rapi sangat penting:

absensi_lab/
├── app/
│   ├── Controllers/       # Controller (AuthController.php, DosenController.php, AsdosController.php)
│   ├── Models/            # Model (User.php, Kegiatan.php, Pendaftaran.php, Absensi.php)
│   └── Views/             # View (Auth/, Dosen/, Asdos/, Templates/)
├── core/                  # Class inti (Database.php)
├── public/                # Document root server
│   ├── assets/            # File CSS, JS, gambar, uploads/
│   └── index.php          # Entry point utama (Front Controller)
├── absensi_lab.sql        # Database dump / schema
└── context.md             # Dokumentasi konteks proyek

📝 7. Rencana Pengembangan Bertahap
Kerjakan secara bertahap dan terstruktur:

Tahap 1: Fondasi: Setup struktur folder MVC, koneksi PDO Database Singleton, dan Front Controller (index.php).
Tahap 2: Autentikasi: Buat registrasi dan login multi-role (Dosen & Asdos) dengan hashing password.
Tahap 3: Fitur Dosen (Kegiatan): CRUD kegiatan/praktikum oleh dosen beserta kuota & status.
Tahap 4: Fitur Asisten (Marketplace & Pendaftaran): Halaman marketplace, filter kegiatan, dan submit pendaftaran asdos.
Tahap 5: Fitur Seleksi Dosen: Dosen meninjau pelamar dan menentukan status penerimaan asisten.
Tahap 6: Fitur Absensi & Verifikasi Tugas: Asdos yang diterima mengisi absensi & upload bukti tugas, dosen memverifikasi absensi.
Tahap 7: Penyempurnaan & Keamanan: Penambahan CSRF token, validasi upload, perbaikan UI/UX, dan pengujian menyeluruh.