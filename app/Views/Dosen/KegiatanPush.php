<?php 
$editKegiatan = $editKegiatan ?? null;
require_once __DIR__ . '/../Templates/HeaderDosen.php'; 
?>

<div class="space-y-6">

    <!-- Header Page -->
    <div class="bg-white border border-slate-200 p-5 sm:p-6 rounded-xl shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Manajemen Kegiatan Praktikum</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Publikasikan lowongan tugas baru atau kelola kegiatan praktikum yang sedang aktif.</p>
        </div>
        <?php if ($editKegiatan): ?>
            <a href="index.php?page=dosen/kegiatan" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Kegiatan Baru
            </a>
        <?php endif; ?>
    </div>

    <!-- Alert Notifications -->
    <?php if (!empty($error)): ?>
        <div class="p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm flex items-start gap-2.5">
            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-start gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <!-- Form Push / Edit Kegiatan -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 shadow-xs">
        <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full <?= $editKegiatan ? 'bg-amber-500' : 'bg-[#1867c0]' ?>"></span>
            <span><?= $editKegiatan ? 'Edit Kegiatan: ' . htmlspecialchars($editKegiatan['nama_kegiatan']) : 'Tambah Kegiatan Praktikum Baru' ?></span>
        </h3>

        <form method="POST" action="index.php?page=dosen/kegiatan" class="space-y-4">
            <input type="hidden" name="action" value="<?= $editKegiatan ? 'edit' : 'add' ?>">
            <?php if ($editKegiatan): ?>
                <input type="hidden" name="id_kegiatan" value="<?= $editKegiatan['id_kegiatan'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Nama Kegiatan -->
                <div class="sm:col-span-2 lg:col-span-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Kegiatan / Praktikum <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kegiatan" required
                           value="<?= htmlspecialchars($editKegiatan['nama_kegiatan'] ?? $_POST['nama_kegiatan'] ?? '') ?>"
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                           placeholder="Contoh: Praktikum Pemrograman Web & Bergerak - Kelas A">
                </div>

                <!-- Periode Mulai -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Periode Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="periode_mulai" required
                           value="<?= htmlspecialchars($editKegiatan['periode_mulai'] ?? $_POST['periode_mulai'] ?? '') ?>"
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150">
                </div>

                <!-- Periode Selesai -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Periode Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="periode_selesai" required
                           value="<?= htmlspecialchars($editKegiatan['periode_selesai'] ?? $_POST['periode_selesai'] ?? '') ?>"
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150">
                </div>

                <!-- Insentif -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Insentif / Honor (Rp)
                    </label>
                    <input type="number" name="insentif" min="0" placeholder="0"
                           value="<?= htmlspecialchars($editKegiatan['insentif'] ?? $_POST['insentif'] ?? '') ?>"
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150">
                </div>

                <!-- Kuota -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kuota Asdos <span class="text-slate-400 font-normal">(Org)</span>
                    </label>
                    <input type="number" name="kuota" min="0" placeholder="Contoh: 3"
                           value="<?= htmlspecialchars($editKegiatan['kuota'] ?? $_POST['kuota'] ?? '') ?>"
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150">
                </div>

                <!-- Status (hanya saat mode edit) -->
                <?php if ($editKegiatan): ?>
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pendaftaran</label>
                        <?php $selectedStatus = $editKegiatan['status'] ?? 'open'; ?>
                        <select name="status" class="w-full sm:w-64 bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150">
                            <option value="open" <?= $selectedStatus === 'open' ? 'selected' : '' ?>>Open (Buka Lowongan)</option>
                            <option value="closed" <?= $selectedStatus === 'closed' ? 'selected' : '' ?>>Closed (Tutup Lowongan)</option>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Deskripsi Tugas -->
                <div class="sm:col-span-2 lg:col-span-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Deskripsi & Rincian Tugas <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi_tugas" rows="3" required
                              class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150 resize-none"
                              placeholder="Tuliskan rincian tugas pendampingan, materi yang diajarkan, jadwal lab, dan persyaratan asisten..."><?= htmlspecialchars($editKegiatan['deskripsi_tugas'] ?? $_POST['deskripsi_tugas'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <?php if ($editKegiatan): ?>
                    <a href="index.php?page=dosen/kegiatan" class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                        Batal
                    </a>
                <?php endif; ?>
                <button type="submit" class="bg-[#1867c0] hover:bg-[#14529d] text-white text-xs font-semibold py-2 px-5 rounded-lg transition duration-150 shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span><?= $editKegiatan ? 'Simpan Perubahan' : 'Publikasikan Kegiatan' ?></span>
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Kegiatan -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-slate-900">Daftar Kegiatan Anda</h3>
                <p class="text-xs text-slate-500 mt-0.5">Total <?= count($kegiatanList ?? []) ?> kegiatan praktikum terdata</p>
            </div>
        </div>

        <?php if (empty($kegiatanList)): ?>
            <div class="p-12 text-center text-slate-500">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">Belum ada kegiatan yang dipublikasikan</p>
                <p class="text-xs text-slate-400 mt-1">Gunakan form di atas untuk membuat kegiatan praktikum baru.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700 border-collapse">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Nama Kegiatan</th>
                            <th class="px-5 py-3.5">Periode Pelaksanaan</th>
                            <th class="px-5 py-3.5 text-center">Kuota</th>
                            <th class="px-5 py-3.5">Insentif</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($kegiatanList as $row): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4 font-semibold text-slate-900">
                                    <?= htmlspecialchars($row['nama_kegiatan']) ?>
                                    <p class="text-xs text-slate-500 font-normal truncate max-w-sm mt-0.5"><?= htmlspecialchars($row['deskripsi_tugas']) ?></p>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-slate-600">
                                    <?= date('d M Y', strtotime($row['periode_mulai'])) ?> – <?= date('d M Y', strtotime($row['periode_selesai'])) ?>
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap font-bold text-slate-800">
                                    <?= htmlspecialchars($row['kuota'] ?? '-') ?> <span class="text-[11px] font-normal text-slate-400">Org</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-[#1867c0] font-bold">
                                    Rp <?= is_numeric($row['insentif']) ? number_format($row['insentif'], 0, ',', '.') : htmlspecialchars($row['insentif']) ?>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border <?= $row['status'] === 'open' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="index.php?page=dosen/kegiatan&edit=<?= $row['id_kegiatan'] ?>"
                                           class="px-2.5 py-1.5 rounded-md bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-semibold border border-amber-200 transition">
                                            Edit
                                        </a>
                                        <a href="index.php?page=dosen/kegiatan&delete=<?= $row['id_kegiatan'] ?>"
                                           onclick="return confirm('Yakin ingin menghapus kegiatan ini?')"
                                           class="px-2.5 py-1.5 rounded-md bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold border border-red-200 transition">
                                            Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
