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

$hariLibur = [

    /* =====================
       LIBUR NASIONAL & CUTI BERSAMA 2025
       ===================== */
    "2025-01-01", // Tahun Baru
    "2025-01-27", // Isra Mi'raj
    "2025-01-28", // Imlek
    "2025-01-29", // Imlek
    "2025-03-28", // Nyepi
    "2025-03-29", // Nyepi
    "2025-03-31", // Hari Raya Idulfitri
    "2025-04-01", // Hari Raya Idulfitri
    "2025-04-02", // Hari Raya Idulfitri
    "2025-04-03", // Hari Raya Idulfitri
    "2025-04-04", // Hari Raya Idulfitri
    "2025-04-07", // Hari Raya Idulfitri
    "2025-04-18", // Wafat Isa Al Masih
    "2025-04-20", // Paskah
    "2025-05-01", // Hari Buruh
    "2025-05-12", // Waisak
    "2025-05-13", // Waisak
    "2025-05-29", // Kenaikan Isa Al Masih
    "2025-05-30", // Kenaikan Isa Al Masih
    "2025-06-01", // Hari Lahir Pancasila
    "2025-06-06", // Idul Adha
    "2025-06-09", // Idul Adha
    "2025-06-27", // Tahun Baru Hijriah
    "2025-08-17", // Hari Kemerdekaan
    "2025-08-18", // Hari Kemerdekaan
    "2025-09-05", // Maulid Nabi
    "2025-12-25", // Natal
    "2025-12-26", // Natal

    /* =====================
       LIBUR NASIONAL & CUTI BERSAMA 2026
       ===================== */
    "2026-01-01","2026-01-16","2026-02-17","2026-03-19",
    "2026-03-21","2026-03-22","2026-04-03","2026-04-05",
    "2026-05-01","2026-05-14","2026-05-27","2026-05-31",
    "2026-06-01","2026-06-16","2026-08-17","2026-08-25",
    "2026-12-25",

    // CUTI BERSAMA 2026
    "2026-02-16","2026-03-18","2026-03-20",
    "2026-03-23","2026-03-24",
    "2026-05-15","2026-05-28","2026-12-24"
];


function hitungTanggalSelesai($tglMulai, $jumlahHari, $hariLibur) {
    $tanggal = new DateTime($tglMulai);
    $hariKerja = 0;

    while ($hariKerja < $jumlahHari) {

        $hari = $tanggal->format('N'); // 6=Sabtu, 7=Minggu
        $tglString = $tanggal->format('Y-m-d');

        // hitung hanya jika hari kerja
        if ($hari < 6 && !in_array($tglString, $hariLibur)) {
            $hariKerja++;
        }

        // jika belum mencapai target, maju ke hari berikutnya
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
                    <div class="card-header">
                        <span class="h5"><i class="fa-solid fa-square-plus"></i> List SPU</span>
                        <button class="btn btn-primary float-end" type="submit" name="simpan"> Tambah SPU </button>
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