<?php
$pageTitle = 'Staff Dashboard';
$staff = $staff ?? [];
$modules = $modules ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'Staff', ENT_QUOTES) ?> | UniHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
</head>
<body class="bg-light">

<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<nav class="navbar navbar-dark bg-dark" role="navigation" aria-label="Staff navigation">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url('/staff') ?>">👨‍🏫 Staff Portal</a>
    <div class="d-flex gap-3 align-items-center">
      <span class="text-white small">Welcome, <strong><?= htmlspecialchars($_SESSION['staff_name'] ?? 'Staff') ?></strong></span>
      <a href="<?= base_url('/staff/logout') ?>" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<main id="main-content" class="container py-4">
  <h1 class="mb-4">Dashboard</h1>

  <div class="row g-3">
    <!-- Staff Info Card -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Staff Information</h5>
          <table class="table table-sm mb-0">
            <tr>
              <th>Name:</th>
              <td><strong><?= htmlspecialchars($staff['full_name'] ?? 'N/A', ENT_QUOTES) ?></strong></td>
            </tr>
            <tr>
              <th>Email:</th>
              <td><?= htmlspecialchars($staff['email'] ?? 'N/A', ENT_QUOTES) ?></td>
            </tr>
            <tr>
              <th>Role:</th>
              <td>
                <span class="badge" style="background-color: <?= 
                  ($staff['role'] ?? 'instructor') === 'instructor' ? '#0d6efd' :
                  (($staff['role'] ?? '') === 'coordinator' ? '#198754' : '#dc3545')
                ?>">
                  <?= ucfirst($staff['role'] ?? 'instructor') ?>
                </span>
              </td>
            </tr>
            <tr>
              <th>Member Since:</th>
              <td><?= date('F j, Y', strtotime($staff['created_at'] ?? 'now')) ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <!-- Module Count Card -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h2 class="h5 text-muted">Assigned Modules</h2>
          <p class="display-5 mb-0"><?= count($modules) ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modules Section -->
  <div class="mt-4">
    <h2 class="h4 mb-3">My Modules</h2>
    
    <?php if (empty($modules)): ?>
      <div class="alert alert-info">
        You don't have any modules assigned yet. Please contact the admin.
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($modules as $module): ?>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($module['title'] ?? 'Untitled', ENT_QUOTES) ?></h5>
                <p class="card-text text-muted small">
                  <?= htmlspecialchars(substr($module['description'] ?? '', 0, 100), ENT_QUOTES) ?>...
                </p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="badge bg-secondary">Year <?= $module['year_of_study'] ?? 1 ?></span>
                  <a href="#" class="btn btn-sm btn-outline-primary">View Details</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
