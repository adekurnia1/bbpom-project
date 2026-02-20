<?php
session_start();
require_once "../config.php";
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_POST['no_spl_sipt'])) {
    header("location: ../index.php");
    exit;
}

$no_spl_sipt = mysqli_real_escape_string($koneksi, $_POST['no_spl_sipt']);
$id_penguji  = mysqli_real_escape_string($koneksi, $_POST['id_penguji']);
$id_penyelia = $_SESSION['ssId'];

// cek duplikat
$cek = mysqli_query($koneksi, "
    SELECT 1 FROM tbl_pengiriman_sampel 
    WHERE no_spl_sipt = '$no_spl_sipt'
");

if (mysqli_num_rows($cek) > 0) {
    header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=already");
    exit;
}
mysqli_begin_transaction($koneksi);

try {
    mysqli_query($koneksi, "
        INSERT INTO tbl_pengiriman_sampel
        (no_spl_sipt, id_penyelia, id_penguji, status_pengiriman, status_uji, tgl_kirim)
        VALUES
        ('$no_spl_sipt', '$id_penyelia', '$id_penguji', 'dikirim', 'menunggu', NOW())
    ");

    $q = mysqli_query($koneksi, "
    SELECT 
        s.no_spl_sipt,
        s.brand,
        s.nama_sampel,
        s.komposisi,
        s.no_bet,
        s.kategori,
        s.no_spu,

        u1.nama AS nama_penyelia,
        u2.nama AS nama_penguji,

        pt.id_penguji AS kode_penguji, 

        sp.tgl_spk,
        sp.timeline,
        sp.asal_sampling,

        ps.status_pengiriman

    FROM tbl_sampel s
    JOIN tbl_pengiriman_sampel ps 
        ON ps.no_spl_sipt = s.no_spl_sipt
    JOIN tbl_users u1 
        ON u1.id_user = ps.id_penyelia
    JOIN tbl_users u2 
        ON u2.id_user = ps.id_penguji
    LEFT JOIN tbl_petugas pt
        ON pt.penguji = u2.nama
    JOIN tbl_spu sp 
        ON sp.no_spu = s.no_spu
    WHERE s.no_spl_sipt = '$no_spl_sipt'
    LIMIT 1
");

    $data = mysqli_fetch_assoc($q);

    if (!$data) {
        throw new Exception("Data sampel tidak ditemukan");
    }

    /* 3. GENERATE PDF (SPP v1) */
    ob_start();
    include "../doc_templates/template_spp.php";
    $html = ob_get_clean();

    $options = new Options();
    $options -> set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    /* 4. SIMPAN FILE */
    $filename = "SPP_{$no_spl_sipt}.pdf";
    $path = "../file_spp/" . $filename;
    file_put_contents($path, $dompdf->output());

    /* 5. SIMPAN PATH FILE */
    mysqli_query($koneksi, "
        UPDATE tbl_pengiriman_sampel
        SET file_spp = '$filename'
        WHERE no_spl_sipt = '$no_spl_sipt'
    ");

    mysqli_commit($koneksi);

    header("location:view-sampel.php?no_spl_sipt=$no_spl_sipt&msg=dikirim");
    exit;
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    die("Gagal kirim sampel: " . $e->getMessage());
}
