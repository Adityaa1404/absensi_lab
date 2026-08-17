<?php require_once __DIR__ . '/../Templates/HeaderAsdos.php'; ?>

<?php 
$dataKegiatan = $kegiatanList ?? $kegiatan ?? []; 
?>

<div class="bg-white rounded-md border border-gray-200 p-6 sm:p-8 mb-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 leading-tight">
                Marketplace Kegiatan Lab
            </h2>
            <p class="text-sm md:text-base text-gray-500 mt-1">
                Temukan kegiatan laboratorium yang tersedia dan daftarkan dirimu.
            </p>
        </div>
        <div class="shrink-0">
            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs sm:text-sm font-semibold bg-[#1867c0]/10 text-[#1867c0] border border-[#1867c0]/20">
                Status: Terbuka
            </span>
        </div>
    </div>
</div>

<?php if (empty($dataKegiatan)): ?>

    <div class="p-12 border border-dashed border-gray-300 rounded-md bg-white text-center">
        <div class="w-12 h-12 mx-auto bg-gray-100 rounded-md flex items-center justify-center mb-3 text-gray-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
        <h3 class="text-base md:text-lg font-bold text-gray-800">
            Belum Ada Kegiatan Tersedia
        </h3>
        <p class="text-sm text-gray-500 mt-1">
            Saat ini belum ada kegiatan laboratorium berstatus buka yang dapat kamu ikuti.
        </p>
    </div>

<?php else: ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <?php foreach ($dataKegiatan as $item): ?>

            <div class="bg-white border border-gray-200 rounded-md p-6 flex flex-col justify-between shadow-sm hover:border-[#1867c0]/50 transition duration-200">
                
                <div>
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 leading-snug">
                            <?= htmlspecialchars($item['nama_kegiatan']) ?>
                        </h3>

                        <span class="shrink-0 px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                            OPEN
                        </span>
                    </div>

                    <div class="text-xs md:text-sm text-gray-500 mb-4 pb-3 border-b border-gray-100 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>
                            <?= date('d M Y', strtotime($item['periode_mulai'])) ?> – <?= date('d M Y', strtotime($item['periode_selesai'])) ?>
                        </span>
                    </div>

                    <div class="space-y-3 mb-5">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">
                                Deskripsi Tugas
                            </p>
                            <p class="text-sm text-gray-600 line-clamp-3 leading-relaxed">
                                <?= htmlspecialchars($item['deskripsi_tugas'] ?? $item['deskripsi_kegiatan'] ?? '-') ?>
                            </p>
                        </div>

                        <div class="flex items-center justify-between text-xs md:text-sm pt-2">
                            <span class="font-semibold text-gray-500">Kuota Tersedia</span>
                            <span class="font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded">
                                <?= htmlspecialchars($item['kuota'] ?? '-') ?> orang
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Insentif</span>
                        <span class="text-base md:text-lg font-bold text-gray-800">
                            Rp <?= number_format((float) ($item['insentif'] ?? 0), 0, ',', '.') ?>
                        </span>
                    </div>

                    <a
                        href="index.php?page=asdos/daftar&id=<?= (int) ($item['id_kegiatan'] ?? $item['id'] ?? 0) ?>"
                        class="block w-full text-center py-2.5 px-4 bg-[#1867c0] hover:bg-[#14529d] text-white font-medium rounded-md text-sm md:text-base transition duration-200 shadow-sm"
                    >
                        Daftar Kegiatan
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>