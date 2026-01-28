<?php

//connect ke database db_penyelia
$koneksi = mysqli_connect("localhost","root","","db_pengujian");

// cek koneksi ke database
// if (mysqli_connect_errno()){
//     echo "Gagal koneksi ke database";
// } else{
//     echo "Berhasil koneksi ke database";
// }

//main url
$main_url = "http://localhost/bbpom/";
?>