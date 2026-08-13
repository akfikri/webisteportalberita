<?php
include 'koneksi.php';

// Ambil ID dari URL, pastikan berupa angka
$id_berita = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query untuk mengambil detail berita berdasarkan ID
$query = "SELECT tbl_berita.*, tbl_kategori.nama_kategori, tbl_penulis.nama_lengkap 
          FROM tbl_berita 
          JOIN tbl_kategori ON tbl_berita.id_kategori = tbl_kategori.id_kategori 
          JOIN tbl_penulis ON tbl_berita.id_penulis = tbl_penulis.id_penulis 
          WHERE tbl_berita.id_berita = $id_berita";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Jika berita tidak ditemukan
if (!$row) {
    echo "<script>alert('Berita tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><= $row['judul'] ?> - Portal Berita SMK Palapa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; }
        .artikel-img { max-height: 400px; object-fit: cover; width: 100%; }
        .content-body { font-size: 1.1rem; line-height: 1.8; white-space: pre-line; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">📰 Portal Berita SMK Palapa</a>
        </div>
    </nav>

    <div class="container mt-4 mb-5" style="max-width: 800px;">
        <a href="index.php" class="btn btn-outline-secondary mb-3">&larr; Kembali ke Beranda</a>
        
        <div class="card shadow-sm border-0 p-4">
            <!-- Kategori Badge -->
            <div>
                <span class="badge bg-primary mb-2"><?= $row['nama_kategori'] ?></span>
            </div>
            
            <!-- Judul Berita -->
            <h1 class="fw-bold mb-3"><?= $row['judul'] ?></h1>
            
            <!-- Informasi Penulis & Tanggal -->
            <p class="text-muted small border-bottom pb-3 mb-4">
                Ditulis oleh: <strong><?= $row['nama_lengkap'] ?></strong> | Dipublikasikan pada: <?= date('d M Y, H:i', strtotime($row['tanggal_posting'])) ?> WIB
            </p>

            <!-- Gambar Berita (jika ada) -->
            <?php if (!empty($row['gambar']) && file_exists('img/' . $row['gambar'])): ?>
                <div class="mb-4">
                    <img src="img/<?= $row['gambar'] ?>" class="rounded artikel-img shadow-sm" alt="Gambar Berita">
                </div>
            <?php endif; ?>

            <!-- Isi Berita Lengkap -->
            <div class="content-body text-dark">
                <?= $row['isi_berita'] ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>