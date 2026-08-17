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

    /**
     * Mengambil seluruh pelamar pada kegiatan milik seorang Dosen.
     * Dapat difilter berdasarkan ID kegiatan jika diinginkan.
     */
    public function getByDosen(int $dosenId, ?int $kegiatanId = null): array
    {
        $sql = "SELECT 
                    p.id_pendaftaran,
                    p.kegiatan_id,
                    p.asdos_id,
                    p.status_pendaftaran,
                    p.pesan_lamaran,
                    p.catatan_dosen,
                    p.created_at AS tanggal_daftar,
                    k.nama_kegiatan,
                    k.periode_mulai,
                    k.periode_selesai,
                    k.kuota,
                    u.nama            AS nama_asdos,
                    u.identity_number AS npm_asdos,
                    u.email           AS email_asdos,
                    u.no_hp           AS no_hp_asdos
                FROM pendaftaran_kegiatan p
                INNER JOIN kegiatan k ON p.kegiatan_id = k.id_kegiatan
                INNER JOIN users u    ON p.asdos_id    = u.id_user
                WHERE k.dosen_id = :dosen_id";

        $params = [':dosen_id' => $dosenId];

        if ($kegiatanId !== null && $kegiatanId > 0) {
            $sql .= " AND p.kegiatan_id = :kegiatan_id";
            $params[':kegiatan_id'] = $kegiatanId;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Memperbarui status seleksi pendaftar (diterima / ditolak / pending) oleh Dosen.
     */
    public function updateStatus(int $pendaftaranId, int $dosenId, string $status, ?string $catatanDosen = null): void
    {
        $sql = "UPDATE pendaftaran_kegiatan p
                INNER JOIN kegiatan k ON p.kegiatan_id = k.id_kegiatan
                SET p.status_pendaftaran = :status,
                    p.catatan_dosen      = :catatan
                WHERE p.id_pendaftaran  = :id_pendaftaran 
                  AND k.dosen_id         = :dosen_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':status'         => $status,
            ':catatan'        => $catatanDosen,
            ':id_pendaftaran' => $pendaftaranId,
            ':dosen_id'       => $dosenId,
        ]);
    }

    /**
     * Menghitung total pelamar yang masih pending untuk seorang Dosen.
     */
    public function countPendingByDosen(int $dosenId): int
    {
        $sql = "SELECT COUNT(*) 
                FROM pendaftaran_kegiatan p
                INNER JOIN kegiatan k ON p.kegiatan_id = k.id_kegiatan
                WHERE k.dosen_id = :dosen_id AND p.status_pendaftaran = 'pending'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':dosen_id' => $dosenId]);
        return (int) $stmt->fetchColumn();
    }
}
