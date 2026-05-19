<?php
include 'config/database.php';
include 'includes/header.php';

$query_rumah = mysqli_query($conn, "SELECT * FROM rumah ORDER BY nomor_rumah ASC");
?>

<p class="page-title">Input Tamu Baru</p>
<p class="page-subtitle">Isi data tamu yang akan berkunjung</p>

<?php if (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
  <div class="alert alert-error">❌ Gagal menyimpan data. Pastikan semua field terisi.</div>
<?php endif; ?>

<div class="card">
  <h3>Form Data Tamu</h3>
  <form action="/TugasAkhir/proses/simpan_tamu.php" method="POST">

    <div class="form-group">
      <label>Tanggal & Jam Masuk</label>
      <input type="text" value="<?php echo date('d/m/Y H:i:s'); ?>" disabled
        style="background:#f8f9fa; color:#888;">
      <small style="color:#aaa">Otomatis tercatat saat data disimpan</small>
    </div>

    <div class="form-group">
      <label>Nama Lengkap Tamu *</label>
      <input type="text" name="nama_tamu" placeholder="Masukkan nama tamu..." required>
    </div>

    <div class="form-group">
      <label>Rumah Tujuan *</label>
      <select name="rumah_id" required>
        <option value="">— Pilih rumah tujuan —</option>
        <?php while ($rumah = mysqli_fetch_assoc($query_rumah)): ?>
          <option value="<?php echo $rumah['id']; ?>">
            No. <?php echo $rumah['nomor_rumah']; ?> — <?php echo $rumah['nama_pemilik']; ?>
            <?php if ($rumah['blok']) echo '(' . $rumah['blok'] . ')'; ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Keperluan *</label>
      <input type="text" name="keperluan" placeholder="Contoh: Kunjungan keluarga, antar barang..." required>
    </div>

    <div class="form-group">
      <label>No. Kendaraan *</label>
      <input type="text" name="no_kendaraan" placeholder="Contoh: L 1234 AB" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan & Cetak Barcode</button>
    <a href="/TugasAkhir/index.php" class="btn btn-secondary" style="margin-left:10px">Batal</a>

  </form>
</div>

<?php include 'includes/footer.php'; ?>