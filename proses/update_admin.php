<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /TugasAkhir/kelola_admin.php');
  exit;
}

$id              = (int) $_POST['id'];
$nama            = trim($_POST['nama']);
$password_lama   = trim($_POST['password_lama']);
$password_baru   = trim($_POST['password_baru']);
$password_konfirm = trim($_POST['password_konfirm']);

if (!$id || !$nama) {
  header('Location: /TugasAkhir/edit_admin.php?id='.$id.'&status=error');
  exit;
}

if ($password_baru) {
  // Validasi password baru
  if (strlen($password_baru) < 6 || $password_baru !== $password_konfirm) {
    header('Location: /TugasAkhir/edit_admin.php?id='.$id.'&status=error');
    exit;
  }

  $cek = mysqli_prepare($conn, "SELECT password FROM admin WHERE id = ?");
  mysqli_stmt_bind_param($cek, 'i', $id);
  mysqli_stmt_execute($cek);
  $hasil = mysqli_fetch_assoc(mysqli_stmt_get_result($cek));

  if (!password_verify($password_lama, $hasil['password'])) {
    header('Location: /TugasAkhir/edit_admin.php?id='.$id.'&status=pw_salah');
    exit;
  }

  $hash = password_hash($password_baru, PASSWORD_DEFAULT);
  $stmt = mysqli_prepare($conn, "UPDATE admin SET nama = ?, password = ? WHERE id = ?");
  mysqli_stmt_bind_param($stmt, 'ssi', $nama, $hash, $id);
} else {
  $stmt = mysqli_prepare($conn, "UPDATE admin SET nama = ? WHERE id = ?");
  mysqli_stmt_bind_param($stmt, 'si', $nama, $id);
}

if (mysqli_stmt_execute($stmt)) {
  header('Location: /TugasAkhir/kelola_admin.php?status=edit_sukses');
} else {
  header('Location: /TugasAkhir/edit_admin.php?id='.$id.'&status=error');
}
exit;