<?php
include '../config/database.php';

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
  header('Location: /TugasAkhir/master_rumah.php');
  exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM rumah WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

header('Location: /TugasAkhir/master_rumah.php?status=hapus');
exit;