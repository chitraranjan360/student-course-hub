<?php
$totalProgrammes = $totalProgrammes ?? 0;
$pageTitle = 'Dashboard';
include __DIR__ . '/header.php';
?>
<h1 class="mb-4">Dashboard</h1>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card text-white bg-primary shadow">
      <div class="card-body">
        <h2 class="h5">Total Programmes</h2>
        <p class="display-6 mb-0"><?= (int)$totalProgrammes ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-white bg-info shadow">
      <div class="card-body">
        <h2 class="h5">Total Staff</h2>
        <p class="display-6 mb-0"><?= (int)($totalStaff ?? 0) ?></p>
      </div>
    </div>
  </div>
</div>
<div class="mt-4 d-flex gap-2">
  <a href="<?= base_url('/admin/programmes') ?>" class="btn btn-outline-primary">Manage Programmes</a>
  <a href="<?= base_url('/admin/modules') ?>"    class="btn btn-outline-secondary">Manage Modules</a>
  <a href="<?= base_url('/admin/staff') ?>"      class="btn btn-outline-info">Manage Staff</a>
</div>
<?php include __DIR__ . '/footer.php'; ?>
