<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | UniHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
  <?php
    $loginCss = __DIR__ . '/../../public/css/admin-login.css';
    $loginVer = is_file($loginCss) ? filemtime($loginCss) : time();
  ?>
  <link rel="stylesheet" href="<?= base_url('/css/admin-login.css') ?>?v=<?= $loginVer ?>">
</head>
<body class="admin-login">
<div class="login-wrapper d-flex justify-content-center align-items-center border-solid rounded shadow">
  <div class="login-panel">
    <div class="brand text-center mb-3">
      <img src="<?= base_url('/uploads/logo.png') ?>" alt="UniHub" class="brand-logo mb-2" onerror="this.style.display='none'">
      <h1 class="h4 mb-0">UniHub Admin</h1>
      <p class="text-muted small">Secure administrator access</p>
    </div>
    <div class="card shadow login-card">
      <div class="card-body p-4">
      <?php $error = $error ?? null; if ($error): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
      <?php endif; ?>
      <form method="POST" action="<?= base_url('/admin/login') ?>">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input id="username" type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
      </form>
    </div>
      <div class="text-center py-3">
        <p class="text-muted small mb-0">Are you a staff member? <a href="<?= base_url('/staff/login') ?>">Staff login</a></p>
        <p class="text-muted small mb-0"><a href="<?= base_url('/') ?>">Back to home</a></p>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
