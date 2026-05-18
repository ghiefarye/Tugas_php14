<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Model PengajuanSurat — modul pengajuan surat akademik mahasiswa.
 */
class PengajuanSurat extends Model {

    public function all() {
        $sql = "SELECT p.*, u.nama_lengkap, u.username, u.nim, u.prodi
                FROM pengajuan_surat p JOIN users u ON u.id = p.user_id
                ORDER BY p.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function byUser($userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM pengajuan_surat WHERE user_id = ? ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.nama_lengkap, u.username, u.nim, u.prodi
            FROM pengajuan_surat p JOIN users u ON u.id = p.user_id
            WHERE p.id = ? LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO pengajuan_surat
                (user_id, nomor_surat, jenis_surat, keperluan, tanggal_dibutuhkan, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        return $stmt->execute([
            $data['user_id'],
            $data['nomor_surat'],
            $data['jenis_surat'],
            $data['keperluan'],
            $data['tanggal_dibutuhkan'],
        ]);
    }

    public function updateStatus($id, $status, $catatan = null) {
        $stmt = $this->db->prepare("
            UPDATE pengajuan_surat SET status = ?, catatan_admin = ? WHERE id = ?
        ");
        return $stmt->execute([$status, $catatan, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM pengajuan_surat WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function statistik() {
        $sql = "SELECT status, COUNT(*) AS jml FROM pengajuan_surat GROUP BY status";
        $rows = $this->db->query($sql)->fetchAll();
        $r = ['pending' => 0, 'disetujui' => 0, 'ditolak' => 0, 'total' => 0];
        foreach ($rows as $row) {
            $r[$row['status']] = (int)$row['jml'];
            $r['total'] += (int)$row['jml'];
        }
        return $r;
    }

    public function generateNomorSurat() {
        $thn = date('Y');
        $bln = date('m');
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM pengajuan_surat
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
        ");
        $stmt->execute([$thn, $bln]);
        $no = (int)$stmt->fetchColumn() + 1;
        return sprintf("PA/%03d/%s/%s", $no, $bln, $thn);
    }

    /** Daftar jenis surat untuk dropdown */
    public static function jenisSuratList() {
        return [
            'Surat Aktif Kuliah',
            'Surat Keterangan Lulus',
            'Surat Izin Penelitian',
            'Surat Cuti Akademik',
            'Surat Rekomendasi Beasiswa',
            'Surat Pengantar',
        ];
    }
}
