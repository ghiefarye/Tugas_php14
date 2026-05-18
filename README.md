# 🎓 Portal Akademik Mahasiswa

Aplikasi **Portal Akademik Mahasiswa** untuk pengelolaan layanan administrasi & pengajuan surat akademik, dibangun dengan **PHP Native + MySQL** menggunakan pola **MVC** (Model–View–Controller).

---

## 📋 Fitur Utama

### Untuk **Admin**:
- ✅ Dashboard dengan statistik pengajuan surat
- ✅ Manajemen Pengguna (tambah/edit/hapus admin & mahasiswa)
- ✅ Verifikasi pengajuan surat akademik (setujui / tolak + catatan)
- ✅ Hapus pengajuan surat

### Untuk **Mahasiswa (User)**:
- ✅ Dashboard pribadi dengan info NIM & Prodi
- ✅ Ajukan surat akademik baru (6 jenis surat tersedia)
- ✅ Lihat daftar pengajuan & status real-time
- ✅ Lihat catatan dari Admin

### Jenis Surat yang Tersedia:
1. Surat Aktif Kuliah
2. Surat Keterangan Lulus
3. Surat Izin Penelitian
4. Surat Cuti Akademik
5. Surat Rekomendasi Beasiswa
6. Surat Pengantar

### Keamanan:
- ✅ Password di-hash dengan `password_hash()` (BCRYPT)
- ✅ Verifikasi login dengan `password_verify()`
- ✅ PDO + prepared statements (anti SQL injection)
- ✅ Middleware proteksi role (`Auth::requireAdmin()`, `Auth::requireUser()`)
- ✅ Session regeneration setelah login (anti session fixation)
- ✅ Escape output dengan `htmlspecialchars()` (anti XSS)
- ✅ User tidak bisa paksa masuk URL admin & sebaliknya

---

## 🚀 Cara Menjalankan

### 1. Persyaratan
- **Laragon** sudah terinstal
- **PHP 7.4+** (rekomendasi 8.0+)
- **MySQL/MariaDB** aktif di port 3306

### 2. Penempatan File
Letakkan folder `portal_akademik/` di dalam direktori `www` Laragon Anda:
```
C:\laragon\www\portal_akademik\
```

### 3. Pastikan Laragon Berjalan
- Buka **Laragon**, klik **Start All**
- Pastikan **MySQL aktif** (port 3306)

> ⚠️ **Database `portal_akademik` akan dibuat *otomatis*** saat aplikasi pertama kali dijalankan — Anda tidak perlu membuat database secara manual.

### 4. Akses Aplikasi
Buka browser dan kunjungi:
```
http://localhost/portal_akademik/
```
atau (jika Laragon menggunakan pretty URL):
```
http://portal_akademik.test/
```

Aplikasi akan otomatis:
- Membuat database `portal_akademik`
- Membuat tabel `users` dan `pengajuan_surat`
- Menambahkan akun demo

---

## 🔐 Akun Demo

| Role      | Username | Password    | Keterangan              |
|-----------|----------|-------------|-------------------------|
| Admin     | `admin`  | `admin123`  | Administrator Kampus    |
| Mahasiswa | `andi`   | `user123`   | Andi Pratama (TI)       |
| Mahasiswa | `bunga`  | `user123`   | Bunga Lestari (SI)      |

---

## 📁 Struktur Folder (MVC)

```
portal_akademik/
├── config/
│   └── database.php                ← Koneksi PDO + auto-create DB & tabel
├── app/
│   ├── core/
│   │   ├── App.php                 ← Router sederhana
│   │   ├── Controller.php          ← Base Controller
│   │   ├── Model.php               ← Base Model (PDO singleton)
│   │   └── Auth.php                ← Middleware proteksi role
│   ├── models/
│   │   ├── User.php                ← Logika tabel users
│   │   └── PengajuanSurat.php      ← Logika tabel pengajuan_surat
│   └── controllers/
│       ├── AuthController.php      ← Login, register, logout
│       ├── AdminController.php     ← Aksi-aksi admin
│       └── UserController.php      ← Aksi-aksi mahasiswa
├── views/
│   ├── layouts/
│   │   ├── header.php              ← Sidebar + topbar
│   │   └── footer.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── admin/
│   │   ├── dashboard.php
│   │   ├── users.php, user_form.php
│   │   └── pengajuan.php, pengajuan_detail.php
│   └── user/
│       ├── dashboard.php
│       ├── pengajuan.php
│       ├── pengajuan_form.php
│       └── pengajuan_detail.php
├── admin/                          ← Entry point halaman admin
│   ├── dashboard.php
│   ├── users.php, users_create.php, users_edit.php, users_delete.php
│   └── pengajuan.php, pengajuan_detail.php, pengajuan_delete.php
├── user/                           ← Entry point halaman mahasiswa
│   ├── dashboard.php
│   └── pengajuan.php, pengajuan_create.php, pengajuan_detail.php
├── assets/
│   └── css/style.css               ← Stylesheet utama (tema biru akademik)
├── index.php                       ← Root entry
├── login.php
├── register.php
├── logout.php
└── README.md
```

---

## 🛠️ Cara Mengganti Konfigurasi Database

Jika konfigurasi MySQL Anda berbeda (port/user/password), buka:
```
config/database.php
```
Dan sesuaikan baris berikut:
```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portal_akademik');
```

---

## 📊 Skema Database

### Tabel `users`
| Kolom         | Tipe                          | Keterangan                |
|---------------|-------------------------------|---------------------------|
| id            | INT AUTO_INCREMENT PK         |                           |
| username      | VARCHAR(50) UNIQUE            |                           |
| password      | VARCHAR(255)                  | hashed dengan BCRYPT      |
| nama_lengkap  | VARCHAR(100)                  |                           |
| email         | VARCHAR(100)                  | nullable                  |
| nim           | VARCHAR(20)                   | nullable (khusus mhs)     |
| prodi         | VARCHAR(100)                  | nullable (khusus mhs)     |
| role          | ENUM('admin','user')          | default 'user'            |
| created_at    | TIMESTAMP                     | auto                      |

### Tabel `pengajuan_surat`
| Kolom              | Tipe                                       | Keterangan       |
|--------------------|--------------------------------------------|------------------|
| id                 | INT AUTO_INCREMENT PK                      |                  |
| user_id            | INT FK → users.id                          | ON DELETE CASCADE|
| nomor_surat        | VARCHAR(50) UNIQUE                         | auto-generate    |
| jenis_surat        | ENUM (6 jenis)                             |                  |
| keperluan          | TEXT                                       |                  |
| tanggal_dibutuhkan | DATE                                       |                  |
| status             | ENUM('pending','disetujui','ditolak')      | default 'pending'|
| catatan_admin      | TEXT                                       | nullable         |
| created_at         | TIMESTAMP                                  | auto             |

---

## 🧪 Skenario Pengujian Proteksi Role

1. Login sebagai `andi` (mahasiswa) → coba akses paksa URL:
   ```
   http://localhost/portal_akademik/admin/dashboard.php
   ```
   ✅ **Akan ditolak** dan diarahkan kembali ke dashboard mahasiswa dengan pesan error.

2. Login sebagai `admin` → coba akses paksa URL:
   ```
   http://localhost/portal_akademik/user/dashboard.php
   ```
   ✅ **Akan ditolak** dan diarahkan ke dashboard admin.

3. Tanpa login → akses URL apapun selain `/login.php` atau `/register.php` → **otomatis diarahkan ke halaman login**.

---

## 📝 Lisensi
Aplikasi ini dibuat untuk keperluan pembelajaran/edukasi. Bebas dimodifikasi sesuai kebutuhan.

© 2026 Portal Akademik Mahasiswa
