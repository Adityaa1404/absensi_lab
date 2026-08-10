<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../auth/login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Dosen - Absensi Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col">
    <!-- Header / Navbar -->
    <header class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <!-- Brand / Logo -->   
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-bold shadow-inner">
                    LAB
                </div>
                <div>
                    <h1 class="text-base font-bold text-white leading-tight"><?= htmlspecialchars($_SESSION['nama']) ?></h1>
                    <p class="text-xs text-slate-400">Sistem Absensi Lab</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="kegiatan_push.php" class="px-4 py-2 rounded-xl text-sm font-medium transition duration-200 <?= $current_page === 'kegiatan_push.php' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' ?>">
                    Push Kegiatan
                </a>
                <a href="verifikasi.php" class="px-4 py-2 rounded-xl text-sm font-medium transition duration-200 <?= $current_page === 'verifikasi.php' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' ?>">
                    Verifikasi Absensi
                </a>
                <a href="profil.php" class="px-4 py-2 rounded-xl text-sm font-medium transition duration-200 <?= $current_page === 'profil.php' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' ?>">
                    Profil
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-right">
                    <p class="text-xs font-semibold text-white"><?= htmlspecialchars($_SESSION['nama'] ?? 'Dosen') ?></p>
                    <p class="text-[10px] text-indigo-400 font-mono">NIDN: <?= htmlspecialchars($_SESSION['username'] ?? '-') ?></p>
                </div>
                <a href="../auth/logout.php" class="px-3.5 py-1.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-medium hover:bg-red-500/20 transition duration-200 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="md:hidden border-t border-slate-800 px-4 py-2 flex items-center justify-around bg-slate-900/90">
            <a href="kegiatan_push.php" class="px-3 py-1.5 rounded-lg text-xs font-medium <?= $current_page === 'kegiatan_push.php' ? 'bg-indigo-600 text-white' : 'text-slate-300' ?>">Kegiatan</a>
            <a href="verifikasi.php" class="px-3 py-1.5 rounded-lg text-xs font-medium <?= $current_page === 'verifikasi.php' ? 'bg-indigo-600 text-white' : 'text-slate-300' ?>">Verifikasi</a>
            <a href="profil.php" class="px-3 py-1.5 rounded-lg text-xs font-medium <?= $current_page === 'profil.php' ? 'bg-indigo-600 text-white' : 'text-slate-300' ?>">Profil</a>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
