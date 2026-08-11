<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asdos') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../config/koneksi.php';

$error_message = null;
$kegiatan = [];

try {
    $stmt = $pdo->prepare("SELECT id_kegiatan, nama_kegiatan, periode_mulai, periode_selesai, deskripsi_tugas, insentif FROM kegiatan WHERE status = 'open' ORDER BY periode_mulai DESC");
    $stmt->execute();
    $kegiatan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Terjadi kesalahan saat mengambil data kegiatan: " . $e->getMessage();
}

require_once '../../includes/header_asdos.php';
?>

<!-- Header / Banner Title -->
<div class="bg-white rounded-md border border-gray-200 p-6 sm:p-8 mb-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 leading-tight">Marketplace Lowongan Asdos</h2>
            <p class="text-sm md:text-base text-gray-500 mt-1">Pilih dan mendaftar pada kegiatan atau mata kuliah yang sedang dibuka.</p>
        </div>
        <div class="shrink-0">
            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs sm:text-sm font-semibold bg-[#1867c0]/10 text-[#1867c0] border border-[#1867c0]/20">
                Status: Terbuka
            </span>
        </div>
    </div>
</div>

<!-- Alert Error -->
<?php if (!empty($error_message)): ?>
    <div class="p-4 mb-6 bg-red-50 border border-red-200 text-red-600 rounded-md text-sm md:text-base">
        <?= htmlspecialchars($error_message) ?>
    </div>

<!-- Empty State -->
<?php elseif (empty($kegiatan)): ?>
    <div class="p-12 border border-dashed border-gray-300 rounded-md bg-white text-center">
        <p class="text-gray-500 font-medium text-base md:text-lg">Tidak ada kegiatan yang tersedia saat ini.</p>
    </div>

<!-- Grid Cards Lowongan -->
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($kegiatan as $item): ?>
            <div class="bg-white border border-gray-200 rounded-md p-6 flex flex-col justify-between shadow-sm hover:border-[#1867c0]/50 transition duration-200">
                <div>
                    <!-- Judul Kegiatan -->
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2 leading-snug">
                        <?= htmlspecialchars($item['nama_kegiatan']) ?>
                    </h3>

                    <!-- Periode Tanggal -->
                    <div class="text-xs md:text-sm text-gray-500 mb-4 pb-3 border-b border-gray-100 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span><?= date('d M Y', strtotime($item['periode_mulai'])) ?> – <?= date('d M Y', strtotime($item['periode_selesai'])) ?></span>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Deskripsi Tugas</p>
                        <p class="text-sm text-gray-600 whitespace-pre-line line-clamp-3 leading-relaxed">
                            <?= htmlspecialchars($item['deskripsi_kegiatan'] ?? '-') ?>
                        </p>
                    </div>
                </div>

                <div>
                    <!-- Insentif -->
                    <div class="mb-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Insentif</span>
                        <span class="text-base md:text-lg font-bold text-gray-800">
                            Rp <?= number_format($item['insentif'] ?? 0, 0, ',', '.') ?>
                        </span>
                    </div>

                    <!-- Tombol Aksi -->
                    <a href="daftar.php?id=<?= urlencode($item['id_kegiatan']) ?>" class="block w-full text-center py-2.5 px-4 bg-[#1867c0] hover:bg-[#14529d] text-white font-medium rounded-md text-sm md:text-base transition duration-200 shadow-sm">
                        Daftar Kegiatan
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once '../../includes/footer.php'; ?>