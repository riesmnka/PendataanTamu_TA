<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

include 'config/database.php';

$dari   = $_GET['dari'] ?? date('Y-m-01');
$sampai = $_GET['sampai'] ?? date('Y-m-d');

if (isset($_GET['export'])) {

  $query_export = mysqli_query($conn, "SELECT k.*, r.nomor_rumah, r.nama_pemilik
    FROM kunjungan k
    JOIN rumah r ON k.rumah_id = r.id
    WHERE DATE(k.waktu_masuk) BETWEEN '$dari' AND '$sampai'
    ORDER BY k.waktu_masuk DESC");

  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  $sheet->setTitle('Laporan Kunjungan');

  $headers = ['Kode', 'Nama Tamu', 'Rumah Tujuan', 'Keperluan', 'No. Kendaraan', 'Jam Masuk', 'Jam Keluar', 'Status'];
  $sheet->fromArray($headers, NULL, 'A1');

  $sheet->getStyle('A1:H1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00b8a6']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
  ]);

  $row_num = 2;
  while ($row = mysqli_fetch_assoc($query_export)) {
    $sheet->fromArray([
      $row['kode_kunjungan'],
      $row['nama_tamu'],
      'No. '.$row['nomor_rumah'].' - '.$row['nama_pemilik'],
      $row['keperluan'],
      $row['no_kendaraan'],
      $row['waktu_masuk'] ? date('d/m/Y H:i', strtotime($row['waktu_masuk'])) : '',
      $row['waktu_keluar'] ? date('d/m/Y H:i', strtotime($row['waktu_keluar'])) : '',
      $row['status'] == 'active' ? 'Aktif' : 'Selesai'
    ], NULL, 'A'.$row_num);
    $row_num++;
  }

  foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
  }

  $filename = 'laporan_kunjungan_'.$dari.'_'.$sampai.'.xlsx';
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="'.$filename.'"');
  header('Cache-Control: max-age=0');

  $writer = new Xlsx($spreadsheet);
  $writer->save('php://output');
  exit;
}

include 'includes/header.php';

$query = mysqli_query($conn, "SELECT k.*, r.nomor_rumah, r.nama_pemilik
  FROM kunjungan k
  JOIN rumah r ON k.rumah_id = r.id
  WHERE DATE(k.waktu_masuk) BETWEEN '$dari' AND '$sampai'
  ORDER BY k.waktu_masuk DESC");

$total    = mysqli_num_rows($query);
$q_aktif  = mysqli_query($conn, "SELECT COUNT(*) as total FROM kunjungan WHERE status='active' AND DATE(waktu_masuk) BETWEEN '$dari' AND '$sampai'");
$q_selesai= mysqli_query($conn, "SELECT COUNT(*) as total FROM kunjungan WHERE status='done' AND DATE(waktu_masuk) BETWEEN '$dari' AND '$sampai'");
$aktif    = mysqli_fetch_assoc($q_aktif)['total'];
$selesai  = mysqli_fetch_assoc($q_selesai)['total'];
?>

<p class="page-title">Laporan Kunjungan</p>
<p class="page-subtitle">Rekap data kunjungan tamu untuk keperluan administrasi</p>

<div class="card">
  <h3>Filter Tanggal</h3>
  <form method="GET" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap">
    <div class="form-group" style="margin:0">
      <label>Dari Tanggal</label>
      <input type="date" name="dari" value="<?php echo $dari; ?>" style="width:auto">
    </div>
    <div class="form-group" style="margin:0">
      <label>Sampai Tanggal</label>
      <input type="date" name="sampai" value="<?php echo $sampai; ?>" style="width:auto">
    </div>
    <button type="submit" class="btn btn-primary">Tampilkan</button>
    <a href="/TugasAkhir/laporan.php" class="btn btn-secondary">Reset</a>
  </form>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr)">
  <div class="stat-card cyan">
    <div class="label">Total Kunjungan</div>
    <div class="value"><?php echo $total; ?></div>
  </div>
  <div class="stat-card green">
    <div class="label">Selesai</div>
    <div class="value"><?php echo $selesai; ?></div>
  </div>
  <div class="stat-card orange">
    <div class="label">Masih Aktif</div>
    <div class="value"><?php echo $aktif; ?></div>
  </div>
</div>

<div class="card">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px">
    <h3 style="margin:0">Data Kunjungan</h3>
    <a href="/TugasAkhir/laporan.php?dari=<?php echo $dari; ?>&sampai=<?php echo $sampai; ?>&export=1"
        class="btn btn-secondary">📥 Export Excel</a>
  </div>
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
      </tr>
    </thead>
    <tbody>
      <?php if ($total > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <tr>
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
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" style="text-align:center; color:#888; padding:30px">
            Tidak ada data untuk rentang tanggal ini
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>