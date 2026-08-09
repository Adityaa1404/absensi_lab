<?php
// Konfigurasi Database
$host     = "localhost";
$user     = "root";      // Username default XAMPP/Laragon
$password = "";          // Password default XAMPP kosong
$dbname   = "absensi_lab";

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    
    // Mengatur penanganan error ke Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mengatur format query agar selalu mengembalikan Array Asosiatif
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Tampilkan pesan jika koneksi gagal
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>