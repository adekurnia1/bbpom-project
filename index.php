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

$libur2026 = [
    ["2026-01-01", "Kamis", "Tahun Baru 2026 Masehi", "Libur Nasional"],
    ["2026-01-16", "Jumat", "Isra Mikraj Nabi Muhammad S.A.W.", "Libur Nasional"],
    ["2026-02-17", "Selasa", "Tahun Baru Imlek 2577 Kongzili", "Libur Nasional"],
    ["2026-03-19", "Kamis", "Hari Suci Nyepi (Tahun Baru Saka 1948)", "Libur Nasional"],
    ["2026-03-21", "Sabtu", "Idul Fitri 1447 Hijriah", "Libur Nasional"],
    ["2026-03-22", "Minggu", "Idul Fitri 1447 Hijriah", "Libur Nasional"],
    ["2026-04-03", "Jumat", "Wafat Yesus Kristus", "Libur Nasional"],
    ["2026-04-05", "Minggu", "Kebangkitan Yesus Kristus (Paskah)", "Libur Nasional"],
    ["2026-05-01", "Jumat", "Hari Buruh Internasional", "Libur Nasional"],
    ["2026-05-14", "Kamis", "Kenaikan Yesus Kristus", "Libur Nasional"],
    ["2026-05-27", "Rabu", "Idul Adha 1447 Hijriah", "Libur Nasional"],
    ["2026-05-31", "Minggu", "Hari Raya Waisak 2570 BE", "Libur Nasional"],
    ["2026-06-01", "Senin", "Hari Lahir Pancasila", "Libur Nasional"],
    ["2026-06-16", "Selasa", "1 Muharam Tahun Baru Islam 1448 Hijriah", "Libur Nasional"],
    ["2026-08-17", "Senin", "Proklamasi Kemerdekaan", "Libur Nasional"],
    ["2026-08-25", "Selasa", "Maulid Nabi Muhammad S.A.W.", "Libur Nasional"],
    ["2026-12-25", "Jumat", "Kelahiran Yesus Kristus", "Libur Nasional"],

    ["2026-02-16", "Senin", "Tahun Baru Imlek 2577 Kongzili", "Cuti Bersama"],
    ["2026-03-18", "Rabu", "Hari Suci Nyepi (Tahun Baru Saka 1948)", "Cuti Bersama"],
    ["2026-03-20", "Jumat", "Idul Fitri 1447 Hijriah", "Cuti Bersama"],
    ["2026-03-23", "Senin", "Idul Fitri 1447 Hijriah", "Cuti Bersama"],
    ["2026-03-24", "Selasa", "Idul Fitri 1447 Hijriah", "Cuti Bersama"],
    ["2026-05-15", "Jumat", "Kenaikan Yesus Kristus", "Cuti Bersama"],
    ["2026-05-28", "Kamis", "Idul Adha 1447 Hijriah", "Cuti Bersama"],
    ["2026-12-24", "Kamis", "Kelahiran Yesus Kristus", "Cuti Bersama"]
];
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
                                    <?php $no = 1;
                                    foreach ($libur2026 as $libur) : ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= date('d-m-Y', strtotime($libur[0])); ?></td>
                                            <td><?= $libur[1]; ?></td>
                                            <td><?= $libur[2]; ?></td>
                                            <td class="text-center">
                                                <span class="badge <?= $libur[3] == 'Libur Nasional' ? 'bg-danger' : 'bg-warning'; ?>">
                                                    <?= $libur[3]; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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