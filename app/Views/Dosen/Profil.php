<?php require_once __DIR__ . '/../Templates/HeaderDosen.php'; ?>

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Page -->
    <div class="bg-white border border-slate-200 p-5 sm:p-6 rounded-xl shadow-xs">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Profil Dosen</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola data pribadi, informasi kontak, dan kata sandi akun dosen Anda.</p>
    </div>

    <!-- Alert Notifications -->
    <?php if (!empty($error)): ?>
        <div class="p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm flex items-start gap-2.5">
            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-start gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <!-- Overview Card (READ) -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row items-center gap-5">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-blue-50 border-2 border-[#1867c0] flex items-center justify-center text-[#1867c0] text-xl sm:text-2xl font-bold shrink-0">
            <?= strtoupper(substr($user['nama'] ?? 'D', 0, 2)) ?>
        </div>
        <div class="text-center sm:text-left flex-1">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900"><?= htmlspecialchars($user['nama'] ?? 'Dosen') ?></h3>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-[#1867c0] border border-blue-200">
                    <?= htmlspecialchars($user['role'] ?? 'dosen') ?>
                </span>
            </div>
            <p class="text-xs font-mono text-slate-600 mt-1">NIDN: <strong><?= htmlspecialchars($user['identity_number'] ?? '-') ?></strong></p>
            <p class="text-xs text-slate-500 mt-0.5"><?= htmlspecialchars($user['email'] ?? 'Email belum diatur') ?> • <?= htmlspecialchars($user['no_hp'] ?? 'No. HP belum diatur') ?></p>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 shadow-xs">
        <h3 class="text-base font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#1867c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Informasi Pribadi</span>
        </h3>

        <form method="POST" action="index.php?page=dosen/profil" class="space-y-4">
            <input type="hidden" name="action" value="update_profile">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                           placeholder="Nama Lengkap beserta Gelar">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        NIDN / Nomor Induk Dosen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="identity_number" value="<?= htmlspecialchars($user['identity_number'] ?? '') ?>" required
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                           placeholder="NIDN">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                           placeholder="nama@email.com">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        No. Handphone / WhatsApp
                    </label>
                    <input type="text" name="no_hp" value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>"
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                           placeholder="Contoh: 08123456789">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-[#1867c0] hover:bg-[#14529d] text-white font-semibold py-2 px-5 rounded-lg text-xs transition duration-150 shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Form -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 shadow-xs">
        <h3 class="text-base font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <span>Ubah Kata Sandi</span>
        </h3>

        <form method="POST" action="index.php?page=dosen/profil" class="space-y-4">
            <input type="hidden" name="action" value="change_password">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password Saat Ini <span class="text-red-500">*</span></label>
                    <input type="password" name="current_password" required
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                           placeholder="Password saat ini">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="new_password" required minlength="6"
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                           placeholder="Minimal 6 karakter">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="confirm_password" required minlength="6"
                           class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2 px-5 rounded-lg text-xs transition duration-150 shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"/>
                    </svg>
                    <span>Ubah Password</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50/60 border border-red-200 rounded-xl p-5 sm:p-6 shadow-xs">
        <h3 class="text-base font-bold text-red-700 mb-1">Zona Berbahaya</h3>
        <p class="text-xs text-red-600 mb-4">Tindakan ini akan menghapus akun Dosen Anda beserta seluruh riwayat kegiatan secara permanen.</p>

        <form method="POST" action="index.php?page=dosen/profil" onsubmit="return confirm('PERHATIAN: Apakah Anda yakin ingin menghapus akun Anda secara permanen?');">
            <input type="hidden" name="action" value="delete_profile">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg text-xs transition duration-150 shadow-xs">
                Hapus Akun Permanen
            </button>
        </form>
    </div>

</div>

<?php require_once __DIR__ . '/../Templates/Footer.php'; ?>
