<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../Models/User.php';

/**
 * Controller Autentikasi
 * 
 * Menangani alur Login, Register, dan Logout.
 * Setiap method mengambil data dari Model, memproses logika bisnis,
 * kemudian memanggil View yang sesuai atau melakukan redirect.
 */
class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $pdo             = Database::getInstance()->getConnection();
        $this->userModel = new User($pdo);
    }

    // =========================================================================
    // LOGIN
    // =========================================================================

    /**
     * Menampilkan halaman form login (GET).
     */
    public function showLogin(): void
    {
        // Redirect kalau sudah login
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
        }

        // Ambil & hapus flash message dari session (PRG Pattern)
        $error   = $_SESSION['login_error']   ?? '';
        $success = $_SESSION['login_success'] ?? '';
        unset($_SESSION['login_error'], $_SESSION['login_success']);

        // Pesan sukses dari registrasi
        if (isset($_GET['message']) && $_GET['message'] === 'registered') {
            $success = 'Registrasi berhasil! Silakan login dengan akun Anda.';
        }

        require_once __DIR__ . '/../Views/Auth/Login.php';
    }

    /**
     * Memproses form login (POST).
     */
    public function login(): void
    {
        $identityNumber = trim($_POST['identity_number'] ?? '');
        $password       = trim($_POST['password'] ?? '');

        if (empty($identityNumber) || empty($password)) {
            $_SESSION['login_error'] = 'NPM / NIDN dan password wajib diisi!';
            header('Location: index.php?page=login');
            exit();
        }

        try {
            $user = $this->userModel->findByIdentity($identityNumber);

            if ($user && password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id']         = $user['id_user'];
                $_SESSION['identity_number'] = $user['identity_number'];
                $_SESSION['no_hp']           = $user['no_hp'];
                $_SESSION['nama']            = $user['nama'];
                $_SESSION['role']            = $user['role'];
                $_SESSION['email']           = $user['email'];

                $this->redirectToDashboard();
            } else {
                $_SESSION['login_error'] = 'NPM / NIDN atau password salah!';
                header('Location: index.php?page=login');
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['login_error'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
            header('Location: index.php?page=login');
            exit();
        }
    }

    // =========================================================================
    // REGISTER
    // =========================================================================

    /**
     * Menampilkan halaman form registrasi (GET).
     */
    public function showRegister(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
        }

        $error   = $_SESSION['register_error']   ?? '';
        $success = $_SESSION['register_success'] ?? '';
        unset($_SESSION['register_error'], $_SESSION['register_success']);

        require_once __DIR__ . '/../Views/Auth/Register.php';
    }

    /**
     * Memproses form registrasi (POST).
     */
    public function register(): void
    {
        $nama           = trim($_POST['nama'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $password       = $_POST['password'] ?? '';
        $identityNumber = trim($_POST['identity_number'] ?? '');
        $noHp           = trim($_POST['no_hp'] ?? '');
        $role           = trim($_POST['role'] ?? '');

        // Normalisasi nomor HP agar diawali dengan 08
        if (!empty($noHp)) {
            if (str_starts_with($noHp, '+62')) {
                $noHp = '0' . substr($noHp, 3);
            } elseif (str_starts_with($noHp, '62')) {
                $noHp = '0' . substr($noHp, 2);
            }
            $noHp = preg_replace('/[^0-9]/', '', $noHp);
        }

        // Validasi
        if (empty($nama) || empty($email) || empty($password) || empty($identityNumber) || empty($role)) {
            $_SESSION['register_error'] = 'Semua kolom wajib diisi!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['register_error'] = 'Format email tidak valid!';
        } elseif (!empty($noHp) && !preg_match('/^08[0-9]{8,11}$/', $noHp)) {
            $_SESSION['register_error'] = 'Nomor HP harus diawali dengan 08 dan terdiri dari 10-13 digit angka!';
        } else {
            try {
                $this->userModel->create([
                    'nama'            => $nama,
                    'email'           => $email,
                    'identity_number' => $identityNumber,
                    'password'        => password_hash($password, PASSWORD_BCRYPT),
                    'role'            => $role,
                    'no_hp'           => $noHp,
                ]);

                header('Location: index.php?page=login&message=registered');
                exit();
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['register_error'] = 'Email atau NPM/NIDN sudah terdaftar!';
                } else {
                    $_SESSION['register_error'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
                }
            }
        }

        header('Location: index.php?page=register');
        exit();
    }

    // =========================================================================
    // LOGOUT
    // =========================================================================

    /**
     * Menghapus semua session dan redirect ke halaman login.
     */
    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: index.php?page=login');
        exit();
    }

    // =========================================================================
    // HELPER
    // =========================================================================

    /**
     * Redirect ke halaman dashboard sesuai role user.
     */
    private function redirectToDashboard(): void
    {
        $role = $_SESSION['role'] ?? '';
        if ($role === 'dosen') {
            header('Location: index.php?page=dosen/profil');
        } elseif ($role === 'asdos') {
            header('Location: index.php?page=asdos/marketplace');
        } else {
            header('Location: index.php?page=login');
        }
        exit();
    }
}