<?php
session_start();
include 'koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Mengecek data admin/penulis berdasarkan email dan password
    $query = "SELECT * FROM tbl_penulis WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Menyimpan sesi login
        $_SESSION['status_login'] = true;
        $_SESSION['id_penulis'] = $row['id_penulis'];
        $_SESSION['nama_lengkap'] = $row['nama_lengkap'];

        // Mengarahkan ke halaman admin
        header("Location: admin.php");
        exit;
    } else {
        $error = "Email atau Password yang Anda masukkan salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Portal Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; }
        .login-card { max-width: 400px; margin-top: 100px; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card login-card shadow-sm border-0 w-100">
            <div class="card-body p-4">
                <h3 class="text-center fw-bold mb-4">🔐 Login Admin</h3>
                
                <?php if ($error != ''): ?>
                    <div class="alert alert-danger py-2"><?= $error ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="admin@smkpalapa.sch.id">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="******">
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100 py-2">Masuk</button>
                </form>
                <div class="text-center mt-3">
                    <a href="index.php" class="text-decoration-none small">&larr; Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>