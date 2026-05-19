<?php
include 'config/database.php';

$kode = $_GET['kode'] ?? '';

if (!$kode) {
  header('Location: /TugasAkhir/input_tamu.php');
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
  header('Location: /TugasAkhir/input_tamu.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cetak Barcode</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 30px;
      gap: 30px;
    }

    .struk {
      width: 302px;
      background: #fff;
      padding: 20px 16px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 13px;
    }

    .struk-header {
      text-align: center;
      border-bottom: 1px dashed #ccc;
      padding-bottom: 12px;
      margin-bottom: 12px;
    }

    .struk-header h2 { font-size: 15px; font-weight: bold; }
    .struk-header p  { font-size: 11px; color: #666; margin-top: 2px; }

    .struk-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 6px;
      font-size: 12px;
    }

    .struk-row .key { color: #666; }
    .struk-row .val { font-weight: 500; text-align: right; max-width: 60%; }

    .struk-divider {
      border: none;
      border-top: 1px dashed #ccc;
      margin: 12px 0;
    }

    .barcode-area {
      text-align: center;
      margin: 14px 0;
    }

    .barcode-area svg { max-width: 100%; }

    .kode-text {
      font-size: 16px;
      font-weight: bold;
      letter-spacing: 4px;
      text-align: center;
      margin-top: 6px;
    }

    .struk-footer {
      text-align: center;
      font-size: 10px;
      color: #999;
      margin-top: 12px;
      border-top: 1px dashed #ccc;
      padding-top: 10px;
    }

    .panel {
      background: #fff;
      border-radius: 8px;
      padding: 24px;
      width: 280px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.08);
      height: fit-content;
    }

    .panel h3 { font-size: 15px; margin-bottom: 16px; }

    .btn {
      display: block;
      width: 100%;
      padding: 11px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      border: none;
      text-align: center;
      text-decoration: none;
      margin-bottom: 10px;
    }

    .btn-primary   { background: #00b8a6; color: #fff; }
    .btn-secondary { background: #6c757d; color: #fff; }

    @media print {
      body { background: #fff; padding: 0; display: block; }
      .panel { display: none; }
      .struk { border: none; width: 100%; }
    }
  </style>
</head>
<body>

<div class="struk">
  <div class="struk-header">
    <h2>POS KEAMANAN</h2>
    <p>Perumahan Griya Asri</p>
    <p>— TANDA MASUK —</p>
  </div>

  <div class="struk-row">
    <span class="key">Kode Kunjungan</span>
    <span class="val"><?php echo $data['kode_kunjungan']; ?></span>
  </div>
  <div class="struk-row">
    <span class="key">Tanggal</span>
    <span class="val"><?php echo date('d/m/Y', strtotime($data['waktu_masuk'])); ?></span>
  </div>
  <div class="struk-row">
    <span class="key">Jam Masuk</span>
    <span class="val"><?php echo date('H:i', strtotime($data['waktu_masuk'])); ?></span>
  </div>
  <div class="struk-row">
    <span class="key">No. Kendaraan</span>
    <span class="val"><?php echo $data['no_kendaraan']; ?></span>
  </div>

  <hr class="struk-divider">

  <div class="barcode-area">
    <svg id="barcode"></svg>
    <div class="kode-text"><?php echo $data['kode_kunjungan']; ?></div>
  </div>

  <hr class="struk-divider">

  <div class="struk-footer">
    Tunjukkan barcode ini saat keluar<br>
    Berlaku hanya untuk 1x kunjungan
  </div>
</div>

<div class="panel">
  <h3>🖨️ Cetak Struk Masuk</h3>
  <p style="font-size:13px; color:#888; margin-bottom:16px">
    Berikan struk ini kepada tamu. Akan digunakan saat keluar.
  </p>
  <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak Sekarang</button>
  <a href="/TugasAkhir/input_tamu.php" class="btn btn-secondary">+ Input Tamu Baru</a>
  <a href="/TugasAkhir/index.php" class="btn btn-secondary">← Dashboard</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
<script>
  JsBarcode("#barcode", "<?php echo $data['kode_kunjungan']; ?>", {
    format: "CODE128",
    width: 2,
    height: 60,
    displayValue: false
  });
</script>

</body>
</html>