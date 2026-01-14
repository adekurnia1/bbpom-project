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
        fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $kategori        = mysqli_real_escape_string($koneksi, $data[0]);
            $parameter_uji   = mysqli_real_escape_string($koneksi, $data[1]);
            $lod             = mysqli_real_escape_string($koneksi, $data[2]);
            $loq             = mysqli_real_escape_string($koneksi, $data[3]);
            $syarat          = mysqli_real_escape_string($koneksi, $data[4]);
            $metode          = mysqli_real_escape_string($koneksi, $data[5]);
            $pustaka         = mysqli_real_escape_string($koneksi, $data[6]);
            $tipe_pu         = mysqli_real_escape_string($koneksi, $data[7]);
            $jenis_pu        = mysqli_real_escape_string($koneksi, $data[8]);
            $pusurveilance   = mysqli_real_escape_string($koneksi, $data[9]);
            $keterangan      = mysqli_real_escape_string($koneksi, $data[10]);

            // ❗ Cegah duplikat parameter
            $cek = mysqli_query($koneksi, "
                SELECT 1 FROM tbl_kategori_parameter
                WHERE kategori='$kategori'
                AND parameter_uji='$parameter_uji'
            ");

            if (mysqli_num_rows($cek) == 0) {

                mysqli_query($koneksi, "
                    INSERT INTO tbl_kategori_parameter
                    (kategori, parameter_uji, lod, loq, syarat, metode, pustaka,
                     tipe_pu, jenis_pu, pusurveilance, keterangan)
                    VALUES
                    ('$kategori','$parameter_uji','$lod','$loq','$syarat','$metode','$pustaka',
                     '$tipe_pu','$jenis_pu','$pusurveilance','$keterangan')
                ");
            }
        }

        fclose($handle);

        echo "<script>
            alert('Import parameter uji berhasil');
            window.location='list-kategori.php';
        </script>";
    }
}
