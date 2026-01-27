<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}

if (isset($_POST['kirim'])) {

    $id_sampel   = $_POST['id_sampel'];   
    $id_penguji  = $_POST['id_penguji'];
    $id_penyelia = $_SESSION['ssId'];

    mysqli_query($koneksi, "
        INSERT INTO tbl_pengiriman_sampel
        (id_sampel, status_sampel, id_penyelia, id_penguji, tgl_kirim)
        VALUES
        ('$id_sampel', 'dikirim', '$id_penyelia', '$id_penguji', CURDATE())
    ");

    header("location:view-sampel.php?id_sampel=$id_sampel&msg=dikirim");
    exit;
}
