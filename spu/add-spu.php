<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}
require_once "../config.php";
$title = "Tambah SPU - BBPOM";

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
  <i class="fa-solid fa-triangle-exclamation"></i> Tambah SPU gagal, SPU sudah ada..
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Tambah SPU Berhasil !
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">SPU</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="list-spu.php">List SPU</a></li>
                <li class="breadcrumb-item active">Tambah SPU</li>
            </ol>
            <form action="proses-spu.php" method="POST" enctype="multipart/form-data">
                <?php
                if ($msg !== '') {
                    echo $alert;
                }

                ?>
                <div class="card">
                    <div class="card-header">
                        <span class="h5"><i class="fa-solid fa-square-plus"></i>Tambah SPU</span>
                        <button class="btn btn-primary float-end" type="submit" name="simpan">Simpan </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div class="mb-3 row">
                                    <label for="no_spu" class="col-sm-2 col-form-label">Nomor SPU</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="no_spu" name="no_spu">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spu" class="col-sm-2 col-form-label">Tipe Sampel</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="tipe_sampel" name="tipe_sampel">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spu" class="col-sm-2 col-form-label">Asal Sampling</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <select name="asal_sampling" id="asal_sampling" class="">
                                            <option value="" selected>-- Pilih Asal Sampling --</option>
                                            <option value="Balai Bandung">Balai Bandung</option>
                                            <option value="Balai Bogor">Balai Bogor</option>
                                            <option value="Balai Tasik">Balai Tasik</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spu" class="col-sm-2 col-form-label">Bulan Masuk</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="bulan_masuk" name="bulan_masuk">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="tgl_masuk_lab" class="col-sm-2 col-form-label">Tanggal Masuk Lab</label>
                                    <label class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="date" class="form-control" id="tgl_masuk_lab" name="tgl_masuk_lab" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="tgl_spk" class="col-sm-2 col-form-label">Tanggal SPK</label>
                                    <label class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="date" class="form-control" id="tgl_spk" name="tgl_spk" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spu" class="col-sm-2 col-form-label">Jumlah Sampel</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="jumlah_sampel" name="jumlah_sampel">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="no_spu" class="col-sm-2 col-form-label">Timeline</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control" id="timeline" name="timeline">
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