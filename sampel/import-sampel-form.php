<?php
session_start();
require_once "../config.php";
$title = "Import Sampel";

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
            <h1 class="mt-4">Import Sampel</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="list-sampel.php">List Sampel</a></li>
                <li class="breadcrumb-item active">Import</li>
            </ol>

            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            Upload File Sampel
                        </div>

                        <div class="card-body">

                            <!-- INSTRUKSI -->
                            <div class="alert alert-info">
                                <strong>Ketentuan File:</strong>
                                <ul class="mb-0">
                                    <li>Format file harus <b>.csv atau .xlsx</b></li>
                                    <li>CSV menggunakan pemisah <b>titik koma (;)</b></li>
                                    <li>Baris pertama harus berisi nama kolom</li>
                                    <li>Format tanggal <b>dd/mm/yyyy</b></li>
                                    <li>Urutan kolom:
                                        <br>
                                        <small>
                                            no_spl_sipt | no_spu | pabrik | no_reg | no_bet |
                                            nama_sampel | brand | komposisi | kadaluarsa |
                                            kategori | wadah | netto
                                        </small>
                                    </li>
                                    <li>Data duplikat (no_spl_sipt) akan dilewati otomatis</li>
                                </ul>
                            </div>

                            <!-- FORM UPLOAD -->
                            <form action="import-sampel.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Pilih File</label>
                                    <input type="file"
                                        name="file_csv"
                                        accept=".csv, .xlsx"
                                        class="form-control"
                                        required>
                                </div>

                                <div class="text-end">
                                    <a href="list-sampel.php" class="btn btn-secondary">
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