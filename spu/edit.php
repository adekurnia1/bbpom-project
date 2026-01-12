<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_GET['no_spu'])) {
    header("location: list-spu.php");
    exit;
}

$no_spu = mysqli_real_escape_string($koneksi, $_GET['no_spu']);

$query = mysqli_query($koneksi, "SELECT * FROM tbl_spu WHERE no_spu='$no_spu'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("location: list-spu.php");
    exit;
}
?>

<form method="POST">
<div class="card">
    <div class="card-header bg-warning">
        <strong>Edit SPU</strong>
    </div>

    <div class="card-body">

        <!-- NO SPU (TIDAK BISA DIEDIT) -->
        <div class="mb-3">
            <label>No SPU</label>
            <input type="text" class="form-control" value="<?= $data['no_spu']; ?>" disabled>
            <input type="hidden" name="no_spu" value="<?= $data['no_spu']; ?>">
            <small class="text-danger">No SPU tidak dapat diubah</small>
        </div>

        <div class="mb-3">
            <label>Tipe Sampel</label>
            <input type="text" name="tipe_sampel" class="form-control"
                   value="<?= $data['tipe_sampel']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Asal Sampling</label>
            <input type="text" name="asal_sampling" class="form-control"
                   value="<?= $data['asal_sampling']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Bulan Masuk</label>
            <input type="number" name="bulan_masuk" class="form-control"
                   value="<?= $data['bulan_masuk']; ?>" min="1" max="12" required>
        </div>

        <div class="mb-3">
            <label>Tgl Masuk Lab</label>
            <input type="date" name="tgl_masuk_lab" class="form-control"
                   value="<?= $data['tgl_masuk_lab']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Tgl SPK</label>
            <input type="date" name="tgl_spk" class="form-control"
                   value="<?= $data['tgl_spk']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Jumlah Sampel</label>
            <input type="number" name="jumlah_sampel" class="form-control"
                   value="<?= $data['jumlah_sampel']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Timeline (Hari)</label>
            <input type="number" name="timeline" class="form-control"
                   value="<?= $data['timeline']; ?>" required>
        </div>

    </div>

    <div class="card-footer text-end">
        <button type="submit" name="update" class="btn btn-success">
            Update
        </button>
        <a href="list-spu.php" class="btn btn-secondary">Batal</a>
    </div>
</div>
</form>

<?php
if (isset($_POST['update'])) {

    $no_spu = $_POST['no_spu']; // dari hidden input
    $tipe = $_POST['tipe_sampel'];
    $asal = $_POST['asal_sampling'];
    $bulan = $_POST['bulan_masuk'];
    $tgl_masuk = $_POST['tgl_masuk_lab'];
    $tgl_spk = $_POST['tgl_spk'];
    $jumlah = $_POST['jumlah_sampel'];
    $timeline = $_POST['timeline'];

    // ❗ NO SPU TIDAK DIUBAH (TIDAK ADA UPDATE no_spu)
    $update = mysqli_query($koneksi, "
        UPDATE tbl_spu SET
            tipe_sampel='$tipe',
            asal_sampling='$asal',
            bulan_masuk='$bulan',
            tgl_masuk_lab='$tgl_masuk',
            tgl_spk='$tgl_spk',
            jumlah_sampel='$jumlah',
            timeline='$timeline'
        WHERE no_spu='$no_spu'
    ");

    if ($update) {
        echo "<script>
            alert('Data SPU berhasil diperbarui');
            window.location='list-spu.php';
        </script>";
    } else {
        echo "<script>alert('Gagal update data');</script>";
    }
}
?>
