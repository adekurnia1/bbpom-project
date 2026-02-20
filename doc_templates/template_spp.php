<?php
// no_spl_sipt

use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use function Safe\strtotime;

$noSPL = $data['no_spl_sipt'];

// bulan masuk
$bulanMasuk = (int) date('m', strtotime($data['tgl_spk']));

// asal sampel
$asal = trim($data['asal_sampling']);
switch ($asal) {
    case 'Balai Bandung':
        $kodeAsal = 'BD';
        break;
    case 'Balai Tasik':
        $kodeAsal = 'TS';
        break;
    case 'Balai Bogor':
        $kodeAsal = 'BG';
        break;
    default:
        $kodeAsal = 'XX';
}

// 4 digit terakhir no_spl_sipt
$last4 = substr($noSPL, -4);
$last4 = (int) $last4;

// id penguji
$idPenguji = $data['kode_penguji'];

$kodeContohFormat = "$noSPL ($bulanMasuk-$kodeAsal-$last4-$idPenguji)";

$qLibur = mysqli_query($koneksi, "SELECT tanggal FROM tbl_hari_libur");

$hariLibur = [];
while ($row = mysqli_fetch_assoc($qLibur)) {
    $hariLibur[] = $row['tanggal'];
}


function hitungTanggalSelesai($tglMulai, $jumlahHari, $hariLibur)
{
    $tanggal = new DateTime($tglMulai);
    $hariKerja = 0;

    while ($hariKerja < $jumlahHari) {

        $hari = $tanggal->format('N');
        $tglString = $tanggal->format('Y-m-d');

        if ($hari < 6 && !in_array($tglString, $hariLibur)) {
            $hariKerja++;
        }

        if ($hariKerja < $jumlahHari) {
            $tanggal->modify('+1 day');
        }
    }

    return $tanggal->format('d-m-Y');
}

$ttdPenyeliaPath = realpath(__DIR__ . "/../tanda_tangan/ttd_" . $data['username_penyelia'] . ".png");
$ttdPenyeliaBase64 = '';

if ($ttdPenyeliaPath && file_exists($ttdPenyeliaPath)) {
    $imageDataPenyelia = file_get_contents($ttdPenyeliaPath);
    $ttdPenyeliaBase64 = 'data:image/png;base64,' . base64_encode($imageDataPenyelia);
}

$ttdPengujiPath = realpath(__DIR__ . "/../tanda_tangan/ttd_" . $data['username_penguji'] . ".png");
$ttdPengujiBase64 = '';

if ($ttdPengujiPath && file_exists($ttdPengujiPath)) {
    $imageDataPenguji = file_get_contents($ttdPengujiPath);
    $ttdPengujiBase64 = 'data:image/png;base64,' . base64_encode($imageDataPenguji);
}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin-top: 40px;
        }

        .center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        td,
        th {
            border: 1px solid black;
            padding: 4px;
            vertical-align: top;
        }

        .no-border td {
            border: 0;
        }

        .header-1 {
            font-size: 14px;
            font-weight: normal;
            margin: 0;
        }

        .header-2 {
            font-size: 12px;
            font-weight: normal;
            margin: 0;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
        }

        .param-cell {
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }

        .param-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .checkbox-box {
            width: 12px;
            height: 12px;
            border: 1px solid black;
            display: inline-block;
        }

        .param-cell input {
            margin-top: 2px;
        }

        .nomor-dokumen {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 10px;
        }

        .ttd-img {
            height: 60px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <div class="nomor-dokumen">
        <?= $data['no_dokumen'] ?? '7.4/PTJM-01/BBPOM BDG/18 F(03)' ?>
    </div>

    <div class="center header-1">BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDUNG</div>

    <div class="center header-2">JL. PASTEUR NO. 25 BANDUNG 40171</div>

    <div class="center header-title">SURAT PERINTAH PENGUJIAN</div>

    <p>Kepada Petugas Penguji, agar dilakukan pengujian terhadap contoh berikut:</p>

    <table>
        <tr>
            <td width="20%">Nama Contoh</td>
            <td width="40%"><?= $data['brand'] ?> <?= $data['nama_sampel'] ?></td>
            <td rowspan="3" width="40%"><?= $data['komposisi'] ?></td>
        </tr>
        <tr>
            <td>Kode Contoh</td>
            <td>
                <?= $kodeContohFormat ?>
            </td>
        </tr>
        <tr>
            <td>No Batch</td>
            <td><?= $data['no_bet'] ?></td>
        </tr>
    </table>

    <br>

    <table>
        <tr>
            <th width="60%">PARAMETER UJI</th>
            <th width="20%">METODA</th>
            <th width="20%">PUSTAKA</th>
        </tr>

        <?php
        $qParam = mysqli_query($koneksi, "
    SELECT parameter_uji, metode, pustaka 
    FROM tbl_kategori_parameter 
    WHERE kategori = '{$data['kategori']}'
    ORDER BY parameter_uji
");

        $params = [];
        while ($row = mysqli_fetch_assoc($qParam)) {
            $params[] = $row;
        }

        $totalRow = 15;

        for ($i = 0; $i < $totalRow; $i++) {

            $param = $params[$i]['parameter_uji'] ?? '';
            $metode = $params[$i]['metode'] ?? '';
            $pustaka = $params[$i]['pustaka'] ?? '';
        ?>
            <tr>
                <td>
                    <div class="param-row">
                        <span class="checkbox-box"></span>
                        <span><?= $param ?></span>
                    </div>
                </td>
                <td class="center"><?= $metode ?></td>
                <td class="center"><?= $pustaka ?></td>
            </tr>
        <?php } ?>
    </table>

    <br><br>

    <table class="no-border" style="width:100%;">
        <tr>
            <td width="20%"><strong>Catatan :</strong></td>
            <td width="2%"></td>
            <td width="78%"></td>
        </tr>
        <tr>
            <td>Tgl SPP</td>
            <td>:</td>
            <td><?= date('d-m-Y', strtotime($data['tgl_spk'])) ?> </td>
        </tr>
        <tr>
            <td>Timeline</td>
            <td>:</td>
            <td>
                <?= hitungTanggalSelesai(
                    $data['tgl_spk'],
                    $data['timeline'],
                    $hariLibur
                ); ?>
            </td>
        </tr>
        <tr>
            <td>SPU</td>
            <td>:</td>
            <td><?= $data['no_spu'] ?></td>
        </tr>
        <tr>
            <td>Kategori</td>
            <td>:</td>
            <td><?= $data['kategori'] ?></td>
        </tr>
    </table>

    <br><br><br>

    <table class="no-border" style="text-align:center;">
        <tr>
            <td width="50%">
                Penyelia<br>
                <?php if (file_exists($ttdPenyeliaPath)) { ?>
                    <?php if ($ttdPenyeliaBase64) { ?>
                        <img src="<?= $ttdPenyeliaBase64 ?>" class="ttd-img">
                    <?php } ?>
                <?php } else { ?>
                    <br><br><br>
                <?php } ?>

                <br>
                <u><?= $data['nama_penyelia'] ?></u>
            </td>

            <td width="50%">
                Penguji<br>
                <?php if ($data['status_pengiriman'] == 'diterima' && file_exists($ttdPengujiPath)) { ?>
                    <?php if ($ttdPengujiBase64) { ?>
                        <img src="<?= $ttdPengujiBase64 ?>" class="ttd-img">
                    <?php } ?>
                <?php } else { ?>
                    <br><br><br>
                <?php } ?>

                <br>
                <u><?= $data['nama_penguji'] ?></u>
            </td>
        </tr>
    </table>

</body>

</html>