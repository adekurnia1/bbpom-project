<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"]) || $_SESSION["ssRole"] !== "penyelia") {
    header("location: ../auth/login.php");
    exit;
}

$title = "Verifikasi Hasil Uji";
require_once "../template/header.php";
require_once "../template/sidebar.php";
require_once "../template/navbar.php";


// Query dasar daftar sampel dari tbl_hasil_uji
$query = mysqli_query($koneksi, "
    SELECT 
        h.no_spl_sipt,
        s.no_spu,
        s.nama_sampel,
        s.kategori,
        COUNT(h.id_kategori_parameter) AS total_param,
        SUM(CASE WHEN h.status_verifikasi IN ('disetujui_penyelia','disetujui_ketua_tim','ditolak') THEN 1 ELSE 0 END) AS verified_param
    FROM tbl_hasil_uji h
    JOIN tbl_sampel s ON s.no_spl_sipt = h.no_spl_sipt
    WHERE h.status_hasil = 'selesai'
    GROUP BY h.no_spl_sipt
    ORDER BY h.no_spl_sipt
");

if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="card p-3">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No SPL</th>
                    <th>No SPU</th>
                    <th>Nama Sampel</th>
                    <th>Kategori</th>
                    <th>Status Verifikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                <?php if (mysqli_num_rows($query) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                        <?php
                        $total = $row['total_param'];
                        $verified = $row['verified_param'];

                        if ($verified == 0) {
                            $status = '<span class="badge bg-danger">Belum</span>';
                        } elseif ($verified < $total) {
                            $status = '<span class="badge bg-warning text-dark">Sebagian</span>';
                        } else {
                            $status = '<span class="badge bg-success">Semua</span>';
                        }

                        // Ambil parameter per sampel untuk diverifikasi
                        $qParam = mysqli_query($koneksi, "
                                    SELECT 
                                        h.id_hasil_uji,
                                        h.id_kategori_parameter,
                                        p.parameter_uji,
                                        h.hasil_uji,
                                        h.status_verifikasi
                                    FROM tbl_hasil_uji h
                                    JOIN tbl_kategori_parameter p 
                                        ON p.id_kategori_parameter = h.id_kategori_parameter
                                    WHERE h.no_spl_sipt = '{$row['no_spl_sipt']}'
                                    ORDER BY p.parameter_uji
                                ");

                        ?>

                        <tr>
                            <td><?= $row['no_spl_sipt']; ?></td>
                            <td><?= $row['no_spu']; ?></td>
                            <td><?= $row['nama_sampel']; ?></td>
                            <td><?= $row['kategori']; ?></td>
                            <td class="text-center"><?= $status; ?></td>

                            <td>
                                <ul class="mb-0">
                                    <?php while ($p = mysqli_fetch_assoc($qParam)) : ?>
                                        <li class="mb-2">
                                            <strong><?= $p['parameter_uji']; ?></strong> : <?= $p['hasil_uji']; ?>
                                            <span class="badge <?= $p['status_verifikasi'] == 'belum' ? 'bg-secondary' : ($p['status_verifikasi'] == 'ditolak' ? 'bg-danger' : 'bg-success'); ?> ms-1">
                                                <?= $p['status_verifikasi']; ?>
                                            </span>

                                            <?php if ($p['status_verifikasi'] == 'belum') : ?>
                                                <form action="proses-verifikasi.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="id_hasil_uji" value="<?= $p['id_hasil_uji']; ?>">
                                                    <input type="hidden" name="id_kategori_parameter" value="<?= $p['id_kategori_parameter']; ?>">
                                                    <input type="hidden" name="no_spl_sipt" value="<?= $row['no_spl_sipt']; ?>">

                                                    <button type="submit" name="aksi" value="disetujui_penyelia" class="btn btn-sm btn-success ms-2">Setujui</button>
                                                    <button type="submit" name="aksi" value="ditolak" class="btn btn-sm btn-danger">Tolak</button>
                                                    <input type="text" name="catatan" class="form-control form-control-sm mt-1 ms-2 d-inline-block"
                                                        placeholder="Catatan jika ditolak" style="width:200px;">
                                                </form>
                                            <?php endif; ?>

                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada sampel yang menunggu verifikasi</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
    <?php require_once "../template/footer.php"; ?>
</div>