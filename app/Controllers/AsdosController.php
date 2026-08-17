<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Kegiatan.php';
require_once __DIR__ . '/../Models/Pendaftaran.php';
require_once __DIR__ . '/../Models/Absensi.php';

/**
 * Controller Asdos
 * 
 * Menangani seluruh fitur panel asdos:
 * - marketplace() : Menampilkan daftar kegiatan yang buka dan belum didaftari
 * - dashboard()   : Menampilkan pendaftaran dan riwayat absensi asdos
 * - daftar()      : Memproses pendaftaran ke kegiatan
 * - profil()      : Menampilkan & update data profil dan ganti password asdos
 * - absensi()     : Form dan proses submit laporan tugas/absensi asdos
 */
class AsdosController
{
    private function requireAsdos(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asdos') {
            header('Location: index.php?page=login');
            exit();
        }
    }

    public function marketplace(): void
    {
        $this->requireAsdos();
        $db = Database::getInstance()->getConnection();
        $kegiatanModel = new Kegiatan($db);
        $asdosId = (int) $_SESSION['user_id'];
        $kegiatanList = $kegiatanModel->getMarketplaceByAsdos($asdosId);
        require_once __DIR__ . '/../Views/Asdos/Marketplace.php';
    }

    public function dashboard(): void
    {
        $this->requireAsdos();
        $db = Database::getInstance()->getConnection();
        $pendaftaranModel = new Pendaftaran($db);
        $absensiModel = new Absensi($db);
        $asdosId = (int) $_SESSION['user_id'];
        $pendaftaran = $pendaftaranModel->getByAsdos($asdosId);
        foreach ($pendaftaran as &$item) {
            $item['absensi'] = $absensiModel->getByPendaftaran(
                (int) $item['id_pendaftaran']
            );
        }
        unset($item);

        require_once __DIR__ . '/../Views/Asdos/Dashboard.php';
    }

    public function daftar(): void
    {
        $this->requireAsdos();
        $db = Database::getInstance()->getConnection();
        $kegiatanModel = new Kegiatan($db);
        $pendaftaranModel = new Pendaftaran($db);
        $kegiatanId = (int) ($_GET['id'] ?? 0);

        if (!$kegiatanId) {
            header('Location: index.php?page=asdos/marketplace');
            exit();
        }

        $asdosId = (int) $_SESSION['user_id'];
        $message = '';
        $messageType = '';

        try {
            $kegiatan = $kegiatanModel->findOpenById($kegiatanId);
            if (!$kegiatan) {
                $message = 'Kegiatan tidak ditemukan atau sudah tidak tersedia.';
                $messageType = 'error';
            } else {
                $pendaftaran = $pendaftaranModel->findByKegiatanAndAsdos(
                    $kegiatanId,
                    $asdosId
                );

                if ($pendaftaran) {
                    switch ($pendaftaran['status_pendaftaran']) {
                        case 'pending':
                            $message = 'Kamu sudah mendaftar kegiatan ini. Pendaftaran masih menunggu verifikasi dosen.';
                            break;

                        case 'diterima':
                            $message = 'Kamu sudah diterima pada kegiatan ini.';
                            break;

                        case 'ditolak':
                            $message = 'Pendaftaran kamu untuk kegiatan ini sebelumnya ditolak.';
                            break;

                        default:
                            $message = 'Kamu sudah memiliki pendaftaran pada kegiatan ini.';
                    }
                    $messageType = 'info';
                } else {
                    $pendaftaranModel->create(
                        $kegiatanId,
                        $asdosId
                    );

                    $message = 'Berhasil mendaftar kegiatan. Pendaftaran kamu sedang menunggu verifikasi dosen.';
                    $messageType = 'success';
                }
            }

        } catch (PDOException $e) {
            $message = 'Terjadi kesalahan saat memproses pendaftaran.';
            $messageType = 'error';
        }

        require_once __DIR__ . '/../Views/Asdos/Daftar.php';
    }

    public function profil(): void
    {
        $this->requireAsdos();
        $db = Database::getInstance()->getConnection();
        $userModel = new User($db);
        $userId = (int) $_SESSION['user_id'];
        $user = $userModel->findById($userId);
        if (!$user) {
            session_destroy();
            header('Location: index.php?page=login');
            exit();
        }
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'update_profile') {
                $nama = trim($_POST['nama'] ?? '');
                $identityNumber = trim($_POST['identity_number'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $noHp = trim($_POST['no_hp'] ?? '');

                if ($nama === '' || $identityNumber === '' || $email === '') {
                    $error = 'Nama, NIM/NPM, dan email wajib diisi.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Format email tidak valid.';
                } elseif ($userModel->isIdentityTaken($identityNumber, $userId)) {
                    $error = 'NIM/NPM tersebut sudah digunakan oleh pengguna lain.';
                } else {
                    try {
                        $userModel->update($userId, [
                            'nama' => $nama,
                            'identity_number' => $identityNumber,
                            'email' => $email,
                            'no_hp' => $noHp,
                        ]);

                        $_SESSION['nama'] = $nama;
                        $_SESSION['identity_number'] = $identityNumber;
                        $user = $userModel->findById($userId);
                        $success = 'Profil berhasil diperbarui.';
                    } catch (PDOException $e) {
                        $error = 'Gagal memperbarui profil: ' . $e->getMessage();
                    }
                }
            } elseif ($action === 'change_password') {
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                if (
                    $currentPassword === '' ||
                    $newPassword === '' ||
                    $confirmPassword === ''
                ) {
                    $error = 'Semua kolom password wajib diisi.';
                } elseif ($newPassword !== $confirmPassword) {
                    $error = 'Konfirmasi password baru tidak cocok.';
                } elseif (strlen($newPassword) < 6) {
                    $error = 'Password baru minimal 6 karakter.';
                } elseif (!password_verify($currentPassword, $user['password'])) {
                    $error = 'Password saat ini tidak sesuai.';
                } else {
                    try {
                        $hashedPassword = password_hash(
                            $newPassword,
                            PASSWORD_DEFAULT
                        );
                        $userModel->changePassword(
                            $userId,
                            $hashedPassword
                        );
                        $user['password'] = $hashedPassword;
                        $success = 'Password berhasil diubah.';
                    } catch (PDOException $e) {
                        $error = 'Gagal mengubah password: ' . $e->getMessage();
                    }
                }
            } elseif ($action === 'delete_profile') {
                try {
                    $userModel->delete($userId);
                    session_destroy();
                    header('Location: index.php?page=login&message=deleted');
                    exit();
                } catch (PDOException $e) {
                    $error = 'Gagal menghapus akun: ' . $e->getMessage();
                }
            }
        }
        require_once __DIR__ . '/../Views/Asdos/Profil.php';
    }

    public function absensi(): void
    {
        $this->requireAsdos();
        $db = Database::getInstance()->getConnection();
        $absensiModel = new Absensi($db);
        $asdosId = (int) $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $pendaftaranId = (int) ($_GET['id'] ?? 0);
            if (!$pendaftaranId) {
                header('Location: index.php?page=asdos/dashboard');
                exit();
            }

            $pendaftaran = $absensiModel->getPendaftaranDiterima($pendaftaranId, $asdosId);
            if (!$pendaftaran) {
                http_response_code(403);
                echo "Akses ditolak. Kamu tidak memiliki pendaftaran diterima untuk kegiatan ini.";
                exit();
            }

            require_once __DIR__ . '/../Views/Asdos/Absensi.php';
            return;
        }

        $pendaftaranId = (int) ($_POST['pendaftaran_id'] ?? 0);
        $tanggal = $_POST['tanggal'] ?? '';
        $deskripsiTugas = trim($_POST['deskripsi_tugas'] ?? '');
        if (!$pendaftaranId || !$tanggal || !$deskripsiTugas) {
            $_SESSION['absensi_error'] = 'Semua field wajib diisi.';
            header('Location: index.php?page=asdos/absensi&id=' . urlencode($pendaftaranId));
            exit();
        }

        $pendaftaran = $absensiModel->getPendaftaranDiterima($pendaftaranId, $asdosId);
        if (!$pendaftaran) {
            http_response_code(403);
            exit('Akses ditolak. Pendaftaran tidak ditemukan atau tidak diterima.');
        }

        if (!isset($_FILES['foto_kegiatan']) || !isset($_FILES['foto_selfie'])) {
            $_SESSION['absensi_error'] = 'Foto kegiatan dan foto selfie wajib diunggah.';
            header('Location: index.php?page=asdos/absensi&id=' . urlencode($pendaftaranId));
            exit();
        }

        $fotoKegiatan = $this->uploadFoto($_FILES['foto_kegiatan'], 'foto_kegiatan');
        $fotoSelfie = $this->uploadFoto($_FILES['foto_selfie'], 'foto_selfie');
        $absensiModel->create([
            'pendaftaran_id' => $pendaftaranId,
            'tanggal'        => $tanggal,
            'deskripsi_tugas'=> $deskripsiTugas,
            'foto_kegiatan'  => $fotoKegiatan,
            'foto_selfie'    => $fotoSelfie
        ]);

        $_SESSION['absensi_success'] = 'Absensi berhasil dikirim. Menunggu verifikasi dosen.';
        header('Location: index.php?page=asdos/dashboard');
        exit();
    }

    private function uploadFoto(array $file, string $prefix): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            exit('Terjadi kesalahan saat mengunggah file.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowedTypes[$mimeType])) {
            exit('Format file tidak didukung. Hanya JPG, PNG, dan WEBP yang diperbolehkan.');
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            exit('Ukuran file terlalu besar. Maksimal 5MB.');
        }

        $extension = $allowedTypes[$mimeType];
        $uploadDir = __DIR__ . '/../../public/assets/uploads/absensi/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = $prefix . '_' . $_SESSION['user_id'] . '_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            exit('Gagal memindahkan file ke direktori tujuan.');
        }

        return 'absensi/' . $filename;
    }
}
