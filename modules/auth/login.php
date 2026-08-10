<?php
session_start();
require_once '../../config/koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../../dashboard.php");
    exit();
}

$error = '';

//tangkap data form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    //cek apakah NPM/NIDN dan password tidak kosong
    if (empty($username) || empty($password)) {
        $error = 'NPM / NIDN dan password wajib diisi!';
    } else {
        try {
            //menampilkan data user dari database berdasarkan username (NPM/NIDN)
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            //verifikasi password
            if ($user && password_verify($password, $user['password'])) {
                // Simpan data ke dalam $_SESSION
                $_SESSION['user_id']  = $user['id_user'];
                $_SESSION['username'] = $user['username']; // berisi NPM atau NIDN
                $_SESSION['nama']     = $user['nama'];     // berisi Nama penguna
                $_SESSION['role']     = $user['role'];

                // Direct ke dashboard utama yang bertindak sebagai dispatcher role
                header("Location: ../../dashboard.php");
                exit();
            } else {
                $error = 'NPM / NIDN atau password salah!';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }
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
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10">
    <!-- Login Card Container -->
    <div class="w-full max-w-[560px] bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-8 sm:px-10 sm:py-12 md:px-14 md:py-16">
        
        <!-- Logo / Brand Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 tracking-tight">Sistem Absensi Lab</h1>
        </div>

        <!-- Error Notification -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 rounded bg-red-50 border border-red-200 text-red-600 text-base sm:text-lg">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="" class="space-y-6">
            <div>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus
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
            <a href="#" class="text-base sm:text-lg text-[#1867c0] hover:underline font-medium">Lost password?</a>
        </div>
        
        <div class="mt-10 border-t border-gray-200 pt-8">
            <h2 class="text-base sm:text-lg md:text-xl font-semibold text-gray-800 mb-5">Log in using your account on:</h2>
            <div class="space-y-4">
                <button type="button" class="w-full bg-white border border-gray-300 rounded-md py-3.5 sm:py-4 px-4 text-base sm:text-lg md:text-xl text-gray-700 hover:bg-gray-50 flex items-center justify-center gap-3 transition">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/><path fill="none" d="M1 1h22v22H1z"/></svg>
                    Google
                </button>
            </div>
        </div>

    </div>
</body>
</html>
