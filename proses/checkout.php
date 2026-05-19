<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /TugasAkhir/scan.php');
  exit;
}

$kode = strtoupper(trim($_POST['kode']));

if (!$kode) {
  header('Location: /TugasAkhir/scan.php?status=tidak_ditemukan');
  exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM kunjungan WHERE kode_kunjungan = ?");
mysqli_stmt_bind_param($stmt, 's', $kode);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
  header('Location: /TugasAkhir/scan.php?status=tidak_ditemukan');
  exit;
}

if ($data['status'] === 'done') {
  header('Location: /TugasAkhir/scan.php?status=sudah_keluar');
  exit;
}

$update = mysqli_prepare($conn, "UPDATE kunjungan 
  SET waktu_keluar = NOW(), status = 'done', qr_used = 1 
  WHERE kode_kunjungan = ?");
mysqli_stmt_bind_param($update, 's', $kode);
mysqli_stmt_execute($update);

header('Location: /TugasAkhir/cetak_keluar.php?kode=' . $kode);
exit;