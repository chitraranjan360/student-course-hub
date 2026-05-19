<?php
$programmes = $programmes ?? [];
$pageTitle = 'Programmes';
include __DIR__ . '/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Programmes</h1>
  <a href="<?= base_url('/admin/programmes/create') ?>" class="btn btn-primary">+ New Programme</a>
</div>

<div class="row mb-3">
  <div class="col-md-6 col-lg-5">
    <label for="programmeSearch" class="form-label">Search Programmes</label>
    <input type="text" id="programmeSearch" class="form-control" placeholder="Search by title..." autocomplete="off">
  </div>
</div>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible auto-dismiss" role="alert" aria-live="polite">
    <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="table-responsive">
  <table class="table table-bordered table-hover bg-white" id="programmesTable">
    <thead class="table-dark">
      <tr>
        <th>Title</th><th>Level</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($programmes as $p): ?>
        <tr id="prog-row-<?= $p['id'] ?>" class="programme-row">
          <td>
            <a href="<?= base_url('/admin/programmes/' . $p['id']) ?>" class="prog-link"><?= htmlspecialchars($p['title'], ENT_QUOTES) ?></a>
          </td>

          <td>
            <?= htmlspecialchars($p['level'], ENT_QUOTES) ?>
          </td>
          <td>
            <select class="form-select form-select-sm status-select" data-id="<?= $p['id'] ?>">
              <option value="publish" <?= $p['is_published'] ? 'selected' : '' ?>>Published</option>
              <option value="draft" <?= !$p['is_published'] ? 'selected' : '' ?>>Draft</option>
            </select>
          </td>
          <td class="d-flex gap-1 flex-wrap">
            <a href="<?= base_url('/admin/programmes/' . $p['id']) ?>" class="btn btn-sm btn-info">View</a>
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
  <div id="noProgrammeResults" class="alert alert-info mt-2 d-none">No matching programmes found.</div>
</div>
<script id="admin-programmes-js"
        src="<?= base_url('/js/admin-programmes.js') ?>"
        data-publish-url-base="<?= base_url('/admin/programmes') ?>"></script>
<?php include __DIR__ . '/footer.php'; ?>