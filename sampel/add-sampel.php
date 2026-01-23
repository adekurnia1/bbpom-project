<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}
require_once "../config.php";
$title = "Tambah Sampel - BBPOM";

require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';
if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-triangle-exclamation"></i> Tambah Sampel gagal, Sampel sudah ada..
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Tambah Sampel Berhasil !
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

// ambil data SPU dan kategori untuk dropdown
$qSpu       = mysqli_query($koneksi, "SELECT no_spu FROM tbl_spu ORDER BY no_spu ASC");
$qKategori  = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM tbl_kategori_parameter ORDER BY kategori ASC");
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Sampel</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="list-sampel.php">List Sampel</a></li>
                <li class="breadcrumb-item active">Tambah Sampel</li>
            </ol>
            <form action="proses-sampel.php" method="POST" enctype="multipart/form-data">
                <?php
                if ($msg !== '') {
                    echo $alert;
                }

                ?>
                <div class="card">
                    <div class="card-header">
                        <span class="h5"><i class="fa-solid fa-square-plus"></i> Tambah Sampel</span>
                        <button class="btn btn-primary float-end" type="submit" name="simpan">Simpan </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">Nomor SPL SPIT</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="no_spl_sipt" name="no_spl_sipt">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>No SPU</label>
                                    <select name="no_spu" class="form-control" required>
                                        <option value="">-- Pilih SPU --</option>
                                        <?php while ($spu = mysqli_fetch_assoc($qSpu)) : ?>
                                            <option value="<?= $spu['no_spu']; ?>">
                                                <?= $spu['no_spu']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">Pabrik</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="pabrik" name="pabrik">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">No Registrasi</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="no_re" name="no_reg">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">No Bet</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="no_bet" name="no_bet">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">Nama Sampel</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="nama_sampel" name="nama_sampel">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">Brand</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="brand" name="brand">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">Komposisi</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="komposisi" name="komposisi">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">Kadaluarsa</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="date" class="form-control" id="kadaluarsa" name="kadaluarsa">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Kategori</label>
                                    <select name="kategori" class="form-control" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php while ($kategori = mysqli_fetch_assoc($qKategori)) : ?>
                                            <option value="<?= $kategori['kategori']; ?>">
                                                <?= $kategori['kategori']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">Wadah</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="wadah" name="wadah">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spl_sipt" class="col-sm-2 col-form-label">Netto</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="netto" name="netto">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php require_once "../template/footer.php"; ?>
</div>