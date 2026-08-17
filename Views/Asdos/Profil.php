<?php require_once __DIR__ . '/../Templates/HeaderAsdos.php'; ?>

<!-- Header / Banner Title -->
<div class="bg-white rounded-md border border-gray-200 p-6 sm:p-8 mb-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs sm:text-sm font-semibold text-[#1867c0] uppercase tracking-wider mb-1">
                Pengaturan Akun
            </p>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 leading-tight">
                Profil Asdos
            </h1>
            <p class="text-sm md:text-base text-gray-500 mt-1">
                Kelola informasi akun dan pembaruan kata sandi pribadi kamu.
            </p>
        </div>
    </div>
</div>

<div class="space-y-6">

    <!-- Alert Notifikasi Error -->
    <?php if (!empty($error)): ?>
        <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-md text-sm md:text-base">
            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <span class="font-bold">Gagal Menyimpan:</span>
                <p class="mt-0.5"><?= htmlspecialchars($error) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Alert Notifikasi Sukses -->
    <?php if (!empty($success)): ?>
        <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-md text-sm md:text-base">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <span class="font-bold">Berhasil!</span>
                <p class="mt-0.5"><?= htmlspecialchars($success) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- CARD 1: INFORMASI PRIBADI -->
    <div class="bg-white border border-gray-200 rounded-md p-6 sm:p-8 shadow-sm">
        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#1867c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Informasi Pribadi</span>
        </h3>

        <form method="POST" action="index.php?page=asdos/profil" class="space-y-6">
            <input type="hidden" name="action" value="update_profile">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama"
                        value="<?= htmlspecialchars($user['nama'] ?? '') ?>"
                        required
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1867c0] focus:border-transparent transition duration-200"
                    >
                </div>

                <!-- NIM / NPM -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        NIM / NPM <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="identity_number"
                        value="<?= htmlspecialchars($user['identity_number'] ?? '') ?>"
                        required
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1867c0] focus:border-transparent transition duration-200"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                        required
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1867c0] focus:border-transparent transition duration-200"
                    >
                </div>

                <!-- No. HP -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        No. HP / WhatsApp
                    </label>
                    <input
                        type="text"
                        name="no_hp"
                        value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>"
                        placeholder="Contoh: 081234567890"
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1867c0] focus:border-transparent transition duration-200"
                    >
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button
                    type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-[#1867c0] hover:bg-[#14529d] text-white text-sm font-medium rounded-md transition duration-200 shadow-sm flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- CARD 2: GANTI PASSWORD -->
    <div class="bg-white border border-gray-200 rounded-md p-6 sm:p-8 shadow-sm">
        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <span>Ubah Kata Sandi</span>
        </h3>

        <form method="POST" action="index.php?page=asdos/profil" class="space-y-6">
            <input type="hidden" name="action" value="change_password">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Password Saat Ini -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        name="current_password"
                        required
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1867c0] focus:border-transparent transition duration-200"
                    >
                </div>

                <!-- Password Baru -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        name="new_password"
                        required
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1867c0] focus:border-transparent transition duration-200"
                    >
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        name="confirm_password"
                        required
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1867c0] focus:border-transparent transition duration-200"
                    >
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button
                    type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-md transition duration-200 shadow-sm flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"/>
                    </svg>
                    <span>Ubah Password</span>
                </button>
            </div>
        </form>
    </div>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>