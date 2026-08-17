<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10">

    <!-- Login Card Container -->
    <div class="w-full max-w-[560px] bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-8 sm:px-10 sm:py-12 md:px-14 md:py-16">

        <!-- Logo / Brand Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-3xl md:text-4xl font-bold text-gray-800 tracking-tight">Sistem Absensi Asdos</h1>
        </div>

        <!-- Success Notification -->
        <?php if (!empty($success)): ?>
            <div class="mb-6 p-4 rounded bg-green-50 border border-green-200 text-green-700 text-base sm:text-lg">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Error Notification -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 rounded bg-red-50 border border-red-200 text-red-600 text-base sm:text-lg">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="index.php?page=login" class="space-y-6">
            <div>
                <input type="text" id="identity_number" name="identity_number" required autofocus
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]"
                    placeholder="NPM/NIDN">
            </div>

            <div>
                <input type="password" id="password" name="password" required
                    class="w-full bg-white border border-gray-300 rounded-md px-4 py-3.5 sm:px-5 sm:py-4 text-base sm:text-lg md:text-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0056b3] focus:ring-2 focus:ring-[#0056b3]"
                    placeholder="Password">
            </div>

            <button type="submit"
                class="bg-[#1867c0] hover:bg-[#1355a1] active:bg-[#0f4482] text-white font-medium py-3 px-8 sm:py-3.5 sm:px-10 text-base sm:text-lg md:text-xl rounded-md transition duration-200 mt-2">
                Log in
            </button>
        </form>

        <div class="mt-6">
            <a href="index.php?page=register" class="text-base sm:text-lg text-[#1867c0] hover:underline font-medium">Belum punya akun? Daftar disini</a>
        </div>
        <div class="mt-6">
            <a href="#" class="text-base sm:text-lg text-[#1867c0] hover:underline font-medium">Lupa Password?</a>
        </div>

    </div>
</body>

</html>
