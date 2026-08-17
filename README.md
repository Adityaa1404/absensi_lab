# 🎓 Sistem Marketplace & Absensi Laboratorium Sistem Informasi

Sistem informasi berbasis web yang dibangun menggunakan **PHP Native** dengan pola arsitektur **Model-View-Controller (MVC)**. Aplikasi ini dirancang sebagai platform internal laboratorium jurusan Sistem Informasi untuk memfasilitasi publikasi kegiatan/praktikum oleh dosen, pendaftaran & seleksi asisten dosen (*marketplace*), serta pelaporan absensi dan verifikasi tugas asisten.

---

## 📌 Fitur Utama

### 1. 🔐 Autentikasi & Multi-Role
- **Registrasi & Login Akun:** Mendukung dua peran utama (**Dosen** dan **Asisten Dosen / Asdos**).
- **Keamanan Akun:** Password dienkripsi menggunakan `password_hash()` (Bcrypt).
- **Session Guard:** Otorisasi berbasis peran (`requireDosen()` dan `requireAsdos()`) untuk melindungi rute dan controller.

### 2. 👨‍🏫 Modul Dosen
- **Kelola Profil:** Update data diri (Nama, NIDN, Email, No. HP) dan ganti password.
- **CRUD Kegiatan Praktikum:** Publikasi kegiatan baru, edit data, atur kuota, status kegiatan (*open/closed*), dan insentif.
- **Verifikasi Absensi Tugas:** Meninjau laporan pelaksanaan tugas asisten (foto bukti kegiatan, foto selfie kehadiran) dan memberikan persetujuan (*disetujui/ditolak*) beserta catatan evaluasi.

### 3. 🧑‍🎓 Modul Asisten Dosen (Asdos)
- **Marketplace Kegiatan:** Menjelajahi daftar kegiatan laboratorium yang sedang dibuka oleh dosen.
- **Pendaftaran Kegiatan:** Melamar ke kegiatan praktikum yang diminati dengan menyertakan motivasi/pesan lamaran.
- **Pelaporan & Absensi Tugas:** Mengisi logbook/absensi harian pelaksanaan tugas praktikum dan mengunggah foto bukti kegiatan.

---

## 🏗️ Arsitektur & Struktur Folder

Proyek ini menggunakan arsitektur MVC terstruktur yang memisahkan logika bisnis, antarmuka pengguna, dan perutean:

```text
absensi_lab/
├── app/
│   ├── Controllers/             # Logika pengendali request
│   │   ├── AsdosController.php  # Menangani alur marketplace & absensi asdos
│   │   ├── AuthController.php   # Menangani login, registrasi, dan logout
│   │   └── DosenController.php  # Menangani profil, CRUD kegiatan, dan verifikasi
│   ├── Models/                  # Logika data & query database (PDO)
│   │   ├── Absensi.php          # Operasi tabel absensi
│   │   ├── Kegiatan.php         # Operasi tabel kegiatan
│   │   └── User.php             # Operasi tabel users
│   └── Views/                   # Antarmuka tampilan (UI/HTML)
│       ├── Asdos/               # Tampilan panel asisten (Marketplace.php, dll.)
│       ├── Auth/                # Tampilan autentikasi (Login.php, Register.php)
│       ├── Dosen/               # Tampilan panel dosen (Profil.php, KegiatanPush.php, Verifikasi.php)
│       └── Templates/           # Template layout (HeaderDosen.php, HeaderAsdos.php, Footer.php)
├── core/
│   └── Database.php             # Class koneksi PDO dengan Singleton Pattern
├── public/                      # Document Root Web Server
│   ├── assets/                  # Berkas statis publik
│   │   ├── css/                 # Custom stylesheet
│   │   ├── js/                  # Client-side JavaScript
│   │   └── uploads/             # Berkas unggahan pengguna
│   │       ├── kegiatan/        # Foto bukti kegiatan
│   │       └── selfie/          # Foto selfie kehadiran
│   └── index.php                # Front Controller & Router Utama
├── absensi_lab.sql              # Skema DDL & DML Database MySQL
├── context.md                   # Dokumen perencanaan & spesifikasi sistem
└── README.md                    # Dokumentasi proyek
```

---

## 🛠️ Stack Teknologi

- **Backend:** PHP 8.x (PHP Native MVC)
- **Database:** MySQL / MariaDB (Koneksi PDO dengan *Prepared Statements*)
- **Frontend / Styling:** HTML5, Tailwind CSS (via CDN), Google Fonts (Inter)
- **Web Server:** Apache / Nginx (Mendukung Laragon, XAMPP, atau Docker)

---

## 🗄️ Skema Database

Aplikasi menggunakan database MySQL bernama `absensi_lab` yang terdiri dari 4 tabel utama:

1. **`users`**: Menyimpan kredensial dan biodata dosen serta asdos (`id_user`, `nama`, `identity_number`, `email`, `no_hp`, `password`, `role`, `created_at`).
2. **`kegiatan`**: Menyimpan kegiatan praktikum yang dipublikasikan dosen (`id_kegiatan`, `dosen_id`, `nama_kegiatan`, `periode_mulai`, `periode_selesai`, `deskripsi_tugas`, `insentif`, `kuota`, `status`, `created_at`).
3. **`pendaftaran_kegiatan`**: Relasi *many-to-many* antara kegiatan dan asdos pendaftar (`id_pendaftaran`, `kegiatan_id`, `asdos_id`, `status_pendaftaran`, `pesan_lamaran`, `catatan_dosen`, `created_at`).
4. **`absensi`**: Log kehadiran dan bukti pelaksanaan tugas praktikum (`id_absensi`, `pendaftaran_id`, `tanggal`, `pertemuan_ke`, `jam_mulai`, `jam_selesai`, `deskripsi_tugas`, `foto_kegiatan`, `foto_selfie`, `status_verifikasi`, `pesan_dosen`, `created_at`).

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat
- PHP versi 8.0 ke atas.
- MySQL Server (aktif di Laragon / XAMPP).

### 2. Setup Database
1. Buka phpMyAdmin, HeidiSQL, atau MySQL CLI.
2. Buat database baru bernama `absensi_lab`:
   ```sql
   CREATE DATABASE absensi_lab;
   ```
3. Import file `absensi_lab.sql` ke dalam database `absensi_lab`.

### 3. Konfigurasi Koneksi Database
Buka file [`core/Database.php`](file:///c:/laragon/www/absensi_lab/core/Database.php) dan sesuaikan konfigurasi jika diperlukan:
```php
private string $host     = 'localhost';
private string $dbName   = 'absensi_lab';
private string $user     = 'root';
private string $password = '';
```

### 4. Menjalankan Aplikasi
- **Melalui Laragon / XAMPP:**
  Akses aplikasi melalui browser di:
  ```text
  http://localhost/absensi_lab/public/index.php
  ```
- **Melalui PHP Built-in Server:**
  Buka terminal di folder root proyek, lalu jalankan:
  ```bash
  php -S localhost:8000 -t public
  ```
  Kemudian buka browser di: `http://localhost:8000`

---

## 🚦 Rute & Parameter Navigasi

Aplikasi menggunakan pola *Front Controller* berbasis parameter `?page=`:

| Halaman | Parameter URL | Controller & Handler Method |
| :--- | :--- | :--- |
| **Login** | `index.php?page=login` | `AuthController::showLogin()` / `login()` |
| **Registrasi** | `index.php?page=register` | `AuthController::showRegister()` / `register()` |
| **Logout** | `index.php?page=logout` | `AuthController::logout()` |
| **Profil Dosen** | `index.php?page=dosen/profil` | `DosenController::profil()` |
| **Kelola Kegiatan** | `index.php?page=dosen/kegiatan` | `DosenController::kegiatan()` |
| **Verifikasi Absensi** | `index.php?page=dosen/verifikasi` | `DosenController::verifikasi()` |
| **Marketplace Asdos** | `index.php?page=asdos/marketplace` | `AsdosController::marketplace()` |

---

## 🔒 Standar Keamanan yang Diterapkan

- **SQL Injection Prevention:** 100% interaksi query database menggunakan PDO *Prepared Statements* dan parameter binding.
- **XSS Prevention:** Output dinamis dari user di-render menggunakan `htmlspecialchars()`.
- **Password Security:** Hashing satu arah yang aman menggunakan algoritma standar industri (`PASSWORD_BCRYPT` / `PASSWORD_DEFAULT`).
