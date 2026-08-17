<?php require_once __DIR__ . '/../Templates/HeaderAsdos.php'; ?>

<?php 
$dataKegiatan = $kegiatanList ?? $kegiatan ?? []; 
?>

<div class="space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Marketplace Lowongan Kegiatan Lab
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Temukan kegiatan laboratorium yang sedang membuka pendaftaran asisten dan ajukan diri Anda.
            </p>
        </div>
        <div class="shrink-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                ● Lowongan Terbuka
            </span>
        </div>
    </div>

    <?php if (empty($dataKegiatan)): ?>

        <div class="p-12 border border-dashed border-slate-300 rounded-xl bg-white text-center text-slate-500">
            <div class="w-12 h-12 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-3 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-800">
                Belum Ada Kegiatan Tersedia
            </h3>
            <p class="text-xs text-slate-400 mt-1">
                Saat ini belum ada kegiatan laboratorium berstatus buka yang dapat Anda ikuti.
            </p>
        </div>

    <?php else: ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            <?php foreach ($dataKegiatan as $item): ?>

                <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between shadow-xs hover:border-[#1867c0]/40 hover:shadow-sm transition duration-150">
                    
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-2.5">
                            <h3 class="text-base font-bold text-slate-900 leading-snug">
                                <?= htmlspecialchars($item['nama_kegiatan']) ?>
                            </h3>

                            <span class="shrink-0 px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold">
                                OPEN
                            </span>
                        </div>

                        <div class="text-xs text-slate-500 mb-3.5 pb-3 border-b border-slate-100 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>
                                <?= date('d M Y', strtotime($item['periode_mulai'])) ?> – <?= date('d M Y', strtotime($item['periode_selesai'])) ?>
                            </span>
                        </div>

                        <div class="space-y-3 mb-4">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                    Deskripsi Tugas
                                </p>
                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                    <?= htmlspecialchars($item['deskripsi_tugas'] ?? $item['deskripsi_kegiatan'] ?? '-') ?>
                                </p>
                            </div>

                            <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-100">
                                <span class="font-medium text-slate-500">Kuota Tersedia</span>
                                <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded text-[11px]">
                                    <?= htmlspecialchars($item['kuota'] ?? '-') ?> orang
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between mb-3.5">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Insentif</span>
                            <span class="text-sm font-bold font-mono text-[#1867c0]">
                                Rp <?= number_format((float) ($item['insentif'] ?? 0), 0, ',', '.') ?>
                            </span>
                        </div>

                        <a
                            href="index.php?page=asdos/daftar&id=<?= (int) ($item['id_kegiatan'] ?? $item['id'] ?? 0) ?>"
                            class="block w-full text-center py-2 px-4 bg-[#1867c0] hover:bg-[#14529d] text-white font-semibold rounded-lg text-xs transition duration-150 shadow-xs"
                        >
                            Daftar Sebagai Asisten
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
