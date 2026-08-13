<?php
include 'koneksi.php';

// 1. Validasi ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. Ambil data kategori dengan penanganan error
$query_kat = "SELECT nama_kategori FROM tbl_kategori WHERE id_kategori = $id";
$res_kat = mysqli_query($conn, $query_kat);
$data_kat = mysqli_fetch_assoc($res_kat);

// 3. Ambil berita
$query = "SELECT tbl_berita.*, tbl_kategori.nama_kategori, tbl_penulis.nama_lengkap 
          FROM tbl_berita 
          JOIN tbl_kategori ON tbl_berita.id_kategori = tbl_kategori.id_kategori 
          JOIN tbl_penulis ON tbl_berita.id_penulis = tbl_penulis.id_penulis 
          WHERE tbl_berita.id_kategori = $id
          ORDER BY tbl_berita.tanggal_posting DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">&laquo; Kembali</a>
        
        <?php if ($data_kat): ?>
            <h2 class="mb-4">Berita dalam Kategori: <span class="text-primary"><?= $data_kat['nama_kategori'] ?></span></h2>
        <?php else: ?>
            <h2 class="mb-4 text-danger">Kategori tidak ditemukan!</h2>
        <?php endif; ?>
        
        <div class="row">
            <?php if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5><?= $row['judul'] ?></h5>
                            <p class="text-muted small"><?= date('d M Y', strtotime($row['tanggal_posting'])) ?></p>
                        </div>
                    </div>
                </div>
            <?php } 
            } else { echo "<p class='alert alert-warning'>Belum ada berita yang diposting untuk kategori ini.</p>"; } ?>
        </div>
    </div>
</body>
</html>