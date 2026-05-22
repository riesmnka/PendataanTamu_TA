<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /TugasAkhir/master_rumah.php');
    exit;
}

$nomor_rumah  = trim($_POST['nomor_rumah']);
$nama_pemilik = trim($_POST['nama_pemilik']);
$blok         = trim($_POST['blok']);

if (!$nomor_rumah || !$nama_pemilik) {
    header('Location: /TugasAkhir/master_rumah.php?status=error');
    exit;
}

if (!preg_match('/^[0-9]+$/', $nomor_rumah)) {
    header('Location: /TugasAkhir/master_rumah.php?status=nomor_salah');
    exit;
}

if (!preg_match('/^[A-Za-z\s]+$/', $nama_pemilik)) {
    header('Location: /TugasAkhir/master_rumah.php?status=nama_salah');
    exit;
}

$cek_nomor = mysqli_query($conn, "SELECT id FROM rumah WHERE nomor_rumah='$nomor_rumah'");
if (mysqli_num_rows($cek_nomor) > 0) {
    header('Location: /TugasAkhir/master_rumah.php?status=nomor_duplikat');
    exit;
}

$cek_nama = mysqli_query($conn, "SELECT id FROM rumah WHERE nama_pemilik='$nama_pemilik'");
if (mysqli_num_rows($cek_nama) > 0) {
    header('Location: /TugasAkhir/master_rumah.php?status=nama_duplikat');
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO rumah (nomor_rumah, nama_pemilik, blok) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sss', $nomor_rumah, $nama_pemilik, $blok);

if (mysqli_stmt_execute($stmt)) {
    header('Location: /TugasAkhir/master_rumah.php?status=sukses');
} else {
    header('Location: /TugasAkhir/master_rumah.php?status=error');
}
exit;