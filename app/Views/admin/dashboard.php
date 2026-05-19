<?php
$totalProgrammes = $totalProgrammes ?? 0;
$pageTitle = 'Dashboard';
// Ensure we have programs array and compute total students if not provided
$programs = $programs ?? [];
$totalStudents = $totalStudents ?? null;
if ($totalStudents === null) {
  $computed = 0;
  foreach ($programs as $p) {
    if (is_array($p)) {
      $computed += (int)($p['count'] ?? $p['students'] ?? 0);
    } elseif (is_object($p)) {
      $computed += (int)($p->count ?? $p->students ?? 0);
    }
  }
  $totalStudents = $computed;
}
include __DIR__ . '/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4 prog-hero">
  <div>
    <h1 class="mb-0">Dashboard</h1>
    <small>Overview & quick actions</small>
  </div>
</div>

<div class="row g-3 mb-4 dashboard-stats">
  <div class="col-sm-6 col-md-3">
    <div class="card stat-card shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon bg-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-grid-3x3-gap-fill" viewBox="0 0 16 16">
            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1H4a1 1 0 0 1 1 1v1.5A1.5 1.5 0 0 1 4.5 5H3a1 1 0 0 1-1-1V2.5zM6.5 1A1.5 1.5 0 0 1 8 2.5V4a1 1 0 0 1-1 1H5.5A1.5 1.5 0 0 1 4 3.5V2A1.5 1.5 0 0 1 5.5 1H6.5zM12.5 1A1.5 1.5 0 0 1 14 2.5V4a1 1 0 0 1-1 1h-1.5A1.5 1.5 0 0 1 10 3.5V2A1.5 1.5 0 0 1 11.5 1H12.5zM1 6.5A1.5 1.5 0 0 1 2.5 5H4a1 1 0 0 1 1 1v1.5A1.5 1.5 0 0 1 4.5 9H3a1 1 0 0 1-1-1V6.5zM6.5 5A1.5 1.5 0 0 1 8 6.5V8a1 1 0 0 1-1 1H5.5A1.5 1.5 0 0 1 4 7.5V6A1.5 1.5 0 0 1 5.5 5H6.5z"/>
          </svg>
        </div>
        <div>
          <h6 class="mb-0">Total Programmes</h6>
          <p class="h4 mb-0"><?= (int)$totalProgrammes ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-3">
    <div class="card stat-card shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon bg-info">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
            <path d="M13 7c0 1.105-.672 2-1.5 2S10 8.105 10 7s.672-2 1.5-2S13 5.895 13 7zM6.5 8A2.5 2.5 0 1 0 6.5 3a2.5 2.5 0 0 0 0 5z"/>
          </svg>
        </div>
        <div>
          <h6 class="mb-0">Total Staff</h6>
          <p class="h4 mb-0"><?= (int)($totalStaff ?? 0) ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-3">
    <div class="card stat-card shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon bg-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-mortarboard-fill" viewBox="0 0 16 16">
            <path d="M8.211.045a.5.5 0 0 0-.422 0L1.11 2.84a.5.5 0 0 0 0 .92l6.679 2.865a.5.5 0 0 0 .422 0l6.679-2.865a.5.5 0 0 0 0-.92L8.211.045zM4.5 10.5v1.5a3 3 0 0 0 6 0V10.5"/>
          </svg>
        </div>
        <div>
          <h6 class="mb-0">Total Students</h6>
          <p class="h4 mb-0"><?= (int)($totalStudents ?? 0) ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-3">
    <div class="card stat-card shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon bg-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-journal-bookmark-fill" viewBox="0 0 16 16">
            <path d="M6 8V1h1v7l-1-1z"/>
            <path d="M2 2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v12l-3-1-3 1-3-1V2z"/>
          </svg>
        </div>
        <div>
          <h6 class="mb-0"> Total Modules</h6>
          <p class="h4 mb-0"><?= (int)($totalModules ?? 0) ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Students by Program -->
<?php if (!empty($programs)): ?>
  <div class="mb-4">
    <h5 class="mb-3">Students by Program</h5>
    <div class="row g-3">
      <?php foreach ($programs as $prog):
        if (is_array($prog)) {
          $pName = htmlspecialchars($prog['name'] ?? $prog['title'] ?? 'Unnamed', ENT_QUOTES);
          $pCount = (int)($prog['count'] ?? $prog['students'] ?? 0);
          $pId = $prog['id'] ?? null;
        } else {
          $pName = htmlspecialchars($prog->name ?? $prog->title ?? 'Unnamed', ENT_QUOTES);
          $pCount = (int)($prog->count ?? $prog->students ?? 0);
          $pId = $prog->id ?? null;
        }
      ?>
        <div class="col-sm-6 col-md-4">
          <div class="card stat-card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1"><?= $pName ?></h6>
                <p class="mb-0 fw-bold"><?= $pCount ?> students</p>
              </div>
              <div class="text-end">
                <a href="<?= $pId ? base_url('/admin/interests/' . $pId) : base_url('/admin/interests') ?>" class="btn btn-sm btn-outline-primary">View</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Recent Activity</h5>
        <div class="recent-activity mt-3">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">No recent activity to show.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card quick-actions">
      <div class="card-body">
        <h5 class="card-title">Quick Actions</h5>
        <div class="mt-3 d-grid gap-2">
          <a href="<?= base_url('/admin/programmes/create') ?>?action=add" class="btn btn-outline-primary">+ Add Programme</a>
          <a href="<?= base_url('/admin/staff/create') ?>?action=add" class="btn btn-outline-info">+ Add Staff</a>
          <a href="<?= base_url('/admin/modules/create') ?>?action=add" class="btn btn-outline-success">+ Add Module</a>
          
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
