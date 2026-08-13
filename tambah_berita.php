<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Ambil data kategori untuk dropdown
$kategori = mysqli_query($conn, "SELECT * FROM tbl_kategori");

if (isset($_POST['simpan'])) {
    $judul       = mysqli_real_escape_string($conn, $_POST['judul']);
    $slug        = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    $isi_berita  = mysqli_real_escape_string($conn, $_POST['isi_berita']);
    $id_kategori = (int)$_POST['id_kategori'];
    $id_penulis  = $_SESSION['id_penulis'];

    // Proses Upload Gambar
    $nama_file   = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $tmp_file    = $_FILES['gambar']['tmp_name'];
    $EkstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensi    = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    if ($nama_file != "") {
        // Cek ekstensi file
        if (!in_array($ekstensi, $EkstensiValid)) {
            $error = "Ekstensi gambar harus jpg, jpeg, png, atau webp!";
        } 
        // Cek ukuran file (maksimal 2MB)
        elseif ($ukuran_file > 2097152) {
            $error = "Ukuran gambar terlalu besar! Maksimal 2MB.";
        } else {
            // Buat nama file unik agar tidak bentrok
            $nama_file_baru = uniqid() . '.' . $ekstensi;
            
            // Pastikan folder 'img' ada, jika belum buat otomatis
            if (!is_dir('img')) {
                mkdir('img', 0777, true);
            }

            move_uploaded_file($tmp_file, 'img/' . $nama_file_baru);
            $gambar_db = $nama_file_baru;
        }
    } else {
        // Jika tidak upload gambar, gunakan nilai default/kosong
        $gambar_db = "default.jpg";
    }

    if (!isset($error)) {
        $query = "INSERT INTO tbl_berita (judul, slug, isi_berita, gambar, id_kategori, id_penulis) 
                  VALUES ('$judul', '$slug', '$isi_berita', '$gambar_db', $id_kategori, $id_penulis)";
                  
        if (mysqli_query($conn, $query)) {
            header("Location: admin_berita.php");
            exit;
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Berita - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 700px;">
        <div class="card shadow-sm border-0 p-4 mb-5">
            <h3 class="fw-bold mb-4">📝 Tambah Berita Baru</h3>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <!-- Tambahkan enctype="multipart/form-data" agar form dapat memproses file -->
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Judul Berita</label>
                    <input type="text" name="judul" class="form-control" required placeholder="Masukkan judul berita...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php while ($kat = mysqli_fetch_assoc($kategori)): ?>
                            <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Unggah Gambar Thumbnail</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <div class="form-text">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Isi Berita</label>
                    <textarea name="isi_berita" class="form-control" rows="6" required placeholder="Tulis isi berita di sini..."></textarea>
                </div>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan Berita</button>
                <a href="admin_berita.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>