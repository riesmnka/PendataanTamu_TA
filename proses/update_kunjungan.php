<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /TugasAkhir/kunjungan.php');
  exit;
}

$kode         = trim($_POST['kode']);
$nama_tamu    = trim($_POST['nama_tamu']);
$rumah_id     = (int) $_POST['rumah_id'];
$keperluan    = trim($_POST['keperluan']);
$no_kendaraan = strtoupper(trim($_POST['no_kendaraan']));

if (!$kode || !$nama_tamu || !$rumah_id || !$keperluan || !$no_kendaraan) {
  header('Location: /TugasAkhir/kunjungan.php');
  exit;
}

$stmt = mysqli_prepare($conn, "UPDATE kunjungan 
  SET nama_tamu = ?, rumah_id = ?, keperluan = ?, no_kendaraan = ?
  WHERE kode_kunjungan = ?");

mysqli_stmt_bind_param($stmt, 'sisss',
  $nama_tamu, $rumah_id, $keperluan, $no_kendaraan, $kode);

if (mysqli_stmt_execute($stmt)) {
  header('Location: /TugasAkhir/kunjungan.php?status=sukses&edited=' . $kode);
} else {
  header('Location: /TugasAkhir/kunjungan.php');
}
exit;