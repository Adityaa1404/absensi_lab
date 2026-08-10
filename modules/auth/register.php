<?php

session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: ../../dashboard.php");
    exit();
}

$error = '';
$message = '';

//tangkap data form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = trim($_POST['role']);

    if (empty($username) || empty($password) || empty($role)) {
        $error = "Semua kolom harus diisi!";
    } else {
        require_once '../../config/koneksi.php';

        try {
            //cek duplikasi username
            $stmt = $pdo->prepare("SELECT id_user FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user) {
                $error = "Username sudah terdaftar!";
            } else {
                //enkripsi password
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                //simpan user baru
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
                $stmt->execute([
                    'username' => $username,
                    'password' => $password_hash,
                    'role'     => $role
                ]);

                $message = "User berhasil ditambahkan. User: <span class='font-bold'>$username</span> | Role: <span class='font-bold'>$role</span>";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

//Ambil semua data user yang sudah ada untuk ditampilkan
try {
    require_once '../../config/koneksi.php';
    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id_user ASC");
    $stmt->execute();
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
    $error = "Gagal memuat daftar user: " . $e->getMessage();
}



?>