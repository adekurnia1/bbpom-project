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

//jika tombol "simpan" ditekan
if (isset($_POST['simpan'])) {

    $no_spl_sipt   = trim(htmlspecialchars($_POST['no_spl_sipt']));
    $no_spu        = trim(htmlspecialchars($_POST['no_spu']));
    $pabrik        = trim(htmlspecialchars($_POST['pabrik']));
    $no_reg        = trim(htmlspecialchars($_POST['no_reg']));
    $no_bet        = trim(htmlspecialchars($_POST['no_bet']));
    $nama_sampel   = trim(htmlspecialchars($_POST['nama_sampel']));
    $brand         = trim(htmlspecialchars($_POST['brand']));
    $komposisi     = trim(htmlspecialchars($_POST['komposisi']));
    $kadaluarsa    = trim(htmlspecialchars($_POST['kadaluarsa']));
    $kategori      = trim(htmlspecialchars($_POST['kategori']));
    $wadah         = trim(htmlspecialchars($_POST['wadah']));
    $netto         = trim(htmlspecialchars($_POST['netto']));

    //CEK no_spl_sipt apakah sudah ada
    $CekSampel = mysqli_query($koneksi, "SELECT * FROM tbl_sampel WHERE no_spl_sipt = '$no_spl_sipt'");
    if (mysqli_num_rows($CekSampel) > 0) {
        header("location:add-sampel.php?msg=cancel");
        return;
    }

    //INSERT data awal sampel
    mysqli_query($koneksi, "INSERT INTO tbl_sampel
    (no_spl_sipt, no_spu, pabrik, no_reg, no_bet, nama_sampel, brand, komposisi, kadaluarsa, kategori, wadah, netto, status_sampel)
    VALUES
    ('$no_spl_sipt','$no_spu','$pabrik','$no_reg','$no_bet','$nama_sampel','$brand','$komposisi','$kadaluarsa','$kategori','$wadah','$netto','dikirim')
    ");

    header("location:add-sampel.php?msg=added");
    exit;
}
