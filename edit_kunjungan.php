<?php
include 'config/database.php';
include 'includes/header.php';

$kode = $_GET['kode'] ?? '';

if (!$kode) {
  header('Location: /TugasAkhir/kunjungan.php');
  exit;
}

$stmt = mysqli_prepare($conn, "SELECT k.*, r.nomor_rumah, r.nama_pemilik
  FROM kunjungan k
  JOIN rumah r ON k.rumah_id = r.id
  WHERE k.kode_kunjungan = ?");
mysqli_stmt_bind_param($stmt, 's', $kode);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
  header('Location: /TugasAkhir/kunjungan.php');
  exit;
}

$query_rumah = mysqli_query($conn, "SELECT * FROM rumah ORDER BY nomor_rumah ASC");
?>

<p class="page-title">Edit Data Kunjungan</p>
<p class="page-subtitle">Waktu tidak dapat diubah</p>

<?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
  <div class="alert alert-success">✅ Data berhasil diperbarui!</div>
<?php endif; ?>

<div class="card">
  <h3>Edit Kunjungan — <span style="color:#00b8a6"><?php echo $data['kode_kunjungan']; ?></span></h3>

  <form action="/TugasAkhir/proses/update_kunjungan.php" method="POST">
    <input type="hidden" name="kode" value="<?php echo $data['kode_kunjungan']; ?>">

    <div class="form-group">
      <label>Waktu Masuk</label>
      <input type="text" value="<?php echo date('d/m/Y H:i:s', strtotime($data['waktu_masuk'])); ?>" 
        disabled style="background:#f8f9fa; color:#888;">
    </div>

    <div class="form-group">
      <label>Waktu Keluar</label>
      <input type="text" 
        value="<?php echo $data['waktu_keluar'] ? date('d/m/Y H:i:s', strtotime($data['waktu_keluar'])) : '— Belum keluar —'; ?>" 
        disabled style="background:#f8f9fa; color:#888;">
    </div>

    <div style="height:1px; background:#eee; margin:16px 0"></div>

    <div class="form-group">
      <label>Nama Tamu</label>
      <input type="text" name="nama_tamu" 
        value="<?php echo $data['nama_tamu']; ?>" required>
    </div>

    <div class="form-group">
      <label>Rumah Tujuan</label>
      <select name="rumah_id" required>
        <?php while ($rumah = mysqli_fetch_assoc($query_rumah)): ?>
          <option value="<?php echo $rumah['id']; ?>"
            <?php echo $rumah['id'] == $data['rumah_id'] ? 'selected' : ''; ?>>
            No. <?php echo $rumah['nomor_rumah']; ?> — <?php echo $rumah['nama_pemilik']; ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Keperluan</label>
      <input type="text" name="keperluan" 
        value="<?php echo $data['keperluan']; ?>" required>
    </div>

    <div class="form-group">
      <label>No. Kendaraan </label>
      <input type="text" name="no_kendaraan" id="no_kendaraan"
        value="<?php echo $data['no_kendaraan']; ?>"
        style="text-transform:uppercase" required>
      <small id="plat-error" style="color:red; display:none">
        Format plat tidak valid. Contoh: L 1234 AB
      </small>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="/TugasAkhir/kunjungan.php" class="btn btn-secondary" style="margin-left:10px">Batal</a>

  </form>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
  const plat = document.getElementById('no_kendaraan').value.trim().toUpperCase();
  const error = document.getElementById('plat-error');
  const regex = /^[A-Z]{1,2}\s\d{1,4}\s[A-Z]{1,3}$/;

  if (!regex.test(plat)) {
    error.style.display = 'block';
    e.preventDefault();
  } else {
    error.style.display = 'none';
  }
});

document.getElementById('no_kendaraan').addEventListener('input', function() {
  this.value = this.value.toUpperCase();
});
</script>

<?php include 'includes/footer.php'; ?>