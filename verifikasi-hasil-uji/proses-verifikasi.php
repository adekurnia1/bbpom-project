<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"]) || $_SESSION["ssRole"] !== "penyelia") {
    die("Akses ditolak!");
}

$id = mysqli_real_escape_string($koneksi, $_POST['id_hasil_uji']);
$catatan = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');
$dateNow = date("Y-m-d H:i:s");

// Inisialisasi dulu biar tidak undefined
$update = false;

// Jika tombol setuju dikirim (opsional kalau masih dipakai)
if (isset($_POST['setuju'])) {
    $update = mysqli_query($koneksi, "
        UPDATE tbl_hasil_uji
        SET status_verifikasi = 'disetujui_penyelia',
            catatan_penguji = '',
            updated_at = '$dateNow'
        WHERE id_hasil_uji = '$id'
    ");
}

// Jika tombol aksi dikirim (yang kamu gunakan sekarang)
if (isset($_POST['aksi'])) {
    $status = mysqli_real_escape_string($koneksi, $_POST['aksi']);
    $update = mysqli_query($koneksi, "
        UPDATE tbl_hasil_uji
        SET status_verifikasi = '$status',
            catatan_penguji = '$catatan',
            updated_at = NOW()
        WHERE id_hasil_uji = '$id'
    ");
}

// Cek setelah kedua kemungkinan query
if (!$update) {
    die("Gagal update: " . mysqli_error($koneksi));
}

echo "<script>alert('Verifikasi berhasil diproses!');window.location='verifikasi-hasil-uji.php';</script>";
exit;
