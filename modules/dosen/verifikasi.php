<?php
require_once '../../includes/header_dosen.php';
require_once '../../config/koneksi.php';

$error = '';
$success = '';

// Handling Status Update & Pesan Dosen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verifikasi') {
    $id_absensi        = (int)($_POST['id_absensi'] ?? 0);
    $status_verifikasi = trim($_POST['status_verifikasi'] ?? '');
    $pesan_dosen       = trim($_POST['pesan_dosen'] ?? '');

    if (empty($id_absensi) || empty($status_verifikasi)) {
        $error = 'Pilih status verifikasi!';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE absensi SET status_verifikasi = :status, pesan_dosen = :pesan WHERE id_absensi = :id");
            $stmt->execute([
                'status' => $status_verifikasi,
                'pesan'  => $pesan_dosen,
                'id'     => $id_absensi
            ]);
            $success = 'Status verifikasi absensi berhasil diperbarui!';
        } catch (PDOException $e) {
            $error = 'Gagal memproses verifikasi: ' . $e->getMessage();
        }
    }
}

// Fetch Rekap Absensi
try {
    $sql = "SELECT a.*, k.nama_kegiatan, u.nama AS nama_asdos, u.username AS npm_asdos 
            FROM absensi a 
            LEFT JOIN pendaftaran p ON a.pendaftaran_id = p.id_pendaftaran 
            LEFT JOIN kegiatan k ON p.kegiatan_id = k.id_kegiatan 
            LEFT JOIN users u ON p.asdos_id = u.id_user 
            ORDER BY a.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $absensi_list = $stmt->fetchAll();
} catch (PDOException $e) {
    try {
        $stmt = $pdo->query("SELECT * FROM absensi ORDER BY created_at DESC");
        $absensi_list = $stmt->fetchAll();
    } catch (PDOException $ex) {
        $absensi_list = [];
    }
}
?>

<div class="space-y-8 md:space-y-12">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-gray-200 p-6 sm:p-8 md:p-10 rounded-lg shadow-sm">
        <div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 tracking-tight">Verifikasi Absensi Asdos</h2>
            <p class="text-sm sm:text-base md:text-xl text-gray-500 mt-2">Periksa laporan kegiatan, foto bukti, dan berikan persetujuan atau catatan dosen</p>
        </div>
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

    <!-- List Laporan Absensi -->
    <?php if (empty($absensi_list)): ?>
        <div class="bg-white border border-gray-200 rounded-lg p-12 sm:p-16 md:p-20 text-center text-gray-500 text-base sm:text-lg md:text-xl shadow-sm">
            <svg class="w-16 h-16 md:w-24 md:h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Belum ada laporan absensi yang dikirimkan oleh Asdos.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 gap-8 md:gap-10">
            <?php foreach ($absensi_list as $row): ?>
                <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm space-y-8">
                    <!-- Top Bar Info -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 pb-6">
                        <div>
                            <span class="text-sm sm:text-base md:text-lg text-[#1867c0] font-semibold">Kegiatan: <?= htmlspecialchars($row['nama_kegiatan'] ?? 'Laporan Absensi') ?></span>
                            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mt-1">
                                Asdos: <?= htmlspecialchars($row['nama_asdos'] ?? 'Asdos') ?> 
                                <?php if (!empty($row['npm_asdos'])): ?>
                                    <span class="text-sm sm:text-base md:text-lg font-mono text-gray-500 font-normal">(NPM: <?= htmlspecialchars($row['npm_asdos']) ?>)</span>
                                <?php endif; ?>
                            </h3>
                        </div>

                        <div class="flex flex-wrap items-center gap-4">
                            <span class="text-sm sm:text-base md:text-lg text-gray-500">Tanggal: <strong class="text-gray-800"><?= date('d M Y', strtotime($row['tanggal'])) ?></strong></span>
                            <?php
                            $status = strtolower($row['status_verifikasi'] ?? 'pending');
                            $badge_class = 'bg-amber-50 text-amber-600 border-amber-200';
                            if ($status === 'disetujui') $badge_class = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                            if ($status === 'ditolak')   $badge_class = 'bg-red-50 text-red-600 border-red-200';
                            ?>
                            <span class="px-4 py-1.5 rounded-full text-xs sm:text-sm md:text-base font-bold uppercase tracking-wider border <?= $badge_class ?>">
                                <?= htmlspecialchars($row['status_verifikasi'] ?? 'pending') ?>
                            </span>
                        </div>
                    </div>

                    <!-- Content Grid: Detail + Gambar -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Detail Deskripsi Tugas -->
                        <div class="lg:col-span-1 space-y-4">
                            <h4 class="text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500">Deskripsi Pekerjaan:</h4>
                            <p class="text-base sm:text-lg md:text-xl text-gray-700 leading-relaxed bg-gray-50 p-5 md:p-6 rounded-md border border-gray-200">
                                <?= nl2br(htmlspecialchars($row['deskripsi_tugas'])) ?>
                            </p>

                            <?php if (!empty($row['pesan_dosen'])): ?>
                                <div class="p-5 md:p-6 rounded-md bg-blue-50 border border-blue-200 text-sm sm:text-base md:text-lg">
                                    <span class="font-bold text-[#1867c0]">Catatan Dosen Sebelumnya:</span>
                                    <p class="text-gray-700 mt-2"><?= htmlspecialchars($row['pesan_dosen']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Gambar Bukti Kegiatan & Selfie -->
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Foto Kegiatan -->
                            <div>
                                <span class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Foto Kegiatan</span>
                                <div class="aspect-video bg-gray-100 rounded-md overflow-hidden border border-gray-200 flex items-center justify-center relative group">
                                    <?php if (!empty($row['foto_kegiatan'])): ?>
                                        <img src="../../uploads/<?= htmlspecialchars($row['foto_kegiatan']) ?>" 
                                             alt="Foto Kegiatan" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/f3f4f6/9ca3af?text=Foto+Kegiatan+Tidak+Ditemukan';">
                                    <?php else: ?>
                                        <span class="text-sm sm:text-base md:text-lg text-gray-400">Tidak ada foto</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Foto Selfie -->
                            <div>
                                <span class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Foto Selfie / Presensi</span>
                                <div class="aspect-video bg-gray-100 rounded-md overflow-hidden border border-gray-200 flex items-center justify-center relative group">
                                    <?php if (!empty($row['foto_selfie'])): ?>
                                        <img src="../../uploads/<?= htmlspecialchars($row['foto_selfie']) ?>" 
                                             alt="Foto Selfie" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/f3f4f6/9ca3af?text=Foto+Selfie+Tidak+Ditemukan';">
                                    <?php else: ?>
                                        <span class="text-sm sm:text-base md:text-lg text-gray-400">Tidak ada foto</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Update Verifikasi Dosen -->
                    <form method="POST" action="" class="bg-gray-50 p-5 sm:p-6 md:p-8 rounded-md border border-gray-200 flex flex-col md:flex-row md:items-center gap-6">
                        <input type="hidden" name="action" value="verifikasi">
                        <input type="hidden" name="id_absensi" value="<?= $row['id_absensi'] ?>">

                        <!-- Radio Options Status -->
                        <div class="flex items-center gap-6 shrink-0">
                            <label class="inline-flex items-center gap-3 cursor-pointer text-base sm:text-lg md:text-xl font-bold text-gray-700">
                                <input type="radio" name="status_verifikasi" value="disetujui" <?= ($row['status_verifikasi'] ?? '') === 'disetujui' ? 'checked' : '' ?> class="w-5 h-5 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-emerald-600">Setujui</span>
                            </label>
                            <label class="inline-flex items-center gap-3 cursor-pointer text-base sm:text-lg md:text-xl font-bold text-gray-700">
                                <input type="radio" name="status_verifikasi" value="ditolak" <?= ($row['status_verifikasi'] ?? '') === 'ditolak' ? 'checked' : '' ?> class="w-5 h-5 text-red-600 focus:ring-red-500">
                                <span class="text-red-600">Tolak</span>
                            </label>
                        </div>

                        <!-- Pesan Dosen Input -->
                        <input type="text" name="pesan_dosen" value="<?= htmlspecialchars($row['pesan_dosen'] ?? '') ?>" 
                               placeholder="Beri Catatan / Pesan Dosen (Opsional)" 
                               class="flex-1 bg-white border border-gray-300 rounded-md px-5 py-3 md:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#1867c0]">

                        <button type="submit" class="px-8 py-3.5 md:py-4 rounded-md bg-[#1867c0] hover:bg-[#1355a1] text-white text-base sm:text-lg md:text-xl font-medium transition duration-200 shrink-0">
                            Simpan Verifikasi
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>
