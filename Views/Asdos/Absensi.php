<?php require_once __DIR__ . '/../Templates/HeaderAsdos.php'; ?>

<!-- Header / Banner Title -->
<div class="bg-white rounded-md border border-gray-200 p-6 sm:p-8 mb-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a
                href="index.php?page=asdos/dashboard"
                class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-[#1867c0] hover:underline mb-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 leading-tight">
                Isi Absensi Kegiatan
            </h1>
            <p class="text-sm md:text-base text-gray-500 mt-1">
                Laporkan rincian pelaksanaan kegiatan laboratorium yang telah kamu lakukan.
            </p>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto">

    <!-- Card Informasi Kegiatan -->
    <div class="bg-white rounded-md border border-gray-200 p-6 mb-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-[#1867c0] mb-1">
            Kegiatan Terdaftar
        </p>
        <h3 class="text-lg md:text-xl font-bold text-gray-800">
            <?= htmlspecialchars($pendaftaran['nama_kegiatan'] ?? '-') ?>
        </h3>
        <p class="text-xs md:text-sm text-gray-500 mt-2 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-md text-sm md:text-base mb-6">
            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        class="bg-white rounded-md border border-gray-200 p-6 sm:p-8 shadow-sm space-y-6"
    >
        <input
            type="hidden"
            name="pendaftaran_id"
            value="<?= htmlspecialchars($pendaftaran['id_pendaftaran'] ?? '') ?>"
        >

        <!-- Input Tanggal -->
        <div>
            <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                Tanggal Pelaksanaan Kegiatan <span class="text-red-500">*</span>
            </label>
            <input
                type="date"
                id="tanggal"
                name="tanggal"
                required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1867c0] focus:border-transparent transition duration-200"
            >
        </div>

        <!-- Input Deskripsi Tugas -->
        <div>
            <label for="deskripsi_tugas" class="block text-sm font-semibold text-gray-700 mb-2">
                Deskripsi Tugas / Kegiatan <span class="text-red-500">*</span>
            </label>
            <textarea
                id="deskripsi_tugas"
                name="deskripsi_tugas"
                rows="4"
                required
                placeholder="Tuliskan detail pekerjaan atau materi yang diajarkan pada sesi ini..."
                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1867c0] focus:border-transparent transition duration-200 resize-none"
            ></textarea>
        </div>

        <!-- Grid Unggah Foto (Kegiatan & Selfie) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            <!-- Foto Kegiatan -->
            <div class="bg-gray-50/60 p-4 border border-gray-200 rounded-md">
                <label for="foto_kegiatan" class="block text-sm font-semibold text-gray-700 mb-1">
                    Foto Bukti Kegiatan <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-3">Foto dokumentasi pelaksanaan lab atau kelas.</p>
                
                <input
                    type="file"
                    id="foto_kegiatan"
                    name="foto_kegiatan"
                    accept="image/jpeg,image/png,image/webp"
                    required
                    class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#1867c0]/10 file:text-[#1867c0] hover:file:bg-[#1867c0]/20 cursor-pointer"
                >
                <p class="text-[11px] text-gray-400 mt-2">Format: JPG, PNG, WEBP (Maks. 5 MB)</p>
            </div>

            <!-- Foto Selfie -->
            <div class="bg-gray-50/60 p-4 border border-gray-200 rounded-md">
                <label for="foto_selfie" class="block text-sm font-semibold text-gray-700 mb-1">
                    Foto Presensi (Selfie) <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-3">Foto verifikasi kehadiran asisten di ruang lab.</p>

                <input
                    type="file"
                    id="foto_selfie"
                    name="foto_selfie"
                    accept="image/jpeg,image/png,image/webp"
                    required
                    class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#1867c0]/10 file:text-[#1867c0] hover:file:bg-[#1867c0]/20 cursor-pointer"
                >
                <p class="text-[11px] text-gray-400 mt-2">Format: JPG, PNG, WEBP (Maks. 5 MB)</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a
                href="index.php?page=asdos/dashboard"
                class="w-full sm:w-auto text-center px-5 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-medium rounded-md transition duration-200"
            >
                Batal
            </a>
            <button
                type="submit"
                class="w-full sm:w-auto px-6 py-2.5 bg-[#1867c0] hover:bg-[#14529d] text-white text-sm font-medium rounded-md transition duration-200 shadow-sm flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Kirim Presensi</span>
            </button>
        </div>
    </form>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>