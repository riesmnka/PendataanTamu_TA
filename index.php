<?php
include 'config/database.php';
include 'includes/header.php';

$query_aktif = mysqli_query($conn, "SELECT COUNT(*) as total FROM kunjungan WHERE status = 'active'");
$aktif = mysqli_fetch_assoc($query_aktif)['total'];

$query_selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM kunjungan WHERE status = 'done' AND DATE(waktu_keluar) = CURDATE()");
$selesai = mysqli_fetch_assoc($query_selesai)['total'];

$query_bulan = mysqli_query($conn, "SELECT COUNT(*) as total FROM kunjungan WHERE MONTH(waktu_masuk) = MONTH(NOW()) AND YEAR(waktu_masuk) = YEAR(NOW())");
$bulan = mysqli_fetch_assoc($query_bulan)['total'];

$query_rumah = mysqli_query($conn, "SELECT COUNT(*) as total FROM rumah");
$jml_rumah = mysqli_fetch_assoc($query_rumah)['total'];
?>

<p class="page-title">Dashboard</p>
<p class="page-subtitle">Ringkasan pos keamanan hari ini</p>

<div class="stats-grid">
  <div class="stat-card cyan">
    <div class="label">Tamu Aktif</div>
    <div class="value"><?php echo $aktif; ?></div>
  </div>
  <div class="stat-card orange">
    <div class="label">Selesai Hari Ini</div>
    <div class="value"><?php echo $selesai; ?></div>
  </div>
  <div class="stat-card purple">
    <div class="label">Total Bulan Ini</div>
    <div class="value"><?php echo $bulan; ?></div>
  </div>
  <div class="stat-card green">
    <div class="label">Data Rumah</div>
    <div class="value"><?php echo $jml_rumah; ?></div>
  </div>
</div>

<div class="card">
  <h3>Kunjungan Terkini</h3>
  <table>
    <thead>
      <tr>
        <th>Kode</th>
        <th>Nama Tamu</th>
        <th>Tujuan</th>
        <th>Waktu Masuk</th>
        <th>Waktu Keluar</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query_kunjungan = mysqli_query($conn, "
        SELECT k.*, r.nomor_rumah, r.nama_pemilik 
        FROM kunjungan k
        JOIN rumah r ON k.rumah_id = r.id
        ORDER BY k.waktu_masuk DESC
        LIMIT 10
        ");

        if (mysqli_num_rows($query_kunjungan) > 0) {
          while ($row = mysqli_fetch_assoc($query_kunjungan)) {
      ?>
        <tr>
          <td><?php echo $row['kode_kunjungan']; ?></td>
          <td><?php echo $row['nama_tamu']; ?></td>
          <td>No. <?php echo $row['nomor_rumah']; ?> — <?php echo $row['nama_pemilik']; ?></td>
          <td><?php echo date('d/m/Y H:i', strtotime($row['waktu_masuk'])); ?></td>
          <td><?php echo $row['waktu_keluar'] ? date('d/m/Y H:i', strtotime($row['waktu_keluar'])) : '—'; ?></td>
          <td>
            <?php if ($row['status'] == 'active'): ?>
              <span class="badge badge-active">Aktif</span>
            <?php else: ?>
              <span class="badge badge-done">Selesai</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php
          }
        } else {
        ?>
          <tr>
            <td colspan="6" style="text-align: center; color: #888;">
              Belum ada data kunjungan
            </td>
          </tr>
        <?php } ?>
    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>