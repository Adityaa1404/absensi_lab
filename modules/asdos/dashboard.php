<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asdos') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../config/koneksi.php';

$asdos_id = $_SESSION['user_id'];

$pendaftaran = [];
$total = 0;
$diterima = 0;
$pending = 0;
$ditolak = 0;
$error_message = null;

try {
    /*
     * Ambil seluruh kegiatan yang pernah didaftarkan
     * oleh Asdos yang sedang login.
     */
    $stmt = $pdo->prepare("
        SELECT
            p.id_pendaftaran,
            p.kegiatan_id,
            p.status_pendaftaran,
            p.created_at,
            k.nama_kegiatan,
            k.periode_mulai,
            k.periode_selesai,
            k.deskripsi_tugas,
            k.insentif
        FROM pendaftaran_kegiatan p
        INNER JOIN kegiatan k ON k.id_kegiatan = p.kegiatan_id
        WHERE p.asdos_id = :asdos_id
        ORDER BY p.created_at DESC
    ");

    $stmt->execute(['asdos_id' => $asdos_id]);
    $pendaftaran = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*
     * Hitung statistik berdasarkan status pendaftaran.
     */
    foreach ($pendaftaran as $item) {
        $total++;
        switch ($item['status_pendaftaran']) {
            case 'diterima':
                $diterima++;
                break;
            case 'pending':
                $pending++;
                break;
            case 'ditolak':
                $ditolak++;
                break;
        }
    }
} catch (PDOException $e) {
    $error_message = "Terjadi kesalahan saat mengambil data kegiatan.";
}

require_once '../../includes/header_asdos.php';
?>

<!-- Header / Banner Welcome -->
<div class="bg-white rounded-md border border-gray-200 p-6 sm:p-8 mb-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs sm:text-sm font-semibold text-[#1867c0] uppercase tracking-wider mb-1">
                Dashboard Asdos
            </p>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 leading-tight">
                Selamat Datang Kembali 👋
            </h1>
            <p class="text-sm md:text-base text-gray-500 mt-1">
                Pantau kegiatan dan status pendaftaran yang telah Anda ajukan.
            </p>
        </div>
        <div class="shrink-0">
            <a href="marketplace.php" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1867c0] hover:bg-[#14529d] text-white text-sm md:text-base font-medium rounded-md transition duration-200 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari Kegiatan
            </a>
        </div>
    </div>
</div>

<!-- Cards Statistik -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <!-- Total -->
    <div class="bg-white border border-gray-200 rounded-md p-5 shadow-sm">
        <p class="text-xs md:text-sm font-semibold uppercase tracking-wider text-gray-400">
            Total Pendaftaran
        </p>
        <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-2">
            <?= $total ?>
        </p>
    </div>

    <!-- Diterima -->
    <div class="bg-white border border-gray-200 rounded-md p-5 shadow-sm">
        <p class="text-xs md:text-sm font-semibold uppercase tracking-wider text-gray-400">
            Diterima
        </p>
        <p class="text-2xl md:text-3xl font-bold text-emerald-600 mt-2">
            <?= $diterima ?>
        </p>
    </div>

    <!-- Pending -->
    <div class="bg-white border border-gray-200 rounded-md p-5 shadow-sm">
        <p class="text-xs md:text-sm font-semibold uppercase tracking-wider text-gray-400">
            Menunggu
        </p>
        <p class="text-2xl md:text-3xl font-bold text-amber-500 mt-2">
            <?= $pending ?>
        </p>
    </div>
</div>

<!-- Header Seksi Kegiatan -->
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-lg md:text-xl font-bold text-gray-800">
            Kegiatan Saya
        </h2>
        <p class="text-xs md:text-sm text-gray-500">
            Daftar kegiatan yang pernah Anda daftarkan.
        </p>
    </div>
</div>

<!-- Pesan Error -->
<?php if (!empty($error_message)): ?>
    <div class="p-4 mb-6 bg-red-50 border border-red-200 text-red-600 rounded-md text-sm md:text-base">
        <?= htmlspecialchars($error_message) ?>
    </div>

<!-- State Kosong -->
<?php elseif (empty($pendaftaran)): ?>
    <div class="p-12 border border-dashed border-gray-300 rounded-md bg-white text-center">
        <div class="w-12 h-12 mx-auto bg-gray-100 rounded-md flex items-center justify-center mb-3 text-gray-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.414 4.414A2 2 0 0119 9v10a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-base md:text-lg font-bold text-gray-800">Belum Ada Kegiatan</h3>
        <p class="text-sm text-gray-500 mt-1 mb-4">Anda belum mendaftar pada kegiatan apa pun.</p>
        <a href="marketplace.php" class="inline-flex items-center px-4 py-2 bg-[#1867c0] hover:bg-[#14529d] text-white text-sm font-medium rounded-md transition duration-200">
            Lihat Marketplace
        </a>
    </div>

<!-- Grid Daftar Kegiatan -->
<?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php foreach ($pendaftaran as $item): ?>
            <article class="bg-white border border-gray-200 rounded-md p-6 shadow-sm flex flex-col justify-between hover:border-[#1867c0]/50 transition duration-200">
                <div>
                    <!-- Card Header -->
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div>
                            <h3 class="text-base md:text-lg font-bold text-gray-800 leading-snug">
                                <?= htmlspecialchars($item['nama_kegiatan']) ?>
                            </h3>
                            <p class="text-xs md:text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?= date('d M Y', strtotime($item['periode_mulai'])) ?> – <?= date('d M Y', strtotime($item['periode_selesai'])) ?>
                            </p>
                        </div>

                        <!-- Status Badge -->
                        <?php if ($item['status_pendaftaran'] === 'diterima'): ?>
                            <span class="shrink-0 px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                                Diterima
                            </span>
                        <?php elseif ($item['status_pendaftaran'] === 'pending'): ?>
                            <span class="shrink-0 px-2.5 py-1 rounded-md bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                                Menunggu
                            </span>
                        <?php else: ?>
                            <span class="shrink-0 px-2.5 py-1 rounded-md bg-red-50 border border-red-200 text-red-600 text-xs font-semibold">
                                Ditolak
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Deskripsi Tugas -->
                    <div class="mt-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Deskripsi Tugas</p>
                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">
                            <?= htmlspecialchars($item['deskripsi_kegiatan'] ?? '-') ?>
                        </p>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Insentif</p>
                        <p class="text-base font-bold text-gray-800">
                            Rp <?= number_format($item['insentif'] ?? 0, 0, ',', '.') ?>
                        </p>
                    </div>

                    <div>
                        <?php if ($item['status_pendaftaran'] === 'diterima'): ?>
                            <a href="absensi.php?id=<?= urlencode($item['kegiatan_id']) ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#1867c0] hover:bg-[#14529d] text-white text-sm font-medium rounded-md transition duration-200 shadow-sm">
                                <span>Isi Absensi</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        <?php elseif ($item['status_pendaftaran'] === 'pending'): ?>
                            <span class="text-xs text-gray-400 italic">Menunggu verifikasi dosen</span>
                        <?php else: ?>
                            <span class="text-xs text-red-500 italic">Pendaftaran ditolak</span>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once '../../includes/footer.php'; ?>