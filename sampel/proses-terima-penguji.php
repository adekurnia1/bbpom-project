<?php
session_start();
require_once "../config.php";
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;

$id_pengiriman = $_POST['id_pengiriman'];
$id_penguji    = $_SESSION['ssId'];

/* UPDATE STATUS */
mysqli_query($koneksi, "
    UPDATE tbl_pengiriman_sampel
    SET status_pengiriman='diterima'
    WHERE id_pengiriman='$id_pengiriman'
      AND id_penguji='$id_penguji'
");

/* AMBIL DATA */
$q = mysqli_query($koneksi, "
    SELECT 
        ps.*, s.*,
        u1.nama AS nama_penyelia,
        u2.nama AS nama_penguji,
        sp.no_spu,
        sp.tgl_spk,
        sp.timeline
    FROM tbl_pengiriman_sampel ps
    JOIN tbl_sampel s ON s.no_spl_sipt = ps.no_spl_sipt
    JOIN tbl_users u1 ON u1.id_user = ps.id_penyelia
    JOIN tbl_users u2 ON u2.id_user = ps.id_penguji
    JOIN tbl_spu sp ON sp.no_spu = s.no_spu
    WHERE ps.id_pengiriman='$id_pengiriman'
");

$data = mysqli_fetch_assoc($q);

/* GENERATE PDF BARU (v2) */
ob_start();
include "../doc_templates/template_spp.php";
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* OVERWRITE FILE */
file_put_contents("../file_spp/".$data['file_spp'], $dompdf->output());

header("location:inbox-sampel-penguji.php?msg=diterima");
exit;
