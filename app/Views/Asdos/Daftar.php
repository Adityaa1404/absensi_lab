<?php 
require_once __DIR__ . '/../Templates/HeaderAsdos.php'; 

$messageType = $messageType ?? '';
$message     = $message ?? '';
$kegiatan    = $kegiatan ?? null;
?>

<!-- Header / Banner Title -->
<div class="bg-white rounded-md border border-gray-200 p-6 sm:p-8 mb-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 leading-tight">
                Pendaftaran Kegiatan
            </h2>
            <p class="text-sm md:text-base text-gray-500 mt-1">
                Status pengajuan pendaftaran kegiatan asisten laboratorium.
            </p>
        </div>
        <div class="shrink-0">
            <a
                href="index.php?page=asdos/marketplace"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm md:text-base font-medium rounded-md transition duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Marketplace
            </a>
        </div>
    </div>
</div>

<!-- Main Card Status & Info -->
<div class="bg-white rounded-md border border-gray-200 p-6 sm:p-8 shadow-sm">

    <!-- Alert Status Pesan -->
    <?php if ($messageType === 'success'): ?>

        <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-md text-sm md:text-base mb-6">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <span class="font-bold">Berhasil!</span>
                <p class="mt-0.5"><?= htmlspecialchars($message) ?></p>
            </div>
        </div>

    <?php elseif ($messageType === 'info'): ?>

        <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-md text-sm md:text-base mb-6">
            <svg class="w-5 h-5 text-[#1867c0] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <span class="font-bold">Informasi</span>
                <p class="mt-0.5"><?= htmlspecialchars($message) ?></p>
            </div>
        </div>

    <?php elseif ($messageType === 'error'): ?>

        <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-md text-sm md:text-base mb-6">
            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <span class="font-bold">Pendaftaran Gagal</span>
                <p class="mt-0.5"><?= htmlspecialchars($message) ?></p>
            </div>
        </div>

    <?php endif; ?>

    <!-- Detail Singkat Kegiatan -->
    <?php if (!empty($kegiatan)): ?>
        <div class="border border-gray-200 rounded-md p-5 bg-gray-50/50 mb-6">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                Kegiatan yang Didaftarkan
            </h3>
            <p class="text-lg md:text-xl font-bold text-gray-800 mb-2">
                <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
            </p>

            <?php if (!empty($kegiatan['periode_mulai']) && !empty($kegiatan['periode_selesai'])): ?>
                <p class="text-xs md:text-sm text-gray-500 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span><?= date('d M Y', strtotime($kegiatan['periode_mulai'])) ?> – <?= date('d M Y', strtotime($kegiatan['periode_selesai'])) ?></span>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
        <a
            href="index.php?page=asdos/dashboard"
            class="w-full sm:w-auto text-center px-5 py-2.5 bg-[#1867c0] hover:bg-[#14529d] text-white font-medium rounded-md text-sm md:text-base transition duration-200 shadow-sm"
        >
            Lihat Status di Dashboard
        </a>
        <a
            href="index.php?page=asdos/marketplace"
            class="w-full sm:w-auto text-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-md text-sm md:text-base transition duration-200"
        >
            Cari Kegiatan Lain
        </a>
    </div>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
