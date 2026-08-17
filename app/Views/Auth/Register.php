<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem Absensi Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="min-h-full flex items-center justify-center p-4 sm:p-6 bg-slate-50 text-slate-800 antialiased">

    <!-- Register Card Container -->
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xs border border-slate-200 p-6 sm:p-8 my-6">

        <!-- Logo / Brand Header -->
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-[#1867c0] text-white font-bold text-lg flex items-center justify-center mx-auto mb-3 shadow-xs">
                LAB
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Form Pendaftaran Akun</h1>
            <p class="text-xs text-slate-500 mt-1">Daftarkan diri Anda sebagai Dosen atau Asisten Laboratorium</p>
        </div>

        <!-- Error Notification -->
        <?php if (!empty($error)): ?>
            <div class="mb-5 p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm flex items-start gap-2.5">
                <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Success Notification -->
        <?php if (!empty($success)): ?>
            <div class="mb-5 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-start gap-2.5">
                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span><?= htmlspecialchars($success) ?></span>
            </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form method="POST" action="index.php?page=register" class="space-y-4">
            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" required autofocus
                    class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                    placeholder="Nama lengkap beserta gelar (jika ada)">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="identity_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        NPM / NIDN <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="identity_number" name="identity_number" required
                        class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                        placeholder="NPM atau NIDN">
                </div>

                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Peran Akun <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" required
                        class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150">
                        <option value="" disabled selected>-- Pilih Peran --</option>
                        <option value="asdos">Asisten Dosen (Asdos)</option>
                        <option value="dosen">Dosen Pengampu</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Email Aktif <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                        class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                        placeholder="nama@email.com">
                </div>

                <div>
                    <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        No. HP / WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="no_hp" name="no_hp" required
                        class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                        placeholder="Contoh: 08123456789">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="password" name="password" required minlength="6"
                    class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1867c0] focus:ring-2 focus:ring-[#1867c0]/20 transition duration-150"
                    placeholder="Minimal 6 karakter">
            </div>

            <button type="submit"
                class="w-full bg-[#1867c0] hover:bg-[#14529d] active:bg-[#0f4482] text-white font-semibold py-2.5 px-4 text-sm rounded-lg transition duration-150 shadow-xs mt-2 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>Daftar Akun</span>
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-600">
                Sudah memiliki akun?
                <a href="index.php?page=login" class="text-[#1867c0] font-semibold hover:underline">Masuk di sini</a>
            </p>
        </div>

    </div>
</body>

</html>
