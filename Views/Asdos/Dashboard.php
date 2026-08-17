<?php require_once __DIR__ . '/../Templates/HeaderAsdos.php'; ?>

<?php
$pending = 0;
$diterima = 0;
$ditolak = 0;

if (!empty($pendaftaran)) {
    foreach ($pendaftaran as $item) {
        if ($item['status_pendaftaran'] === 'pending') {
            $pending++;
        } elseif ($item['status_pendaftaran'] === 'diterima') {
            $diterima++;
        } elseif ($item['status_pendaftaran'] === 'ditolak') {
            $ditolak++;
        }
    }
}
?>

<div class="bg-white rounded-md border border-gray-200 p-6 sm:p-8 mb-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs sm:text-sm font-semibold text-[#1867c0] uppercase tracking-wider mb-1">
                Dashboard Asdos
            </p>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 leading-tight">
                Selamat Datang Kembali 
            </h1>
            <p class="text-sm md:text-base text-gray-500 mt-1">
                Pantau status pendaftaran kegiatan laboratorium yang telah kamu ajukan.
            </p>
        </div>
        <div class="shrink-0">
            <a
                href="index.php?page=asdos/marketplace"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1867c0] hover:bg-[#14529d] text-white text-sm md:text-base font-medium rounded-md transition duration-200 shadow-sm"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari Kegiatan
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-gray-200 rounded-md p-5 shadow-sm">
        <p class="text-xs md:text-sm font-semibold uppercase tracking-wider text-gray-400">
            Menunggu Verifikasi
        </p>
        <p class="text-2xl md:text-3xl font-bold text-amber-500 mt-2">
            <?= $pending ?>
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-md p-5 shadow-sm">
        <p class="text-xs md:text-sm font-semibold uppercase tracking-wider text-gray-400">
            Diterima
        </p>
        <p class="text-2xl md:text-3xl font-bold text-emerald-600 mt-2">
            <?= $diterima ?>
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-md p-5 shadow-sm">
        <p class="text-xs md:text-sm font-semibold uppercase tracking-wider text-gray-400">
            Ditolak
        </p>
        <p class="text-2xl md:text-3xl font-bold text-red-600 mt-2">
            <?= $ditolak ?>
        </p>
    </div>
</div>

<div class="bg-white rounded-md border border-gray-200 shadow-sm overflow-hidden">

    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg md:text-xl font-bold text-gray-800">
            Pendaftaran Saya
        </h3>
        <p class="text-xs md:text-sm text-gray-500 mt-0.5">
            Riwayat dan status kegiatan yang pernah kamu daftarkan.
        </p>
    </div>

    <?php if (empty($pendaftaran)): ?>

        <div class="p-12 border-dashed border-gray-300 text-center">
            <div class="w-12 h-12 mx-auto bg-gray-100 rounded-md flex items-center justify-center mb-3 text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.414 4.414A2 2 0 0119 9v10a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h4 class="text-base md:text-lg font-bold text-gray-800">Belum Ada Pendaftaran</h4>
            <p class="text-sm text-gray-500 mt-1">
                Kamu belum mendaftar pada kegiatan laboratorium apa pun.
            </p>
            <a
                href="index.php?page=asdos/marketplace"
                class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-[#1867c0] hover:bg-[#14529d] text-white text-sm font-medium rounded-md transition duration-200 shadow-sm"
            >
                Lihat Marketplace
            </a>
        </div>

    <?php else: ?>

        <div class="divide-y divide-gray-100">
            <?php foreach ($pendaftaran as $item): ?>
                <?php
                $status = $item['status_pendaftaran'];

                if ($status === 'pending') {
                    $statusLabel = 'PENDING';
                    $statusBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                    $statusMessage = 'Menunggu verifikasi dosen.';
                } elseif ($status === 'diterima') {
                    $statusLabel = 'DITERIMA';
                    $statusBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    $statusMessage = 'Kamu diterima pada kegiatan ini.';
                } else {
                    $statusLabel = 'DITOLAK';
                    $statusBadge = 'bg-red-50 text-red-600 border-red-200';
                    $statusMessage = 'Pendaftaran kamu ditolak.';
                }
                ?>

                <div class="p-6 hover:bg-gray-50/80 transition duration-150">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-base md:text-lg font-bold text-gray-800 leading-snug">
                                        <?= htmlspecialchars($item['nama_kegiatan']) ?>
                                    </h4>
                                    <p class="text-xs md:text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>
                                            <?= date('d M Y', strtotime($item['periode_mulai'])) ?> – <?= date('d M Y', strtotime($item['periode_selesai'])) ?>
                                        </span>
                                    </p>
                                </div>

                                <span class="shrink-0 px-2.5 py-1 text-xs font-semibold rounded-md border <?= $statusBadge ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </div>

                            <p class="text-xs md:text-sm text-gray-600 mt-3">
                                <?= $statusMessage ?>
                            </p>
                            <?php if ($status === 'diterima' && !empty($item['absensi'])): ?>
                            <div class="mt-4 space-y-2">
                                <?php foreach ($item['absensi'] as $absensi): ?>
                                    <?php
                                    $statusAbsensi = $absensi['status_verifikasi'] ?? 'pending';
                                    if ($statusAbsensi === 'pending') {
                                        $absensiLabel = 'ABSENSI PENDING';
                                        $absensiClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                        $absensiMessage = 'Absensi sedang menunggu verifikasi dosen.';
                                    } elseif ($statusAbsensi === 'disetujui') {
                                        $absensiLabel = 'ABSENSI DISETUJUI';
                                        $absensiClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        $absensiMessage = 'Absensi kamu telah disetujui dosen.';
                                    } else {
                                        $absensiLabel = 'ABSENSI DITOLAK';
                                        $absensiClass = 'bg-red-50 text-red-700 border-red-200';
                                        $absensiMessage = 'Absensi kamu ditolak. Silakan periksa catatan dosen.';
                                    }
                                    ?>
                                    <div class="p-3 rounded-md border <?= $absensiClass ?>">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-xs font-bold">
                                                <?= $absensiLabel ?>
                                            </span>
                                            <span class="text-xs">
                                                <?= date('d M Y', strtotime($absensi['tanggal'])) ?>
                                            </span>
                                        </div>
                                        <p class="text-xs mt-1">
                                            <?= $absensiMessage ?>
                                        </p>
                                        <?php if (!empty($absensi['pesan_dosen'])): ?>
                                            <p class="text-xs mt-2">
                                                <strong>Catatan dosen:</strong>
                                                <?= htmlspecialchars($absensi['pesan_dosen']) ?>
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
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#1867c0] hover:bg-[#14529d] text-white text-sm font-medium rounded-md transition duration-200 shadow-sm"
                                >
                                    <span>Isi Absensi</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            <?php elseif ($status === 'pending'): ?>
                                <span class="inline-block px-3 py-1.5 bg-gray-100 text-gray-500 rounded-md font-medium text-xs md:text-sm">
                                    Menunggu ACC
                                </span>
                            <?php else: ?>
                                <a
                                    href="index.php?page=asdos/marketplace"
                                    class="inline-block px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md font-medium text-xs md:text-sm transition duration-200"
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

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>