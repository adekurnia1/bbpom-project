<?php
session_start();
require_once "../config.php";
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_POST['terima'])) {
    header("location: ../index.php");
    exit;
}

$id_pengiriman = mysqli_real_escape_string($koneksi, $_POST['id_pengiriman']);
$id_penguji    = $_SESSION['ssId'];

mysqli_begin_transaction($koneksi);

try {

    // ==================== 1. UPDATE STATUS ====================
    mysqli_query($koneksi, "
        UPDATE tbl_pengiriman_sampel
        SET status_pengiriman = 'diterima',
            status_uji = 'proses',
            tgl_terima = NOW()
        WHERE id_pengiriman = '$id_pengiriman'
          AND id_penguji = '$id_penguji'
    ");

    // ==================== 2. AMBIL DATA ====================
    $q = mysqli_query($koneksi, "
        SELECT 
            ps.*, 
            s.*, 
            u1.nama AS nama_penyelia,
            u2.nama AS nama_penguji,
            sp.tgl_spk,
            sp.timeline,
            sp.no_spu
        FROM tbl_pengiriman_sampel ps
        JOIN tbl_sampel s ON s.no_spl_sipt = ps.no_spl_sipt
        JOIN tbl_users u1 ON u1.id_user = ps.id_penyelia
        JOIN tbl_users u2 ON u2.id_user = ps.id_penguji
        JOIN tbl_spu sp ON sp.no_spu = s.no_spu
        WHERE ps.id_pengiriman = '$id_pengiriman'
    ");

    $data = mysqli_fetch_assoc($q);

    // ==================== 3. GENERATE PDF (V2 RESMI) ====================
    ob_start();
    include "../doc_templates/template_spp.php";
    $html = ob_get_clean();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // ==================== 4. SIMPAN FILE BARU ====================
    $filename = "SPP_{$data['no_spl_sipt']}_FINAL.pdf";
    $path = "../file_spp/" . $filename;
    file_put_contents($path, $dompdf->output());

    // ==================== 5. UPDATE PATH ====================
    mysqli_query($koneksi, "
        UPDATE tbl_pengiriman_sampel
        SET file_spp = '$filename'
        WHERE id_pengiriman = '$id_pengiriman'
    ");

    // ==================== 6. COMMIT ====================
    mysqli_commit($koneksi);

    header("location:list-sampel-penguji.php?msg=diterima");
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    echo "Gagal menerima sampel: " . $e->getMessage();
}
