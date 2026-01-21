<?php
session_start();
require_once "../config.php";
$title = "Edit Parameter Uji";

require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id_kategori_parameter'])) {
    header("location: list-kategori.php");
    exit;
}

$id_kategori_parameter = mysqli_real_escape_string($koneksi, $_GET['id_kategori_parameter']);

$query = mysqli_query($koneksi, 
"SELECT * FROM tbl_kategori_parameter WHERE id_kategori_parameter='$id_kategori_parameter'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("location: list-kategori.php");
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
                                <strong>Edit Parameter Uji</strong>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Kategori</label>
                                        <input type="text" name="kategori" class="form-control" value="<?= $data['kategori']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Parameter Uji</label>
                                        <input type="text" name="parameter_uji" class="form-control" value="<?= $data['parameter_uji']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>lod</label>
                                        <input type="text" name="lod" class="form-control" value="<?= $data['lod']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>loq</label>
                                        <input type="text" name="loq" class="form-control" value="<?= $data['loq']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Syarat</label>
                                        <input type="text" name="syarat" class="form-control" value="<?= $data['syarat']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Metode</label>
                                        <input type="text" name="metode" class="form-control" value="<?= $data['metode']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Pustaka</label>
                                        <input type="text" name="pustaka" class="form-control" value="<?= $data['pustaka']; ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Tipe PU</label>
                                        <input type="text" name="tipe_pu" class="form-control" value="<?= $data['tipe_pu']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Jenis PU</label>
                                        <input type="text" name="jenis_pu" class="form-control" value="<?= $data['jenis_pu']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>PU Surveilance</label>
                                        <input type="text" name="pusurveilance" class="form-control" value="<?= $data['pusurveilance']; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Keterangan</label>
                                        <input type="text" name="keterangan" class="form-control" value="<?= $data['keterangan']; ?>">
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" name="update" class="btn btn-success">
                                    Update
                                </button>
                                <a href="list-kategori.php" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['update'])) {

                        $no_spl_sipt = $_POST['id_kategori_parameter'];

                        $update = mysqli_query($koneksi, "
        UPDATE tbl_kategori_parameter SET
            kategori='{$_POST['kategori']}',
            parameter_uji='{$_POST['parameter_uji']}',
            lod='{$_POST['lod']}',
            loq='{$_POST['loq']}',
            syarat='{$_POST['syarat']}',
            metode='{$_POST['metode']}',
            pustaka='{$_POST['pustaka']}',
            tipe_pu='{$_POST['tipe_pu']}',
            jenis_pu='{$_POST['jenis_pu']}',
            pusurveilance='{$_POST['pusurveilance']}',
            keterangan='{$_POST['keterangan']}'
        WHERE id_kategori_parameter='$id_kategori_parameter'
    ");

                        if ($update) {
                            echo "<script>
            alert('Data Parameter Uji berhasil diperbarui');
            window.location='list-kategori.php';
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