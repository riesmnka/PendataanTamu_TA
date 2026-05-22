<?php
include 'config/database.php';
include 'includes/header.php';
?>

<p class="page-title">Scan Barcode</p>
<p class="page-subtitle">Scan sebagai bukti keluar</p>

<?php if (isset($_GET['status'])): ?>
  <?php if ($_GET['status'] == 'tidak_ditemukan'): ?>
    <div class="alert alert-error">❌ Kode tidak ditemukan. Pastikan barcode terbaca dengan benar.</div>
  <?php elseif ($_GET['status'] == 'sudah_keluar'): ?>
    <div class="alert alert-error">⚠️ Sudah tercatat keluar.</div>
  <?php endif; ?>
<?php endif; ?>

<div class="card">
  <h3>Input Kode Barcode</h3>
  <p style="font-size:13px; color:#888; margin-bottom:16px">
    Scan barcode atau ketik kode kunjungan secara manual.
  </p>
  <form action="/TugasAkhir/proses/checkout.php" method="POST">
    <div class="form-group">
      <label>Kode Kunjungan</label>
      <input type="text" name="kode" id="kode-input"
        placeholder="Contoh: T-00001"
        style="font-size:20px; letter-spacing:4px; text-align:center; font-weight:bold;"
        autocomplete="off" autofocus required>
      <small style="color:#aaa">Kursor otomatis aktif di sini — langsung scan barcodenya</small>
    </div>
    <button type="submit" class="btn btn-primary">Proses Keluar</button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>