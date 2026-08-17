<?php 
require_once __DIR__ . '/../Templates/HeaderAsdos.php'; 

$messageType = $messageType ?? '';
$message     = $message ?? '';
$kegiatan    = $kegiatan ?? null;
?>

<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header / Banner Title -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Pendaftaran Kegiatan Lab
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Konfirmasi status pengajuan pendaftaran Anda sebagai asisten laboratorium.
            </p>
        </div>
        <div class="shrink-0">
            <a
                href="index.php?page=asdos/marketplace"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition duration-150"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Marketplace
            </a>
        </div>
    </div>

    <!-- Main Card Status & Info -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-xs space-y-5">

        <!-- Alert Status Pesan -->
        <?php if ($messageType === 'success'): ?>

            <div class="flex items-start gap-2.5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs sm:text-sm">
                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <span class="font-bold">Pendaftaran Berhasil Terkirim!</span>
                    <p class="mt-0.5"><?= htmlspecialchars($message) ?></p>
                </div>
            </div>

        <?php elseif ($messageType === 'info'): ?>

            <div class="flex items-start gap-2.5 p-3.5 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-xs sm:text-sm">
                <svg class="w-4 h-4 text-[#1867c0] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <span class="font-bold">Informasi Pendaftaran</span>
                    <p class="mt-0.5"><?= htmlspecialchars($message) ?></p>
                </div>
            </div>

        <?php elseif ($messageType === 'error'): ?>

            <div class="flex items-start gap-2.5 p-3.5 bg-red-50 border border-red-200 text-red-800 rounded-lg text-xs sm:text-sm">
                <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <span class="font-bold">Pendaftaran Tidak Dapat Diproses</span>
                    <p class="mt-0.5"><?= htmlspecialchars($message) ?></p>
                </div>
            </div>

        <?php endif; ?>

        <!-- Detail Singkat Kegiatan -->
        <?php if (!empty($kegiatan)): ?>
            <div class="border border-slate-200 rounded-lg p-4 bg-slate-50/50">
                <h3 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                    Kegiatan yang Anda Daftarkan
                </h3>
                <p class="text-sm font-bold text-slate-800">
                    <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                </p>

                <?php if (!empty($kegiatan['periode_mulai']) && !empty($kegiatan['periode_selesai'])): ?>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span><?= date('d M Y', strtotime($kegiatan['periode_mulai'])) ?> – <?= date('d M Y', strtotime($kegiatan['periode_selesai'])) ?></span>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-2.5 pt-2">
            <a
                href="index.php?page=asdos/dashboard"
                class="w-full sm:w-auto text-center px-4 py-2 bg-[#1867c0] hover:bg-[#14529d] text-white font-semibold rounded-lg text-xs transition duration-150 shadow-xs"
            >
                Buka Dashboard
            </a>
            <a
                href="index.php?page=asdos/marketplace"
                class="w-full sm:w-auto text-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs transition duration-150"
            >
                Cari Kegiatan Lain
            </a>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
