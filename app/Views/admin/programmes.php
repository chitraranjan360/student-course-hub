<?php
$programmes = $programmes ?? [];
$pageTitle = 'Programmes';
include __DIR__ . '/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Programmes</h1>
  <a href="<?= base_url('/admin/programmes/create') ?>" class="btn btn-primary">+ New Programme</a>
</div>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible auto-dismiss" role="alert" aria-live="polite">
    <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="table-responsive">
  <table class="table table-bordered table-hover bg-white">
    <thead class="table-dark">
      <tr>
        <th>Title</th><th>Level</th><th>Published</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($programmes as $p): ?>
        <tr id="prog-row-<?= $p['id'] ?>">
          <td><?= htmlspecialchars($p['title'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($p['level'], ENT_QUOTES) ?></td>
          <td>
            <button class="btn btn-sm <?= $p['is_published'] ? 'btn-success' : 'btn-secondary' ?> publish-toggle"
                    data-id="<?= $p['id'] ?>"
                    aria-label="<?= $p['is_published'] ? 'Unpublish' : 'Publish' ?> <?= htmlspecialchars($p['title'], ENT_QUOTES) ?>">
              <?= $p['is_published'] ? 'Published' : 'Draft' ?>
            </button>
          </td>
          <td class="d-flex gap-1 flex-wrap">
            <a href="<?= base_url('/admin/interests/' . $p['id']) ?>" class="btn btn-sm btn-info">Interests</a>
            <a href="<?= base_url('/admin/programmes/' . $p['id'] . '/edit') ?>" class="btn btn-sm btn-warning">Edit</a>
            <form method="POST" action="<?= base_url('/admin/programmes/' . $p['id'] . '/delete') ?>" class="delete-form">
              <button type="submit" class="btn btn-sm btn-danger"
                      aria-label="Delete <?= htmlspecialchars($p['title'], ENT_QUOTES) ?>">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/footer.php'; ?>
