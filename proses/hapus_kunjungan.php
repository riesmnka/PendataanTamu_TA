<?php
include '../config/database.php';

$kode = $_GET['kode'] ?? '';

if (!$kode) {
  header('Location: /TugasAkhir/kunjungan.php');
  exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM kunjungan WHERE kode_kunjungan = ?");
mysqli_stmt_bind_param($stmt, 's', $kode);
mysqli_stmt_execute($stmt);

header('Location: /TugasAkhir/kunjungan.php');
exit;