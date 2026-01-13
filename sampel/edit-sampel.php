<?php
session_start();
require_once "../config.php";
$title = "Edit Data Sampel";

require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_GET['no_spl_sipt'])) {
    header("location: list-sampel.php");
    exit;
}

$no_spl_sipt = mysqli_real_escape_string($koneksi, $_GET['no_spl_sipt']);

$query = mysqli_query($koneksi, "SELECT * FROM tbl_sampel WHERE no_spl_sipt='$no_spl_sipt'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("location: list-sampel.php");
    exit;
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="row justify-content-center">
                <div class="col-lg-11 col-md-12">

                    <form method="POST">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning">
                                <strong>Edit Data Sampel</strong>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    <!-- NO SPL SIPT -->
                                    <div class="col-md-6 mb-3">
                                        <label>No SPL SIPT</label>
                                        <input type="text" class="form-control" value="<?= $data['no_spl_sipt']; ?>" disabled>
                                        <input type="hidden" name="no_spl_sipt" value="<?= $data['no_spl_sipt']; ?>">
                                        <small class="text-danger">Tidak dapat diubah</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>No SPU</label>
                                        <input type="text" name="no_spu" class="form-control" value="<?= $data['no_spu']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Pabrik</label>
                                        <input type="text" name="pabrik" class="form-control" value="<?= $data['pabrik']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>No Registrasi</label>
                                        <input type="text" name="no_reg" class="form-control" value="<?= $data['no_reg']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>No Bet</label>
                                        <input type="text" name="no_bet" class="form-control" value="<?= $data['no_bet']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Nama Sampel</label>
                                        <input type="text" name="nama_sampel" class="form-control" value="<?= $data['nama_sampel']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Brand</label>
                                        <input type="text" name="brand" class="form-control" value="<?= $data['brand']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Komposisi</label>
                                        <input type="text" name="komposisi" class="form-control" value="<?= $data['komposisi']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Kadaluarsa</label>
                                        <input type="date" name="kadaluarsa" class="form-control" value="<?= $data['kadaluarsa']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Kategori</label>
                                        <input type="text" name="kategori" class="form-control" value="<?= $data['kategori']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Wadah</label>
                                        <input type="text" name="wadah" class="form-control" value="<?= $data['wadah']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Netto</label>
                                        <input type="text" name="netto" class="form-control" value="<?= $data['netto']; ?>">
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" name="update" class="btn btn-success">
                                    Update
                                </button>
                                <a href="list-sampel.php" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['update'])) {

                        $no_spl_sipt = $_POST['no_spl_sipt'];

                        $update = mysqli_query($koneksi, "
        UPDATE tbl_sampel SET
            no_spu='{$_POST['no_spu']}',
            pabrik='{$_POST['pabrik']}',
            no_registrasi='{$_POST['no_registrasi']}',
            no_bet='{$_POST['no_bet']}',
            nama_sampel='{$_POST['nama_sampel']}',
            brand='{$_POST['brand']}',
            komposisi='{$_POST['komposisi']}',
            kadaluarsa='{$_POST['kadaluarsa']}',
            kategori='{$_POST['kategori']}',
            wadah='{$_POST['wadah']}',
            netto='{$_POST['netto']}'
        WHERE no_spl_sipt='$no_spl_sipt'
    ");

                        if ($update) {
                            echo "<script>
            alert('Data sampel berhasil diperbarui');
            window.location='list-sampel.php';
        </script>";
                        } else {
                            echo "<script>alert('Gagal update data');</script>";
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </main>
    <?php require_once "../template/footer.php"; ?>
</div>