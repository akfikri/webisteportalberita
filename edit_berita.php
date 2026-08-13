<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data berita berdasarkan ID
$data = mysqli_query($conn, "SELECT * FROM tbl_berita WHERE id_berita = $id");
$berita = mysqli_fetch_assoc($data);

if (!$berita) {
    header("Location: admin_berita.php");
    exit;
}

// Ambil kategori untuk dropdown
$kategori = mysqli_query($conn, "SELECT * FROM tbl_kategori");

if (isset($_POST['update'])) {
    $judul       = mysqli_real_escape_string($conn, $_POST['judul']);
    $slug        = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    $isi_berita  = mysqli_real_escape_string($conn, $_POST['isi_berita']);
    $id_kategori = (int)$_POST['id_kategori'];
    $gambar_lama = $_POST['gambar_lama'];

    // Cek apakah admin mengunggah gambar baru
    $nama_file   = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $tmp_file    = $_FILES['gambar']['tmp_name'];
    $EkstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
    
    if ($nama_file != "") {
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        if (!in_array($ekstensi, $EkstensiValid)) {
            $error = "Ekstensi gambar harus jpg, jpeg, png, atau webp!";
        } elseif ($ukuran_file > 2097152) {
            $error = "Ukuran gambar terlalu besar! Maksimal 2MB.";
        } else {
            $nama_file_baru = uniqid() . '.' . $ekstensi;
            move_uploaded_file($tmp_file, 'img/' . $nama_file_baru);
            $gambar_db = $nama_file_baru;

            // Hapus gambar lama jika ada dan bukan file default/class warna lama
            if ($gambar_lama && file_exists('img/' . $gambar_lama)) {
                unlink('img/' . $gambar_lama);
            }
        }
    } else {
        $gambar_db = $gambar_lama;
    }

    if (!isset($error)) {
        $query = "UPDATE tbl_berita SET 
                    judul = '$judul', 
                    slug = '$slug', 
                    isi_berita = '$isi_berita', 
                    gambar = '$gambar_db',
                    id_kategori = $id_kategori 
                  WHERE id_berita = $id";

        if (mysqli_query($conn, $query)) {
            header("Location: admin_berita.php");
            exit;
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Berita - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 700px;">
        <div class="card shadow-sm border-0 p-4 mb-5">
            <h3 class="fw-bold mb-4">✏️ Edit Berita</h3>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="gambar_lama" value="<?= $berita['gambar'] ?>">
                
                <div class="mb-3">
                    <label class="form-label">Judul Berita</label>
                    <input type="text" name="judul" class="form-control" value="<?= $berita['judul'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" class="form-select" required>
                        <?php while ($kat = mysqli_fetch_assoc($kategori)): ?>
                            <option value="<?= $kat['id_kategori'] ?>" <?= ($kat['id_kategori'] == $berita['id_kategori']) ? 'selected' : '' ?>>
                                <?= $kat['nama_kategori'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ganti Gambar (Opsional)</label>
                    <?php if (!empty($berita['gambar']) && file_exists('img/' . $berita['gambar'])): ?>
                        <div class="mb-2">
                            <img src="img/<?= $berita['gambar'] ?>" alt="Thumbnail" width="120" class="rounded shadow-sm">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Isi Berita</label>
                    <textarea name="isi_berita" class="form-control" rows="6" required><?= $berita['isi_berita'] ?></textarea>
                </div>
                <button type="submit" name="update" class="btn btn-success">Update Berita</button>
                <a href="admin_berita.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>