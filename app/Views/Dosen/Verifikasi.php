<?php require_once __DIR__ . '/../Templates/HeaderDosen.php'; ?>

<div class="space-y-6">

    <!-- Header Page -->
    <div class="bg-white border border-slate-200 p-5 sm:p-6 rounded-xl shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Verifikasi Absensi & Tugas Asdos</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tinjau laporan pelaksanaan tugas, periksa bukti foto, dan berikan persetujuan atau catatan.</p>
        </div>
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

    <!-- List Laporan Absensi -->
    <?php if (empty($absensiList)): ?>
        <div class="bg-white border border-slate-200 rounded-xl p-12 text-center text-slate-500 shadow-xs">
            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-700">Belum ada laporan absensi</p>
            <p class="text-xs text-slate-400 mt-1">Laporan pelaksanaan kegiatan yang diunggah asisten dosen akan muncul di sini.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 gap-5">
            <?php foreach ($absensiList as $row): ?>
                <?php
                $statusVerif = strtolower($row['status_verifikasi'] ?? 'pending');
                $badgeClass  = 'bg-amber-50 text-amber-700 border-amber-300';
                $statusLabel = 'PENDING';
                if ($statusVerif === 'disetujui') {
                    $badgeClass  = 'bg-emerald-50 text-emerald-700 border-emerald-300';
                    $statusLabel = 'DISETUJUI';
                } elseif ($statusVerif === 'ditolak') {
                    $badgeClass  = 'bg-red-50 text-red-700 border-red-300';
                    $statusLabel = 'DITOLAK';
                }
                ?>
                <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 shadow-xs space-y-5">

                    <!-- Top Bar Info -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-xs font-semibold text-[#1867c0]">
                                Kegiatan: <?= htmlspecialchars($row['nama_kegiatan'] ?? 'Kegiatan Lab') ?>
                            </span>
                            <h3 class="text-base font-bold text-slate-900 mt-0.5 flex items-center gap-2">
                                <span><?= htmlspecialchars($row['nama_asdos'] ?? 'Asisten Dosen') ?></span>
                                <?php if (!empty($row['npm_asdos'])): ?>
                                    <span class="text-xs font-mono text-slate-500 font-normal">(NPM: <?= htmlspecialchars($row['npm_asdos']) ?>)</span>
                                <?php endif; ?>
                            </h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-500">
                                Pelaksanaan: <strong class="text-slate-800"><?= date('d M Y', strtotime($row['tanggal'])) ?></strong>
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border <?= $badgeClass ?>">
                                <?= $statusLabel ?>
                            </span>
                        </div>
                    </div>

                    <!-- Content Grid: Detail + Gambar -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                        <!-- Deskripsi Tugas -->
                        <div class="lg:col-span-1 space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Rincian Pekerjaan:</h4>
                            <div class="text-xs sm:text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <?= nl2br(htmlspecialchars($row['deskripsi_tugas'])) ?>
                            </div>

                            <?php if (!empty($row['pesan_dosen'])): ?>
                                <div class="p-3 bg-blue-50/70 border border-blue-200 rounded-lg text-xs text-slate-700">
                                    <strong class="text-[#1867c0]">Catatan Dosen:</strong>
                                    <p class="mt-1"><?= htmlspecialchars($row['pesan_dosen']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Foto Bukti -->
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Foto Kegiatan -->
                            <div>
                                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Foto Kegiatan</span>
                                <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center relative group">
                                    <?php if (!empty($row['foto_kegiatan'])): ?>
                                        <img src="assets/uploads/<?= htmlspecialchars($row['foto_kegiatan']) ?>"
                                             alt="Foto Kegiatan"
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/f8fafc/94a3b8?text=Foto+Tidak+Ditemukan';">
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400">Tidak ada foto kegiatan</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Foto Selfie -->
                            <div>
                                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Foto Presensi (Selfie)</span>
                                <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center relative group">
                                    <?php if (!empty($row['foto_selfie'])): ?>
                                        <img src="assets/uploads/<?= htmlspecialchars($row['foto_selfie']) ?>"
                                             alt="Foto Selfie"
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/f8fafc/94a3b8?text=Foto+Tidak+Ditemukan';">
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400">Tidak ada foto selfie</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Verifikasi -->
                    <form method="POST" action="index.php?page=dosen/verifikasi" class="bg-slate-50 p-4 rounded-lg border border-slate-200 flex flex-col sm:flex-row sm:items-center gap-3">
                        <input type="hidden" name="action" value="verifikasi">
                        <input type="hidden" name="id_absensi" value="<?= $row['id_absensi'] ?>">

                        <!-- Radio Status -->
                        <div class="flex items-center gap-4 shrink-0">
                            <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="radio" name="status_verifikasi" value="disetujui" <?= ($row['status_verifikasi'] ?? '') === 'disetujui' ? 'checked' : '' ?> class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-emerald-700">Setujui</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="radio" name="status_verifikasi" value="ditolak" <?= ($row['status_verifikasi'] ?? '') === 'ditolak' ? 'checked' : '' ?> class="w-4 h-4 text-red-600 focus:ring-red-500">
                                <span class="text-red-700">Tolak</span>
                            </label>
                        </div>

                        <!-- Pesan Dosen -->
                        <input type="text" name="pesan_dosen" value="<?= htmlspecialchars($row['pesan_dosen'] ?? '') ?>"
                               placeholder="Beri Catatan / Pesan Dosen (Opsional)"
                               class="flex-1 bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20">

                        <button type="submit" class="px-4 py-2 rounded-lg bg-[#1867c0] hover:bg-[#14529d] text-white text-xs font-semibold transition duration-150 shadow-xs shrink-0 flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Verifikasi</span>
                        </button>
                    </form>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
