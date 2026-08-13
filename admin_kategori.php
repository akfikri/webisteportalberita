<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$pesan = '';
$error = '';

// 1. PROSES TAMBAH KATEGORI
if (isset($_POST['tambah_kategori'])) {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    
    if (!empty($nama_kategori)) {
        $query_tambah = "INSERT INTO tbl_kategori (nama_kategori) VALUES ('$nama_kategori')";
        if (mysqli_query($conn, $query_tambah)) {
            $pesan = "Kategori baru berhasil ditambahkan!";
        } else {
            $error = "Gagal menambah kategori: " . mysqli_error($conn);
        }
    } else {
        $error = "Nama kategori tidak boleh kosong!";
    }
}

// 2. PROSES HAPUS KATEGORI
if (isset($_GET['hapus'])) {
    $id_kategori = (int)$_GET['hapus'];
    // Hapus kategori berdasarkan ID
    $query_hapus = "DELETE FROM tbl_kategori WHERE id_kategori = $id_kategori";
    if (mysqli_query($conn, $query_hapus)) {
        header("Location: admin_kategori.php?pesan=hapus_sukses");
        exit;
    } else {
        $error = "Gagal menghapus kategori! Pastikan tidak ada berita yang menggunakan kategori ini.";
    }
}

// 3. PROSES EDIT KATEGORI (Jika tombol update ditekan)
if (isset($_POST['update_kategori'])) {
    $id_kategori   = (int)$_POST['id_kategori'];
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

    if (!empty($nama_kategori)) {
        $query_update = "UPDATE tbl_kategori SET nama_kategori = '$nama_kategori' WHERE id_kategori = $id_kategori";
        if (mysqli_query($conn, $query_update)) {
            header("Location: admin_kategori.php?pesan=edit_sukses");
            exit;
        } else {
            $error = "Gagal mengupdate kategori!";
        }
    }
}

// Ambil data untuk mode edit jika parameter ?edit=id tersedia
$edit_data = null;
if (isset($_GET['edit'])) {
    $id_edit = (int)$_GET['edit'];
    $res_edit = mysqli_query($conn, "SELECT * FROM tbl_kategori WHERE id_kategori = $id_edit");
    $edit_data = mysqli_fetch_assoc($res_edit);
}

// Ambil semua data kategori untuk ditampilkan di tabel
$result_kategori = mysqli_query($conn, "SELECT * FROM tbl_kategori ORDER BY id_kategori DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="admin.php">Panel Admin</a>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Manajemen Kategori Berita</h2>
                <a href="admin.php" class="text-decoration-none">&larr; Kembali ke Dashboard</a>
            </div>
        </div>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'hapus_sukses'): ?>
            <div class="alert alert-success">Kategori berhasil dihapus!</div>
        <?php endif; ?>
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'edit_sukses'): ?>
            <div class="alert alert-success">Kategori berhasil diperbarui!</div>
        <?php endif; ?>
        <?php if ($pesan != ''): ?>
            <div class="alert alert-success"><?= $pesan ?></div>
        <?php endif; ?>
        <?php if ($error != ''): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="row">
            <!-- Kolom Form Tambah / Edit Kategori -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold mb-3"><?= $edit_data ? 'Edit Kategori' : 'Tambah Kategori Baru' ?></h5>
                    <form action="" method="POST">
                        <?php if ($edit_data): ?>
                            <input type="hidden" name="id_kategori" value="<?= $edit_data['id_kategori'] ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" value="<?= $edit_data ? $edit_data['nama_kategori'] : '' ?>" required placeholder="Contoh: Pemrograman">
                        </div>

                        <?php if ($edit_data): ?>
                            <button type="submit" name="update_kategori" class="btn btn-success w-100">Update Kategori</button>
                            <a href="admin_kategori.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                        <?php else: ?>
                            <button type="submit" name="tambah_kategori" class="btn btn-primary w-100">Simpan Kategori</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Kolom Tabel Daftar Kategori -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th width="10%">No</th>
                                        <th>Nama Kategori</th>
                                        <th width="25%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    if (mysqli_num_rows($result_kategori) > 0) {
                                        while ($kat = mysqli_fetch_assoc($result_kategori)) { 
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="fw-bold"><?= $kat['nama_kategori'] ?></td>
                                        <td class="text-center">
                                            <a href="admin_kategori.php?edit=<?= $kat['id_kategori'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="admin_kategori.php?hapus=<?= $kat['id_kategori'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada kategori.</td></tr>";
                                    } 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>