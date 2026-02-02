<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: Arial; font-size: 11px; }
table { width:100%; border-collapse:collapse; }
td, th { border:1px solid black; padding:4px; }
</style>
</head>
<body>

<h3 style="text-align:center">SURAT PERINTAH PENGUJIAN</h3>

<table>
<tr><td>Nama Contoh</td><td><?= $brand ?> <?= $nama_sampel ?></td></tr>
<tr><td>Kode Contoh</td><td><?= $kode_contoh ?></td></tr>
<tr><td>No Batch</td><td><?= $no_batch ?></td></tr>
<tr><td>Kategori</td><td><?= $kategori ?></td></tr>
<tr><td>SPU</td><td><?= $no_spu ?></td></tr>
<tr><td>Tgl SPP</td><td><?= $tgl_spu ?></td></tr>
</table>

<br>

<table>
<tr>
<th>Parameter Uji</th>
<th>Metoda</th>
<th>Pustaka</th>
</tr>

<?php foreach($parameter as $p): ?>
<tr>
<td><?= $p['parameter_uji'] ?></td>
<td><?= $p['metode'] ?></td>
<td><?= $p['pustaka'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<br><br>

<table style="border:0">
<tr>
<td>Penguji<br><br><br><?= $nama_penguji ?></td>
<td>Penyelia<br><br><br><?= $nama_penyelia ?></td>
</tr>
</table>

</body>
</html>
