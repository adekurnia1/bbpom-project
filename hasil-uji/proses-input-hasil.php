<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"]) || $_SESSION["ssRole"] !== "penguji") {
    header("location: ../auth/login.php");
    exit;
}

$no_spl  = mysqli_real_escape_string($koneksi, $_POST['no_spl_sipt']);
$id_uji  = mysqli_real_escape_string($koneksi, $_POST['id_kategori_parameter']);
$id_penguji = $_SESSION['ssId'];

$hasil  = mysqli_real_escape_string($koneksi, $_POST['hasil_uji']);
$tgl_lcp = mysqli_real_escape_string($koneksi, $_POST['tgl_lcp']);
$bentuk = mysqli_real_escape_string($koneksi, $_POST['bentuk']);
$konsistensi = mysqli_real_escape_string($koneksi, $_POST['konsistensi']);
$warna  = mysqli_real_escape_string($koneksi, $_POST['warna']);
$bau    = mysqli_real_escape_string($koneksi, $_POST['bau']);
$catatan = mysqli_real_escape_string($koneksi, $_POST['catatan_penguji'] ?? '');

if ($no_spl == '' || $id_uji == '') {
    die("Data tidak valid!");
}

// cek apakah hasil sudah ada
$cek = mysqli_query($koneksi, "
    SELECT id_hasil_uji FROM tbl_hasil_uji
    WHERE no_spl_sipt = '$no_spl'
      AND id_kategori_parameter = '$id_uji'
      AND id_penguji = '$id_penguji'
");

$exist = mysqli_fetch_assoc($cek);

if ($exist) {
    // update jika sudah ada
    $update = mysqli_query($koneksi, "
        UPDATE tbl_hasil_uji
        SET hasil_uji = '$hasil',
            tgl_lcp = '$tgl_lcp',
            bentuk = '$bentuk',
            konsistensi = '$konsistensi',
            warna = '$warna',
            bau = '$bau',
            catatan_penguji = '$catatan',
            status_hasil = 'selesai',
            status_verifikasi = 'belum',
            updated_at = NOW()
        WHERE id_hasil_uji = '{$exist['id_hasil_uji']}'
    ");

    if (!$update) die(mysqli_error($koneksi));

} else {
    // insert baru jika belum ada
    $insert = mysqli_query($koneksi, "
        INSERT INTO tbl_hasil_uji
        (no_spl_sipt, id_kategori_parameter, id_penguji, hasil_uji, tgl_lcp, bentuk, konsistensi, warna, bau, catatan_penguji, status_hasil, status_verifikasi, created_at, updated_at)
        VALUES
        ('$no_spl', '$id_uji', '$id_penguji', '$hasil', '$tgl_lcp', '$bentuk', '$konsistensi', '$warna', '$bau', '$catatan', 'selesai', 'belum', NOW(), NOW())
    ");

    if (!$insert) die(mysqli_error($koneksi));
}

echo "<script>alert('Hasil uji berhasil disimpan!');window.location='list-sampel-uji.php';</script>";
exit;
