<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

$role = $_SESSION['ssRole'];
$id_user = $_SESSION['ssId'];

/*
  Penyelia: lihat yang dia kirim
  Penguji : lihat yang dia terima
*/
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
?>

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
<?php while($r = mysqli_fetch_assoc($q)) { ?>
<tr>
    <td><?= $r['no_spl_sipt'] ?></td>
    <td><?= date('d-m-Y H:i', strtotime($r['tgl_kirim'])) ?></td>
    <td><?= $r['penyelia'] ?></td>
    <td><?= $r['penguji'] ?></td>

    <td>
        <?php if($r['status_pengiriman']=='diterima'){ ?>
            <span class="badge bg-success">Diterima</span>
        <?php } else { ?>
            <span class="badge bg-warning">Dikirim</span>
        <?php } ?>
    </td>

    <td class="text-center">
        <?php if($r['status_pengiriman']=='diterima'){ ?>
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
