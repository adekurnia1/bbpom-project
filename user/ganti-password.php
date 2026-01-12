<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Ganti Password";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = "";
if ($msg == 'err1') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-triangle-exclamation"></i> Ganti password gagal, password lama tidak cocok..
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'err2') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-triangle-exclamation"></i> Ganti password gagal, konfirmasi password tidak sama..
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Ganti Password Berhasil !
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Password</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Ganti Password"</li>
            </ol>
            <form action="proses-password.php" method="POST">
                <?php
                if ($msg !== '') {
                    echo $alert;
                }
                ?>
                <div class="card">
                    <div class="card-header">
                        <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i>Ganti Password</span>
                        <button type="submit" name="simpan" class="btn btn-primary float-end">Simpan</button>
                    </div>
                    <div class="card-body">
                        <div class="col-7">
                            <label for="curPass" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="curPass" name="curPass"
                                minlength="5" maxlength="10" placeholder="Masukan Password Saat Ini" required>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-7">
                            <label for="newPass" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="newPass" name="newPass"
                                minlength="5" maxlength="10" placeholder="Masukan Password Baru">

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-7">
                            <label for="confPass" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confPass" name="confPass"
                                minlength="5" maxlength="10" placeholder="Ulangi Password Baru">

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php require_once "../template/footer.php" ?>;
</div>