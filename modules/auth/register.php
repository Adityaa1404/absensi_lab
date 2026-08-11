<?php
session_start();
require_once '../../config/koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../../dashboard.php");
    exit();
}

// Ambil dan hapus notifikasi session (flash message) agar hilang saat halaman di-refresh
$error = $_SESSION['register_error'] ?? '';
$success = $_SESSION['register_success'] ?? '';
unset($_SESSION['register_error'], $_SESSION['register_success']);

// Cek apakah form dikirim melalui HTTP POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dan bersihkan dari whitespace
    $nama            = trim($_POST['nama'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $identity_number = trim($_POST['identity_number'] ?? '');
    $no_hp           = trim($_POST['no_hp'] ?? '');
    $role            = trim($_POST['role'] ?? '');
    // Normalisasi nomor HP agar diawali dengan 08
    if (!empty($no_hp)) {
        if (strpos($no_hp, '+62') === 0) {
            $no_hp = '0' . substr($no_hp, 3);
        } elseif (strpos($no_hp, '62') === 0) {
            $no_hp = '0' . substr($no_hp, 2);
        }
        $no_hp = preg_replace('/[^0-9]/', '', $no_hp);
    }

    // Validasi Backend
    if (empty($nama) || empty($email) || empty($password) || empty($identity_number) || empty($role)) {
        $_SESSION['register_error'] = "Semua kolom wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Format email tidak valid!";
    } elseif (!empty($no_hp) && !preg_match('/^08[0-9]{8,11}$/', $no_hp)) {
        $_SESSION['register_error'] = "Nomor HP harus diawali dengan 08 dan terdiri dari 10-13 digit angka!";
    } else {
        try {
            // Hash Password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Query Prepared Statement
            $sql = "INSERT INTO users (nama, email, identity_number, password, role, no_hp) 
                    VALUES (:nama, :email, :identity_number, :password, :role, :no_hp)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nama'            => $nama,
                ':email'           => $email,
                ':identity_number' => $identity_number,
                ':password'        => $hashedPassword,
                ':role'            => $role,
                ':no_hp'           => $no_hp
            ]);

            // Redirect ke halaman login setelah registrasi berhasil
            header("Location: login.php?message=registered");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['register_error'] = "Email atau NPM/NIDN sudah terdaftar!";
            } else {
                $_SESSION['register_error'] = "Terjadi kesalahan sistem: " . $e->getMessage();
            }
        }
    }

    // Redirect kembali ke register (PRG Pattern) agar pesan hilang di refresh berikutnya
    header("Location: register.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Absensi Lab</title>
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
    <!-- Register Card Container -->
    <div class="w-full max-w-[560px] bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-8 sm:px-10 sm:py-12 md:px-14 md:py-16">

        <!-- Logo / Brand Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-3xl md:text-4xl font-bold text-gray-800 tracking-tight">Form Registrasi</h1>
        </div>

        <!-- Notification Messages -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 rounded bg-red-50 border border-red-200 text-red-600 text-base sm:text-lg">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="mb-6 p-4 rounded bg-green-50 border border-green-200 text-green-700 text-base sm:text-lg">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form method="POST" action="" class="space-y-6">
            <div>
                <input type="text" id="nama" name="nama" required autofocus
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]"
                    placeholder="Nama Lengkap">
            </div>

            <div>
                <input type="text" id="identity_number" name="identity_number" required
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]"
                    placeholder="NPM/NIDN">
            </div>

            <div>
                <input type="email" id="email" name="email" required
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]"
                    placeholder="Email">
            </div>

            <div>
                <input type="text" id="no_hp" name="no_hp" required
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]"
                    placeholder="No. HP">
            </div>

            <div>
                <input type="password" id="password" name="password" required
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]"
                    placeholder="Password">
            </div>

            <div>
                <select id="role" name="role" required
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]">
                    <option value="" disabled selected>Pilih Peran (Dosen / Asdos)</option>
                    <option value="dosen">Dosen</option>
                    <option value="asdos">Asdos</option>
                </select>
            </div>

            <button type="submit"
                class="w-full bg-[#1867c0] hover:bg-[#1355a1] active:bg-[#0f4482] text-white font-medium py-3 px-8 sm:py-3.5 sm:px-10 text-base sm:text-lg md:text-xl rounded-md transition duration-200 mt-2">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-6">
            <a href="login.php" class="text-base sm:text-lg text-[#1867c0] hover:underline font-medium">Sudah punya akun? Login di sini</a>
        </div>

    </div>
</body>

</html>