<?php require_once __DIR__ . '/../Templates/HeaderAsdos.php'; ?>

<div class="space-y-6">

    <!-- Header / Welcome Banner -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Dashboard Asisten Dosen
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Pantau status pendaftaran kegiatan praktikum dan laporkan absensi tugas laboratorium.
            </p>
        </div>
        <div class="shrink-0">
            <a
                href="index.php?page=asdos/marketplace"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#1867c0] hover:bg-[#14529d] text-white text-xs font-semibold rounded-lg transition duration-150 shadow-xs"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Cari Kegiatan Baru</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Absensi Sukses -->
    <?php if (!empty($_SESSION['absensi_success'])): ?>
        <div class="flex items-start gap-2.5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs sm:text-sm">
            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <span class="font-bold">Berhasil!</span>
                <p class="mt-0.5"><?= htmlspecialchars($_SESSION['absensi_success']) ?></p>
            </div>
        </div>
        <?php unset($_SESSION['absensi_success']); ?>
    <?php endif; ?>

    <!-- Main Card: Daftar Kegiatan Terdaftar -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-slate-900">
                    Status Kegiatan Praktikum Terdaftar
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Total <?= count($pendaftaran ?? []) ?> kegiatan yang Anda ikuti</p>
            </div>
        </div>

        <?php if (empty($pendaftaran)): ?>

            <div class="p-12 text-center text-slate-500">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">Belum ada pendaftaran kegiatan</p>
                <p class="text-xs text-slate-400 mt-1">Anda belum mendaftar pada kegiatan laboratorium apa pun.</p>
                <a
                    href="index.php?page=asdos/marketplace"
                    class="inline-flex items-center gap-1.5 mt-4 px-3.5 py-1.5 bg-[#1867c0] hover:bg-[#14529d] text-white text-xs font-semibold rounded-lg transition duration-150 shadow-xs"
                >
                    Lihat Marketplace
                </a>
            </div>

        <?php else: ?>

            <div class="divide-y divide-slate-100">
                <?php foreach ($pendaftaran as $item): ?>
                    <?php
                    $status = $item['status_pendaftaran'];

                    if ($status === 'pending') {
                        $statusLabel = 'PENDING';
                        $statusBadge = 'bg-amber-50 text-amber-700 border-amber-300';
                        $statusMessage = 'Pendaftaran Anda sedang menunggu verifikasi dari dosen pengampu.';
                    } elseif ($status === 'diterima') {
                        $statusLabel = 'DITERIMA';
                        $statusBadge = 'bg-emerald-50 text-emerald-700 border-emerald-300';
                        $statusMessage = 'Selamat! Anda diterima sebagai asisten pada kegiatan ini.';
                    } else {
                        $statusLabel = 'DITOLAK';
                        $statusBadge = 'bg-red-50 text-red-700 border-red-300';
                        $statusMessage = 'Pendaftaran Anda belum dapat diterima untuk kegiatan ini.';
                    }
                    ?>

                    <div class="p-5 hover:bg-slate-50/70 transition">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div class="flex-1 space-y-2">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <h4 class="text-base font-bold text-slate-900">
                                        <?= htmlspecialchars($item['nama_kegiatan']) ?>
                                    </h4>
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border <?= $statusBadge ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </div>

                                <p class="text-xs text-slate-500 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>
                                        <?= date('d M Y', strtotime($item['periode_mulai'])) ?> – <?= date('d M Y', strtotime($item['periode_selesai'])) ?>
                                    </span>
                                </p>

                                <p class="text-xs text-slate-600">
                                    <?= $statusMessage ?>
                                </p>

                                <!-- Riwayat Absensi / Bukti Pelaksanaan Tugas -->
                                <?php if ($status === 'diterima' && !empty($item['absensi'])): ?>
                                    <div class="mt-3 space-y-2">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Riwayat Laporan Absensi:</p>
                                        <?php foreach ($item['absensi'] as $absensi): ?>
                                            <?php
                                            $statusAbsensi = $absensi['status_verifikasi'] ?? 'pending';
                                            if ($statusAbsensi === 'pending') {
                                                $absensiLabel = 'ABSENSI PENDING';
                                                $absensiClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                                $absensiMsg   = 'Laporan absensi sedang menunggu verifikasi dosen.';
                                            } elseif ($statusAbsensi === 'disetujui') {
                                                $absensiLabel = 'ABSENSI DISETUJUI';
                                                $absensiClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                                $absensiMsg   = 'Laporan absensi telah disetujui dosen pengampu.';
                                            } else {
                                                $absensiLabel = 'ABSENSI DITOLAK';
                                                $absensiClass = 'bg-red-50 text-red-700 border-red-200';
                                                $absensiMsg   = 'Laporan absensi ditolak. Silakan periksa catatan dosen.';
                                            }
                                            ?>
                                            <div class="p-3 rounded-lg border <?= $absensiClass ?> text-xs">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="font-bold text-[11px]">
                                                        <?= $absensiLabel ?>
                                                    </span>
                                                    <span class="text-slate-500 text-[11px]">
                                                        <?= date('d M Y', strtotime($absensi['tanggal'])) ?>
                                                    </span>
                                                </div>
                                                <p class="mt-1 text-slate-600">
                                                    <?= $absensiMsg ?>
                                                </p>
                                                <?php if (!empty($absensi['pesan_dosen'])): ?>
                                                    <p class="mt-1.5 pt-1.5 border-t border-slate-200/60 text-slate-700">
                                                        <strong>Catatan Dosen:</strong> <?= htmlspecialchars($absensi['pesan_dosen']) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>  
                                <?php endif; ?>
                            </div>

                            <div class="shrink-0 pt-2 md:pt-0">
                                <?php if ($status === 'diterima'): ?>
                                    <a
                                        href="index.php?page=asdos/absensi&id=<?= urlencode($item['id_pendaftaran']) ?>"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#1867c0] hover:bg-[#14529d] text-white text-xs font-semibold rounded-lg transition duration-150 shadow-xs"
                                    >
                                        <span>Isi Absensi</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                <?php elseif ($status === 'pending'): ?>
                                    <span class="inline-block px-3 py-1.5 bg-slate-100 text-slate-500 rounded-lg font-semibold text-xs border border-slate-200">
                                        Menunggu ACC
                                    </span>
                                <?php else: ?>
                                    <a
                                        href="index.php?page=asdos/marketplace"
                                        class="inline-block px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold text-xs border border-slate-200 transition"
                                    >
                                        Cari Kegiatan Lain
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
