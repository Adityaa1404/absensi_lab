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

<div class="space-y-8 md:space-y-12">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-gray-200 p-6 sm:p-8 md:p-10 rounded-lg shadow-sm">
        <div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 tracking-tight">Push Lowongan Kegiatan</h2>
            <p class="text-sm sm:text-base md:text-xl text-gray-500 mt-2">Publikasikan tugas atau kegiatan baru untuk Asisten Laboratorium</p>
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

    <!-- Form Push Kegiatan -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm">
        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mb-8 flex items-center gap-3">
            <span class="w-3.5 h-3.5 rounded-full bg-[#1867c0]"></span>
            Form Kegiatan Baru
        </h3>

        <form method="POST" action="" class="space-y-8">
            <input type="hidden" name="action" value="add">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Nama Kegiatan -->
                <div class="md:col-span-2">
                    <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Nama Kegiatan *</label>
                    <input type="text" name="nama_kegiatan" required
                           class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                           placeholder="Contoh: Pendampingan Praktikum Pemrograman Web">
                </div>

                <!-- Periode Mulai -->
                <div>
                    <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Periode Mulai *</label>
                    <input type="date" name="periode_mulai" required
                           class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200">
                </div>

                <!-- Periode Selesai -->
                <div>
                    <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Periode Selesai *</label>
                    <input type="date" name="periode_selesai" required
                           class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200">
                </div>

                <!-- Insentif -->
                <div>
                    <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Insentif / Honor (Rp)</label>
                    <input type="number" name="insentif" min="0" placeholder="0"
                           class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Status Kegiatan</label>
                    <select name="status" class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200">
                        <option value="open">Open (Membuka Pendaftaran)</option>
                        <option value="closed">Closed (Tutup)</option>
                    </select>
                </div>

                <!-- Deskripsi Tugas -->
                <div class="md:col-span-2">
                    <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Deskripsi Tugas *</label>
                    <textarea name="deskripsi_tugas" rows="4" required
                              class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                              placeholder="Jelaskan detail tugas, persyaratannya, dan tanggung jawab asdos..."></textarea>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#1867c0] hover:bg-[#1355a1] text-white font-medium py-3.5 px-8 sm:py-4 sm:px-10 rounded-md text-base sm:text-lg md:text-xl transition duration-200 flex items-center gap-3">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Publish Kegiatan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Kegiatan Yang Pernah Dipublish -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm">
        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mb-8">Daftar Kegiatan Dipublikasikan</h3>

        <?php if (empty($kegiatan_list)): ?>
            <div class="p-10 text-center text-gray-500 border border-dashed border-gray-300 rounded-lg text-base sm:text-lg md:text-xl">
                Belum ada kegiatan yang Anda publikasikan.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-base sm:text-lg md:text-xl text-gray-700 border-collapse">
                    <thead class="bg-gray-50 text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="p-5">Nama Kegiatan</th>
                            <th class="p-5">Periode</th>
                            <th class="p-5">Insentif</th>
                            <th class="p-5">Status</th>
                            <th class="p-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($kegiatan_list as $row): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-5 font-bold text-gray-800">
                                    <?= htmlspecialchars($row['nama_kegiatan']) ?>
                                    <p class="text-sm sm:text-base text-gray-500 font-normal truncate max-w-xs mt-1"><?= htmlspecialchars($row['deskripsi_tugas']) ?></p>
                                </td>
                                <td class="p-5 whitespace-nowrap text-sm sm:text-base md:text-lg text-gray-500">
                                    <?= date('d M Y', strtotime($row['periode_mulai'])) ?> - <?= date('d M Y', strtotime($row['periode_selesai'])) ?>
                                </td>
                                <td class="p-5 whitespace-nowrap font-mono text-base sm:text-lg md:text-xl text-[#1867c0] font-semibold">
                                    Rp <?= is_numeric($row['insentif']) ? number_format($row['insentif'], 0, ',', '.') : htmlspecialchars($row['insentif']) ?>
                                </td>
                                <td class="p-5 whitespace-nowrap">
                                    <span class="px-4 py-1.5 rounded-full text-xs sm:text-sm md:text-base font-bold uppercase tracking-wider border <?= $row['status'] === 'open' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-gray-100 text-gray-600 border-gray-200' ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>
                                <td class="p-5 text-center whitespace-nowrap">
                                    <a href="?delete=<?= $row['id_kegiatan'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus kegiatan ini?')" 
                                       class="px-4 py-2 rounded-md bg-red-50 text-red-600 hover:bg-red-100 text-sm sm:text-base md:text-lg font-medium border border-red-200 transition">
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
