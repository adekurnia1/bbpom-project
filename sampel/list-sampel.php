<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}
require_once "../config.php";
$title = "List Sampel - BBPOM";

require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$query = mysqli_query($koneksi, "SELECT * FROM tbl_sampel ORDER BY no_spl_sipt");
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4">Sampel</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">List Sampel</li>
            </ol>

            <div class="card mb-4">

                <!-- HEADER -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="h5 mb-0">
                        <i class="fa-solid fa-square-plus"></i> List Sampel
                    </span>
                    <div class="d-flex gap-2 mb-0">
                        <a href="add-kategori.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Manual
                        </a>

                        <a href="import-sampel-form.php" class="btn btn-primary">
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
                                    <th>No SPL SIPT</th>
                                    <th>No SPU</th>
                                    <th>Pabrik</th>
                                    <th>No Registrasi</th>
                                    <th>No Bet</th>
                                    <th>Nama Sampel</th>
                                    <th>Brand</th>
                                    <th>Komposisi</th>
                                    <th>Kadaluarsa</th>
                                    <th>Kategori</th>
                                    <th>Wadah</th>
                                    <th>Netto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td><?= $row['no_spl_sipt']; ?></td>
                                        <td><?= $row['no_spu']; ?></td>
                                        <td><?= $row['pabrik']; ?></td>
                                        <td><?= $row['no_reg']; ?></td>
                                        <td><?= $row['no_bet']; ?></td>
                                        <td><?= $row['nama_sampel']; ?></td>
                                        <td><?= $row['brand']; ?></td>

                                        <!-- TEXT PANJANG -->
                                        <td title="<?= htmlspecialchars($row['komposisi']); ?>">
                                            <?= htmlspecialchars($row['komposisi']); ?>
                                        </td>

                                        <td class="text-center">
                                            <?= date('d-m-Y', strtotime($row['kadaluarsa'])); ?>
                                        </td>
                                        <td><?= $row['kategori']; ?></td>
                                        <td><?= $row['wadah']; ?></td>
                                        <td><?= $row['netto']; ?></td>

                                        <td class="text-center">
                                            <a href="edit-sampel.php?no_spl_sipt=<?= $row['no_spl_sipt']; ?>"
                                                class="text-warning me-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="delete-sampel.php?no_spl_sipt=<?= $row['no_spl_sipt']; ?>"
                                                class="text-danger me-2"
                                                onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                            <a href="view-sampel.php?no_spl_sipt=<?= $row['no_spl_sipt']; ?>"
                                                class="text-primary" title="Detail">
                                                <i class="fas fa-eye"></i>
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
    <?php require_once "../template/footer.php"; ?>
</div>