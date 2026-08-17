<?php
// Guard: hanya dosen yang bisa akses halaman yang menggunakan header ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    header('Location: index.php?page=login');
    exit();
}

// Tentukan halaman aktif untuk penanda navigasi (active state)
$currentPage = basename($_SERVER['PHP_SELF']);
// Karena semua halaman sekarang diakses melalui index.php,
// kita baca dari parameter ?page= di URL
$currentPageParam = $_GET['page'] ?? '';
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-20 md:h-24">

            <!-- Brand / Logo -->
            <div class="flex items-center gap-3 md:gap-4">
                <div class="w-12 h-12 md:w-16 md:h-16 rounded-md bg-[#1867c0]/10 border border-[#1867c0]/20 flex items-center justify-center text-[#1867c0] font-bold text-lg md:text-2xl">
                    LAB
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 leading-tight"><?= htmlspecialchars($_SESSION['nama']) ?></h1>
                    <p class="text-sm md:text-base text-gray-500">Sistem Absensi Lab</p>
                </div>
            </div>

            <!-- Navigation Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-2">
                <a href="index.php?page=dosen/kegiatan"
                   class="px-4 py-2.5 rounded-md text-sm md:text-lg font-medium transition duration-200 <?= $currentPageParam === 'dosen/kegiatan' ? 'bg-[#1867c0] text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' ?>">
                    Kegiatan
                </a>
                <a href="index.php?page=dosen/seleksi"
                   class="px-4 py-2.5 rounded-md text-sm md:text-lg font-medium transition duration-200 <?= $currentPageParam === 'dosen/seleksi' ? 'bg-[#1867c0] text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' ?>">
                    Seleksi Pelamar
                </a>
                <a href="index.php?page=dosen/verifikasi"
                   class="px-4 py-2.5 rounded-md text-sm md:text-lg font-medium transition duration-200 <?= $currentPageParam === 'dosen/verifikasi' ? 'bg-[#1867c0] text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' ?>">
                    Verifikasi Absensi
                </a>
                <a href="index.php?page=dosen/profil"
                   class="px-4 py-2.5 rounded-md text-sm md:text-lg font-medium transition duration-200 <?= $currentPageParam === 'dosen/profil' ? 'bg-[#1867c0] text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' ?>">
                    Profil
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div class="flex items-center gap-4 md:gap-6">
                <div class="hidden sm:block text-right">
                    <p class="text-sm md:text-lg font-semibold text-gray-800"><?= htmlspecialchars($_SESSION['nama'] ?? 'Dosen') ?></p>
                    <p class="text-xs md:text-sm text-gray-500 font-mono">NIDN: <?= htmlspecialchars($_SESSION['identity_number'] ?? '-') ?></p>
                </div>
                <a href="index.php?page=logout"
                   class="px-4 py-2.5 rounded-md bg-red-50 border border-red-200 text-red-600 text-sm md:text-lg font-medium hover:bg-red-100 transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden border-t border-gray-200 px-2 py-3 flex items-center justify-around bg-white text-xs sm:text-sm">
            <a href="index.php?page=dosen/kegiatan"
               class="px-3 py-1.5 rounded-md font-medium <?= $currentPageParam === 'dosen/kegiatan' ? 'bg-[#1867c0] text-white' : 'text-gray-600' ?>">Kegiatan</a>
            <a href="index.php?page=dosen/seleksi"
               class="px-3 py-1.5 rounded-md font-medium <?= $currentPageParam === 'dosen/seleksi' ? 'bg-[#1867c0] text-white' : 'text-gray-600' ?>">Seleksi</a>
            <a href="index.php?page=dosen/verifikasi"
               class="px-3 py-1.5 rounded-md font-medium <?= $currentPageParam === 'dosen/verifikasi' ? 'bg-[#1867c0] text-white' : 'text-gray-600' ?>">Verifikasi</a>
            <a href="index.php?page=dosen/profil"
               class="px-3 py-1.5 rounded-md font-medium <?= $currentPageParam === 'dosen/profil' ? 'bg-[#1867c0] text-white' : 'text-gray-600' ?>">Profil</a>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto p-6 sm:p-8 lg:p-12">
