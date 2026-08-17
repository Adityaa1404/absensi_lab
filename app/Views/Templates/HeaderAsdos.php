<?php
// Guard: hanya asdos yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asdos') {
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
    <title>Panel Asdos - Sistem Absensi Lab</title>
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
                        <span class="text-sm font-bold text-slate-900 leading-tight">Panel Asdos</span>
                        <span class="px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 rounded">
                            ASISTEN
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Sistem Informasi & Absensi Lab</p>
                </div>
            </div>

            <!-- Navigation Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="index.php?page=asdos/dashboard"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center gap-1.5 <?= $currentPageParam === 'asdos/dashboard' ? 'bg-[#1867c0] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="index.php?page=asdos/marketplace"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center gap-1.5 <?= $currentPageParam === 'asdos/marketplace' ? 'bg-[#1867c0] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Marketplace</span>
                </a>
                <a href="index.php?page=asdos/profil"
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold transition duration-150 flex items-center gap-1.5 <?= $currentPageParam === 'asdos/profil' ? 'bg-[#1867c0] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profil</span>
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-right">
                    <p class="text-xs font-bold text-slate-800 leading-tight"><?= htmlspecialchars($_SESSION['nama'] ?? 'Asdos') ?></p>
                    <p class="text-[11px] text-slate-500 font-mono">NPM: <?= htmlspecialchars($_SESSION['identity_number'] ?? '-') ?></p>
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
            <a href="index.php?page=asdos/dashboard"
               class="px-3 py-1.5 rounded-md font-medium <?= $currentPageParam === 'asdos/dashboard' ? 'bg-[#1867c0] text-white font-semibold' : 'text-slate-600' ?>">Dashboard</a>
            <a href="index.php?page=asdos/marketplace"
               class="px-3 py-1.5 rounded-md font-medium <?= $currentPageParam === 'asdos/marketplace' ? 'bg-[#1867c0] text-white font-semibold' : 'text-slate-600' ?>">Marketplace</a>
            <a href="index.php?page=asdos/profil"
               class="px-3 py-1.5 rounded-md font-medium <?= $currentPageParam === 'asdos/profil' ? 'bg-[#1867c0] text-white font-semibold' : 'text-slate-600' ?>">Profil</a>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
