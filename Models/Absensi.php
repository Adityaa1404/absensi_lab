<?php

/**
 * Model Absensi
 * 
 * Bertanggung jawab atas seluruh operasi database pada tabel `absensi`.
 */
class Absensi
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Mengambil seluruh data absensi dengan JOIN ke tabel kegiatan dan users,
     * agar nama asdos dan nama kegiatan ikut ditampilkan di halaman verifikasi.
     */
    public function getAll(): array
    {
        $sql = "SELECT 
                    a.*, 
                    k.nama_kegiatan, 
                    u.nama            AS nama_asdos, 
                    u.identity_number AS npm_asdos
                FROM absensi a
                LEFT JOIN pendaftaran_kegiatan p ON a.pendaftaran_id = p.id_pendaftaran
                LEFT JOIN kegiatan k             ON p.kegiatan_id    = k.id_kegiatan
                LEFT JOIN users u                ON p.asdos_id       = u.id_user
                ORDER BY a.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Memperbarui status verifikasi dan pesan dosen untuk satu record absensi.
     */
    public function updateVerifikasi(int $id, string $status, string $pesanDosen): void
    {
        $sql = "UPDATE absensi 
                SET status_verifikasi = :status, pesan_dosen = :pesan 
                WHERE id_absensi = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':pesan'  => $pesanDosen,
            ':id'     => $id,
        ]);
    }
}
