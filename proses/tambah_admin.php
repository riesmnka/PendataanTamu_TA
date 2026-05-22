<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /TugasAkhir/kelola_admin.php');
  exit;
}

$nama     = trim($_POST['nama']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (!$nama || !$username || !$password || strlen($password) < 6) {
  header('Location: /TugasAkhir/kelola_admin.php?status=error');
  exit;
}

$cek = mysqli_prepare($conn, "SELECT id FROM admin WHERE username = ?");
mysqli_stmt_bind_param($cek, 's', $username);
mysqli_stmt_execute($cek);
mysqli_stmt_store_result($cek);

if (mysqli_stmt_num_rows($cek) > 0) {
  header('Location: /TugasAkhir/kelola_admin.php?status=duplikat');
  exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, "INSERT INTO admin (nama, username, password) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sss', $nama, $username, $hash);

if (mysqli_stmt_execute($stmt)) {
  header('Location: /TugasAkhir/kelola_admin.php?status=tambah');
} else {
  header('Location: /TugasAkhir/kelola_admin.php?status=error');
}
exit;