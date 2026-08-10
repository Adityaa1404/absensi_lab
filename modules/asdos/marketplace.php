<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asdos') {
    header("Location: ../auth/login.php");
    exit();
}

// Memuat navigasi yang sudah berisi tombol logout
require_once '../../includes/header.php';
?>

<div class="p-8 bg-white rounded-2xl shadow-sm border border-slate-200 mt-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Marketplace Asdos</h2>
            <p class="text-slate-500 mt-1">Kelola data job Anda di sini.</p>
        </div>
        
        <!-- Fallback tombol logout eksplisit jika user tidak melihat navbar -->
        <a href="../auth/logout.php" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-medium transition-colors md:hidden">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </a>
    </div>

    <!-- Konten marketplace lainnya nanti di sini -->
    <div class="p-4 border border-dashed border-slate-300 rounded-xl bg-slate-50 text-center text-slate-500">
        (Konten modul marketplace akan diletakkan di sini)
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
