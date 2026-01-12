<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}

require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: add-sampel.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $kategori = $_POST['kategori'];
    $parameter_uji = $_POST['parameter_uji'];
    $lod = $_POST['lod'];
    $loq = $_POST['loq'];
    $syarat = $_POST['syarat'];
    $metode = $_POST['metode'];
    $pustaka = $_POST['pustaka'];
    $tipe_pu = $_POST['tipe_pu'];
    $jenis_pu = $_POST['jenis_pu'];
    $pusurveilance = $_POST['pusurveilance'];
    $keterangan = $_POST['keterangan'];

    mysqli_query($koneksi, "
        INSERT INTO tbl_kategori_parameter
        (kategori, parameter_uji, lod, loq, syarat, metode, pustaka, tipe_pu, jenis_pu, pusurveilance, keterangan)
        VALUES
        ('$kategori', '$parameter_uji', '$lod', '$loq', '$syarat', '$metode', '$pustaka', '$tipe_pu', '$jenis_pu', '$pusurveilance', '$keterangan')
    ");

    header("location:list-kategori.php?msg=added");
    exit;
};