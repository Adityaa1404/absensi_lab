<?php
session_start();
require_once '../../config/koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../../dashboard.php");
    exit();
}

// Ambil dan hapus notifikasi session (flash message) agar hilang saat halaman di-refresh
$error = $_SESSION['login_error'] ?? '';
$success = $_SESSION['login_success'] ?? '';
unset($_SESSION['login_error'], $_SESSION['login_success']);

if (isset($_GET['message']) && $_GET['message'] === 'registered') {
    $success = 'Registrasi berhasil! Silakan login dengan akun Anda.';
}

//tangkap data form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity_number = trim($_POST['identity_number'] ?? '');
    $password = trim($_POST['password'] ?? '');

    //cek apakah NPM/NIDN dan password tidak kosong
    if (empty($identity_number) || empty($password)) {
        $_SESSION['login_error'] = 'NPM / NIDN dan password wajib diisi!';
    } else {
        try {
            //menampilkan data user dari database berdasarkan identity_number (NPM/NIDN)
            $stmt = $pdo->prepare("SELECT * FROM users WHERE identity_number = :identity_number LIMIT 1");
            $stmt->execute(['identity_number' => $identity_number]);
            $user = $stmt->fetch();

            //verifikasi password
            if ($user && password_verify($password, $user['password'])) {
                // Simpan data ke dalam $_SESSION
                $_SESSION['user_id']         = $user['id_user'];
                $_SESSION['identity_number'] = $user['identity_number'];
                $_SESSION['no_hp']           = $user['no_hp'];
                $_SESSION['nama']            = $user['nama'];            
                $_SESSION['role']            = $user['role'];
                $_SESSION['email']           = $user['email'];

                // Direct ke dashboard utama yang bertindak sebagai dispatcher role
                header("Location: ../../dashboard.php");
                exit();
            } else {
                $_SESSION['login_error'] = 'NPM / NIDN atau password salah!';
            }
        } catch (PDOException $e) {
            $_SESSION['login_error'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }

    // Redirect kembali ke login (PRG Pattern) agar tidak re-submit saat refresh & pesan hilang di refresh berikutnya
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10">
    <!-- Login Card Container -->
    <div class="w-full max-w-[560px] bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-8 sm:px-10 sm:py-12 md:px-14 md:py-16">

        <!-- Logo / Brand Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-3xl md:text-4xl font-bold text-gray-800 tracking-tight">Sistem Absensi Asdos</h1>
        </div>

        <!-- Success Notification -->
        <?php if (!empty($success)): ?>
            <div class="mb-6 p-4 rounded bg-green-50 border border-green-200 text-green-700 text-base sm:text-lg">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Error Notification -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 rounded bg-red-50 border border-red-200 text-red-600 text-base sm:text-lg">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="" class="space-y-6">
            <div>
                <input type="text" id="identity_number" name="identity_number" required autofocus
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]"
                    placeholder="NPM/NIDN">
            </div>

            <div>
                <input type="password" id="password" name="password" required
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]"
                    placeholder="Password">
            </div>

            <button type="submit"
                class="bg-[#1867c0] hover:bg-[#1355a1] active:bg-[#0f4482] text-white font-medium py-3 px-8 sm:py-3.5 sm:px-10 text-base sm:text-lg md:text-xl rounded-md transition duration-200 mt-2">
                Log in
            </button>
        </form>

        <div class="mt-6">
            <a href="register.php" class="text-base sm:text-lg text-[#1867c0] hover:underline font-medium">Belum punya akun? Daftar disini</a>
        </div>
        <div class="mt-6">
            <a href="#" class="text-base sm:text-lg text-[#1867c0] hover:underline font-medium">Lupa Password?</a>
        </div>

    </div>
</body>

</html>