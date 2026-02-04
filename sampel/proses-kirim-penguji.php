<?php
session_start();
require_once "../config.php";
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}

$cek = mysqli_query($koneksi, "
    SELECT 1 FROM tbl_pengiriman_sampel 
    WHERE no_spl_sipt = '$no_spl_sipt'
");

if (mysqli_num_rows($cek) > 0) {
    header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=already");
    exit;
}

if (isset($_POST['kirim'])) {

    $no_spl_sipt = mysqli_real_escape_string($koneksi, $_POST['no_spl_sipt']); 
    $id_penguji  = mysqli_real_escape_string($koneksi, $_POST['id_penguji']);
    $id_penyelia = $_SESSION['ssId'];

    // 1. SIMPAN PENGIRIMAN
    mysqli_query($koneksi, "
        INSERT INTO tbl_pengiriman_sampel
        (no_spl_sipt, status_sampel, id_penyelia, id_penguji, tgl_kirim)
        VALUES
        ('$no_spl_sipt', 'dikirim', '$id_penyelia', '$id_penguji', NOW())
    ");

    // 2. AMBIL DATA UNTUK PDF
    $q = mysqli_query($koneksi, "
        SELECT 
            s.*, 
            u1.nama AS nama_penyelia,
            u2.nama AS nama_penguji,
            sp.tgl_spk,
            sp.timeline,
            sp.no_spu
        FROM tbl_sampel s
        JOIN tbl_pengiriman_sampel ps ON ps.no_spl_sipt = s.no_spl_sipt
        JOIN tbl_users u1 ON u1.id_user = ps.id_penyelia
        JOIN tbl_users u2 ON u2.id_user = ps.id_penguji
        JOIN tbl_spu sp ON sp.no_spu = s.no_spu
        WHERE s.no_spl_sipt = '$no_spl_sipt'
    ");

    $data = mysqli_fetch_assoc($q);

    // 3. GENERATE HTML DARI TEMPLATE
    ob_start();
    include "../doc_templates/template_spp.php";
    $html = ob_get_clean();

    // 4. GENERATE PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // 5. SIMPAN FILE
    $filename = "SPP_{$no_spl_sipt}.pdf";
    $path = "../file_spp/" . $filename;
    file_put_contents($path, $dompdf->output());

    // 6. SIMPAN PATH PDF
    mysqli_query($koneksi, "
        UPDATE tbl_pengiriman_sampel 
        SET file_spp = '$filename'
        WHERE no_spl_sipt = '$no_spl_sipt'
    ");

    // 7. REDIRECT
    header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=dikirim");
    exit;
}
