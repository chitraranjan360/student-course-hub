<?php
$pageTitle = 'Staff Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'Staff', ENT_QUOTES) ?> | UniHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
  <style>
    body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
    .login-container { max-width: 400px; width: 100%; }
    .card { box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); border: none; }
    .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; }
  </style>
</head>
<body>

<div class="login-container mx-auto">
  <div class="card">
    <div class="card-header py-3">
      <h4 class="mb-0">👨‍🏫 Staff Portal</h4>
    </div>
    <div class="card-body p-4">

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= base_url('/staff/login') ?>">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>
        <div class="mb-4">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
      </form>

      <hr class="my-3">
      
      <div class="text-center">
        <p class="text-muted small mb-0">Not a staff member? <a href="<?= base_url('/admin/login') ?>">Admin login</a></p>
        <p class="text-muted small"><a href="<?= base_url('/') ?>">Back to home</a></p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
