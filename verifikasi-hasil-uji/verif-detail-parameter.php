$no_spl = $_GET['no_spl_sipt'] ?? '';
if ($no_spl == '') die("Sampel tidak valid!");

$q = mysqli_query($koneksi, "
    SELECT h.id_hasil_uji, p.parameter_uji, h.hasil_uji, h.status_verifikasi
    FROM tbl_hasil_uji h
    JOIN tbl_kategori_parameter p ON p.id_kategori_parameter = h.id_kategori_parameter
    WHERE h.no_spl_sipt = '$no_spl'
      AND h.id_penguji = '$id_penguji'
    ORDER BY p.parameter_uji
");

<form method="POST" action="proses-verifikasi.php">
    <input type="hidden" name="id_hasil_uji" value="<?= $r['id_hasil_uji'] ?>">
    <select name="status_verifikasi" class="form-select form-select-sm mb-1" required>
        <option value="">-- Pilih --</option>
        <option value="disetujui_penyelia">Disetujui Penyelia</option>
        <option value="ditolak">Ditolak</option>
    </select>
    <input type="text" name="catatan_penguji" class="form-control form-control-sm mb-1" placeholder="Catatan">
    <button name="btnVerif" class="btn btn-sm btn-success">Simpan Verifikasi</button>
</form>
