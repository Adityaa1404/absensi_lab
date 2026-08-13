<?php

/**
 * Class Database
 * 
 * Mengimplementasikan pola Singleton untuk koneksi PDO.
 * Memastikan hanya ada SATU instance koneksi database
 * yang digunakan di seluruh aplikasi.
 */
class Database
{
    // Menyimpan satu-satunya instance dari class ini (Singleton)
    private static ?Database $instance = null;

    // Objek koneksi PDO
    private PDO $pdo;

    // Konfigurasi Database
    private string $host     = 'localhost';
    private string $dbName   = 'absensi_lab';
    private string $user     = 'root';
    private string $password = '';
    private string $charset  = 'utf8mb4';

    /**
     * Constructor bersifat private agar class tidak bisa
     * di-instantiate dari luar dengan keyword 'new'.
     */
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password);

            // Lempar Exception jika ada error query
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Kembalikan hasil fetch sebagai array asosiatif secara default
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die('Koneksi ke database gagal: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil satu-satunya instance class Database.
     * Jika belum ada, akan dibuat terlebih dahulu.
     * 
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Mengembalikan objek koneksi PDO yang aktif.
     * 
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    // Mencegah class di-clone
    private function __clone() {}
}