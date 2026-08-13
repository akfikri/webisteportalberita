<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Proses Hapus Berita
if (isset($_GET['hapus'])) {
    $id_berita = (int)$_GET['hapus'];
    $query_hapus = "DELETE FROM tbl_berita WHERE id_berita = $id_berita";
    if (mysqli_query($conn, $query_hapus)) {
        header("Location: admin_berita.php?pesan=hapus_sukses");
        exit;
    }
}

// Ambil data berita, kategori, dan penulis
$query = "SELECT tbl_berita.*, tbl_kategori.nama_kategori, tbl_penulis.nama_lengkap 
          FROM tbl_berita 
          JOIN tbl_kategori ON tbl_berita.id_kategori = tbl_kategori.id_kategori 
          JOIN tbl_penulis ON tbl_berita.id_penulis = tbl_penulis.id_penulis 
          ORDER BY tbl_berita.tanggal_posting DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Berita - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="admin.php">Panel Admin</a>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manajemen Artikel Berita</h2>
            <a href="tambah_berita.php" class="btn btn-primary">+ Tambah Berita Baru</a>
        </div>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'hapus_sukses'): ?>
            <div class="alert alert-success">Berita berhasil dihapus!</div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Berita</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) { 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="fw-bold"><?= $row['judul'] ?></td>
                                <td><span class="badge bg-secondary"><?= $row['nama_kategori'] ?></span></td>
                                <td class="small"><?= $row['nama_lengkap'] ?></td>
                                <td class="small"><?= date('d/m/Y H:i', strtotime($row['tanggal_posting'])) ?></td>
                                <td class="text-center">
                                    <a href="edit_berita.php?id=<?= $row['id_berita'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="admin_berita.php?hapus=<?= $row['id_berita'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus berita ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada data berita.</td></tr>";
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="admin.php" class="text-decoration-none">&larr; Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>