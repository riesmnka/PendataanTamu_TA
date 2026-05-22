<?php
include 'config/database.php';
include 'includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM admin ORDER BY created_at ASC");
?>

<p class="page-title">Kelola Admin</p>
<p class="page-subtitle">Mengubah akun dan password</p>

<?php if (isset($_GET['status'])): ?>
  <?php if ($_GET['status'] == 'tambah'): ?>
    <div class="alert alert-success">✅ Akun baru berhasil ditambahkan!</div>
  <?php elseif ($_GET['status'] == 'hapus'): ?>
    <div class="alert alert-success">✅ Akun berhasil dihapus!</div>
  <?php elseif ($_GET['status'] == 'edit_sukses'): ?>
    <div class="alert alert-success">✅ Berhasil diperbarui!</div>
  <?php elseif ($_GET['status'] == 'error'): ?>
    <div class="alert alert-error">❌ Gagal. Pastikan semua field terisi dengan benar.</div>
  <?php elseif ($_GET['status'] == 'duplikat'): ?>
    <div class="alert alert-error">❌ Username sudah digunakan, pilih username lain.</div>
  <?php endif; ?>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 340px; gap:24px; align-items:start">

  <div class="card">
    <h3>Daftar Akun</h3>
    <table>
      <thead>
        <tr>
          <th>Nama Petugas</th>
          <th>Username</th>
          <th>Dibuat</th>
          <th>Kelola</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <tr>
          <td><strong><?php echo $row['nama']; ?></strong></td>
          <td><?php echo $row['username']; ?></td>
          <td style="font-size:12px;color:#888">
            <?php echo date('d/m/Y', strtotime($row['created_at'])); ?>
          </td>
          <td>
            <div style="display:flex;gap:6px">
              <a href="/TugasAkhir/edit_admin.php?id=<?php echo $row['id']; ?>"
                class="btn btn-sm" style="background:#fd7e14;color:#fff">Edit</a>
              <?php if ($row['id'] != $_SESSION['admin_id']): ?>
                <a href="/TugasAkhir/proses/hapus_admin.php?id=<?php echo $row['id']; ?>"
                  class="btn btn-danger btn-sm"
                  onclick="return confirm('Hapus akun <?php echo $row['nama']; ?>?')">Hapus</a>
              <?php else: ?>
                <span style="font-size:12px;color:#aaa;padding:6px">Akun aktif</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h3>Tambah Akun Baru</h3>
    <form action="/TugasAkhir/proses/tambah_admin.php" method="POST">
      <div class="form-group">
        <label>Nama </label>
        <input type="text" name="nama" placeholder="Nama petugas..." required>
      </div>
      <div class="form-group">
        <label>Username </label>
        <input type="text" name="username" placeholder="Nama Akun..." required>
      </div>
      <div class="form-group">
        <label>Password </label>
        <div style="position:relative">
          <input type="password" name="password" id="pw-baru"
            placeholder="Minimal 6 karakter..." required
            style="padding-right:45px">
          <button type="button" onclick="togglePW('pw-baru','btn-pw-baru')"
            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
            background:none;border:none;cursor:pointer;font-size:16px;color:#888"
            id="btn-pw-baru">🙉</button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%">Tambah Akun</button>
    </form>
  </div>

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
</script>

<?php include 'includes/footer.php'; ?>