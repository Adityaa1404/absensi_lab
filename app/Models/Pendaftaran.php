<?php

/**
 * Model Pendaftaran
 *
 * Bertanggung jawab atas operasi database
 * pada tabel pendaftaran_kegiatan.
 */
class Pendaftaran
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Mengecek apakah Asdos sudah pernah mendaftar
     * pada kegiatan tertentu.
     */
    public function findByKegiatanAndAsdos(
        int $kegiatanId,
        int $asdosId
    ): array|false {
        $stmt = $this->db->prepare("
            SELECT
                id_pendaftaran,
                kegiatan_id,
                asdos_id,
                status_pendaftaran,
                created_at
            FROM pendaftaran_kegiatan
            WHERE kegiatan_id = :kegiatan_id
              AND asdos_id = :asdos_id
            LIMIT 1
        ");

        $stmt->execute([
            'kegiatan_id' => $kegiatanId,
            'asdos_id' => $asdosId
        ]);

        return $stmt->fetch();
    }

    /**
     * Membuat pendaftaran baru.
     */
    public function create(
        int $kegiatanId,
        int $asdosId
    ): void {
        $stmt = $this->db->prepare("
            INSERT INTO pendaftaran_kegiatan
                (kegiatan_id, asdos_id, status_pendaftaran)
            VALUES
                (:kegiatan_id, :asdos_id, 'pending')
        ");

        $stmt->execute([
            'kegiatan_id' => $kegiatanId,
            'asdos_id' => $asdosId
        ]);
    }

    /**
     * Mengambil seluruh pendaftaran milik seorang Asdos.
     *
     * Digunakan untuk Dashboard Asdos.
     */
    public function getByAsdos(int $asdosId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.id_pendaftaran, p.kegiatan_id, p.asdos_id, p.status_pendaftaran, p.created_at, k.nama_kegiatan, k.periode_mulai, k.periode_selesai, k.deskripsi_tugas, k.insentif, k.status AS status_kegiatan
            FROM pendaftaran_kegiatan p
            INNER JOIN kegiatan k
                ON p.kegiatan_id = k.id_kegiatan
            WHERE p.asdos_id = :asdos_id
            ORDER BY p.created_at DESC
        ");

        $stmt->execute([
            'asdos_id' => $asdosId
        ]);

        return $stmt->fetchAll();
    }
}
