<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['ssLogin'])) {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id_kategori_parameter'])) {
    header("location: list-kategori.php");
    exit;
}

$id_kategori_parameter = mysqli_real_escape_string($koneksi, $_GET['id_kategori_parameter']);

$query = mysqli_query($koneksi, 
"DELETE FROM tbl_kategori_parameter WHERE id_kategori_parameter='$id_kategori_parameter'");

if ($query) {
    echo "<script>
        alert('Data berhasil dihapus');
        window.location = 'list-kategori.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus data');
        window.location = 'list-kategori.php';
    </script>";
}
