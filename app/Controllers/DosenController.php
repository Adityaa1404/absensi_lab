<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Kegiatan.php';
require_once __DIR__ . '/../Models/Absensi.php';
require_once __DIR__ . '/../Models/Pendaftaran.php';

/**
 * Controller Dosen
 * 
 * Menangani seluruh fitur panel dosen:
 * - profil()      : CRUD profil & ganti password
 * - kegiatan()    : CRUD kegiatan lab
 * - seleksi()     : Tinjau dan seleksi calon asisten dosen
 * - verifikasi()  : Verifikasi absensi asdos
 */
class DosenController
{
    private User        $userModel;
    private Kegiatan    $kegiatanModel;
    private Absensi     $absensiModel;
    private Pendaftaran $pendaftaranModel;

    public function __construct()
    {
        $pdo                  = Database::getInstance()->getConnection();
        $this->userModel      = new User($pdo);
        $this->kegiatanModel  = new Kegiatan($pdo);
        $this->absensiModel   = new Absensi($pdo);
        $this->pendaftaranModel = new Pendaftaran($pdo);
    }

    // =========================================================================
    // GUARD — pastikan hanya dosen yang bisa akses
    // =========================================================================

    private function requireDosen(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
            header('Location: index.php?page=login');
            exit();
        }
    }

    // =========================================================================
    // PROFIL
    // =========================================================================

    public function profil(): void
    {
        $this->requireDosen();

        $error   = '';
        $success = '';

        // Ambil data user dari database
        $user = $this->userModel->findById((int) $_SESSION['user_id']);
        if (!$user) {
            $error = 'Gagal memuat data profil.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];

            // --- Update Profil ---
            if ($action === 'update_profile') {
                $nama           = trim($_POST['nama'] ?? '');
                $identityNumber = trim($_POST['identity_number'] ?? '');
                $email          = trim($_POST['email'] ?? '');
                $noHp           = trim($_POST['no_hp'] ?? '');

                // Normalisasi HP
                if (!empty($noHp)) {
                    if (str_starts_with($noHp, '+62')) $noHp = '0' . substr($noHp, 3);
                    elseif (str_starts_with($noHp, '62')) $noHp = '0' . substr($noHp, 2);
                    $noHp = preg_replace('/[^0-9]/', '', $noHp);
                }

                if (empty($nama) || empty($identityNumber)) {
                    $error = 'Nama dan NIDN tidak boleh kosong!';
                } elseif (!empty($noHp) && !preg_match('/^08[0-9]{8,11}$/', $noHp)) {
                    $error = 'Nomor HP harus diawali dengan 08 dan terdiri dari 10-13 digit angka!';
                } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Format alamat email tidak valid!';
                } elseif ($this->userModel->isIdentityTaken($identityNumber, (int) $_SESSION['user_id'])) {
                    $error = 'NIDN sudah digunakan oleh pengguna lain!';
                } else {
                    try {
                        $this->userModel->update((int) $_SESSION['user_id'], [
                            'nama'            => $nama,
                            'identity_number' => $identityNumber,
                            'email'           => $email,
                            'no_hp'           => $noHp,
                        ]);
                        // Update session
                        $_SESSION['nama']            = $nama;
                        $_SESSION['identity_number'] = $identityNumber;
                        $_SESSION['email']           = $email;
                        $_SESSION['no_hp']           = $noHp;
                        // Refresh data lokal
                        $user['nama']            = $nama;
                        $user['identity_number'] = $identityNumber;
                        $user['email']           = $email;
                        $user['no_hp']           = $noHp;
                        $success = 'Profil berhasil diperbarui!';
                    } catch (PDOException $e) {
                        $error = 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage();
                    }
                }
            }

            // --- Ganti Password ---
            elseif ($action === 'change_password') {
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword     = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                    $error = 'Semua kolom password wajib diisi!';
                } elseif ($newPassword !== $confirmPassword) {
                    $error = 'Konfirmasi password baru tidak cocok!';
                } elseif (strlen($newPassword) < 6) {
                    $error = 'Password baru minimal 6 karakter!';
                } elseif (!password_verify($currentPassword, $user['password'])) {
                    $error = 'Password saat ini tidak sesuai!';
                } else {
                    try {
                        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
                        $this->userModel->changePassword((int) $_SESSION['user_id'], $hashed);
                        $user['password'] = $hashed;
                        $success = 'Password berhasil diubah!';
                    } catch (PDOException $e) {
                        $error = 'Gagal mengubah password: ' . $e->getMessage();
                    }
                }
            }

            // --- Hapus Akun ---
            elseif ($action === 'delete_profile') {
                try {
                    $this->userModel->delete((int) $_SESSION['user_id']);
                    session_destroy();
                    header('Location: index.php?page=login&message=deleted');
                    exit();
                } catch (PDOException $e) {
                    $error = 'Gagal menghapus akun: ' . $e->getMessage();
                }
            }
        }

        require_once __DIR__ . '/../Views/Dosen/Profil.php';
    }

    // =========================================================================
    // KEGIATAN
    // =========================================================================

    public function kegiatan(): void
    {
        $this->requireDosen();

        $error         = '';
        $success       = '';
        $editKegiatan  = null;
        $dosenId       = (int) $_SESSION['user_id'];

        // Flash messages
        if (isset($_SESSION['msg_success'])) { $success = $_SESSION['msg_success']; unset($_SESSION['msg_success']); }
        if (isset($_SESSION['msg_error']))   { $error   = $_SESSION['msg_error'];   unset($_SESSION['msg_error']); }

        // --- GET: Load data untuk edit ---
        if (isset($_GET['edit'])) {
            $editKegiatan = $this->kegiatanModel->findById((int) $_GET['edit'], $dosenId);
            if (!$editKegiatan) {
                $error = 'Kegiatan tidak ditemukan atau Anda tidak memiliki akses.';
            }
        }

        // --- GET: Hapus ---
        if (isset($_GET['delete'])) {
            try {
                $this->kegiatanModel->delete((int) $_GET['delete'], $dosenId);
                $_SESSION['msg_success'] = 'Kegiatan berhasil dihapus!';
                header('Location: index.php?page=dosen/kegiatan');
                exit();
            } catch (PDOException $e) {
                $_SESSION['msg_error'] = 'Gagal menghapus kegiatan: ' . $e->getMessage();
                header('Location: index.php?page=dosen/kegiatan');
                exit();
            }
        }

        // --- POST: Tambah / Edit ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action         = $_POST['action'];
            $namaKegiatan   = trim($_POST['nama_kegiatan'] ?? '');
            $periodeMulai   = trim($_POST['periode_mulai'] ?? '');
            $periodeSelesai = trim($_POST['periode_selesai'] ?? '');
            $deskripsiTugas = trim($_POST['deskripsi_tugas'] ?? '');
            $insentif       = trim($_POST['insentif'] ?? 0);
            $kuota          = (int) ($_POST['kuota'] ?? 0);
            $status         = trim($_POST['status'] ?? 'open');

            // Jika kuota <= 0, status otomatis closed
            if ($kuota <= 0) $status = 'closed';

            if (empty($namaKegiatan) || empty($periodeMulai) || empty($periodeSelesai) || empty($deskripsiTugas)) {
                $error = 'Semua kolom bertanda * wajib diisi!';
            } else {
                try {
                    if ($action === 'add') {
                        $status = ($kuota > 0) ? 'open' : 'closed';
                        $this->kegiatanModel->create([
                            'dosen_id'        => $dosenId,
                            'nama_kegiatan'   => $namaKegiatan,
                            'periode_mulai'   => $periodeMulai,
                            'periode_selesai' => $periodeSelesai,
                            'deskripsi_tugas' => $deskripsiTugas,
                            'insentif'        => $insentif,
                            'status'          => $status,
                            'kuota'           => $kuota,
                        ]);
                        $_SESSION['msg_success'] = 'Kegiatan berhasil dipublikasikan!';
                        header('Location: index.php?page=dosen/kegiatan');
                        exit();

                    } elseif ($action === 'edit') {
                        $idKegiatan = (int) ($_POST['id_kegiatan'] ?? 0);
                        $this->kegiatanModel->update($idKegiatan, $dosenId, [
                            'nama_kegiatan'   => $namaKegiatan,
                            'periode_mulai'   => $periodeMulai,
                            'periode_selesai' => $periodeSelesai,
                            'deskripsi_tugas' => $deskripsiTugas,
                            'insentif'        => $insentif,
                            'status'          => $status,
                            'kuota'           => $kuota,
                        ]);
                        $_SESSION['msg_success'] = 'Kegiatan berhasil diperbarui!';
                        header('Location: index.php?page=dosen/kegiatan');
                        exit();
                    }
                } catch (PDOException $e) {
                    $error = 'Gagal menyimpan kegiatan: ' . $e->getMessage();
                }
            }
        }

        // Ambil daftar kegiatan milik dosen ini
        $kegiatanList = $this->kegiatanModel->getByDosen($dosenId);

        require_once __DIR__ . '/../Views/Dosen/KegiatanPush.php';
    }

    // =========================================================================
    // SELEKSI PELAMAR
    // =========================================================================

    public function seleksi(): void
    {
        $this->requireDosen();

        $error    = '';
        $success  = '';
        $dosenId  = (int) $_SESSION['user_id'];

        // Flash messages
        if (isset($_SESSION['msg_success'])) { $success = $_SESSION['msg_success']; unset($_SESSION['msg_success']); }
        if (isset($_SESSION['msg_error']))   { $error   = $_SESSION['msg_error'];   unset($_SESSION['msg_error']); }

        // POST: Update status pendaftaran (Terima / Tolak)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
            $idPendaftaran = (int) ($_POST['id_pendaftaran'] ?? 0);
            $status        = trim($_POST['status'] ?? '');
            $catatanDosen  = trim($_POST['catatan_dosen'] ?? '');

            if (empty($idPendaftaran) || !in_array($status, ['pending', 'diterima', 'ditolak'])) {
                $_SESSION['msg_error'] = 'Data atau status seleksi tidak valid!';
            } else {
                try {
                    $this->pendaftaranModel->updateStatus($idPendaftaran, $dosenId, $status, $catatanDosen);
                    $_SESSION['msg_success'] = 'Status pelamar berhasil diperbarui menjadi ' . strtoupper($status) . '!';
                } catch (PDOException $e) {
                    $_SESSION['msg_error'] = 'Gagal memperbarui status seleksi: ' . $e->getMessage();
                }
            }
            header('Location: index.php?page=dosen/seleksi' . (isset($_GET['kegiatan_id']) ? '&kegiatan_id=' . (int)$_GET['kegiatan_id'] : ''));
            exit();
        }

        // GET: Filter kegiatan jika ada
        $selectedKegiatanId = isset($_GET['kegiatan_id']) && (int)$_GET['kegiatan_id'] > 0 ? (int)$_GET['kegiatan_id'] : null;

        // Ambil daftar kegiatan milik dosen untuk dropdown filter
        $kegiatanList = $this->kegiatanModel->getByDosen($dosenId);

        // Ambil data pelamar
        $pelamarList = $this->pendaftaranModel->getByDosen($dosenId, $selectedKegiatanId);

        require_once __DIR__ . '/../Views/Dosen/Seleksi.php';
    }

    // =========================================================================
    // VERIFIKASI
    // =========================================================================

    public function verifikasi(): void
    {
        $this->requireDosen();

        $error   = '';
        $success = '';

        // POST: Update status verifikasi
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verifikasi') {
            $idAbsensi       = (int) ($_POST['id_absensi'] ?? 0);
            $statusVerifikasi = trim($_POST['status_verifikasi'] ?? '');
            $pesanDosen      = trim($_POST['pesan_dosen'] ?? '');

            if (empty($idAbsensi) || empty($statusVerifikasi)) {
                $error = 'Pilih status verifikasi!';
            } else {
                try {
                    $this->absensiModel->updateVerifikasi($idAbsensi, $statusVerifikasi, $pesanDosen);
                    $success = 'Status verifikasi absensi berhasil diperbarui!';
                } catch (PDOException $e) {
                    $error = 'Gagal memproses verifikasi: ' . $e->getMessage();
                }
            }
        }

        // Ambil semua data absensi
        $absensiList = $this->absensiModel->getAll();

        require_once __DIR__ . '/../Views/Dosen/Verifikasi.php';
    }
}
