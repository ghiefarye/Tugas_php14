<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Model User
 */
class User extends Model {

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function all($role = null) {
        if ($role) {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE role = ? ORDER BY id DESC");
            $stmt->execute([$role]);
        } else {
            $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        }
        return $stmt->fetchAll();
    }

    public function create($data) {
        // Kolom users pada versi yang berbeda bisa berbeda (nama_lengkap vs nama).
        // Untuk kompatibilitas, gunakan nama_lengkap jika tersedia, jika tidak pakai kolom 'nama'.
        $cols = $this->db->query("SHOW COLUMNS FROM users")->fetchAll();
        $colNames = array_map(fn($c) => $c['Field'], $cols);
        $namaCol = in_array('nama_lengkap', $colNames, true) ? 'nama_lengkap' : (in_array('nama', $colNames, true) ? 'nama' : 'nama_lengkap');

        $sql = "INSERT INTO users (username, password, {$namaCol}, email, nim, prodi, role)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['nama_lengkap'],
            $data['email'] ?? null,
            $data['nim']   ?? null,
            $data['prodi'] ?? null,
            $data['role']  ?? 'user',
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE users SET nama_lengkap = ?, email = ?, nim = ?, prodi = ?, role = ? WHERE id = ?
        ");
        return $stmt->execute([
            $data['nama_lengkap'],
            $data['email'] ?? null,
            $data['nim']   ?? null,
            $data['prodi'] ?? null,
            $data['role']  ?? 'user',
            $id,
        ]);
    }

    public function resetPassword($id, $newPassword) {
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countByRole() {
        $stmt = $this->db->query("
            SELECT role, COUNT(*) AS jml FROM users GROUP BY role
        ");
        $result = ['admin' => 0, 'user' => 0];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['role']] = (int)$row['jml'];
        }
        return $result;
    }

    /**
     * Verifikasi login.
     * Return data user (tanpa password) jika berhasil; false jika gagal.
     */
    public function authenticate($username, $password) {
        $user = $this->findByUsername($username);
        if (!$user) return false;
        if (!password_verify($password, $user['password'])) return false;
        unset($user['password']);
        return $user;
    }
}
