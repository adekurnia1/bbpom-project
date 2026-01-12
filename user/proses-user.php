<?php
session_start();

if(!isset($_SESSION["ssLogin"])){
    header("location: auth/login.php");
    exit;
}

require_once "../config.php";

//jika tombol "simpan" ditekan
if (isset($_POST['simpan'])){
    //ambil value elemen yang diposting
    $username = trim(htmlspecialchars($_POST['username']));
    $nama = trim(htmlspecialchars($_POST['nama']));
    $role = $_POST['role'];
    $password = 12345;
    $pass = password_hash($password, PASSWORD_DEFAULT);

    //CEK USERNAME
    $CekUsername = mysqli_query($koneksi, "SELECT * FROM tbl_users WHERE username = '$username'");
    if(mysqli_num_rows($CekUsername) > 0 ){
        header("location:add-user.php?msg=cancel");
        return;
    }

    mysqli_query($koneksi, "INSERT INTO tbl_users VALUES(null, '$username', '$pass', '$role', '$nama')");

    header("location:add-user.php?msg=added");
    return;
}
?>