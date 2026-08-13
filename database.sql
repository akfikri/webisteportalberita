-- Script Database Portal Berita
CREATE DATABASE IF NOT EXISTS db_portal_berita;
USE db_portal_berita;

CREATE TABLE IF NOT EXISTS tbl_kategori (
    id_kategori INT(11) NOT NULL AUTO_INCREMENT,
    nama_kategori VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_kategori)
);

CREATE TABLE IF NOT EXISTS tbl_penulis (
    id_penulis INT(11) NOT NULL AUTO_INCREMENT,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_penulis)
);

CREATE TABLE IF NOT EXISTS tbl_berita (
    id_berita INT(11) NOT NULL AUTO_INCREMENT,
    judul VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL,
    isi_berita TEXT NOT NULL,
    gambar VARCHAR(255),
    tanggal_posting DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_kategori INT(11) NOT NULL,
    id_penulis INT(11) NOT NULL,
    PRIMARY KEY (id_berita),
    FOREIGN KEY (id_kategori) REFERENCES tbl_kategori(id_kategori) ON DELETE CASCADE,
    FOREIGN KEY (id_penulis) REFERENCES tbl_penulis(id_penulis) ON DELETE CASCADE
);

-- Insert Data Dummy
INSERT INTO tbl_kategori (nama_kategori) VALUES ('Teknologi'), ('Pendidikan'), ('Prestasi');
INSERT INTO tbl_penulis (nama_lengkap, email, password) VALUES ('Admin RPL', 'admin@smkpalapa.sch.id', '123456');

-- Note: Kolom gambar diisi dengan class warna bootstrap untuk simulasi tanpa file gambar fisik
INSERT INTO tbl_berita (judul, slug, isi_berita, gambar, id_kategori, id_penulis) VALUES
('Siswa Kembangkan Aplikasi Baru', 'siswa-kembangkan-aplikasi-baru', 'Tim berhasil mengembangkan aplikasi berbasis web untuk sistem presensi menggunakan QR Code dan Google Sheets.', 'bg-primary', 1, 1),
('Kunjungan Industri ke Perusahaan IT', 'kunjungan-industri', 'Siswa kelas XI melakukan kunjungan industri untuk melihat langsung proses Software Development Life Cycle (SDLC).', 'bg-success', 2, 1),
('Juara 1 Lomba Web Design Tingkat Provinsi', 'juara-1-web-design', 'Perwakilan siswa sukses menyabet juara pertama dalam kompetisi desain antarmuka dan basis data.', 'bg-warning text-dark', 3, 1);
