<?php
session_start();
require_once "../config.php";
$title = "Import SPU";

require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Import SPU</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="list-spu.php">List SPU</a></li>
                <li class="breadcrumb-item active">Import</li>
            </ol>

            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            Upload File CSV
                        </div>

                        <div class="card-body">

                            <!-- INSTRUKSI -->
                            <div class="alert alert-info">
                                <strong>Ketentuan File:</strong>
                                <ul class="mb-0">
                                    <li>Format file harus <b>.csv</b></li>
                                    <li>Pemisah kolom menggunakan <b>titik koma (;)</b></li>
                                    <li>Baris pertama harus berisi nama kolom</li>
                                    <li>Urutan kolom:
                                        <br>
                                        <small>
                                            kategori | parameter_uji | lod | loq | syarat | metode | pustaka | tipe_pu | jenis_pu | pusurveilance | keterangan
                                        </small>
                                    </li>
                                    <li>Data duplikat akan dilewati otomatis</li>
                                </ul>
                            </div>

                            <!-- FORM UPLOAD -->
                            <form action="import-parameter.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Pilih File CSV</label>
                                    <input type="file"
                                        name="file_csv"
                                        accept=".csv"
                                        class="form-control"
                                        required>
                                </div>

                                <div class="text-end">
                                    <a href="list-kategori.php" class="btn btn-secondary">
                                        Kembali
                                    </a>
                                    <button type="submit" name="import" class="btn btn-primary">
                                        Import Sekarang
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <?php require_once "../template/footer.php"; ?>
</div>