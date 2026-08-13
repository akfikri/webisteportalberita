<?php
$host = "localhost";
$user = "root";
$pass = ""; // Kosongkan jika default XAMPP
$db   = "b_portal_berita1";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>