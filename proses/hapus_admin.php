<?php
include '../config/database.php';
session_start();

$id = (int)($_GET['id'] ?? 0);

if (!$id || $id == $_SESSION['admin_id']) {
  header('Location: /TugasAkhir/kelola_admin.php');
  exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM admin WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

header('Location: /TugasAkhir/kelola_admin.php?status=hapus');
exit;