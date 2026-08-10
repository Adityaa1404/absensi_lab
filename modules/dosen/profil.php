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

<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header Page -->
    <div class="bg-slate-900/60 border border-slate-800/80 p-6 rounded-2xl backdrop-blur-xl">
        <h2 class="text-xl font-bold text-white tracking-tight">Kelola Profil Dosen</h2>
        <p class="text-xs text-slate-400 mt-1">Perbarui informasi data diri atau kelola keberadaan akun Anda</p>
    </div>

    <!-- Alert Notifications -->
    <?php if (!empty($error)): ?>
        <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <!-- Overview Card (READ) -->
    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl flex flex-col sm:flex-row items-center gap-6">
        <div class="w-20 h-20 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 text-2xl font-bold shrink-0">
            <?= strtoupper(substr($user['nama'] ?? 'D', 0, 2)) ?>
        </div>
        <div class="text-center sm:text-left flex-1">
            <h3 class="text-xl font-bold text-white"><?= htmlspecialchars($user['nama'] ?? 'Dosen') ?></h3>
            <p class="text-sm text-indigo-400 font-mono mt-0.5">NIDN: <?= htmlspecialchars($user['username'] ?? '-') ?></p>
            <span class="inline-block mt-2 px-3 py-0.5 rounded-full bg-slate-800 text-slate-300 text-[11px] font-semibold uppercase tracking-wider">
                Role: <?= htmlspecialchars($user['role'] ?? 'dosen') ?>
            </span>
        </div>
    </div>

    <!-- Edit Profile Form (EDIT) -->
    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
        <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
            Edit Data Profil
        </h3>

        <form method="POST" action="" class="space-y-6">
            <input type="hidden" name="action" value="update_profile">

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200"
                       placeholder="Masukkan Nama Lengkap beserta Gelar">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">NIDN / Nomor Induk Dosen</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200"
                       placeholder="Masukkan NIDN">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Password Baru (Opsional)</label>
                <input type="password" name="password"
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200"
                       placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-3 px-6 rounded-xl shadow-lg shadow-indigo-600/25 transition duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone (DELETE) -->
    <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-6 sm:p-8 shadow-xl">
        <h3 class="text-lg font-semibold text-red-400 mb-2">Zona Bahaya (Hapus Akun)</h3>
        <p class="text-xs text-slate-400 mb-6">Tindakan ini akan menghapus akun Dosen Anda secara permanen dari sistem beserta seluruh data terkait.</p>

        <form method="POST" action="" onsubmit="return confirm('PERHATIAN: Apakah Anda benar-benar yakin ingin menghapus akun Anda secara permanen?');">
            <input type="hidden" name="action" value="delete_profile">
            <button type="submit" class="bg-red-600 hover:bg-red-500 text-white font-medium py-2.5 px-5 rounded-xl text-xs shadow-lg shadow-red-600/25 transition duration-200">
                Hapus Akun Permanen
            </button>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
