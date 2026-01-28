<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

if ($_SESSION['ssRole'] != 'penguji') {
    header("location: ../index.php");
    exit;
}

require_once "../config.php";

$id_penguji = $_SESSION['ssId'];

$title = "List Sampel Uji";

require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// Query utama (fixed)
$query = mysqli_query($koneksi, "
    SELECT 
        s.no_spl_sipt,
        s.no_spu,
        s.nama_sampel,
        s.kategori
    FROM tbl_pengiriman_sampel p
    JOIN tbl_sampel s ON s.no_spl_sipt = p.no_spl_sipt
    WHERE p.id_penguji = '$id_penguji'
      AND p.status_sampel = 'dikirim'
    ORDER BY s.no_spl_sipt
");
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 mt-4">
            <div class="table-responsive" style="overflow-x:auto;">
                <?php if (!$query) die(mysqli_error($koneksi)); ?>
                <table class="table table-bordered table-striped align-middle" style="white-space:nowrap; table-layout:auto;">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No SPL</th>
                            <th>No SPU</th>
                            <th>Nama Sampel</th>
                            <th>Kategori</th>
                            <th>Parameter Uji</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <?php
                            $qTotal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_kategori_parameter WHERE kategori = '{$row['kategori']}'");
                            $total = mysqli_fetch_assoc($qTotal)['total'];

                            $qDone = mysqli_query($koneksi, "SELECT COUNT(*) AS done FROM tbl_hasil_uji WHERE no_spl_sipt = '{$row['no_spl_sipt']}' AND id_penguji = '$id_penguji' AND status_hasil = 'selesai'");
                            $done = mysqli_fetch_assoc($qDone)['done'];

                            $qParam = mysqli_query($koneksi, "SELECT id_kategori_parameter, parameter_uji FROM tbl_kategori_parameter WHERE kategori = '{$row['kategori']}' ORDER BY parameter_uji");
                            ?>

                            <tr>
                                <td><?= $row['no_spl_sipt']; ?></td>
                                <td><?= $row['no_spu']; ?></td>
                                <td><?= $row['nama_sampel']; ?></td>
                                <td><?= $row['kategori']; ?></td>

                                <td>
                                    <ul class="mb-0">
                                        <?php while ($p = mysqli_fetch_assoc($qParam)) : ?>
                                            <?php
                                            $cek = mysqli_query($koneksi, "SELECT id_hasil_uji FROM tbl_hasil_uji WHERE no_spl_sipt = '{$row['no_spl_sipt']}' AND id_kategori_parameter = '{$p['id_kategori_parameter']}' AND id_penguji = '$id_penguji'");
                                            $isi = mysqli_fetch_assoc($cek);
                                            ?>
                                            <li>
                                                <?= $p['parameter_uji']; ?>
                                                <?= $isi ? '<span class="badge bg-success ms-1">✔</span>' : '<span class="badge bg-warning text-dark ms-1">⏳</span>'; ?>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </td>

                                <td class="text-center">
                                    <?= $done == 0 ? '<span class="badge bg-danger">Belum Input</span>' : ($done < $total ? '<span class="badge bg-warning text-dark">Sebagian</span>' : '<span class="badge bg-success">Sudah Input</span>'); ?>
                                </td>

                                <td class="text-center">
                                    <a href="input-hasil-uji.php?no_spl_sipt=<?= $row['no_spl_sipt']; ?>" class="btn btn-sm btn-primary">
                                        Input Hasil
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>