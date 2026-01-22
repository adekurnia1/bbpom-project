<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['ssLogin'])) {
    header("location: ../auth/login.php");
    exit;
}

if (isset($_POST['import'])) {

    $file = $_FILES['file_csv']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {

        // Lewati header
        fgetcsv($handle, 1000, ";");

        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

            $no_spu         = mysqli_real_escape_string($koneksi, $data[0]);
            $tipe_sampel    = mysqli_real_escape_string($koneksi, $data[1]);
            $asal_sampling  = mysqli_real_escape_string($koneksi, $data[2]);
            $bulan_masuk    = mysqli_real_escape_string($koneksi, $data[3]);
            $tgl_masuk_lab  = mysqli_real_escape_string($koneksi, $data[4]);
            $tgl_spk        = mysqli_real_escape_string($koneksi, $data[5]);
            $jumlah_sampel  = mysqli_real_escape_string($koneksi, $data[6]);
            $timeline       = mysqli_real_escape_string($koneksi, $data[7]);

            // cegah duplikat parameter
            $cek = mysqli_query($koneksi, "
                SELECT 1 FROM tbl_spu
                WHERE no_spu='$no_spu'
            ");

            if (mysqli_num_rows($cek) == 0) {

                mysqli_query($koneksi, "
                    INSERT INTO tbl_spu
                    (no_spu, tipe_sampel, asal_sampling, bulan_masuk, tgl_masuk_lab, tgl_spk,
                    jumlah_sampel, timeline)
                    VALUES
                    ('$no_spu','$tipe_sampel','$asal_sampling','$bulan_masuk','$tgl_masuk_lab','$tgl_spk',
                    '$jumlah_sampel', '$timeline')
                ");
            }
        }

        fclose($handle);

        echo "<script>
            alert('Import parameter uji berhasil');
            window.location='list-spu.php';
        </script>";
    }
}
