<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /TugasAkhir/kelola_admin.php');
  exit;
}

$id              = (int) $_POST['id'];
$password_baru   = trim($_POST['password_baru']);
$password_konfirm = trim($_POST['password_konfirm']);

if (!$id || !$password_baru || strlen($password_baru) < 6 || $password_baru !== $password_konfirm) {
  header('Location: /TugasAkhir/ganti_password.php?id='.$id.'&status=error');
  exit;
}

$hash = password_hash($password_baru, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, "UPDATE admin SET password = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'si', $hash, $id);

if (mysqli_stmt_execute($stmt)) {
  header('Location: /TugasAkhir/kelola_admin.php?status=pw_sukses');
} else {
  header('Location: /TugasAkhir/ganti_password.php?id='.$id.'&status=error');
}
exit;