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

<div class="space-y-8">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900/60 border border-slate-800/80 p-6 rounded-2xl backdrop-blur-xl">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Verifikasi Absensi Asdos</h2>
            <p class="text-xs text-slate-400 mt-1">Periksa laporan kegiatan, foto bukti, dan berikan persetujuan atau catatan dosen</p>
        </div>
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

    <!-- List Laporan Absensi -->
    <?php if (empty($absensi_list)): ?>
        <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-12 text-center text-slate-500">
            <svg class="w-12 h-12 mx-auto text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Belum ada laporan absensi yang dikirimkan oleh Asdos.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 gap-6">
            <?php foreach ($absensi_list as $row): ?>
                <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-6">
                    <!-- Top Bar Info -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-4">
                        <div>
                            <span class="text-xs text-indigo-400 font-medium">Kegiatan: <?= htmlspecialchars($row['nama_kegiatan'] ?? 'Laporan Absensi') ?></span>
                            <h3 class="text-base font-bold text-white mt-0.5">
                                Asdos: <?= htmlspecialchars($row['nama_asdos'] ?? 'Asdos') ?> 
                                <?php if (!empty($row['npm_asdos'])): ?>
                                    <span class="text-xs font-mono text-slate-400 font-normal">(NPM: <?= htmlspecialchars($row['npm_asdos']) ?>)</span>
                                <?php endif; ?>
                            </h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-400">Tanggal: <strong class="text-slate-200"><?= date('d M Y', strtotime($row['tanggal'])) ?></strong></span>
                            <?php
                            $status = strtolower($row['status_verifikasi'] ?? 'pending');
                            $badge_class = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
                            if ($status === 'disetujui') $badge_class = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                            if ($status === 'ditolak')   $badge_class = 'bg-red-500/10 text-red-400 border-red-500/20';
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider border <?= $badge_class ?>">
                                <?= htmlspecialchars($row['status_verifikasi'] ?? 'pending') ?>
                            </span>
                        </div>
                    </div>

                    <!-- Content Grid: Detail + Gambar -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Detail Deskripsi Tugas -->
                        <div class="lg:col-span-1 space-y-3">
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400">Deskripsi Pekerjaan:</h4>
                            <p class="text-sm text-slate-300 leading-relaxed bg-slate-950/60 p-4 rounded-xl border border-slate-800">
                                <?= nl2br(htmlspecialchars($row['deskripsi_tugas'])) ?>
                            </p>

                            <?php if (!empty($row['pesan_dosen'])): ?>
                                <div class="p-3.5 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-xs">
                                    <span class="font-semibold text-indigo-400">Catatan Dosen Sebelumnya:</span>
                                    <p class="text-slate-300 mt-1"><?= htmlspecialchars($row['pesan_dosen']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Gambar Bukti Kegiatan & Selfie -->
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Foto Kegiatan -->
                            <div>
                                <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Foto Kegiatan</span>
                                <div class="aspect-video bg-slate-950 rounded-xl overflow-hidden border border-slate-800 flex items-center justify-center relative group">
                                    <?php if (!empty($row['foto_kegiatan'])): ?>
                                        <img src="../../uploads/<?= htmlspecialchars($row['foto_kegiatan']) ?>" 
                                             alt="Foto Kegiatan" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/1e293b/94a3b8?text=Foto+Kegiatan+Tidak+Ditemukan';">
                                    <?php else: ?>
                                        <span class="text-xs text-slate-500">Tidak ada foto</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Foto Selfie -->
                            <div>
                                <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Foto Selfie / Presensi</span>
                                <div class="aspect-video bg-slate-950 rounded-xl overflow-hidden border border-slate-800 flex items-center justify-center relative group">
                                    <?php if (!empty($row['foto_selfie'])): ?>
                                        <img src="../../uploads/<?= htmlspecialchars($row['foto_selfie']) ?>" 
                                             alt="Foto Selfie" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/1e293b/94a3b8?text=Foto+Selfie+Tidak+Ditemukan';">
                                    <?php else: ?>
                                        <span class="text-xs text-slate-500">Tidak ada foto</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Update Verifikasi Dosen -->
                    <form method="POST" action="" class="bg-slate-950/80 p-4 rounded-xl border border-slate-800 flex flex-col md:flex-row md:items-center gap-4">
                        <input type="hidden" name="action" value="verifikasi">
                        <input type="hidden" name="id_absensi" value="<?= $row['id_absensi'] ?>">

                        <!-- Radio Options Status -->
                        <div class="flex items-center gap-4 shrink-0">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-300">
                                <input type="radio" name="status_verifikasi" value="disetujui" <?= ($row['status_verifikasi'] ?? '') === 'disetujui' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-500">
                                <span class="text-emerald-400">Setujui</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-300">
                                <input type="radio" name="status_verifikasi" value="ditolak" <?= ($row['status_verifikasi'] ?? '') === 'ditolak' ? 'checked' : '' ?> class="text-red-600 focus:ring-red-500">
                                <span class="text-red-400">Tolak</span>
                            </label>
                        </div>

                        <!-- Pesan Dosen Input -->
                        <input type="text" name="pesan_dosen" value="<?= htmlspecialchars($row['pesan_dosen'] ?? '') ?>" 
                               placeholder="Beri Catatan / Pesan Dosen (Opsional)" 
                               class="flex-1 bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">

                        <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-medium transition duration-200 shrink-0">
                            Simpan Verifikasi
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>
