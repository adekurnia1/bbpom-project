<?php
session_start();
require_once "../config.php";
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

$id_pengiriman = mysqli_real_escape_string($koneksi, $_POST['id_pengiriman']);
$id_penguji    = $_SESSION['ssId'];

/* CEK DATA VALID */
$cek = mysqli_query($koneksi, "
    SELECT * FROM tbl_pengiriman_sampel
    WHERE id_pengiriman='$id_pengiriman'
      AND id_penguji='$id_penguji'
");

if (mysqli_num_rows($cek) == 0) {
    header("location:inbox-sampel-penguji.php?msg=invalid");
    exit;
}

$pengiriman = mysqli_fetch_assoc($cek);

/* UPDATE STATUS */
mysqli_query($koneksi, "
    UPDATE tbl_pengiriman_sampel
    SET status_pengiriman='diterima',
        status_uji='proses',
        tgl_terima=NOW()
    WHERE id_pengiriman='$id_pengiriman'
");

$q = mysqli_query($koneksi, "
    SELECT 
        s.*, 
        ps.file_spp,
        ps.id_penyelia,
        ps.id_penguji,
        ps.status_pengiriman,

        p.penyelia   AS nama_penyelia,
        p.penguji    AS nama_penguji,
        p.id_penguji,

        sp.no_spu,
        sp.tgl_spk,
        sp.timeline
    FROM tbl_sampel s
    JOIN tbl_pengiriman_sampel ps 
        ON ps.no_spl_sipt = s.no_spl_sipt
    JOIN tbl_petugas p 
        ON p.id_petugas = ps.id_penguji
    JOIN tbl_spu sp 
        ON sp.no_spu = s.no_spu
    WHERE ps.id_pengiriman='$id_pengiriman'
");

$data = mysqli_fetch_assoc($q);

/* GENERATE PDF v2 (SETELAH DITANDATANGANI PENGUJI) */
ob_start();
include "../doc_templates/template_spp.php";
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* OVERWRITE FILE SPP */
$filePath = "../file_spp/" . $data['file_spp'];
file_put_contents($filePath, $dompdf->output());

header("location:inbox-sampel-penguji.php?msg=diterima");
exit;
