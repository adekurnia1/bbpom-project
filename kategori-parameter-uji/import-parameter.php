<?php
session_start();
require_once "../config.php";
require_once "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION['ssLogin'])) {
    header("location: ../auth/login.php");
    exit;
}

if (isset($_POST['import'])) {

    $fileName = $_FILES['file_csv']['name'];
    $fileTmp  = $_FILES['file_csv']['tmp_name'];
    $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // File CSV
    if ($ext == 'csv') {

        $handle = fopen($fileTmp, "r");
        $test = fgetcsv($handle, 1000, ",");
        $delimiter = (count($test) > 1) ? "," : ";";
        rewind($handle);

        fgetcsv($handle, 1000, $delimiter);

        while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {

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
    }

    // File Excel (XLSX)
    elseif ($ext == 'xlsx') {

        $spreadsheet = IOFactory::load($fileTmp);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        for ($i = 1; $i < count($rows); $i++) {

            $row = $rows[$i];

            $kategori        = mysqli_real_escape_string($koneksi, $row[0]);
            $parameter_uji   = mysqli_real_escape_string($koneksi, $row[1]);
            $lod             = mysqli_real_escape_string($koneksi, $row[2]);
            $loq             = mysqli_real_escape_string($koneksi, $row[3]);
            $syarat          = mysqli_real_escape_string($koneksi, $row[4]);
            $metode          = mysqli_real_escape_string($koneksi, $row[5]);
            $pustaka         = mysqli_real_escape_string($koneksi, $row[6]);
            $tipe_pu         = mysqli_real_escape_string($koneksi, $row[7]);
            $jenis_pu        = mysqli_real_escape_string($koneksi, $row[8]);
            $pusurveilance   = mysqli_real_escape_string($koneksi, $row[9]);
            $keterangan      = mysqli_real_escape_string($koneksi, $row[10]);

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
    }

    else {
        echo "<script>alert('Format file tidak didukung');</script>";
        exit;
    }

    echo "<script>
        alert('Import Parameter Uji berhasil');
        window.location='list-kategori.php';
    </script>";
}
