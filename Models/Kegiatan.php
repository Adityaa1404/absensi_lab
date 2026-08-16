<?php

/**
 * Model Kegiatan
 * 
 * Bertanggung jawab atas seluruh operasi database pada tabel `kegiatan`.
 */
class Kegiatan
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Mengambil semua kegiatan milik seorang dosen, diurutkan terbaru.
     */
    public function getByDosen(int $dosenId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM kegiatan WHERE dosen_id = :dosen_id ORDER BY created_at DESC"
        );
        $stmt->execute(['dosen_id' => $dosenId]);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil satu data kegiatan berdasarkan ID, hanya milik dosen yang sedang login.
     */
    public function findById(int $id, int $dosenId): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM kegiatan WHERE id_kegiatan = :id AND dosen_id = :dosen_id LIMIT 1"
        );
        $stmt->execute(['id' => $id, 'dosen_id' => $dosenId]);
        return $stmt->fetch();
    }

    /**
     * Menambahkan kegiatan baru ke database.
     * 
     * @param array $data ['dosen_id', 'nama_kegiatan', 'periode_mulai', 'periode_selesai', 'deskripsi_tugas', 'insentif', 'status', 'kuota']
     */
    public function create(array $data): void
    {
        $sql = "INSERT INTO kegiatan 
                    (dosen_id, nama_kegiatan, periode_mulai, periode_selesai, deskripsi_tugas, insentif, status, kuota)
                VALUES 
                    (:dosen_id, :nama_kegiatan, :periode_mulai, :periode_selesai, :deskripsi_tugas, :insentif, :status, :kuota)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':dosen_id'        => $data['dosen_id'],
            ':nama_kegiatan'   => $data['nama_kegiatan'],
            ':periode_mulai'   => $data['periode_mulai'],
            ':periode_selesai' => $data['periode_selesai'],
            ':deskripsi_tugas' => $data['deskripsi_tugas'],
            ':insentif'        => $data['insentif'],
            ':status'          => $data['status'],
            ':kuota'           => $data['kuota'],
        ]);
    }

    /**
     * Memperbarui data kegiatan. Hanya dosen pemiliknya yang bisa mengubah.
     * 
     * @param array $data ['nama_kegiatan', 'periode_mulai', 'periode_selesai', 'deskripsi_tugas', 'insentif', 'status', 'kuota']
     */
    public function update(int $id, int $dosenId, array $data): void
    {
        $sql = "UPDATE kegiatan SET 
                    nama_kegiatan = :nama_kegiatan, 
                    periode_mulai = :periode_mulai, 
                    periode_selesai = :periode_selesai, 
                    deskripsi_tugas = :deskripsi_tugas, 
                    insentif = :insentif, 
                    status = :status, 
                    kuota = :kuota 
                WHERE id_kegiatan = :id AND dosen_id = :dosen_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nama_kegiatan'   => $data['nama_kegiatan'],
            ':periode_mulai'   => $data['periode_mulai'],
            ':periode_selesai' => $data['periode_selesai'],
            ':deskripsi_tugas' => $data['deskripsi_tugas'],
            ':insentif'        => $data['insentif'],
            ':status'          => $data['status'],
            ':kuota'           => $data['kuota'],
            ':id'              => $id,
            ':dosen_id'        => $dosenId,
        ]);
    }

    /**
     * Menghapus kegiatan. Hanya dosen pemiliknya yang bisa menghapus.
     */
    public function delete(int $id, int $dosenId): void
    {
        $stmt = $this->db->prepare(
            "DELETE FROM kegiatan WHERE id_kegiatan = :id AND dosen_id = :dosen_id"
        );
        $stmt->execute(['id' => $id, 'dosen_id' => $dosenId]);
    }

    /**
     * Mengambil semua kegiatan yang statusnya 'open' dan kuota > 0
     */
    public function getAllOpen(): array
    {
        $stmt = $this->db->query("SELECT * FROM kegiatan WHERE status = 'open' AND kuota > 0");
        return $stmt->fetchAll();
    }

    /**
     * Mengambil satu kegiatan yang masih terbuka berdasarkan ID.
     */
    public function findOpenById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM kegiatan
            WHERE id_kegiatan = :id
            AND status = 'open'
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch();
    }
}
