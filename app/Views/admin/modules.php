<?php
$modules = $modules ?? [];
$pageTitle = 'Modules';
include __DIR__ . '/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Modules</h1>
  <a href="<?= base_url('/admin/modules/create') ?>" class="btn btn-primary">+ New Module</a>
</div>
<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success auto-dismiss" role="alert" aria-live="polite">
    <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
  </div>
<?php endif; ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover bg-white">
    <thead class="table-dark"><tr><th>Title</th><th>Year</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($modules as $m): ?>
        <tr>
          <td><?= htmlspecialchars($m['title'], ENT_QUOTES) ?></td>
          <td>Year <?= (int)$m['year_of_study'] ?></td>
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
</div>
<?php include __DIR__ . '/footer.php'; ?>
