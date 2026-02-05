<?php
session_start();
require_once "../config.php";
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

$no_spl_sipt = mysqli_real_escape_string($koneksi, $_POST['no_spl_sipt']);
$id_penguji  = mysqli_real_escape_string($koneksi, $_POST['id_penguji']);
$id_penyelia = $_SESSION['ssId'];

/* CEK DUPLIKAT */
$cek = mysqli_query($koneksi, "
    SELECT 1 FROM tbl_pengiriman_sampel 
    WHERE no_spl_sipt='$no_spl_sipt'
");

if (mysqli_num_rows($cek) > 0) {
    header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=already");
    exit;
}

/* SIMPAN PENGIRIMAN */
mysqli_query($koneksi, "
    INSERT INTO tbl_pengiriman_sampel
    (no_spl_sipt, id_penyelia, id_penguji, status_pengiriman, status_uji, tgl_kirim)
    VALUES
    ('$no_spl_sipt', '$id_penyelia', '$id_penguji', 'dikirim', 'menunggu', NOW())
");

/* AMBIL DATA UNTUK PDF */
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
    WHERE s.no_spl_sipt='$no_spl_sipt'
");

$data = mysqli_fetch_assoc($q);

/* GENERATE PDF */
ob_start();
include "../doc_templates/template_spp.php"; // pakai $data di dalam
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* SIMPAN FILE */
$filename = "SPP_{$no_spl_sipt}.pdf";
file_put_contents("../file_spp/$filename", $dompdf->output());

/* SIMPAN PATH */
mysqli_query($koneksi, "
    UPDATE tbl_pengiriman_sampel
    SET file_spp='$filename'
    WHERE no_spl_sipt='$no_spl_sipt'
");

header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=dikirim");
exit;
