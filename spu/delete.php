<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['ssLogin'])) {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_GET['no_spu'])) {
    header("location: list-spu.php");
    exit;
}

$no_spu = mysqli_real_escape_string($koneksi, $_GET['no_spu']);

$query = mysqli_query($koneksi, "DELETE FROM tbl_spu WHERE no_spu='$no_spu'");

if ($query) {
    echo "<script>
        alert('Data berhasil dihapus');
        window.location = 'list-spu.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus data');
        window.location = 'list-spu.php';
    </script>";
}
