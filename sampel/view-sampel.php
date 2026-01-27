<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}

require_once "../config.php";

if (!isset($_GET['no_spl_sipt'])) {
    header("location:list-sampel.php");
    exit;
}

$no_spl_sipt = mysqli_real_escape_string($koneksi, $_GET['no_spl_sipt']);

//Data Sampel
$sampel = mysqli_query($koneksi, "
    SELECT * FROM tbl_sampel 
    WHERE no_spl_sipt = '$no_spl_sipt'
");
$data = mysqli_fetch_assoc($sampel);

if (!$data) {
    header("location:list-sampel.php");
    exit;
}

// Mengecek Status Pengiriman
$cekKirim = mysqli_query($koneksi, "
    SELECT ps.*, u.nama AS nama_penguji
    FROM tbl_pengiriman_sampel ps
    LEFT JOIN tbl_users u ON ps.id_penguji = u.id_user
    WHERE ps.no_spl_sipt = '$no_spl_sipt'
    ORDER BY ps.tgl_kirim DESC
    LIMIT 1
");

$dataKirim = mysqli_fetch_assoc($cekKirim);
$sudahDikirim = $dataKirim ? true : false;

// Parameter Uji
$kategori = $data['kategori'];
$parameter = mysqli_query($koneksi, "
    SELECT * FROM tbl_kategori_parameter
    WHERE kategori = '$kategori'
    ORDER BY parameter_uji
");

// Data Penguji
$penguji = mysqli_query($koneksi, "
    SELECT id_user, nama 
    FROM tbl_users 
    WHERE role = 'penguji'
");

$title = "Detail Sampel";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4">Detail Sampel</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="list-sampel.php">List Sampel</a></li>
                <li class="breadcrumb-item active">Detail Sampel</li>
            </ol>

            <!-- Notifikasi Status-->
            <?php if ($sudahDikirim): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Sampel ini sudah dikirim ke penguji
                    <b><?= $dataKirim['nama_penguji']; ?></b>
                    pada
                    <b><?= date('d-m-Y H:i', strtotime($dataKirim['tgl_kirim'])); ?></b>
                </div>
            <?php endif; ?>

            <!-- Informasi Sampel -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Informasi Sampel</strong>

                    <?php if (!$sudahDikirim): ?>
                        <button type="button"
                            class="btn btn-success float-end"
                            data-bs-toggle="modal"
                            data-bs-target="#modalKirim">
                            <i class="fas fa-paper-plane"></i> Kirim ke Penguji
                        </button>
                    <?php else: ?>
                        <span class="badge bg-success float-end">
                            Sudah dikirim
                        </span>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>No SPL SIPT</th>
                            <td><?= $data['no_spl_sipt']; ?></td>
                        </tr>
                        <tr>
                            <th>No SPU</th>
                            <td><?= $data['no_spu']; ?></td>
                        </tr>
                        <tr>
                            <th>Nama Sampel</th>
                            <td><?= $data['nama_sampel']; ?></td>
                        </tr>
                        <tr>
                            <th>Pabrik</th>
                            <td><?= $data['pabrik']; ?></td>
                        </tr>
                        <tr>
                            <th>Brand</th>
                            <td><?= $data['brand']; ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td><?= $data['kategori']; ?></td>
                        </tr>
                        <tr>
                            <th>Kadaluarsa</th>
                            <td><?= date('d-m-Y', strtotime($data['kadaluarsa'])); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Informasi parameter uji pada sampel -->
            <div class="card mb-4">
                <div class="card-header"><strong>Parameter Uji</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Parameter</th>
                                <th>LOD</th>
                                <th>LOQ</th>
                                <th>Syarat</th>
                                <th>Metode</th>
                                <th>Pustaka</th>
                                <th>Tipe PU</th>
                                <th>Jenis PU</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($p = mysqli_fetch_assoc($parameter)) : ?>
                                <tr>
                                    <td><?= $p['parameter_uji']; ?></td>
                                    <td><?= $p['lod']; ?></td>
                                    <td><?= $p['loq']; ?></td>
                                    <td><?= $p['syarat']; ?></td>
                                    <td><?= $p['metode']; ?></td>
                                    <td><?= $p['pustaka']; ?></td>
                                    <td><?= $p['tipe_pu']; ?></td>
                                    <td><?= $p['jenis_pu']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
    <?php require_once "../template/footer.php"; ?>
</div>

<!-- kirim ke penguji -->
<?php if (!$sudahDikirim): ?>
    <div class="modal fade" id="modalKirim" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="proses-kirim-penguji.php" method="POST">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Kirim Sampel ke Penguji</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="no_spl_sipt" value="<?= $data['no_spl_sipt']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Penyelia</label>
                            <input type="text" class="form-control"
                                value="<?= $_SESSION['ssUser']; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kirim ke Penguji</label>
                            <select name="id_penguji" class="form-select" required>
                                <option value="">-- Pilih Penguji --</option>
                                <?php while ($pg = mysqli_fetch_assoc($penguji)) : ?>
                                    <option value="<?= $pg['id_user']; ?>">
                                        <?= $pg['nama']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" name="kirim" class="btn btn-success">
                            Kirim
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
<?php endif; ?>