<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}
require_once "config.php";
$title = "BBPOM BDG - MAGANG PROJECT";
require_once "template/header.php";
require_once "template/navbar.php";
require_once "template/sidebar.php";

$qLibur = mysqli_query($koneksi, "
    SELECT * 
    FROM tbl_hari_libur 
    ORDER BY tanggal ASC
");
?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="row">

                <!--TABEL REKAP SAMPEL BULAN MASUK-->
                <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Rekap Bulan Masuk
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered text-center">
                                <thead style="background-color:#7f91b2; color:white;">
                                    <tr>
                                        <th><em>Bulan Masuk</em></th>
                                        <th>Balai Bandung</th>
                                        <th>Balai Bogor</th>
                                        <th>Balai Tasik</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_bandung = 0;
                                    $total_bogor   = 0;
                                    $total_tasik   = 0;

                                    $q = mysqli_query($koneksi, "
                                            SELECT 
                                                bulan_masuk,
                                                SUM(CASE WHEN asal_sampling = 'Balai Bandung' THEN jumlah_sampel ELSE 0 END) AS bandung,
                                                SUM(CASE WHEN asal_sampling = 'Balai Bogor' THEN jumlah_sampel ELSE 0 END) AS bogor,
                                                SUM(CASE WHEN asal_sampling = 'Balai Tasik' THEN jumlah_sampel ELSE 0 END) AS tasik
                                            FROM tbl_spu
                                            GROUP BY bulan_masuk
                                            ORDER BY bulan_masuk;
                                        ");

                                    while ($row = mysqli_fetch_assoc($q)) {
                                        $total_bandung += $row['bandung'];
                                        $total_bogor   += $row['bogor'];
                                        $total_tasik   += $row['tasik'];
                                    ?>
                                        <tr>
                                            <td><?= $row['bulan_masuk']; ?></td>
                                            <td><?= $row['bandung']; ?></td>
                                            <td><?= $row['bogor']; ?></td>
                                            <td><?= $row['tasik']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                                <tfoot class="fw-bold">
                                    <tr>
                                        <td>Grand Total</td>
                                        <td><?= $total_bandung; ?></td>
                                        <td><?= $total_bogor; ?></td>
                                        <td><?= $total_tasik; ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TABEL PENGUJI -->
                <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-users me-1"></i>
                            Data Penguji
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="table-warning text-center">
                                    <tr>
                                        <th>Penguji</th>
                                        <th>ID</th>
                                        <th>Penyelia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q = mysqli_query($koneksi, "SELECT penguji, id_penguji, penyelia FROM tbl_petugas ORDER BY id_petugas ASC");
                                    while ($row = mysqli_fetch_assoc($q)) {
                                    ?>
                                        <tr>
                                            <td><?= $row['penguji']; ?></td>
                                            <td class="text-center"><?= $row['id_penguji']; ?></td>
                                            <td><?= $row['penyelia']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Hari Libur Nasional & Cuti Bersama 2026
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Hari</th>
                                        <th>Keterangan</th>
                                        <th>Jenis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($qLibur)) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                            <td><?= $row['hari']; ?></td>
                                            <td><?= $row['keterangan']; ?></td>
                                            <td class="text-center">
                                                <span class="badge <?= $row['jenis'] == 'Libur Nasional' ? 'bg-danger' : 'bg-warning'; ?>">
                                                    <?= $row['jenis']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require_once "template/footer.php"; ?>
</div>