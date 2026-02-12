<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
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
    </style>
</head>

<body>

    <div class="center header-1">BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDUNG</div>
    <div class="center header-2">JL. PASTEUR NO. 25 BANDUNG 40171</div>

    <div class="center header-title">SURAT PERINTAH PENGUJIAN</div>

    <p>Kepada Petugas Penguji, agar dilakukan pengujian terhadap contoh berikut:</p>

    <table>
        <tr>
            <td width="20%">Nama Contoh</td>
            <td><?= $data['brand'] ?> <?= $data['nama_sampel'] ?></td>
            <td rowspan="3"><?= $data['komposisi'] ?></td>
        </tr>
        <tr>
            <td>Kode Contoh</td>
            <td>
                <?= $data['no_spl_sipt'] ?> /
                <?= date('m-Y', strtotime($data['tgl_spk'])) ?> /
                <?= $data['no_spu'] ?> /
                <?= $data['nama_penguji'] ?>
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
            <td width="15%"><strong>Catatan</strong></td>
            <td width="2%">:</td>
            <td width="83%"></td>
        </tr>
        <tr>
            <td>Tgl SPP :</td>
            <td><?= $data['tgl_spk'] ?></td>
        </tr>
        <tr>
            <td>Timeline :</td>
            <td><?= $timeline_tgl ?></td>
        </tr>
        <tr>
            <td>SPU :</td>
            <td><?= $data['no_spu'] ?></td>
        </tr>
        <tr>
            <td>Kategori :</td>
            <td><?= $data['kategori'] ?></td>
        </tr>
    </table>

    <br><br><br>

    <table class="no-border" style="text-align:center;">
        <tr>
            <td width="50%">
                Penyelia<br><br><br>
                <u><?= $data['nama_penyelia'] ?></u>
            </td>

            <td width="50%">
                Penguji<br><br><br>
                <?php if ($data['status_pengiriman'] == 'diterima') { ?>
                    <u><?= $data['nama_penguji'] ?></u>
                <?php } else { ?>
                    <i>(Belum ditandatangani)</i>
                <?php } ?>
            </td>
        </tr>
    </table>

</body>

</html>