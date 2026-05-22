<?php
include 'config/database.php';
include 'includes/header.php';

$query = mysqli_query($conn, "SELECT k.*, r.nomor_rumah, r.nama_pemilik
  FROM kunjungan k
  JOIN rumah r ON k.rumah_id = r.id
  ORDER BY k.waktu_masuk DESC");
?>

<p class="page-title">Data Kunjungan</p>
<p class="page-subtitle">Riwayat semua kunjungan tamu</p>

<?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
  <div class="alert alert-success">✅ Berhasil diperbarui!</div>
<?php endif; ?>

<div class="card">
  <table>
    <thead>
      <tr>
        <th>Kode</th>
        <th>Nama Tamu</th>
        <th>Rumah Tujuan</th>
        <th>Keperluan</th>
        <th>No. Kendaraan</th>
        <th>Jam Masuk</th>
        <th>Jam Keluar</th>
        <th>Status</th>
        <th>Kelola</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($query) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <tr id="row-<?php echo $row['kode_kunjungan']; ?>"
          class="<?php echo (isset($_GET['edited']) && $_GET['edited'] == $row['kode_kunjungan']) ? 'row-highlighted' : ''; ?>">
          <td><strong><?php echo $row['kode_kunjungan']; ?></strong></td>
          <td><?php echo $row['nama_tamu']; ?></td>
          <td>No. <?php echo $row['nomor_rumah']; ?> — <?php echo $row['nama_pemilik']; ?></td>
          <td><?php echo $row['keperluan']; ?></td>
          <td><?php echo $row['no_kendaraan']; ?></td>
          <td><?php echo date('d/m/Y H:i', strtotime($row['waktu_masuk'])); ?></td>
          <td>
            <?php echo $row['waktu_keluar']
              ? date('d/m/Y H:i', strtotime($row['waktu_keluar']))
              : '—'; ?>
          </td>
          <td>
            <?php if ($row['status'] == 'active'): ?>
              <span class="badge badge-active">Aktif</span>
            <?php else: ?>
              <span class="badge badge-done">Selesai</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="/TugasAkhir/edit_kunjungan.php?kode=<?php echo $row['kode_kunjungan']; ?>"
              class="btn btn-sm" style="background:#007bff;color:#fff">Edit</a>
            <a href="/TugasAkhir/proses/hapus_kunjungan.php?kode=<?php echo $row['kode_kunjungan']; ?>"
              class="btn btn-danger btn-sm"
              onclick="return confirm('Hapus data kunjungan ini?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" style="text-align:center; color:#888; padding:30px">
            Belum ada data kunjungan
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
const highlighted = document.querySelector('.row-highlighted');
if (highlighted) {
  highlighted.scrollIntoView({behavior: 'smooth', block: 'center'});
  setTimeout(() => {
    highlighted.style.transition = 'background 2s';
    highlighted.style.background = '';
  }, 3000);
}
</script>

<?php include 'includes/footer.php'; ?>