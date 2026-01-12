<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Tambah Parameter Uji";

require_once "../template/header.php";
require_once "../template/sidebar.php";
require_once "../template/navbar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';
if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-triangle-exclamation"></i> Tambah Parameter Uji gagal, Parameter sudah ada..
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Tambah Parameter Uji Berhasil !
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Parameter Uji</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="list-kategori.php">List Parameter Uji</a></li>
                <li class="breadcrumb-item active">Tambah Parameter Uji</li>
            </ol>
            <form action="proses-kategori.php" method="POST">
                <div class="card">
                    <div class="card-header">
                        <span class="h5">
                            <i class="fa-solid fa-square-plus"></i> Tambah Parameter Uji
                        </span>
                        <button class="btn btn-primary float-end" type="submit" name="simpan">
                            Simpan
                        </button>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label>Kategori</label>
                            <input type="text" name="kategori" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Parameter Uji</label>
                            <input type="text" name="parameter_uji" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>LOD</label>
                                <input type="text" name="lod" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>LOQ</label>
                                <input type="text" name="loq" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Syarat</label>
                            <input type="text" name="syarat" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Metode</label>
                            <input type="text" name="metode" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Pustaka</label>
                            <input type="text" name="pustaka" class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Tipe PU</label>
                                <input type="text" name="tipe_pu" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Jenis PU</label>
                                <input type="text" name="jenis_pu" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>PU Surveilance</label>
                            <input type="text" name="pusurveilance" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"></textarea>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php require_once "../template/footer.php"; ?>
</div>