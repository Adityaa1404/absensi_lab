<?php
require_once '../../includes/header_dosen.php';
require_once '../../config/koneksi.php';

$error = '';
$success = '';

// Fetch current user data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id LIMIT 1");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    $user = null;
    $error = 'Gagal memuat data profil: ' . $e->getMessage();
}

// Handling Profile Update (EDIT)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? ''); // NIDN
    $password = trim($_POST['password'] ?? '');

    if (empty($nama) || empty($username)) {
        $error = 'Nama dan NIDN tidak boleh kosong!';
    } else {
        try {
            // Check NIDN/Username duplicate for other users
            $stmt = $pdo->prepare("SELECT id_user FROM users WHERE username = :username AND id_user != :id");
            $stmt->execute(['username' => $username, 'id' => $_SESSION['user_id']]);
            if ($stmt->fetch()) {
                $error = 'NIDN sudah digunakan oleh pengguna lain!';
            } else {
                if (!empty($password)) {
                    // Update dengan password baru
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET nama = :nama, username = :username, password = :password WHERE id_user = :id");
                    $stmt->execute([
                        'nama'     => $nama,
                        'username' => $username,
                        'password' => $password_hash,
                        'id'       => $_SESSION['user_id']
                    ]);
                } else {
                    // Update tanpa mengganti password
                    $stmt = $pdo->prepare("UPDATE users SET nama = :nama, username = :username WHERE id_user = :id");
                    $stmt->execute([
                        'nama'     => $nama,
                        'username' => $username,
                        'id'       => $_SESSION['user_id']
                    ]);
                }

                // Update Session
                $_SESSION['nama']     = $nama;
                $_SESSION['username'] = $username;
                $success = 'Profil berhasil diperbarui!';

                // Refresh user data
                $user['nama']     = $nama;
                $user['username'] = $username;
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage();
        }
    }
}

// Handling Profile Delete (DELETE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_profile') {
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id_user = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);

        // Hancurkan session dan alihkan ke login
        session_destroy();
        header("Location: ../auth/login.php?deleted=1");
        exit();
    } catch (PDOException $e) {
        $error = 'Gagal menghapus akun: ' . $e->getMessage();
    }
}
?>

<div class="max-w-4xl mx-auto space-y-8 md:space-y-12">
    <!-- Header Page -->
    <div class="bg-white border border-gray-200 p-6 sm:p-8 md:p-10 rounded-lg shadow-sm">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 tracking-tight">Kelola Profil Dosen</h2>
        <p class="text-sm sm:text-base md:text-xl text-gray-500 mt-2">Perbarui informasi data diri atau kelola keberadaan akun Anda</p>
    </div>

    <!-- Alert Notifications -->
    <?php if (!empty($error)): ?>
        <div class="p-5 md:p-6 rounded-lg bg-red-50 border border-red-200 text-red-600 text-base sm:text-lg md:text-xl flex items-start gap-4">
            <svg class="w-6 h-6 md:w-8 md:h-8 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="p-5 md:p-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 text-base sm:text-lg md:text-xl flex items-start gap-4">
            <svg class="w-6 h-6 md:w-8 md:h-8 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <!-- Overview Card (READ) -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm flex flex-col sm:flex-row items-center gap-8">
        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-lg bg-blue-50 border-2 border-[#1867c0] flex items-center justify-center text-[#1867c0] text-3xl sm:text-5xl font-bold shrink-0">
            <?= strtoupper(substr($user['nama'] ?? 'D', 0, 2)) ?>
        </div>
        <div class="text-center sm:text-left flex-1">
            <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800"><?= htmlspecialchars($user['nama'] ?? 'Dosen') ?></h3>
            <p class="text-base sm:text-lg md:text-xl text-[#1867c0] font-mono mt-1">NIDN: <?= htmlspecialchars($user['username'] ?? '-') ?></p>
            <span class="inline-block mt-3 px-4 py-1 rounded-full bg-gray-100 text-gray-700 border border-gray-200 text-xs sm:text-sm md:text-base font-bold uppercase tracking-wider">
                Role: <?= htmlspecialchars($user['role'] ?? 'dosen') ?>
            </span>
        </div>
    </div>

    <!-- Edit Profile Form (EDIT) -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm">
        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mb-8 flex items-center gap-3">
            <span class="w-3.5 h-3.5 rounded-full bg-[#1867c0]"></span>
            Edit Data Profil
        </h3>

        <form method="POST" action="" class="space-y-8">
            <input type="hidden" name="action" value="update_profile">

            <div>
                <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required
                       class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                       placeholder="Masukkan Nama Lengkap beserta Gelar">
            </div>

            <div>
                <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">NIDN / Nomor Induk Dosen</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required
                       class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                       placeholder="Masukkan NIDN">
            </div>

            <div>
                <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Password Baru (Opsional)</label>
                <input type="password" name="password"
                       class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                       placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#1867c0] hover:bg-[#1355a1] text-white font-medium py-3.5 px-8 sm:py-4 sm:px-10 rounded-md text-base sm:text-lg md:text-xl transition duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone (DELETE) -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm">
        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-red-600 mb-3">Zona Bahaya (Hapus Akun)</h3>
        <p class="text-sm sm:text-base md:text-lg text-red-500 mb-8">Tindakan ini akan menghapus akun Dosen Anda secara permanen dari sistem beserta seluruh data terkait.</p>

        <form method="POST" action="" onsubmit="return confirm('PERHATIAN: Apakah Anda benar-benar yakin ingin menghapus akun Anda secara permanen?');">
            <input type="hidden" name="action" value="delete_profile">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 sm:py-3.5 sm:px-8 rounded-md text-sm sm:text-base md:text-lg transition duration-200">
                Hapus Akun Permanen
            </button>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
