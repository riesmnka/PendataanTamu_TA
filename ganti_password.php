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

<p class="page-title">Ganti Password</p>
<p class="page-subtitle">Ganti password akun: <strong><?php echo $admin['nama']; ?></strong></p>

<?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
  <div class="alert alert-success">✅ Password berhasil diganti!</div>
<?php elseif (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
  <div class="alert alert-error">❌ Gagal. Pastikan semua field terisi minimal 6 karakter.</div>
<?php endif; ?>

<div class="card" style="max-width:440px">
  <h3>Form Ganti Password</h3>
  <form action="/TugasAkhir/proses/update_password.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">

    <div class="form-group">
      <label>Password Baru </label>
      <div style="position:relative">
        <input type="password" name="password_baru" id="pw1"
          placeholder="Minimal 6 karakter..." required
          style="padding-right:45px">
        <button type="button" onclick="togglePW('pw1','btn1')"
          style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
          background:none;border:none;cursor:pointer;font-size:16px;color:#888"
          id="btn1">🙉</button>
      </div>
    </div>

    <div class="form-group">
      <label>Konfirmasi Password Baru </label>
      <div style="position:relative">
        <input type="password" name="password_konfirm" id="pw2"
          placeholder="Ulangi password baru..." required
          style="padding-right:45px">
        <button type="button" onclick="togglePW('pw2','btn2')"
          style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
          background:none;border:none;cursor:pointer;font-size:16px;color:#888"
          id="btn2">🙉</button>
      </div>
      <small id="pw-error" style="color:red;display:none">Password tidak cocok!</small>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
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
  const pw1 = document.getElementById('pw1').value;
  const pw2 = document.getElementById('pw2').value;
  const error = document.getElementById('pw-error');

  if (pw1 !== pw2) {
    error.style.display = 'block';
    e.preventDefault();
  } else {
    error.style.display = 'none';
  }
});
</script>

<?php include 'includes/footer.php'; ?>