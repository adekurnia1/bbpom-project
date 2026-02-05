<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

if ($_SESSION['ssRole'] != 'penguji') {
    header("location: ../index.php");
    exit;
}

if (!isset($_POST['terima'])) {
    header("location: ../index.php");
    exit;
}

$id_pengiriman = mysqli_real_escape_string($koneksi, $_POST['id_pengiriman']);
$id_penguji    = $_SESSION['ssId'];

/* pastikan data ini memang milik penguji tsb */
$cek = mysqli_query($koneksi, "
    SELECT 1 FROM tbl_pengiriman_sampel
    WHERE id_pengiriman = '$id_pengiriman'
      AND id_penguji = '$id_penguji'
      AND status_pengiriman = 'dikirim'
");

if (mysqli_num_rows($cek) == 0) {
    header("location: inbox-sampel-penguji.php?msg=invalid");
    exit;
}

/* update status */
mysqli_query($koneksi, "
    UPDATE tbl_pengiriman_sampel
    SET 
        status_pengiriman = 'diterima',
        tgl_terima = NOW(),
        status_uji = 'proses'
    WHERE id_pengiriman = '$id_pengiriman'
");

/* redirect */
header("location:../hasil-uji/list-sampel-uji.php?msg=diterima");
exit;
