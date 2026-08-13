<?php
session_start();
// Proteksi halaman: Jika belum login, kembalikan ke halaman login.php
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Portal Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Panel Admin SMK Palapa</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 small">Halo, <strong><?= $_SESSION['nama_lengkap'] ?></strong></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Konten Dashboard -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0 p-4 mb-4">
                    <h2>Selamat Datang di Dashboard Admin</h2>
                    <p class="text-muted">Gunakan panel ini untuk mengelola artikel berita dan kategori pada website portal berita sekolah.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3 bg-white">
                    <h5 class="text-primary fw-bold">Kelola Berita</h5>
                    <p class="text-muted small">Tambah, ubah, atau hapus artikel berita terbaru.</p>
                    <a href="admin_berita.php" class="btn btn-sm btn-primary">Kelola Berita</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3 bg-white">
                    <h5 class="text-success fw-bold">Kelola Kategori</h5>
                    <p class="text-muted small">Atur kategori penelusuran berita.</p>
                    <a href="admin_kategori.php" class="btn btn-sm btn-success">Kelola Kategori</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 p-3 bg-white">
                    <h5 class="text-secondary fw-bold">Lihat Website</h5>
                    <p class="text-muted small">Kembali melihat halaman utama portal berita.</p>
                    <a href="index.php" class="btn btn-sm btn-secondary" target="_blank">Buka Website</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>