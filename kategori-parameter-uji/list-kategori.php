<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}

require_once "../config.php";
$title = "List Kategori - BBPOM";

require_once "../template/header.php";
require_once "../template/sidebar.php";
require_once "../template/navbar.php";

$query = mysqli_query($koneksi, "SELECT * FROM tbl_kategori_parameter")
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4">Parameter Uji</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">List Parameter Uji</li>
            </ol>

            <div class="card mb-4">

                <!-- HEADER -->
                <div class="card-header d-flex justify-content-between align-items-right">
                    <span class="h5 mb-0">
                        <i class="fa-solid fa-square-plus"></i> List Parameter Uji
                    </span>
                    <div class="d-flex gap-2 mb-0">
                        <a href="add-kategori.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Manual
                        </a>

                        <a href="import-parameter-form.php" class="btn btn-primary">
                            <i class="fas fa-file-import"></i> Import Batch
                        </a>
                    </div>
                </div>

                <!-- BODY -->
                <div class="card-body">
                    <div class="table-responsive table-scroll">
                        <table class="table table-bordered table-striped nowrap-table" id="datatablesSimple">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Parameter Uji</th>
                                    <th>LOD</th>
                                    <th>LOQ</th>
                                    <th>Syarat</th>
                                    <th>Metode</th>
                                    <th>Pustaka</th>
                                    <th>Tipe PU</th>
                                    <th>Jenis PU</th>
                                    <th>PU Surveilance</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td><?= $row['kategori']; ?></td>
                                        <td><?= $row['parameter_uji']; ?></td>
                                        <td><?= $row['lod']; ?></td>
                                        <td><?= $row['loq']; ?></td>
                                        <td><?= $row['syarat']; ?></td>
                                        <td><?= $row['metode']; ?></td>
                                        <td><?= $row['pustaka']; ?></td>
                                        <td><?= $row['tipe_pu']; ?></td>
                                        <td><?= $row['jenis_pu']; ?></td>
                                        <td><?= $row['pusurveilance']; ?></td>
                                        <!-- TEXT PANJANG -->
                                        <td title="<?= htmlspecialchars($row['keterangan']); ?>">
                                            <?= htmlspecialchars($row['keterangan']); ?>
                                        </td>

                                        <td class="text-center">
                                            <a href="edit-parameter.php?id_kategori_parameter=<?= $row['id_kategori_parameter']; ?>" class="text-warning me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete-parameter.php?id_kategori_parameter=<?= $row['id_kategori_parameter']; ?>"
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
    </main>
</div>