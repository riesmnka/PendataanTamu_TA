<?php
include '../config/database.php';

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
  header('Location: /TugasAkhir/master_rumah.php');
  exit;
}

$cek = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM kunjungan WHERE rumah_id = ? AND status = 'active'");
mysqli_stmt_bind_param($cek, 'i', $id);
mysqli_stmt_execute($cek);
$total = mysqli_fetch_assoc(mysqli_stmt_get_result($cek))['total'];

if ($total > 0) {
  header('Location: /TugasAkhir/master_rumah.php?status=tidak_bisa_hapus');
  exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM rumah WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

header('Location: /TugasAkhir/master_rumah.php?status=hapus');
exit;