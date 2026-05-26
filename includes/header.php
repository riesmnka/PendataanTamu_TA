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
    <script src="/TugasAkhir/assets/script.js"></script>
</head>
<body>

<div class="wrapper">

    <aside class="sidebar">
        <div class="logo">
            <h2>Perumahan Graha Family</h2>
            <span>Pos Keamanan</span>
        </div>
        <nav>
    <?php
    $current = basename($_SERVER['PHP_SELF']);
    ?>
        <nav>
            <a href="/TugasAkhir/index.php" 
                class="<?php echo $current == 'index.php' ? 'active' : ''; ?>">Home</a>
            <a href="/TugasAkhir/input_tamu.php" 
                class="<?php echo $current == 'input_tamu.php' ? 'active' : ''; ?>">Data Tamu</a>
            <a href="/TugasAkhir/scan.php" 
                class="<?php echo $current == 'scan.php' ? 'active' : ''; ?>">Scan Barcode</a>
            <a href="/TugasAkhir/kunjungan.php" 
                class="<?php echo ($current == 'kunjungan.php' || $current == 'edit_kunjungan.php') ? 'active' : ''; ?>">Data Kunjungan</a>
            <a href="/TugasAkhir/master_rumah.php" 
                class="<?php echo $current == 'master_rumah.php' ? 'active' : ''; ?>">Data Rumah</a>
            <a href="/TugasAkhir/laporan.php" 
                class="<?php echo $current == 'laporan.php' ? 'active' : ''; ?>">Laporan</a>
            <a href="/TugasAkhir/kelola_admin.php" 
                class="<?php echo $current == 'kelola_admin.php' ? 'active' : ''; ?>">Kelola Admin</a>
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