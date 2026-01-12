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
        UPDATE tbl_sampel SET
            status_sampel = 'selesai',
            id_penyelia = '$id_penyelia',
            id_penguji = '$id_penguji',
            tgl_kirim = NOW()
        WHERE no_spl_sipt = '$no_spl_sipt'
    ");

    header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=dikirim");
    exit;
}
