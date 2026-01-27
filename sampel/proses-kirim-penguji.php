<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}

if (isset($_POST['kirim'])) {

    $no_spl_sipt = $_POST['no_spl_sipt']; 
    $id_penguji  = $_POST['id_penguji'];
    $id_penyelia = $_SESSION['ssId'];

    mysqli_query($koneksi, "
        INSERT INTO tbl_pengiriman_sampel
        (no_spl_sipt, status_sampel, id_penyelia, id_penguji, tgl_kirim)
        VALUES
        ('$no_spl_sipt', 'dikirim', '$id_penyelia', '$id_penguji', NOW())
    ");

    header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=dikirim");
    exit;
}
