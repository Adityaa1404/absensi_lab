<?php
// Guard: hanya dosen yang bisa akses halaman yang menggunakan header ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    header('Location: index.php?page=login');
    exit();
}

$currentPageParam = $_GET['page'] ?? '';
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Dosen - Sistem Absensi Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-800 antialiased">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

            <!-- Brand / Logo -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-[#1867c0] flex items-center justify-center text-white font-bold text-sm shadow-xs">
                    LAB
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-slate-900 leading-tight">Panel Dosen</span>
                        <span class="px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider bg-blue-50 text-[#1867c0] border border-blue-200 rounded">
                            DOSEN
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Sistem Informasi & Absensi Lab</p>
                </div>
            </div>

            <!-- Navigation Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="index.php?page=dosen/kegiatan"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center gap-1.5 <?= $currentPageParam === 'dosen/kegiatan' ? 'bg-[#1867c0] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Kegiatan</span>
                </a>
                <a href="index.php?page=dosen/seleksi"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center gap-1.5 <?= $currentPageParam === 'dosen/seleksi' ? 'bg-[#1867c0] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Seleksi Pelamar</span>
                </a>
                <a href="index.php?page=dosen/verifikasi"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center gap-1.5 <?= $currentPageParam === 'dosen/verifikasi' ? 'bg-[#1867c0] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Verifikasi Absensi</span>
                </a>
                <a href="index.php?page=dosen/profil"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center gap-1.5 <?= $currentPageParam === 'dosen/profil' ? 'bg-[#1867c0] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profil</span>
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-right">
                    <p class="text-xs font-bold text-slate-800 leading-tight"><?= htmlspecialchars($_SESSION['nama'] ?? 'Dosen') ?></p>
                    <p class="text-[11px] text-slate-500 font-mono">NIDN: <?= htmlspecialchars($_SESSION['identity_number'] ?? '-') ?></p>
                </div>
                <a href="index.php?page=logout"
                   class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-semibold transition duration-150 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden border-t border-slate-200 px-3 py-2 flex items-center justify-around bg-slate-50/90 text-xs">
            <a href="index.php?page=dosen/kegiatan"
               class="px-2.5 py-1.5 rounded-md font-medium <?= $currentPageParam === 'dosen/kegiatan' ? 'bg-[#1867c0] text-white font-semibold' : 'text-slate-600' ?>">Kegiatan</a>
            <a href="index.php?page=dosen/seleksi"
               class="px-2.5 py-1.5 rounded-md font-medium <?= $currentPageParam === 'dosen/seleksi' ? 'bg-[#1867c0] text-white font-semibold' : 'text-slate-600' ?>">Seleksi</a>
            <a href="index.php?page=dosen/verifikasi"
               class="px-2.5 py-1.5 rounded-md font-medium <?= $currentPageParam === 'dosen/verifikasi' ? 'bg-[#1867c0] text-white font-semibold' : 'text-slate-600' ?>">Verifikasi</a>
            <a href="index.php?page=dosen/profil"
               class="px-2.5 py-1.5 rounded-md font-medium <?= $currentPageParam === 'dosen/profil' ? 'bg-[#1867c0] text-white font-semibold' : 'text-slate-600' ?>">Profil</a>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
