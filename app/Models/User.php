<?php

/**
 * Model User
 * 
 * Bertanggung jawab atas seluruh operasi database pada tabel `users`.
 * Tidak ada logika bisnis di sini — hanya query database.
 */
class User
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Mencari user berdasarkan ID.
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id_user = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Mencari user berdasarkan identity_number (NPM/NIDN) — digunakan saat login.
     */
    public function findByIdentity(string $identityNumber): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE identity_number = :identity_number LIMIT 1");
        $stmt->execute(['identity_number' => $identityNumber]);
        return $stmt->fetch();
    }

    /**
     * Mengecek apakah identity_number sudah dipakai user lain (untuk validasi update profil).
     */
    public function isIdentityTaken(string $identityNumber, int $excludeId): bool
    {
        $stmt = $this->db->prepare("SELECT id_user FROM users WHERE identity_number = :identity_number AND id_user != :id LIMIT 1");
        $stmt->execute(['identity_number' => $identityNumber, 'id' => $excludeId]);
        return (bool) $stmt->fetch();
    }

    /**
     * Membuat user baru (registrasi).
     * 
     * @param array $data ['nama', 'email', 'identity_number', 'password', 'role', 'no_hp']
     */
    public function create(array $data): void
    {
        $sql = "INSERT INTO users (nama, email, identity_number, password, role, no_hp) 
                VALUES (:nama, :email, :identity_number, :password, :role, :no_hp)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nama'            => $data['nama'],
            ':email'           => $data['email'],
            ':identity_number' => $data['identity_number'],
            ':password'        => $data['password'],
            ':role'            => $data['role'],
            ':no_hp'           => $data['no_hp'],
        ]);
    }

    /**
     * Memperbarui data profil user.
     * 
     * @param array $data ['nama', 'identity_number', 'email', 'no_hp']
     */
    public function update(int $id, array $data): void
    {
        $sql = "UPDATE users SET nama = :nama, identity_number = :identity_number, 
                email = :email, no_hp = :no_hp WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nama'            => $data['nama'],
            ':identity_number' => $data['identity_number'],
            ':email'           => $data['email'],
            ':no_hp'           => $data['no_hp'],
            ':id'              => $id,
        ]);
    }

    /**
     * Mengubah password user.
     */
    public function changePassword(int $id, string $hashedPassword): void
    {
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id_user = :id");
        $stmt->execute([':password' => $hashedPassword, ':id' => $id]);
    }

    /**
     * Menghapus akun user secara permanen.
     */
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id_user = :id");
        $stmt->execute([':id' => $id]);
    }
}
