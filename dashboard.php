<?php
session_start();

// Jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: modules/auth/login.php");
    exit();
}

// Redirect ke modul utama masing-masing role
if ($_SESSION['role'] === 'dosen') {
    header("Location: modules/dosen/profil.php");
    exit();
} elseif ($_SESSION['role'] === 'asdos') {
    header("Location: modules/asdos/marketplace.php");
    exit();
} else {
    // Fallback jika role tidak terdefinisi
    header("Location: modules/auth/login.php");
    exit();
}
