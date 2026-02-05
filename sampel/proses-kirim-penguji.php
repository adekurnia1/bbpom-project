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
    SELECT id_pengiriman 
    FROM tbl_pengiriman_sampel 
    WHERE no_spl_sipt = '$no_spl_sipt'
");

if (mysqli_num_rows($cek) > 0) {
    header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=already");
    exit;
}


// ==================== 2. SIMPAN PENGIRIMAN ====================
mysqli_query($koneksi, "
    INSERT INTO tbl_pengiriman_sampel
    (no_spl_sipt, id_penyelia, id_penguji, status_pengiriman, status_uji, tgl_kirim)
    VALUES
    ('$no_spl_sipt', '$id_penyelia', '$id_penguji', 'dikirim', 'menunggu', NOW())
");


// ==================== 3. AMBIL DATA (UNTUK TEMPLATE / PDF) ====================
$q = mysqli_query($koneksi, "
    SELECT 
        s.*,
        u1.nama AS nama_penyelia,
        u2.nama AS nama_penguji,
        sp.no_spu,
        sp.tgl_spk,
        sp.timeline
    FROM tbl_sampel s
    JOIN tbl_pengiriman_sampel ps ON ps.no_spl_sipt = s.no_spl_sipt
    JOIN tbl_users u1 ON u1.id_user = ps.id_penyelia
    JOIN tbl_users u2 ON u2.id_user = ps.id_penguji
    JOIN tbl_spu sp ON sp.no_spu = s.no_spu
    WHERE s.no_spl_sipt = '$no_spl_sipt'
");

$data = mysqli_fetch_assoc($q);

// sekarang $data siap dipakai di template_spp.php
// misalnya:
// $data['nama_sampel']
// $data['nama_penguji']
// $data['nama_penyelia']


// ==================== 4. REDIRECT ====================
header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=dikirim");
exit;
