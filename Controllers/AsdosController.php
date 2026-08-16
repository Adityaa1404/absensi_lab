<?php

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Kegiatan.php';
require_once __DIR__ . '/../Models/Pendaftaran.php';
require_once __DIR__ . '/../Models/Absensi.php';

/**
 * Controller Asdos
 * 
 * Menangani seluruh fitur panel asdos.
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
        $kegiatan = $kegiatanModel->getAllOpen();
        require_once __DIR__ . '/../Views/Asdos/Marketplace.php';
    }

    public function dashboard(): void
    {
        $this->requireAsdos();
        $db = Database::getInstance()->getConnection();
        $pendaftaranModel = new Pendaftaran($db);
        $asdosId = (int) $_SESSION['user_id'];
        $pendaftaran = $pendaftaranModel->getByAsdos((int) $_SESSION['user_id']);
        require_once __DIR__ . '/../Views/Asdos/Dashboard.php';
    }

    public function daftar(): void
    {
        $this ->requireAsdos();
        $db = Database::getInstance()->getConnection();
        $kegiatanModel = new Kegiatan($db);
        $pendaftaranModel = new Pendaftaran($db);
        $kegiatanId = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

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
                $pendaftaran = $pendaftaranModel
                    ->findByKegiatanAndAsdos(
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

    public function absensi(): void
    {
        $this->requireAsdos();
        $db = Database::getInstance()->getConnection();
        $absensiModel = new Absensi($db);
        $asdosId = (int) $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $pendaftaranId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
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

        $pendaftaranId = filter_input(INPUT_POST, 'pendaftaran_id', FILTER_VALIDATE_INT);
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
            'tanggal' => $tanggal,
            'deskripsi_tugas' => $deskripsiTugas,
            'foto_kegiatan' => $fotoKegiatan,
            'foto_selfie' => $fotoSelfie
        ]);

        $_SESSION['absensi_success'] = 'Absensi berhasil dikirim. Menunggu verifikasi dosen.';
        header('Location: index.php?page=asdos/dashboard');
        exit();
    }

    private function uploadFoto(array $file, string $prefix): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            exit ('Terjadi kesalahan saat mengunggah file.');
        }

        $allowedTypes = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'];

        if (!isset($allowedTypes[$file['type']])) {
            exit('Format file tidak didukung. Hanya JPG, PNG, dan WEBP yang diperbolehkan.');
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            exit('Ukuran file terlalu besar. Maksimal 5MB.');
        }
        $extension = $allowedTypes[$file['type']];
        $uploadDir = __DIR__ . '/../uploads/absensi/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = $prefix . '_' . $_SESSION['user_id'] . '_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            exit('Gagal memindahkan file ke direktori tujuan.');
        }

        return 'uploads/absensi/' . $filename;
    }
    
}
