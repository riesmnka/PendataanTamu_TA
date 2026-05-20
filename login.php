<?php
session_start();

if (isset($_SESSION['admin_id'])) {
  header('Location: /TugasAkhir/index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require 'config/database.php';

  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  $stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE username = ?");
  mysqli_stmt_bind_param($stmt, 's', $username);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $admin = mysqli_fetch_assoc($result);

  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id']   = $admin['id'];
    $_SESSION['admin_nama'] = $admin['nama'];
    $_SESSION['admin_login_time'] = date('d/m/Y H:i');
    header('Location: /TugasAkhir/index.php');
    exit;
  } else {
    $error = 'Username atau password salah!';
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Sistem Pendataan Tamu</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      background: #fff;
      border-radius: 10px;
      padding: 40px;
      width: 380px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }

    .login-logo {
      text-align: center;
      margin-bottom: 28px;
    }

    .login-logo h1 {
      font-size: 26px;
      color: #00b8a6;
    }

    .login-logo p {
      font-size: 13px;
      color: #888;
      margin-top: 4px;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 6px;
      color: #555;
    }

    .form-group input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 14px;
      outline: none;
      transition: border 0.2s;
    }

    .form-group input:focus { border-color: #00b8a6; }

    .btn-login {
      width: 100%;
      padding: 12px;
      background: #00b8a6;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 15px;
      font-weight: 500;
      cursor: pointer;
      margin-top: 8px;
      transition: opacity 0.2s;
    }

    .btn-login:hover { opacity: 0.85; }

    .alert-error {
      background: #f8d7da;
      color: #721c24;
      padding: 10px 14px;
      border-radius: 6px;
      font-size: 13px;
      margin-bottom: 16px;
    }

    .login-footer {
      text-align: center;
      font-size: 11px;
      color: #aaa;
      margin-top: 24px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <div class="login-logo">
    <h1>SiGaTa</h1>
    <p>Sistem Pendataan Tamu — Pos Keamanan</p>
  </div>

  <?php if (isset($error)): ?>
    <div class="alert-error">❌ <?php echo $error; ?></div>
  <?php endif; ?>

<form method="POST">
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" placeholder="Masukkan username..." autofocus required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <div style="position:relative">
        <input type="password" name="password" id="password" 
          placeholder="Masukkan password..." required
          style="padding-right:45px">
        <button type="button" onclick="togglePassword()" 
          style="position:absolute; right:10px; top:50%; transform:translateY(-50%);
          background:none; border:none; cursor:pointer; font-size:16px; color:#888"
          id="btn-toggle">🙈</button>
      </div>
    </div>
    <button type="submit" class="btn-login">Masuk</button>
  </form>

  <div class="login-footer">
    Sistem Pendataan Tamu Berbasis Barcode
  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById('password');
  const btn = document.getElementById('btn-toggle');
  if (input.type === 'password') {
    input.type = 'text';
    btn.textContent = '🙉';
  } else {
    input.type = 'password';
    btn.textContent = '🙈';
  }
}
</script>

</body>
</html>