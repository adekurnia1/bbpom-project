<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

if ($_SESSION['ssRole'] != 'penguji') {
    header("location: ../index.php");
    exit;
}

require_once "../config.php";

$id_penguji = $_SESSION['ssId'];

$title = "Inbox Sampel Penguji";

require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// ================= QUERY INBOX =================
$q = mysqli_query($koneksi, "
    SELECT 
        ps.id_pengiriman,
        s.no_spl_sipt,
        s.nama_sampel,
        s.kategori,
        u.nama AS penyelia,
        ps.tgl_kirim
    FROM tbl_pengiriman_sampel ps
    JOIN tbl_sampel s ON s.no_spl_sipt = ps.no_spl_sipt
    JOIN tbl_users u ON u.id_user = ps.id_penyelia
    WHERE ps.id_penguji = '$id_penguji'
      AND ps.status_pengiriman = 'dikirim'
    ORDER BY ps.tgl_kirim DESC
");
?>

<div id="layoutSidenav_content">
    <main class="container-fluid px-4 mt-4">

        <h3 class="mb-3">Inbox Sampel Masuk</h3>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No SPL</th>
                    <th>Nama Sampel</th>
                    <th>Kategori</th>
                    <th>Penyelia</th>
                    <th>Tgl Kirim</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                <?php if (mysqli_num_rows($q) == 0) { ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Tidak ada sampel baru
                        </td>
                    </tr>
                <?php } ?>

                <?php while ($r = mysqli_fetch_assoc($q)) { ?>
                    <tr>
                        <td><?= $r['no_spl_sipt'] ?></td>
                        <td><?= $r['nama_sampel'] ?></td>
                        <td><?= $r['kategori'] ?></td>
                        <td><?= $r['penyelia'] ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($r['tgl_kirim'])) ?></td>
                        <td class="text-center">

                            <form action="proses-terima-penguji.php" method="POST"
                                onsubmit="return confirm('Terima sampel ini? SPP akan difinalisasi.')">

                                <input type="hidden" name="id_pengiriman" value="<?= $r['id_pengiriman'] ?>">

                                <button type="submit" name="terima" class="btn btn-success btn-sm">
                                    Terima / ACC
                                </button>
                            </form>

                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>

    </main>
    <?php require_once "../template/footer.php"; ?>
</div>