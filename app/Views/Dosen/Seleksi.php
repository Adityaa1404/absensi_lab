<?php require_once __DIR__ . '/../Templates/HeaderDosen.php'; ?>

<div class="space-y-6">

    <!-- Header Page -->
    <div class="bg-white border border-slate-200 p-5 sm:p-6 rounded-xl shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Seleksi Calon Asisten Dosen</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tinjau pelamar dan tentukan asisten terpilih untuk kegiatan praktikum Anda.</p>
        </div>
        
        <!-- Filter Kegiatan Dropdown -->
        <?php if (!empty($kegiatanList)): ?>
            <div class="flex items-center gap-2">
                <form method="GET" action="index.php" class="flex items-center gap-2">
                    <input type="hidden" name="page" value="dosen/seleksi">
                    <label for="filter_kegiatan" class="text-xs font-semibold text-slate-600 whitespace-nowrap">Filter:</label>
                    <select id="filter_kegiatan" name="kegiatan_id" onchange="this.form.submit()"
                        class="bg-white border border-slate-300 text-slate-800 text-xs rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#1867c0]/20 focus:border-[#1867c0] transition">
                        <option value="">-- Semua Kegiatan --</option>
                        <?php foreach ($kegiatanList as $k): ?>
                            <option value="<?= $k['id_kegiatan'] ?>" <?= (isset($selectedKegiatanId) && $selectedKegiatanId == $k['id_kegiatan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama_kegiatan']) ?> (Kuota: <?= $k['kuota'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
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

    <!-- Statistik Ringkas -->
    <?php
    $totalPelamar = count($pelamarList ?? []);
    $pendingCount = 0;
    $diterimaCount = 0;
    $ditolakCount = 0;
    foreach ($pelamarList ?? [] as $p) {
        if ($p['status_pendaftaran'] === 'pending') $pendingCount++;
        elseif ($p['status_pendaftaran'] === 'diterima') $diterimaCount++;
        elseif ($p['status_pendaftaran'] === 'ditolak') $ditolakCount++;
    }
    ?>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Pelamar</p>
            <p class="text-2xl font-bold text-slate-800 mt-1"><?= $totalPelamar ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-amber-200 bg-amber-50/20 shadow-xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Menunggu (Pending)</p>
            <p class="text-2xl font-bold text-amber-700 mt-1"><?= $pendingCount ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/20 shadow-xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Diterima</p>
            <p class="text-2xl font-bold text-emerald-700 mt-1"><?= $diterimaCount ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-red-200 bg-red-50/20 shadow-xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-red-700">Ditolak</p>
            <p class="text-2xl font-bold text-red-700 mt-1"><?= $ditolakCount ?></p>
        </div>
    </div>

    <!-- Main List Pelamar -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-slate-900">Daftar Pengajuan Calon Asisten</h3>
                <p class="text-xs text-slate-500 mt-0.5">Total <?= $totalPelamar ?> pendaftar terdata</p>
            </div>
        </div>

        <?php if (empty($pelamarList)): ?>
            <div class="p-12 text-center text-slate-500">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">Belum ada pelamar</p>
                <p class="text-xs text-slate-400 mt-1">Belum ada asisten dosen yang mendaftar ke kegiatan praktikum Anda.</p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-slate-100">
                <?php foreach ($pelamarList as $pelamar): ?>
                    <?php
                    $status = $pelamar['status_pendaftaran'];
                    if ($status === 'pending') {
                        $badgeClass = 'bg-amber-50 text-amber-700 border-amber-300';
                        $statusText = 'MENUNGGU SELEKSI';
                    } elseif ($status === 'diterima') {
                        $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-300';
                        $statusText = 'DITERIMA';
                    } else {
                        $badgeClass = 'bg-red-50 text-red-700 border-red-300';
                        $statusText = 'DITOLAK';
                    }
                    ?>
                    <div class="p-5 hover:bg-slate-50/70 transition">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                            
                            <!-- Info Pelamar & Kegiatan -->
                            <div class="flex-1 space-y-2.5">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <h4 class="text-base font-bold text-slate-900"><?= htmlspecialchars($pelamar['nama_asdos']) ?></h4>
                                    <span class="px-2 py-0.5 text-xs font-mono bg-blue-50 text-[#1867c0] border border-blue-200 rounded font-semibold">
                                        NPM: <?= htmlspecialchars($pelamar['npm_asdos']) ?>
                                    </span>
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border <?= $badgeClass ?>">
                                        <?= $statusText ?>
                                    </span>
                                </div>

                                <div class="text-xs text-slate-600 flex flex-wrap items-center gap-x-5 gap-y-1.5">
                                    <span class="flex items-center gap-1 font-medium text-slate-800">
                                        <svg class="w-3.5 h-3.5 text-[#1867c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        Kegiatan: <strong class="text-[#1867c0]"><?= htmlspecialchars($pelamar['nama_kegiatan']) ?></strong>
                                    </span>

                                    <?php if (!empty($pelamar['email_asdos'])): ?>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <?= htmlspecialchars($pelamar['email_asdos']) ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($pelamar['no_hp_asdos'])): ?>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $pelamar['no_hp_asdos']) ?>" target="_blank"
                                           class="flex items-center gap-1 text-emerald-600 hover:underline font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <?= htmlspecialchars($pelamar['no_hp_asdos']) ?>
                                        </a>
                                    <?php endif; ?>

                                    <span class="text-slate-400 text-[11px]">
                                        Daftar: <?= date('d M Y, H:i', strtotime($pelamar['tanggal_daftar'])) ?> WIB
                                    </span>
                                </div>

                                <?php if (!empty($pelamar['pesan_lamaran'])): ?>
                                    <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700">
                                        <strong>Pesan / Motivasi:</strong> <?= htmlspecialchars($pelamar['pesan_lamaran']) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($pelamar['catatan_dosen'])): ?>
                                    <div class="p-2.5 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800">
                                        <strong>Catatan Dosen:</strong> <?= htmlspecialchars($pelamar['catatan_dosen']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Tombol Aksi Seleksi -->
                            <div class="shrink-0 flex flex-row items-center gap-2">
                                <!-- Form Terima -->
                                <form method="POST" action="index.php?page=dosen/seleksi<?= isset($selectedKegiatanId) ? '&kegiatan_id=' . $selectedKegiatanId : '' ?>">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id_pendaftaran" value="<?= $pelamar['id_pendaftaran'] ?>">
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="submit" 
                                        class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-xs transition duration-150 flex items-center gap-1.5 <?= $status === 'diterima' ? 'opacity-40 cursor-not-allowed' : '' ?>"
                                        <?= $status === 'diterima' ? 'disabled' : '' ?>>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Terima</span>
                                    </button>
                                </form>

                                <!-- Tombol Buka Modal / Form Tolak -->
                                <button type="button" 
                                    onclick="document.getElementById('modal-tolak-<?= $pelamar['id_pendaftaran'] ?>').classList.remove('hidden')"
                                    class="px-3.5 py-1.5 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 text-xs font-semibold rounded-lg shadow-xs transition duration-150 flex items-center gap-1.5 <?= $status === 'ditolak' ? 'opacity-40 cursor-not-allowed' : '' ?>"
                                    <?= $status === 'ditolak' ? 'disabled' : '' ?>>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Tolak</span>
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Modal Alasan Tolak -->
                    <div id="modal-tolak-<?= $pelamar['id_pendaftaran'] ?>" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
                        <div class="bg-white rounded-xl max-w-md w-full p-5 sm:p-6 shadow-xl border border-slate-200 space-y-4">
                            <h4 class="text-base font-bold text-slate-900">Tolak Pelamar: <?= htmlspecialchars($pelamar['nama_asdos']) ?></h4>
                            <p class="text-xs text-slate-500">Anda dapat menyertakan alasan penolakan agar asisten dosen dapat mengetahuinya.</p>
                            
                            <form method="POST" action="index.php?page=dosen/seleksi<?= isset($selectedKegiatanId) ? '&kegiatan_id=' . $selectedKegiatanId : '' ?>" class="space-y-3.5">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id_pendaftaran" value="<?= $pelamar['id_pendaftaran'] ?>">
                                <input type="hidden" name="status" value="ditolak">

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan / Alasan Penolakan (Opsional)</label>
                                    <textarea name="catatan_dosen" rows="3" placeholder="Contoh: Kuota praktikum sudah terpenuhi / Nilai prasyarat belum mencukupi..."
                                        class="w-full border border-slate-300 rounded-lg p-2.5 text-xs text-slate-900 focus:ring-2 focus:ring-[#1867c0]/20 focus:border-[#1867c0] resize-none"></textarea>
                                </div>

                                <div class="flex justify-end gap-2 pt-1">
                                    <button type="button" 
                                        onclick="document.getElementById('modal-tolak-<?= $pelamar['id_pendaftaran'] ?>').classList.add('hidden')"
                                        class="px-3.5 py-1.5 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-100 transition">
                                        Batal
                                    </button>
                                    <button type="submit" 
                                        class="px-3.5 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                                        Konfirmasi Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
