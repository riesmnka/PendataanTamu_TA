<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /TugasAkhir/input_tamu.php');
  exit;
}

$nama_tamu    = trim($_POST['nama_tamu']);
$rumah_id     = (int) $_POST['rumah_id'];
$no_kendaraan = trim($_POST['no_kendaraan']);
$keperluan    = trim($_POST['keperluan']);

if (!$nama_tamu || !$rumah_id || !$no_kendaraan || !$keperluan) {
  header('Location: /TugasAkhir/input_tamu.php?status=error');
  exit;
}

$query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM kunjungan");
$count = mysqli_fetch_assoc($query_count)['total'];
$kode = 'T-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);

$query = mysqli_prepare($conn, "INSERT INTO kunjungan 
  (kode_kunjungan, nama_tamu, no_kendaraan, keperluan, rumah_id, waktu_masuk) 
  VALUES (?, ?, ?, ?, ?, NOW())");

mysqli_stmt_bind_param($query, 'ssssi',
  $kode, $nama_tamu, $no_kendaraan, $keperluan, $rumah_id);

if (mysqli_stmt_execute($query)) {
  header('Location: /TugasAkhir/cetak_barcode.php?kode=' . $kode);
  exit;
} else {
  header('Location: /TugasAkhir/input_tamu.php?status=error');
  exit;
}