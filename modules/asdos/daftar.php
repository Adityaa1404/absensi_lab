<?php

session_start();

// Pastikan user sudah login dan merupakan Asdos
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asdos') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../config/koneksi.php';

$asdos_id = $_SESSION['user_id'];

// Ambil ID kegiatan dari URL
$kegiatan_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$kegiatan_id) {
    header("Location: marketplace.php");
    exit();
}

$message = '';
$message_type = '';

// Ambil data kegiatan
try {
    $stmt = $pdo->prepare("
        SELECT 
            id_kegiatan,
            nama_kegiatan,
            status
        FROM kegiatan
        WHERE id_kegiatan = :id_kegiatan
        LIMIT 1
    ");

    $stmt->execute([
        'id_kegiatan' => $kegiatan_id
    ]);

    $kegiatan = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kegiatan tidak ditemukan
    if (!$kegiatan) {
        $message = "Kegiatan tidak ditemukan.";
        $message_type = 'error';
    }

    // Kegiatan sudah tidak dibuka
    elseif ($kegiatan['status'] !== 'open') {
        $message = "Kegiatan ini sudah tidak tersedia untuk pendaftaran.";
        $message_type = 'error';
    }

    else {
        // Cek apakah Asdos sudah pernah mendaftar
        $stmt = $pdo->prepare("
            SELECT 
                id_pendaftaran,
                status_pendaftaran
            FROM pendaftaran_kegiatan
            WHERE kegiatan_id = :kegiatan_id
              AND asdos_id = :asdos_id
            LIMIT 1
        ");

        $stmt->execute([
            'kegiatan_id' => $kegiatan_id,
            'asdos_id' => $asdos_id
        ]);

        $pendaftaran = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pendaftaran) {

            switch ($pendaftaran['status_pendaftaran']) {
                case 'pending':
                    $message = "Kamu sudah mendaftar kegiatan ini. Pendaftaran masih menunggu verifikasi dosen.";
                    break;

                case 'diterima':
                    $message = "Kamu sudah diterima pada kegiatan ini.";
                    break;

                case 'ditolak':
                    $message = "Pendaftaran kamu untuk kegiatan ini sebelumnya ditolak.";
                    break;

                default:
                    $message = "Kamu sudah memiliki pendaftaran pada kegiatan ini.";
            }

            $message_type = 'info';

        } else {

            // Daftarkan Asdos
            $stmt = $pdo->prepare("
                INSERT INTO pendaftaran_kegiatan
                    (kegiatan_id, asdos_id, status_pendaftaran)
                VALUES
                    (:kegiatan_id, :asdos_id, 'pending')
            ");

            $stmt->execute([
                'kegiatan_id' => $kegiatan_id,
                'asdos_id' => $asdos_id
            ]);

            $message = "Berhasil mendaftar kegiatan. Pendaftaran kamu sedang menunggu verifikasi dosen.";
            $message_type = 'success';
        }
    }

} catch (PDOException $e) {
    $message = "Terjadi kesalahan saat memproses pendaftaran.";
    $message_type = 'error';
}

require_once '../../includes/header_asdos.php';

?>

<div class="p-6 md:p-8 bg-white rounded-2xl shadow-sm border border-slate-200 mt-6">

    <h2 class="text-2xl font-bold text-slate-800 mb-2">
        Pendaftaran Kegiatan
    </h2>

    <?php if (!empty($kegiatan)): ?>
        <p class="text-slate-500 mb-6">
            <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
        </p>
    <?php endif; ?>

    <?php if ($message_type === 'success'): ?>

        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl mb-6">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php elseif ($message_type === 'info'): ?>

        <div class="p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl mb-6">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php else: ?>

        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl mb-6">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>

    <a
        href="marketplace.php"
        class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-semibold transition"
    >
        ← Kembali ke Marketplace
    </a>

</div>

<?php require_once '../../includes/footer.php'; ?>