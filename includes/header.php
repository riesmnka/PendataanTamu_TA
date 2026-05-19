<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
  header('Location: /TugasAkhir/login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pendataan Tamu</title>
    <link rel="stylesheet" href="/TugasAkhir/assets/style.css">
</head>
<body>

<div class="wrapper">

    <aside class="sidebar">
        <div class="logo">
            <h2>Sistem Pendataan Tamu</h2>
            <span>Pos Keamanan</span>
        </div>
        <nav>
            <a href="/TugasAkhir/index.php">Dashboard</a>
            <a href="/TugasAkhir/input_tamu.php">Data Tamu</a>
            <a href="/TugasAkhir/scan.php">Scan Barcode</a>
            <a href="/TugasAkhir/kunjungan.php">Data Kunjungan</a>
            <a href="/TugasAkhir/master_rumah.php">Data Rumah</a>
            <a href="/TugasAkhir/laporan.php">Laporan</a>
        </nav>
        <div class="sidebar-user">
            <div class="user-info">
                <span class="user-name">👤 <?php echo $_SESSION['admin_nama']; ?></span>
                <span class="user-time">Login: <?php echo $_SESSION['admin_login_time']; ?></span>
            </div>
            <a href="/TugasAkhir/logout.php" class="btn-logout">Logout</a>
        </div>
    </aside>

    <main class="main-content">