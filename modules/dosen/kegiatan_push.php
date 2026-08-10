<?php
require_once '../../includes/header_dosen.php';
require_once '../../config/koneksi.php';

$error = '';
$success = '';

// Handling Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nama_kegiatan   = trim($_POST['nama_kegiatan'] ?? '');
    $periode_mulai   = trim($_POST['periode_mulai'] ?? '');
    $periode_selesai = trim($_POST['periode_selesai'] ?? '');
    $deskripsi_tugas = trim($_POST['deskripsi_tugas'] ?? '');
    $insentif        = trim($_POST['insentif'] ?? 0);
    $status          = trim($_POST['status'] ?? 'open');

    // Validasi sederhana
    if (empty($nama_kegiatan) || empty($periode_mulai) || empty($periode_selesai) || empty($deskripsi_tugas)) {
        $error = 'Semua kolom bertanda * wajib diisi!';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO kegiatan (dosen_id, nama_kegiatan, periode_mulai, periode_selesai, deskripsi_tugas, insentif, status) VALUES (:dosen_id, :nama_kegiatan, :periode_mulai, :periode_selesai, :deskripsi_tugas, :insentif, :status)");
            $stmt->execute([
                'dosen_id'        => $_SESSION['user_id'],
                'nama_kegiatan'   => $nama_kegiatan,
                'periode_mulai'   => $periode_mulai,
                'periode_selesai' => $periode_selesai,
                'deskripsi_tugas' => $deskripsi_tugas,
                'insentif'        => $insentif,
                'status'          => $status
            ]);
            $success = 'Kegiatan berhasil dipublikasikan!';
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan kegiatan: ' . $e->getMessage();
        }
    }
}

// Handling Delete Action
if (isset($_GET['delete'])) {
    $id_kegiatan = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM kegiatan WHERE id_kegiatan = :id AND dosen_id = :dosen_id");
        $stmt->execute(['id' => $id_kegiatan, 'dosen_id' => $_SESSION['user_id']]);
        $success = 'Kegiatan berhasil dihapus!';
    } catch (PDOException $e) {
        $error = 'Gagal menghapus kegiatan: ' . $e->getMessage();
    }
}

// Fetch kegiatan milik dosen ini
try {
    $stmt = $pdo->prepare("SELECT * FROM kegiatan WHERE dosen_id = :dosen_id ORDER BY created_at DESC");
    $stmt->execute(['dosen_id' => $_SESSION['user_id']]);
    $kegiatan_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $kegiatan_list = [];
}
?>

<div class="space-y-8">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900/60 border border-slate-800/80 p-6 rounded-2xl backdrop-blur-xl">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Push Lowongan Kegiatan</h2>
            <p class="text-xs text-slate-400 mt-1">Publikasikan tugas atau kegiatan baru untuk Asisten Laboratorium</p>
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

    <!-- Form Push Kegiatan -->
    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
        <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
            Form Kegiatan Baru
        </h3>

        <form method="POST" action="" class="space-y-6">
            <input type="hidden" name="action" value="add">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kegiatan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Kegiatan *</label>
                    <input type="text" name="nama_kegiatan" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200"
                           placeholder="Contoh: Pendampingan Praktikum Pemrograman Web">
                </div>

                <!-- Periode Mulai -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Periode Mulai *</label>
                    <input type="date" name="periode_mulai" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                </div>

                <!-- Periode Selesai -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Periode Selesai *</label>
                    <input type="date" name="periode_selesai" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                </div>

                <!-- Insentif -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Insentif / Honor (Rp)</label>
                    <input type="number" name="insentif" min="0" placeholder="0"
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Status Kegiatan</label>
                    <select name="status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                        <option value="open">Open (Membuka Pendaftaran)</option>
                        <option value="closed">Closed (Tutup)</option>
                    </select>
                </div>

                <!-- Deskripsi Tugas -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Deskripsi Tugas *</label>
                    <textarea name="deskripsi_tugas" rows="4" required
                              class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200"
                              placeholder="Jelaskan detail tugas, persyaratannya, dan tanggung jawab asdos..."></textarea>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-3 px-6 rounded-xl shadow-lg shadow-indigo-600/25 transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Publish Kegiatan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Kegiatan Yang Pernah Dipublish -->
    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
        <h3 class="text-lg font-semibold text-white mb-6">Daftar Kegiatan Dipublikasikan</h3>

        <?php if (empty($kegiatan_list)): ?>
            <div class="p-8 text-center text-slate-500 border border-dashed border-slate-800 rounded-xl">
                Belum ada kegiatan yang Anda publikasikan.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="p-4">Nama Kegiatan</th>
                            <th class="p-4">Periode</th>
                            <th class="p-4">Insentif</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        <?php foreach ($kegiatan_list as $row): ?>
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="p-4 font-medium text-white">
                                    <?= htmlspecialchars($row['nama_kegiatan']) ?>
                                    <p class="text-xs text-slate-400 truncate max-w-xs mt-0.5"><?= htmlspecialchars($row['deskripsi_tugas']) ?></p>
                                </td>
                                <td class="p-4 whitespace-nowrap text-xs text-slate-400">
                                    <?= date('d M Y', strtotime($row['periode_mulai'])) ?> - <?= date('d M Y', strtotime($row['periode_selesai'])) ?>
                                </td>
                                <td class="p-4 whitespace-nowrap font-mono text-xs text-indigo-400">
                                    Rp <?= is_numeric($row['insentif']) ? number_format($row['insentif'], 0, ',', '.') : htmlspecialchars($row['insentif']) ?>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wider <?= $row['status'] === 'open' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-700/40 text-slate-400' ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <a href="?delete=<?= $row['id_kegiatan'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus kegiatan ini?')" 
                                       class="px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 text-xs font-medium border border-red-500/20 transition">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
