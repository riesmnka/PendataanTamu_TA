<?php
include 'config/database.php';
include 'includes/header.php';

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
  header('Location: /TugasAkhir/kelola_admin.php');
  exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);

if (!$admin) {
  header('Location: /TugasAkhir/kelola_admin.php');
  exit;
}
?>

<p class="page-title">Edit Admin</p>
<p class="page-subtitle">Ubah nama lengkap dan password<strong><?php echo $admin['username']; ?></strong></p>

<?php if (isset($_GET['status'])): ?>
  <?php if ($_GET['status'] == 'sukses'): ?>
    <div class="alert alert-success">✅ Berhasil diperbarui!</div>
  <?php elseif ($_GET['status'] == 'error'): ?>
    <div class="alert alert-error">❌ Gagal. Periksa kembali data yang diisi.</div>
  <?php elseif ($_GET['status'] == 'pw_salah'): ?>
    <div class="alert alert-error">❌ Password lama tidak sesuai!</div>
  <?php endif; ?>
<?php endif; ?>

<div class="card" style="max-width:480px">
  <h3>Form Edit Admin</h3>
  <form action="/TugasAkhir/proses/update_admin.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">

    <div class="form-group">
      <label>Nama</label>
      <input type="text" name="nama" value="<?php echo $admin['nama']; ?>" required>
    </div>

    <div class="form-group">
      <label>Username</label>
      <input type="text" value="<?php echo $admin['username']; ?>" disabled
        style="background:#f8f9fa; color:#888;">
      <small style="color:#aaa">Tidak dapat diubah</small>
    </div>

    <hr style="border:none; border-top:1px solid #eee; margin:20px 0">
    <p style="font-size:13px; color:#888; margin-bottom:16px">
      Opsional
    </p>

    <div class="form-group">
      <label>Password Lama</label>
      <div style="position:relative">
        <input type="password" name="password_lama" id="pw-lama"
          placeholder="Isi jika ingin ganti password..."
          style="padding-right:45px">
        <button type="button" onclick="togglePW('pw-lama','btn-lama')"
          style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
          background:none;border:none;cursor:pointer;font-size:16px;color:#888"
          id="btn-lama">🙉</button>
      </div>
    </div>

    <div class="form-group">
      <label>Password Baru</label>
      <div style="position:relative">
        <input type="password" name="password_baru" id="pw-baru"
          placeholder="Minimal 6 karakter..."
          style="padding-right:45px">
        <button type="button" onclick="togglePW('pw-baru','btn-baru')"
          style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
          background:none;border:none;cursor:pointer;font-size:16px;color:#888"
          id="btn-baru">🙉</button>
      </div>
    </div>

    <div class="form-group">
      <label>Konfirmasi Password Baru</label>
      <div style="position:relative">
        <input type="password" name="password_konfirm" id="pw-konfirm"
          placeholder="Ulangi password baru..."
          style="padding-right:45px">
        <button type="button" onclick="togglePW('pw-konfirm','btn-konfirm')"
          style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
          background:none;border:none;cursor:pointer;font-size:16px;color:#888"
          id="btn-konfirm">🙉</button>
      </div>
      <small id="pw-error" style="color:red;display:none">Password baru tidak cocok!</small>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="/TugasAkhir/kelola_admin.php" class="btn btn-secondary" style="margin-left:10px">Batal</a>

  </form>
</div>

<script>
function togglePW(inputId, btnId) {
  const input = document.getElementById(inputId);
  const btn = document.getElementById(btnId);
  if (input.type === 'password') {
    input.type = 'text';
    btn.textContent = '🙈';
  } else {
    input.type = 'password';
    btn.textContent = '🙉';
  }
}

document.querySelector('form').addEventListener('submit', function(e) {
  const pwBaru = document.getElementById('pw-baru').value;
  const pwKonfirm = document.getElementById('pw-konfirm').value;
  const error = document.getElementById('pw-error');

  if (pwBaru && pwBaru !== pwKonfirm) {
    error.style.display = 'block';
    e.preventDefault();
  } else {
    error.style.display = 'none';
  }
});
</script>

<?php include 'includes/footer.php'; ?>