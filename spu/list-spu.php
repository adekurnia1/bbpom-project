<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}
require_once "../config.php";
$title = "List SPU - BBPOM";

require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$hariLibur = [];
$qLibur = mysqli_query($koneksi, "SELECT tanggal FROM tbl_hari_libur");
while ($r = mysqli_fetch_assoc($qLibur)) {
    $hariLibur[] = $r['tanggal'];
}

function hitungTanggalSelesai($tglMulai, $jumlahHari, $hariLibur)
{
    if (empty($tglMulai) || $jumlahHari <= 0) {
        return '-';
    }

    $tanggal = new DateTime($tglMulai);
    $jumlahHari = (int)$jumlahHari;
    $hariKerja = 0;

    while ($hariKerja < $jumlahHari) {

        $hari = $tanggal->format('N'); // 1=Senin ... 7=Minggu
        $tglString = $tanggal->format('Y-m-d');

        // jika hari kerja & bukan hari libur
        if ($hari <= 5 && !in_array($tglString, $hariLibur)) {
            $hariKerja++;
        }

        // maju 1 hari
        if ($hariKerja < $jumlahHari) {
            $tanggal->modify('+1 day');
        }
    }

    return $tanggal->format('d-m-Y');
}

$query = mysqli_query($koneksi, "SELECT * FROM tbl_spu ORDER BY no_spu");
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">SPU</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">List SPU</li>
            </ol>
            <form action="add-spu.php" method="POST" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-right">
                    <span class="h5 mb-0">
                        <i class="fa-solid fa-square-plus"></i> List SPU
                    </span>
                        <div class="d-flex gap-2 mb-0">
                        <a href="add-spu.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Manual
                        </a>

                        <a href="import-spu-form.php" class="btn btn-primary">
                            <i class="fas fa-file-import"></i> Import Batch
                        </a>
                    </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped nowrap-table" id="datatablesSimple">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>No SPU</th>
                                            <th>Tipe Sampel</th>
                                            <th>Asal Sampling</th>
                                            <th>Bulan Masuk</th>
                                            <th>Tgl Masuk Lab</th>
                                            <th>Tgl SPK</th>
                                            <th>Jumlah Sampel</th>
                                            <th>Timeline</th>
                                            <th>Timeline (Tgl)</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                                            <tr>
                                                <td><?= $row['no_spu']; ?></td>
                                                <td><?= $row['tipe_sampel']; ?></td>
                                                <td><?= $row['asal_sampling']; ?></td>
                                                <td class="text-center"><?= $row['bulan_masuk']; ?></td>
                                                <td class="text-center">
                                                    <?= date('d-m-Y', strtotime($row['tgl_masuk_lab'])); ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= date('d-m-Y', strtotime($row['tgl_spk'])); ?>
                                                </td>
                                                <td class="text-center"><?= $row['jumlah_sampel']; ?></td>
                                                <td class="text-center"><?= $row['timeline']; ?> hari</td>
                                                <td class="text-center">
                                                    <?= hitungTanggalSelesai(
                                                        $row['tgl_spk'],
                                                        $row['timeline'],
                                                        $hariLibur
                                                    ); ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="edit.php?no_spu=<?= $row['no_spu']; ?>" class="text-warning me-2">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="delete.php?no_spu=<?= $row['no_spu']; ?>"
                                                        class="text-danger"
                                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php require_once "../template/footer.php"; ?>
</div>