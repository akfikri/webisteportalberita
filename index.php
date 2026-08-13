<?php
include 'koneksi.php';

// Query menggunakan JOIN untuk mengambil nama kategori dan nama penulis
$query = "SELECT tbl_berita.*, tbl_kategori.nama_kategori, tbl_penulis.nama_lengkap 
          FROM tbl_berita 
          JOIN tbl_kategori ON tbl_berita.id_kategori = tbl_kategori.id_kategori 
          JOIN tbl_penulis ON tbl_berita.id_penulis = tbl_penulis.id_penulis 
          ORDER BY tbl_berita.tanggal_posting DESC";

$result = mysqli_query($conn, $query);

// QUERY TAMBAHAN: Untuk mengambil daftar kategori dari database
$query_menu = "SELECT * FROM tbl_kategori";
$res_menu = mysqli_query($conn, $query_menu);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Berita SMK Palapa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .placeholder-img {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
        }
        body { background-color: #f8fafc; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">📰 Portal Berita SMK Palapa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
                    
                    <!-- Dropdown Kategori Dinamis dari Database -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kategori
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php 
                            // Looping menu kategori dari database
                            while($menu = mysqli_fetch_assoc($res_menu)) { 
                            ?>
                                <li><a class="dropdown-item" href="kategori.php?id=<?= $menu['id_kategori'] ?>"><?= $menu['nama_kategori'] ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="login.php">Login Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="border-bottom border-2 border-primary pb-2 mb-4">Berita Terkini</h3>
        <div class="row">
            
            <?php 
            if (mysqli_num_rows($result) > 0) {
                // Looping data dari database
                while($row = mysqli_fetch_assoc($result)) { 
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <!-- Thumbnail Berita -->
                    <?php if (!empty($row['gambar']) && file_exists('img/' . $row['gambar'])): ?>
                        <img src="img/<?= $row['gambar'] ?>" class="card-img-top" alt="Gambar Berita" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top placeholder-img bg-secondary text-white">🖼️ No Image</div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <!-- Badge Kategori -->
                        <a href="kategori.php?id=<?= $row['id_kategori'] ?>" class="badge bg-secondary mb-2 text-decoration-none"><?= $row['nama_kategori'] ?></a>
                        <h5 class="card-title fw-bold"><?= $row['judul'] ?></h5>
                        <!-- Cuplikan teks berita -->
                        <p class="card-text text-muted"><?= substr($row['isi_berita'], 0, 100) ?>...</p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <small class="text-muted">Oleh: <?= $row['nama_lengkap'] ?> | <?= date('d M Y', strtotime($row['tanggal_posting'])) ?></small><br>
                        <!-- Tombol Baca Selengkapnya mengarah ke detail.php berdasarkan id_berita -->
                        <a href="detail.php?id=<?= $row['id_berita'] ?>" class="btn btn-sm btn-outline-primary mt-2 w-100">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else {
                echo "<div class='col-12'><p class='text-center text-muted'>Belum ada berita yang dipublikasikan.</p></div>";
            } 
            ?>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>