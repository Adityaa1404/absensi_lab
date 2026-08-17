<?php require_once __DIR__ . '/../Templates/HeaderAsdos.php'; ?>

<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header / Banner Title -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a
                href="index.php?page=asdos/dashboard"
                class="inline-flex items-center gap-1 text-xs font-semibold text-[#1867c0] hover:underline mb-1"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Lapor Absensi & Tugas Praktikum
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Laporkan rincian pekerjaan dan unggah bukti pelaksanaan pendampingan lab.
            </p>
        </div>
    </div>

    <!-- Card Informasi Kegiatan -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
        <p class="text-[11px] font-bold uppercase tracking-wider text-[#1867c0] mb-1">
            Kegiatan Terdaftar
        </p>
        <h3 class="text-base font-bold text-slate-900">
            <?= htmlspecialchars($pendaftaran['nama_kegiatan'] ?? '-') ?>
        </h3>
        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>
                <?= !empty($pendaftaran['periode_mulai']) ? date('d M Y', strtotime($pendaftaran['periode_mulai'])) : '-' ?>
                –
                <?= !empty($pendaftaran['periode_selesai']) ? date('d M Y', strtotime($pendaftaran['periode_selesai'])) : '-' ?>
            </span>
        </p>
    </div>

    <!-- Alert Message Error -->
    <?php if (!empty($_SESSION['absensi_error'])): ?>
        <div class="flex items-start gap-2.5 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs sm:text-sm">
            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <span class="font-bold">Gagal Mengirim Absensi</span>
                <p class="mt-0.5"><?= htmlspecialchars($_SESSION['absensi_error']) ?></p>
            </div>
        </div>
        <?php unset($_SESSION['absensi_error']); ?>
    <?php endif; ?>

    <!-- Form Absensi -->
    <form
        action="index.php?page=asdos/absensi"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-xs space-y-5"
    >
        <input
            type="hidden"
            name="pendaftaran_id"
            value="<?= htmlspecialchars($pendaftaran['id_pendaftaran'] ?? '') ?>"
        >

        <!-- Input Tanggal -->
        <div>
            <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Tanggal Pelaksanaan Kegiatan <span class="text-red-500">*</span>
            </label>
            <input
                type="date"
                id="tanggal"
                name="tanggal"
                required
                class="w-full px-3.5 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
            >
        </div>

        <!-- Input Deskripsi Tugas -->
        <div>
            <label for="deskripsi_tugas" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Deskripsi Pekerjaan / Materi yang Disampaikan <span class="text-red-500">*</span>
            </label>
            <textarea
                id="deskripsi_tugas"
                name="deskripsi_tugas"
                rows="4"
                required
                placeholder="Tuliskan detail pekerjaan pendampingan, modul praktikum yang dibahas, atau kendala di ruang lab..."
                class="w-full px-3.5 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150 resize-none"
            ></textarea>
        </div>

        <!-- Grid Unggah Foto (Kegiatan & Selfie) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
            <!-- Foto Kegiatan -->
            <div class="bg-slate-50/80 p-4 border border-slate-200 rounded-xl space-y-2">
                <div>
                    <label for="foto_kegiatan" class="block text-xs font-semibold text-slate-800">
                        Foto Bukti Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <p class="text-[11px] text-slate-500">Dokumentasi suasana kelas/lab.</p>
                </div>
                
                <input
                    type="file"
                    id="foto_kegiatan"
                    name="foto_kegiatan"
                    accept="image/jpeg,image/png,image/webp"
                    required
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1867c0] hover:file:bg-blue-100 cursor-pointer"
                >
                <p class="text-[10px] text-slate-400">JPG, PNG, WEBP (Maks. 5 MB)</p>
            </div>

            <!-- Foto Selfie -->
            <div class="bg-slate-50/80 p-4 border border-slate-200 rounded-xl space-y-2">
                <div>
                    <label for="foto_selfie" class="block text-xs font-semibold text-slate-800">
                        Foto Presensi (Selfie) <span class="text-red-500">*</span>
                    </label>
                    <p class="text-[11px] text-slate-500">Verifikasi kehadiran asisten di lab.</p>
                </div>

                <input
                    type="file"
                    id="foto_selfie"
                    name="foto_selfie"
                    accept="image/jpeg,image/png,image/webp"
                    required
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1867c0] hover:file:bg-blue-100 cursor-pointer"
                >
                <p class="text-[10px] text-slate-400">JPG, PNG, WEBP (Maks. 5 MB)</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
            <a
                href="index.php?page=asdos/dashboard"
                class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 text-xs font-semibold rounded-lg transition duration-150"
            >
                Batal
            </a>
            <button
                type="submit"
                class="px-5 py-2 bg-[#1867c0] hover:bg-[#14529d] text-white text-xs font-semibold rounded-lg transition duration-150 shadow-xs flex items-center gap-1.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Kirim Laporan Absensi</span>
            </button>
        </div>
    </form>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
