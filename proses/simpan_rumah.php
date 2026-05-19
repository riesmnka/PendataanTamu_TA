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

$stmt = mysqli_prepare($conn, "INSERT INTO rumah (nomor_rumah, nama_pemilik, blok) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sss', $nomor_rumah, $nama_pemilik, $blok);

if (mysqli_stmt_execute($stmt)) {
  header('Location: /TugasAkhir/master_rumah.php?status=sukses');
} else {
  header('Location: /TugasAkhir/master_rumah.php?status=error');
}
exit;