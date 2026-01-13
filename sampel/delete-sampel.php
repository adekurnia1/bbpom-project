<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['ssLogin'])) {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_GET['no_spl_sipt'])) {
    header("location: list-sampel.php");
    exit;
}

$no_spl_sipt = mysqli_real_escape_string($koneksi, $_GET['no_spl_sipt']);

$query = mysqli_query($koneksi, "DELETE FROM tbl_sampel WHERE no_spl_sipt='$no_spl_sipt'");

if ($query) {
    echo "<script>
        alert('Data berhasil dihapus');
        window.location = 'list-sampel.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus data');
        window.location = 'list-sampel.php';
    </script>";
}
