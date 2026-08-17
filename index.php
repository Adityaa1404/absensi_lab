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
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/DosenController.php';
require_once __DIR__ . '/Controllers/AsdosController.php';

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

    case 'dosen/verifikasi':
        $controller = new DosenController();
        $controller->verifikasi();
        break;

    // --- Asdos ---
    case 'asdos/marketplace':
        $controller = new AsdosController();
        $controller->marketplace();
        break;

    case 'asdos/dashboard':
        $controller = new AsdosController();
        $controller->dashboard();
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
        echo '<div style="font-family:sans-serif;text-align:center;padding:80px;">';
        echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
        echo '<p>Halaman <strong>' . htmlspecialchars($page) . '</strong> tidak ada.</p>';
        echo '<a href="index.php?page=login" style="color:#1867c0;">Kembali ke Login</a>';
        echo '</div>';
        break;
}
