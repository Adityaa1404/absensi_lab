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

    public function getPendaftaranDiterima(int $pendaftaranId, int $asdosId): array|false{
        $sql = "SELECT p.id_pendaftaran, p.status_pendaftaran, k.id_kegiatan, k.nama_kegiatan, k.periode_mulai, k.periode_selesai
                FROM pendaftaran_kegiatan p
                JOIN kegiatan k ON p.kegiatan_id = k.id_kegiatan
                WHERE p.id_pendaftaran = :pendaftaran_id AND p.asdos_id = :asdosId AND p.status_pendaftaran = 'diterima'LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':pendaftaran_id' => $pendaftaranId,
            ':asdosId' => $asdosId
        ]);
        return $stmt->fetch();
    }

    public function create(array $data): void
    {
        $sql = "INSERT INTO absensi 
                    (pendaftaran_id, tanggal, deskripsi_tugas, foto_kegiatan, foto_selfie, status_verifikasi)
                VALUES
                    ( :pendaftaran_id, :tanggal, :deskripsi_tugas, :foto_kegiatan, :foto_selfie, 'pending' )";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':pendaftaran_id'  => $data['pendaftaran_id'],
            ':tanggal'         => $data['tanggal'],
            ':deskripsi_tugas' => $data['deskripsi_tugas'],
            ':foto_kegiatan'   => $data['foto_kegiatan'],
            ':foto_selfie'     => $data['foto_selfie'],
        ]);
    }

    public function updateVerifikasi(int $id, string $status, string $pesanDosen): void
    {
        $sql = "UPDATE absensi
                SET status_verifikasi = :status,
                    pesan_dosen = :pesan
                WHERE id_absensi = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':status' => $status,
            ':pesan'  => $pesanDosen,
            ':id'     => $id
        ]);
    }

    public function getByAsdos(int $asdosId): array
    {
        $sql = "SELECT
                    a.*,
                    k.nama_kegiatan
                FROM absensi a
                INNER JOIN pendaftaran_kegiatan p
                    ON a.pendaftaran_id = p.id_pendaftaran
                INNER JOIN kegiatan k
                    ON p.kegiatan_id = k.id_kegiatan
                WHERE p.asdos_id = :asdos_id
                ORDER BY a.tanggal DESC, a.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':asdos_id' => $asdosId
        ]);

        return $stmt->fetchAll();
    }

    public function getByPendaftaran(int $pendaftaranId): array
    {
        $stmt = $this->db->prepare(
            "SELECT *
            FROM absensi
            WHERE pendaftaran_id = :pendaftaran_id
            ORDER BY created_at DESC"
        );

        $stmt->execute([
            ':pendaftaran_id' => $pendaftaranId
        ]);

        return $stmt->fetchAll();
    }
}
