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
$title = "Input Hasil Uji";

require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";


$id_penguji = $_SESSION['ssId']; 

$no_spl = $_GET['no_spl_sipt'] ?? ''; 

if ($no_spl === '') {
    die("Sampel tidak valid!");
}

$qSampel = mysqli_query($koneksi, "
    SELECT no_spl_sipt, no_spu, nama_sampel, kategori
    FROM tbl_sampel
    WHERE no_spl_sipt = '$no_spl'
");

$sampel = mysqli_fetch_assoc($qSampel);
if (!$sampel) {
    die("Sampel tidak ditemukan!");
}

$qParam = mysqli_query($koneksi, "
    SELECT id_kategori_parameter, parameter_uji
    FROM tbl_kategori_parameter
    WHERE kategori = '{$sampel['kategori']}'
    ORDER BY parameter_uji
");

// Simpan hasil uji untuk parameter yang dipilih
if (isset($_POST['btnSimpan'])) {
    $id_param = $_POST['parameter'] ?? '';
    $hasil = mysqli_real_escape_string($koneksi, $_POST['hasil']);
    $tgl_lcp = mysqli_real_escape_string($koneksi, $_POST['tgl_lcp']);
    $bentuk = mysqli_real_escape_string($koneksi, $_POST['bentuk']);
    $konsistensi = mysqli_real_escape_string($koneksi, $_POST['konsistensi']);
    $warna = mysqli_real_escape_string($koneksi, $_POST['warna']);
    $bau = mysqli_real_escape_string($koneksi, $_POST['bau']);

    if ($id_param === '') {
        die("Parameter tidak valid!");
    }

    // Cek apakah sudah ada hasil uji parameter ini
    $cek = mysqli_query($koneksi, "
        SELECT id_hasil_uji FROM tbl_hasil_uji
        WHERE no_spl_sipt = '$no_spl'
          AND id_kategori_parameter = '$id_param'
          AND id_penguji = '$id_penguji'
    ");
    $exist = mysqli_fetch_assoc($cek);

    if ($exist) {
        mysqli_query($koneksi, "
            UPDATE tbl_hasil_uji
            SET hasil_uji = '$hasil',
                tgl_lcp = '$tgl_lcp',
                bentuk = '$bentuk',
                konsistensi = '$konsistensi',
                warna = '$warna',
                bau = '$bau',
                status_hasil = 'selesai'
            WHERE id_hasil_uji = '{$exist['id_hasil_uji']}'
        ");
    } else {
        mysqli_query($koneksi, "
            INSERT INTO tbl_hasil_uji
            (no_spl_sipt, id_kategori_parameter, id_penguji, hasil_uji, tgl_lcp, bentuk, konsistensi, warna, bau, status_hasil)
            VALUES
            ('$no_spl', '$id_param', '$id_penguji', '$hasil', '$tgl_lcp', '$bentuk', '$konsistensi', '$warna', '$bau', 'selesai')
        ");
    }

    echo "<script>alert('Hasil uji berhasil disimpan!');window.location='list-sampel-uji.php';</script>";
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h2 class="mt-4 mb-3">Input Hasil Uji</h2>

            <div class="card p-3">
                <div class="mb-2"><strong>No SPL:</strong> <?= $sampel['no_spl_sipt']; ?></div>
                <div class="mb-2"><strong>No SPU:</strong> <?= $sampel['no_spu']; ?></div>
                <div class="mb-3"><strong>Nama Sampel:</strong> <?= $sampel['nama_sampel']; ?></div>

                <form action="proses-input-hasil.php" method="POST">

                    <input type="hidden" name="no_spl_sipt" value="<?= $sampel['no_spl_sipt']; ?>">
                    <input type="hidden" name="id_penguji" value="<?= $id_penguji; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Parameter Uji</label>
                        <select name="id_kategori_parameter" class="form-select" required>
                            <option value="">-- Pilih Parameter --</option>
                            <?php while ($p = mysqli_fetch_assoc($qParam)) : ?>
                                <option value="<?= $p['id_kategori_parameter']; ?>">
                                    <?= $p['parameter_uji']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <hr>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Hasil Uji</label>
                            <input type="text" name="hasil_uji" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal LCP</label>
                            <input type="date" name="tgl_lcp" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Bentuk</label>
                            <input type="text" name="bentuk" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Konsistensi</label>
                            <input type="text" name="konsistensi" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Warna</label>
                            <input type="text" name="warna" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Bau</label>
                            <input type="text" name="bau" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Catatan Penguji</label>
                            <input type="text" name="catatan_penguji" class="form-control">
                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Simpan Hasil
                        </button>
                        <a href="list-sampel-uji.php" class="btn btn-secondary">Kembali</a>
                    </div>

                </form>
            </div>

        </div>
    </main>

    <?php require_once "../template/footer.php"; ?>
</div>