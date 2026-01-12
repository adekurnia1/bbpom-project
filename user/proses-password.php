<?php
session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

if (isset($_POST['simpan'])) {
    $curPass = trim(htmlspecialchars($_POST['curPass']));
    $newPass = trim(htmlspecialchars($_POST['newPass']));
    $confPass = trim(htmlspecialchars($_POST['confPass']));

    $username = $_SESSION['ssUser'];
    $queryUser = mysqli_query($koneksi, "SELECT * FROM tbl_users WHERE username = '$username'");
    $data = mysqli_fetch_array($queryUser);

    if ($newPass !== $confPass) {
        header("location:ganti-password.php?msg=err2");
        exit;
    }

    if (!password_verify($curPass, $data['password'])) {
        header("location:ganti-password.php?msg=err1");
        exit;
    } else{
        $pass = password_hash($newPass, PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE tbl_users SET password = '$pass' WHERE username = '$username'");
        header("location:ganti-password.php?msg=updated");
        exit;
    }
}
