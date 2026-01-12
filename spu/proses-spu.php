<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}

require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: add-spu.php");
    exit;
}


//jika tombol "simpan" ditekan
if (isset($_POST['simpan'])) {
    //ambil value elemen yang diposting
    $no_spu = trim(htmlspecialchars($_POST['no_spu']));
    $tipe_sampel = trim(htmlspecialchars($_POST['tipe_sampel']));
    $asal_sampling = $_POST['asal_sampling'];
    $bulan_masuk = trim(htmlspecialchars($_POST['bulan_masuk']));
    $tgl_masuk_lab = trim(htmlspecialchars($_POST['tgl_masuk_lab']));
    $tgl_spk = trim(htmlspecialchars($_POST['tgl_spk']));
    $jumlah_sampel = trim(htmlspecialchars($_POST['jumlah_sampel']));
    $timeline = trim(htmlspecialchars($_POST['timeline']));

    //CEK spu
    $CekSpu = mysqli_query($koneksi, "SELECT * FROM tbl_spu WHERE no_spu = '$no_spu'");
    if (mysqli_num_rows($CekSpu) > 0) {
        header("location:add-spu.php?msg=cancel");
        return;
    }

    mysqli_query($koneksi, "INSERT INTO tbl_spu VALUES('$no_spu','$tipe_sampel','$asal_sampling',
    '$bulan_masuk', '$tgl_masuk_lab', '$tgl_spk', '$jumlah_sampel', '$timeline')");

    header("location:add-spu.php?msg=added");
    return;
}
