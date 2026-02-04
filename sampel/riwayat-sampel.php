<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

$title = "Riwayat Pengujian";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$role = $_SESSION['ssRole'];
$id_user = $_SESSION['ssId'];

$where = "";
if ($role == 'penyelia') {
    $where = "WHERE ps.id_penyelia = '$id_user'";
} elseif ($role == 'penguji') {
    $where = "WHERE ps.id_penguji = '$id_user'";
}

$q = mysqli_query($koneksi, "
    SELECT 
        ps.no_spl_sipt,
        ps.tgl_kirim,
        ps.status_pengiriman,
        ps.file_spp,
        u1.nama AS penyelia,
        u2.nama AS penguji
    FROM tbl_pengiriman_sampel ps
    JOIN tbl_users u1 ON u1.id_user = ps.id_penyelia
    JOIN tbl_users u2 ON u2.id_user = ps.id_penguji
    $where
    ORDER BY ps.tgl_kirim DESC
");

if (!$q) {
    die(mysqli_error($koneksi));
}
?>

<!-- WRAPPER WAJIB -->
<div id="layoutSidenav_content">
    <main class="container-fluid px-4 mt-4">

        <h3 class="mb-3">Riwayat Pengiriman Sampel</h3>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No SPL</th>
                    <th>Tgl Kirim</th>
                    <th>Penyelia</th>
                    <th>Penguji</th>
                    <th>Status</th>
                    <th>Download SPP</th>
                </tr>
            </thead>
            <tbody>

                <?php if (mysqli_num_rows($q) == 0) { ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada riwayat sampel
                        </td>
                    </tr>
                <?php } ?>

                <?php while ($r = mysqli_fetch_assoc($q)) { ?>
                    <tr>
                        <td><?= $r['no_spl_sipt'] ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($r['tgl_kirim'])) ?></td>
                        <td><?= $r['penyelia'] ?></td>
                        <td><?= $r['penguji'] ?></td>

                        <td class="text-center">
                            <?php if ($r['status_sampel'] == 'selesai') { ?>
                                <span class="badge bg-success">Selesai</span>
                            <?php } else { ?>
                                <span class="badge bg-warning">Dikirim</span>
                            <?php } ?>
                        </td>

                        <td class="text-center">
                            <?php if ($r['status_sampel'] == 'selesai') { ?>
                                <a href="../file_spp/<?= $r['file_spp'] ?>"
                                    target="_blank"
                                    class="btn btn-danger btn-sm">
                                    Download
                                </a>
                            <?php } else { ?>
                                <span class="text-muted">Menunggu TTD Penguji</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>

    </main>
    <?php require_once "../template/footer.php"; ?>
</div>