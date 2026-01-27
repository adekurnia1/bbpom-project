<?php
session_start();
require_once "../config.php";
require_once "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION['ssLogin'])) {
    header("location: ../auth/login.php");
    exit;
}

// ================= HELPER =================
function cleanCode($str)
{
    $str = trim($str);
    $str = str_replace("\xC2\xA0", '', $str); // non-breaking space
    $str = preg_replace('/\s+/', '', $str);  // hapus semua spasi
    return $str;
}

function fixDate($tgl)
{
    if (empty($tgl)) return null;
    if (is_numeric($tgl)) {
        return date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($tgl));
    }
    return date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl))));
}

// ================= PROCESS =================
if (isset($_POST['import'])) {

    $fileName = $_FILES['file_csv']['name'];
    $fileTmp  = $_FILES['file_csv']['tmp_name'];
    $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // ================= CSV =================
    if ($ext == 'csv') {

        $handle = fopen($fileTmp, "r");
        $test = fgetcsv($handle, 1000, ",");
        $delimiter = (count($test) > 1) ? "," : ";";
        rewind($handle);

        fgetcsv($handle, 1000, $delimiter); // skip header

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {

            $no_spl_sipt = cleanCode($row[0]);
            $no_spu      = cleanCode($row[1]);
            $pabrik      = mysqli_real_escape_string($koneksi, $row[2]);
            $no_reg      = mysqli_real_escape_string($koneksi, $row[3]);
            $no_bet      = mysqli_real_escape_string($koneksi, $row[4]);
            $nama_sampel = mysqli_real_escape_string($koneksi, $row[5]);
            $brand       = mysqli_real_escape_string($koneksi, $row[6]);
            $komposisi   = mysqli_real_escape_string($koneksi, $row[7]);
            $kadaluarsa  = fixDate($row[8]);
            $kategori    = mysqli_real_escape_string($koneksi, $row[9]);
            $wadah       = mysqli_real_escape_string($koneksi, $row[10]);
            $netto       = mysqli_real_escape_string($koneksi, $row[11]);

            // VALIDASI SPU
            $cekSpu = mysqli_query($koneksi, "
                SELECT 1 FROM tbl_spu WHERE no_spu='$no_spu'
            ");
            if (mysqli_num_rows($cekSpu) == 0) continue;

            // CEK DUPLIKAT
            $cek = mysqli_query($koneksi, "
                SELECT 1 FROM tbl_sampel 
                WHERE no_spl_sipt='$no_spl_sipt'
            ");
            if (mysqli_num_rows($cek) > 0) continue;

            mysqli_query($koneksi, "
                INSERT INTO tbl_sampel
                (no_spl_sipt, no_spu, pabrik, no_reg, no_bet,
                 nama_sampel, brand, komposisi, kadaluarsa,
                 kategori, wadah, netto)
                VALUES
                ('$no_spl_sipt','$no_spu','$pabrik','$no_reg','$no_bet',
                 '$nama_sampel','$brand','$komposisi','$kadaluarsa',
                 '$kategori','$wadah','$netto')
            ");
        }

        fclose($handle);
    }

    // ================= XLSX =================
    elseif ($ext == 'xlsx') {

        $spreadsheet = IOFactory::load($fileTmp);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        for ($i = 1; $i < count($rows); $i++) {

            $row = $rows[$i];

            $no_spl_sipt = cleanCode($row[0]);
            $no_spu      = cleanCode($row[1]);
            $pabrik      = mysqli_real_escape_string($koneksi, $row[2]);
            $no_reg      = mysqli_real_escape_string($koneksi, $row[3]);
            $no_bet      = mysqli_real_escape_string($koneksi, $row[4]);
            $nama_sampel = mysqli_real_escape_string($koneksi, $row[5]);
            $brand       = mysqli_real_escape_string($koneksi, $row[6]);
            $komposisi   = mysqli_real_escape_string($koneksi, $row[7]);
            $kadaluarsa  = fixDate($row[8]);
            $kategori    = mysqli_real_escape_string($koneksi, $row[9]);
            $wadah       = mysqli_real_escape_string($koneksi, $row[10]);
            $netto       = mysqli_real_escape_string($koneksi, $row[11]);

            // VALIDASI SPU
            $cekSpu = mysqli_query($koneksi, "
                SELECT 1 FROM tbl_spu WHERE no_spu='$no_spu'
            ");
            if (mysqli_num_rows($cekSpu) == 0) continue;

            // CEK DUPLIKAT
            $cek = mysqli_query($koneksi, "
                SELECT 1 FROM tbl_sampel 
                WHERE no_spl_sipt='$no_spl_sipt'
            ");
            if (mysqli_num_rows($cek) > 0) continue;

            mysqli_query($koneksi, "
                INSERT INTO tbl_sampel
                (no_spl_sipt, no_spu, pabrik, no_reg, no_bet,
                 nama_sampel, brand, komposisi, kadaluarsa,
                 kategori, wadah, netto)
                VALUES
                ('$no_spl_sipt','$no_spu','$pabrik','$no_reg','$no_bet',
                 '$nama_sampel','$brand','$komposisi','$kadaluarsa',
                 '$kategori','$wadah','$netto')
            ");
        }
    }

    else {
        echo "<script>alert('Format file tidak didukung');</script>";
        exit;
    }

    echo "<script>
        alert('Import Sampel berhasil');
        window.location='list-sampel.php';
    </script>";
}
