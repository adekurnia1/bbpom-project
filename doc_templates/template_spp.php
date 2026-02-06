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
    </style>
</head>

<body>

    <h3 class="center">BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDUNG</h3>
    <h4 class="center">JL. PASTEUR NO. 25 BANDUNG 40171</h4>

    <h3 class="center" style="margin-top:20px;">SURAT PERINTAH PENGUJIAN</h3>

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
        while ($p = mysqli_fetch_assoc($qParam)) {
        ?>
            <tr>
                <td><?= $p['parameter_uji'] ?></td>
                <td><?= $p['metode'] ?></td>
                <td><?= $p['pustaka'] ?></td>
            </tr>
        <?php } ?>
    </table>

    <table class="no-border">
        <tr>
            <td width="20%">Tgl SPP</td>
            <td><?= date('d-m-Y', strtotime($data['tgl_spk'])) ?></td>
        </tr>
        <tr>
            <td>Timeline</td>
            <td><?= $data['timeline'] ?></td>
        </tr>
        <tr>
            <td>SPU</td>
            <td><?= $data['no_spu'] ?></td>
        </tr>
        <tr>
            <td>Kategori</td>
            <td><?= $data['kategori'] ?></td>
        </tr>
    </table>

    <br><br>

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

    <p>Tanggal Cetak: <?= date('d-m-Y H:i') ?></p>

</body>

</html>