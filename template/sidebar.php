<?php
$role = $_SESSION['ssRole'];
?>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">

            <div class="sb-sidenav-menu">
                <div class="nav">

                    <!-- HOME (SEMUA ROLE) -->
                    <div class="sb-sidenav-menu-heading">Home</div>
                    <a class="nav-link" href="<?= $main_url ?>index.php">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        Dashboard
                    </a>
                    <hr class="mb-0">

                    <a class="nav-link" href="<?= $main_url ?>sampel/riwayat-sampel.php">
                        <div class="sb-nav-link-icon">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        Riwayat Sampel
                    </a>
                    <hr class="mb-0">

                    <!-- DATA : KETUA TIM & PENYELIA -->
                    <?php if ($role == 'penyelia' || $role == 'ketua_tim') : ?>

                        <div class="sb-sidenav-menu-heading">Pengaturan</div>

                        <a class="nav-link" href="<?= $main_url ?>user/add-user.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            Users
                        </a>
                        <hr class="mb-0">

                        <a class="nav-link" href="<?= $main_url ?>user/ganti-password.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            Ganti Password
                        </a>
                        <hr class="mb-0">

                        <div class="sb-sidenav-menu-heading">Data</div>

                        <a class="nav-link" href="<?= $main_url ?>spu/list-spu.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-file"></i>
                            </div>
                            SPU
                        </a>
                        <hr class="mb-0">

                        <a class="nav-link" href="<?= $main_url ?>sampel/list-sampel.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-vial"></i>
                            </div>
                            Sampel
                        </a>
                        <hr class="mb-0">

                        <a class="nav-link" href="<?= $main_url ?>kategori-parameter-uji/list-kategori.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-flask"></i>
                            </div>
                            Parameter Uji
                        </a>
                        <hr class="mb-0">

                        <a class="nav-link" href="<?= $main_url ?>verifikasi-hasil-uji/verifikasi-hasil-uji.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                            Verifikasi Hasil Uji
                        </a>
                        <hr class="mb-0">

                    <?php endif; ?>

                    <!-- DATA : PENGUJI -->
                    <?php if ($role == 'penguji') : ?>

                        <div class="sb-sidenav-menu-heading">Data</div>

                        <a class="nav-link" href="<?= $main_url ?>hasil-uji/list-sampel-uji.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-microscope"></i>
                            </div>
                            Hasil Uji
                        </a>
                        <hr class="mb-0">

                    <?php endif; ?>

                </div>
            </div>

            <!-- FOOTER SIDEBAR -->
            <div class="sb-sidenav-footer border">
                <div class="small">Logged in as:</div>
                <?= htmlspecialchars($_SESSION["ssRole"]) ?>
            </div>

        </nav>
    </div>