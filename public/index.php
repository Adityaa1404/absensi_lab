<?php

/**
 * Front Controller / Router Utama
 * 
 * Ini adalah satu-satunya entry point aplikasi.
 * Semua request masuk ke sini, kemudian diteruskan ke Controller yang tepat
 * berdasarkan parameter ?page= di URL.
 * 
 * Contoh URL:
 *   index.php?page=login          → AuthController::showLogin()
 *   index.php?page=register       → AuthController::showRegister()
 *   index.php?page=logout         → AuthController::logout()
 *   index.php?page=dosen/profil   → DosenController::profil()
 *   index.php?page=dosen/kegiatan → DosenController::kegiatan()
 *   index.php?page=dosen/verifikasi → DosenController::verifikasi()
 *   index.php?page=asdos/marketplace → AsdosController::marketplace()
 */

// Mulai session di satu tempat terpusat
session_start();

// Load semua Controller
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DosenController.php';
require_once __DIR__ . '/../app/Controllers/AsdosController.php';

// Baca parameter halaman dari URL, default ke 'login'
$page = $_GET['page'] ?? 'login';

// ============================================================
// Routing — Peta URL ke Controller dan Method
// ============================================================

switch ($page) {

    // --- Auth ---
    case 'login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case 'register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // --- Dosen ---
    case 'dosen/profil':
        $controller = new DosenController();
        $controller->profil();
        break;

    case 'dosen/kegiatan':
        $controller = new DosenController();
        $controller->kegiatan();
        break;

    case 'dosen/seleksi':
        $controller = new DosenController();
        $controller->seleksi();
        break;

    case 'dosen/verifikasi':
        $controller = new DosenController();
        $controller->verifikasi();
        break;

    // --- Asdos ---
    case 'asdos/dashboard':
        $controller = new AsdosController();
        $controller->dashboard();
        break;

    case 'asdos/marketplace':
        $controller = new AsdosController();
        $controller->marketplace();
        break;

    case 'asdos/daftar':
        $controller = new AsdosController();
        $controller->daftar();
        break;

    case 'asdos/absensi':
        $controller = new AsdosController();
        $controller->absensi();
        break;

    case 'asdos/profil':
        $controller = new AsdosController();
        $controller->profil();
        break;

    // --- 404 Fallback ---
    default:
        http_response_code(404);
        echo '<!DOCTYPE html><html lang="id" class="h-full bg-slate-50"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>404 - Halaman Tidak Ditemukan</title><script src="https://cdn.tailwindcss.com"></script><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"><style>body{font-family:\'Inter\',sans-serif;}</style></head><body class="min-h-full flex items-center justify-center p-4"><div class="max-w-md w-full bg-white rounded-2xl border border-slate-200 p-8 text-center shadow-xs"><div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-lg">404</div><h1 class="text-xl font-bold text-slate-900 mb-1">Halaman Tidak Ditemukan</h1><p class="text-xs text-slate-500 mb-6">Halaman <code class="px-1.5 py-0.5 bg-slate-100 rounded text-slate-800 font-mono">' . htmlspecialchars($page) . '</code> tidak tersedia di sistem.</p><a href="index.php?page=login" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#1867c0] hover:bg-[#14529d] text-white text-xs font-semibold rounded-lg shadow-xs transition">Kembali ke Beranda</a></div></body></html>';
        break;
}
