$q = mysqli_query($koneksi,"
SELECT s.no_spl_sipt, spp.file_spp, spp.created_at
FROM tbl_spp spp
JOIN tbl_sampel s ON s.no_spl_sipt = spp.no_spl_sipt
");

while($r=mysqli_fetch_assoc($q)){
?>
<tr>
<td><?= $r['no_spl_sipt'] ?></td>
<td><?= $r['created_at'] ?></td>
<td>
<a href="../file_spp/<?= $r['file_spp'] ?>" target="_blank"
 class="btn btn-sm btn-danger">
 Download PDF
</a>
</td>
</tr>
<?php } ?>
