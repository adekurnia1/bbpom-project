<?php
session_start();
require_once "../config.php";
require_once "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION['ssLogin'])) {
    header("location: ../auth/login.php");
    exit;
}

// fungsi konversi tanggal
function fixDate($tgl)
{
    if (empty($tgl)) return null;

    // kalau numeric excel (contoh: 45321)
    if (is_numeric($tgl)) {
        return date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($tgl));
    }

    // kalau format dd/mm/yyyy
    return date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl))));
}

if (isset($_POST['import'])) {

    $fileName = $_FILES['file_csv']['name'];
    $fileTmp  = $_FILES['file_csv']['tmp_name'];
    $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // ================= CSV =================
    if ($ext == 'csv') {

        if (($handle = fopen($fileTmp, "r")) !== FALSE) {

            // skip header
            fgetcsv($handle, 1000, ";");

            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

                $no_spu        = mysqli_real_escape_string($koneksi, $data[0]);
                $tipe_sampel   = mysqli_real_escape_string($koneksi, $data[1]);
                $asal_sampling = mysqli_real_escape_string($koneksi, $data[2]);
                $bulan_masuk   = mysqli_real_escape_string($koneksi, $data[3]);

                $tgl_masuk_lab = fixDate($data[4]);
                $tgl_spk       = fixDate($data[5]);

                $jumlah_sampel = mysqli_real_escape_string($koneksi, $data[6]);
                $timeline      = mysqli_real_escape_string($koneksi, $data[7]);

                // cegah duplikat
                $cek = mysqli_query($koneksi, "SELECT 1 FROM tbl_spu WHERE no_spu='$no_spu'");

                if (mysqli_num_rows($cek) == 0) {
                    mysqli_query($koneksi, "
                        INSERT INTO tbl_spu
                        (no_spu, tipe_sampel, asal_sampling, bulan_masuk,
                         tgl_masuk_lab, tgl_spk, jumlah_sampel, timeline)
                        VALUES
                        ('$no_spu','$tipe_sampel','$asal_sampling','$bulan_masuk',
                         '$tgl_masuk_lab','$tgl_spk','$jumlah_sampel','$timeline')
                    ");
                }
            }

            fclose($handle);
        }
    }

    // ================= XLSX =================
    elseif ($ext == 'xlsx') {

        $spreadsheet = IOFactory::load($fileTmp);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // mulai dari index 1 (skip header)
        for ($i = 1; $i < count($rows); $i++) {

            $row = $rows[$i];

            $no_spu        = mysqli_real_escape_string($koneksi, $row[0]);
            $tipe_sampel   = mysqli_real_escape_string($koneksi, $row[1]);
            $asal_sampling = mysqli_real_escape_string($koneksi, $row[2]);
            $bulan_masuk   = mysqli_real_escape_string($koneksi, $row[3]);

            $tgl_masuk_lab = fixDate($row[4]);
            $tgl_spk       = fixDate($row[5]);

            $jumlah_sampel = mysqli_real_escape_string($koneksi, $row[6]);
            $timeline      = mysqli_real_escape_string($koneksi, $row[7]);

            // cegah duplikat
            $cek = mysqli_query($koneksi, "SELECT 1 FROM tbl_spu WHERE no_spu='$no_spu'");

            if (mysqli_num_rows($cek) == 0) {
                mysqli_query($koneksi, "
                    INSERT INTO tbl_spu
                    (no_spu, tipe_sampel, asal_sampling, bulan_masuk,
                     tgl_masuk_lab, tgl_spk, jumlah_sampel, timeline)
                    VALUES
                    ('$no_spu','$tipe_sampel','$asal_sampling','$bulan_masuk',
                     '$tgl_masuk_lab','$tgl_spk','$jumlah_sampel','$timeline')
                ");
            }
        }
    } else {
        echo "<script>alert('Format file tidak didukung');</script>";
        exit;
    }

    echo "<script>
        alert('Import SPU berhasil');
        window.location='list-spu.php';
    </script>";
}
