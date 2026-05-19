<?php
include 'config/database.php';
include 'includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM rumah ORDER BY nomor_rumah ASC");
?>

<p class="page-title">Data Rumah</p>
<p class="page-subtitle">Kelola daftar rumah dan pemilik di kompleks perumahan</p>

<?php if (isset($_GET['status'])): ?>
  <?php if ($_GET['status'] == 'sukses'): ?>
    <div class="alert alert-success">✅ Data rumah berhasil disimpan!</div>
  <?php elseif ($_GET['status'] == 'error'): ?>
    <div class="alert alert-error">❌ Gagal menyimpan. Pastikan semua field terisi.</div>
  <?php elseif ($_GET['status'] == 'hapus'): ?>
    <div class="alert alert-success">✅ Data rumah berhasil dihapus!</div>
  <?php endif; ?>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:start">

  <!-- Tabel daftar rumah -->
  <div class="card">
    <h3>Daftar Rumah</h3>
    <table>
      <thead>
        <tr>
          <th>No. Rumah</th>
          <th>Nama Pemilik</th>
          <th>Blok</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($query) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($query)): ?>
          <tr>
            <td><strong><?php echo $row['nomor_rumah']; ?></strong></td>
            <td><?php echo $row['nama_pemilik']; ?></td>
            <td><?php echo $row['blok'] ?: '—'; ?></td>
            <td>
              <a href="/TugasAkhir/proses/hapus_rumah.php?id=<?php echo $row['id']; ?>"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Hapus rumah No. <?php echo $row['nomor_rumah']; ?>?')">
                Hapus
              </a>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" style="text-align:center; color:#888; padding:30px">
              Belum ada data rumah
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Form tambah rumah -->
  <div class="card">
    <h3>Tambah Rumah</h3>
    <form action="/TugasAkhir/proses/simpan_rumah.php" method="POST">
      <div class="form-group">
        <label>Nomor Rumah *</label>
        <input type="text" name="nomor_rumah" placeholder="Contoh: 7, A5..." required>
      </div>
      <div class="form-group">
        <label>Nama Pemilik *</label>
        <input type="text" name="nama_pemilik" placeholder="Nama pemilik rumah" required>
      </div>
      <div class="form-group">
        <label>Blok — Opsional</label>
        <input type="text" name="blok" placeholder="Contoh: Blok A, Cluster B">
      </div>
      <button type="submit" class="btn btn-primary">Simpan Rumah</button>
    </form>
  </div>

</div>

<?php include 'includes/footer.php'; ?>