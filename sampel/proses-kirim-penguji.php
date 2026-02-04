<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_POST['kirim'])) {
    header("location: ../index.php");
    exit;
}

$no_spl_sipt = mysqli_real_escape_string($koneksi, $_POST['no_spl_sipt']);
$id_penguji  = mysqli_real_escape_string($koneksi, $_POST['id_penguji']);
$id_penyelia = $_SESSION['ssId'];


// ==================== 1. CEK DUPLIKAT ====================
$cek = mysqli_query($koneksi, "
    SELECT 1 FROM tbl_pengiriman_sampel 
    WHERE no_spl_sipt = '$no_spl_sipt'
");

if (mysqli_num_rows($cek) > 0) {
    header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=already");
    exit;
}


// ==================== 2. SIMPAN SAJA ====================
mysqli_query($koneksi, "
    INSERT INTO tbl_pengiriman_sampel
    (no_spl_sipt, id_penyelia, id_penguji, status_pengiriman, status_uji)
    VALUES
    ('$no_spl_sipt', '$id_penyelia', '$id_penguji', 'dikirim', 'menunggu')
");

header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=dikirim");
exit;
