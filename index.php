<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: auth/login.php");
    exit;
}
require_once "config.php";
$title = "BBPOM BDG - MAGANG PROJECT";
require_once "template/header.php";
require_once "template/navbar.php";
require_once "template/sidebar.php";

?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <!--
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">Primary Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?= $main_url ?>">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">Warning Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?= $main_url ?>">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">Success Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?= $main_url ?>">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">Danger Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?= $main_url ?>">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row">

                <!-- TABEL REKAP BULAN MASUK (KIRI) -->
                <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Rekap Bulan Masuk
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered text-center">
                                <thead style="background-color:#7f91b2; color:white;">
                                    <tr>
                                        <th><em>Bulan Masuk</em></th>
                                        <th>Balai Bandung</th>
                                        <th>Balai Bogor</th>
                                        <th>Balai Tasik</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>35</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>17</td>
                                        <td>7</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>80</td>
                                        <td></td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>88</td>
                                        <td>19</td>
                                        <td>16</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>92</td>
                                        <td>17</td>
                                        <td>13</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>95</td>
                                        <td>26</td>
                                        <td>12</td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>81</td>
                                        <td>12</td>
                                        <td>16</td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>93</td>
                                        <td><strong>23</strong></td>
                                        <td><strong>8</strong></td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>86</td>
                                        <td>18</td>
                                        <td>13</td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>60</td>
                                        <td>15</td>
                                        <td>14</td>
                                    </tr>
                                </tbody>
                                <tfoot class="fw-bold">
                                    <tr>
                                        <td>Grand Total</td>
                                        <td>727</td>
                                        <td>137</td>
                                        <td>105</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TABEL PENGUJI (KANAN) -->
                <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-users me-1"></i>
                            Data Penguji
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="table-warning text-center">
                                    <tr>
                                        <th>Penguji</th>
                                        <th>ID</th>
                                        <th>Penyelia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Yenny N.F.K</td>
                                        <td class="text-center">YN</td>
                                        <td>Yusrani Salman</td>
                                    </tr>
                                    <tr>
                                        <td>Tarita Kamardi</td>
                                        <td class="text-center">TR</td>
                                        <td>Yenny N.F.K</td>
                                    </tr>
                                    <tr>
                                        <td>Dewiyani Triharto</td>
                                        <td class="text-center">DW</td>
                                        <td>Yenny N.F.K</td>
                                    </tr>
                                    <tr>
                                        <td>Eneng Karmini</td>
                                        <td class="text-center">EN</td>
                                        <td>Yusrani Salman</td>
                                    </tr>
                                    <tr>
                                        <td>Riris Ari Rahmani</td>
                                        <td class="text-center">RS</td>
                                        <td>Yusrani Salman</td>
                                    </tr>
                                    <tr>
                                        <td>Yusrani Salman</td>
                                        <td class="text-center">YS</td>
                                        <td>Tarita Kamardi</td>
                                    </tr>
                                    <tr>
                                        <td>Firdausi</td>
                                        <td class="text-center">FI</td>
                                        <td>Yenny N.F.K</td>
                                    </tr>
                                    <tr>
                                        <td>Salma Zahra</td>
                                        <td class="text-center">SA</td>
                                        <td>Tarita Kamardi</td>
                                    </tr>
                                    <tr>
                                        <td>Nur Hidayat</td>
                                        <td class="text-center">DY</td>
                                        <td>Tarita Kamardi</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <?php require_once "template/footer.php"; ?>
</div>