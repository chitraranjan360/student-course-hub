<?php
$modules = $modules ?? [];
$pageTitle = 'Modules';
include __DIR__ . '/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Modules</h1>
  <a href="<?= base_url('/admin/modules/create') ?>" class="btn btn-primary">+ New Module</a>
</div>
<div class="row mb-3">
  <div class="col-md-6 col-lg-5">
    <label for="moduleSearch" class="form-label">Search Modules</label>
    <input type="text" id="moduleSearch" class="form-control" placeholder="Search by title or description..." autocomplete="off">
  </div>
</div>
<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success auto-dismiss" role="alert" aria-live="polite">
    <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
  </div>
<?php endif; ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover bg-white" id="modulesTable">
    <thead class="table-dark">
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($modules as $m): ?>
        <tr class="module-row">
          <td>
            <a href="<?= base_url('/admin/modules/' . $m['id']) ?>" class="prog-link">
              <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>
            </a>
          </td>
          <td><small class="text-muted"><?= htmlspecialchars(substr($m['description'] ?? '', 0, 60) . (strlen($m['description'] ?? '') > 60 ? '...' : ''), ENT_QUOTES) ?></small></td>
          <td class="d-flex gap-1">
            <a href="<?= base_url('/admin/modules/' . $m['id'] . '/edit') ?>" class="btn btn-sm btn-warning">Edit</a>
            <form method="POST" action="<?= base_url('/admin/modules/' . $m['id'] . '/delete') ?>" class="delete-form">
              <button class="btn btn-sm btn-danger" aria-label="Delete <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div id="noModuleResults" class="alert alert-info mt-2 d-none">No matching modules found.</div>
</div>
<script src="<?= base_url('/js/admin-modules.js') ?>"></script>
<?php include __DIR__ . '/footer.php'; ?>