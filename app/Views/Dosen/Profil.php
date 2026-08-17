<?php require_once __DIR__ . '/../Templates/HeaderDosen.php'; ?>

<div class="max-w-4xl mx-auto space-y-8 md:space-y-12">

    <!-- Header Page -->
    <div class="bg-white border border-gray-200 p-6 sm:p-8 md:p-10 rounded-lg shadow-sm">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 tracking-tight">Kelola Profil Dosen</h2>
        <p class="text-sm sm:text-base md:text-xl text-gray-500 mt-2">Perbarui informasi data diri atau kelola keberadaan akun Anda</p>
    </div>

    <!-- Alert Notifications -->
    <?php if (!empty($error)): ?>
        <div class="p-5 md:p-6 rounded-lg bg-red-50 border border-red-200 text-red-600 text-base sm:text-lg md:text-xl flex items-start gap-4">
            <svg class="w-6 h-6 md:w-8 md:h-8 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="p-5 md:p-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 text-base sm:text-lg md:text-xl flex items-start gap-4">
            <svg class="w-6 h-6 md:w-8 md:h-8 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <!-- Overview Card (READ) -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm flex flex-col sm:flex-row items-center gap-8">
        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-lg bg-blue-50 border-2 border-[#1867c0] flex items-center justify-center text-[#1867c0] text-3xl sm:text-5xl font-bold shrink-0">
            <?= strtoupper(substr($user['nama'] ?? 'D', 0, 2)) ?>
        </div>
        <div class="text-center sm:text-left flex-1">
            <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800"><?= htmlspecialchars($user['nama'] ?? 'Dosen') ?></h3>
            <p class="text-base sm:text-lg md:text-xl text-[#1867c0] font-mono mt-1">NIDN: <?= htmlspecialchars($user['identity_number'] ?? '-') ?></p>
            <p class="text-sm md:text-base text-gray-500 mt-1"><?= htmlspecialchars($user['email'] ?? 'Email belum diatur') ?> | <?= htmlspecialchars($user['no_hp'] ?? 'No. HP belum diatur') ?></p>
            <span class="inline-block mt-3 px-4 py-1 rounded-full bg-gray-100 text-gray-700 border border-gray-200 text-xs sm:text-sm md:text-base font-bold uppercase tracking-wider">
                Role: <?= htmlspecialchars($user['role'] ?? 'dosen') ?>
            </span>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm">
        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mb-8 flex items-center gap-3">
            <span class="w-3.5 h-3.5 rounded-full bg-[#1867c0]"></span>
            Edit Data Profil
        </h3>

        <form method="POST" action="index.php?page=dosen/profil" class="space-y-8">
            <input type="hidden" name="action" value="update_profile">

            <div>
                <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required
                       class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                       placeholder="Masukkan Nama Lengkap beserta Gelar">
            </div>

            <div>
                <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">NIDN / Nomor Induk Dosen</label>
                <input type="text" name="identity_number" value="<?= htmlspecialchars($user['identity_number'] ?? '') ?>" required
                       class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                       placeholder="Masukkan NIDN">
            </div>

            <div>
                <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                       class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                       placeholder="Masukkan Alamat Email Aktif">
            </div>

            <div>
                <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-2">Nomor Handphone (WA)</label>
                <input type="tel" name="no_hp" value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>"
                       pattern="^08[0-9]{8,11}$"
                       class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                       placeholder="Contoh: 08123456789">
                <p class="text-xs sm:text-sm text-gray-500 mt-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Wajib diawali dengan 08 (misal: 08123456789). Jika diisi 628, sistem akan otomatis mengonversi ke 08.
                </p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#1867c0] hover:bg-[#1355a1] text-white font-medium py-3.5 px-8 sm:py-4 sm:px-10 rounded-md text-base sm:text-lg md:text-xl transition duration-200">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Form -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm">
        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mb-8 flex items-center gap-3">
            <span class="w-3.5 h-3.5 rounded-full bg-amber-500"></span>
            Ganti Password
        </h3>

        <form method="POST" action="index.php?page=dosen/profil" class="space-y-8">
            <input type="hidden" name="action" value="change_password">

            <div>
                <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Password Saat Ini *</label>
                <input type="password" name="current_password" required
                       class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                       placeholder="Masukkan password Anda saat ini">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Password Baru *</label>
                    <input type="password" name="new_password" required minlength="6"
                           class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                           placeholder="Minimal 6 karakter">
                </div>

                <div>
                    <label class="block text-sm sm:text-base md:text-lg font-bold uppercase tracking-wider text-gray-500 mb-3">Konfirmasi Password Baru *</label>
                    <input type="password" name="confirm_password" required minlength="6"
                           class="w-full bg-white border border-gray-300 rounded-md px-5 py-3.5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0] transition duration-200"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-medium py-3.5 px-8 sm:py-4 sm:px-10 rounded-md text-base sm:text-lg md:text-xl transition duration-200">
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 sm:p-8 md:p-10 shadow-sm">
        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-red-600 mb-3">Zona Bahaya (Hapus Akun)</h3>
        <p class="text-sm sm:text-base md:text-lg text-red-500 mb-8">Tindakan ini akan menghapus akun Dosen Anda secara permanen dari sistem beserta seluruh data terkait.</p>

        <form method="POST" action="index.php?page=dosen/profil" onsubmit="return confirm('PERHATIAN: Apakah Anda benar-benar yakin ingin menghapus akun Anda secara permanen?');">
            <input type="hidden" name="action" value="delete_profile">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 sm:py-3.5 sm:px-8 rounded-md text-sm sm:text-base md:text-lg transition duration-200">
                Hapus Akun Permanen
            </button>
        </form>
    </div>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
