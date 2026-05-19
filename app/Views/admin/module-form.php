<?php
$module = $module ?? null;
$pageTitle = $module ? 'Edit Module' : 'New Module';
$action    = $module ? base_url('/admin/modules/' . $module['id']) : base_url('/admin/modules');
include __DIR__ . '/header.php';
?>
<h1 class="h3 mb-4"><?= $module ? 'Edit Module' : 'New Module' ?></h1>
<div class="card shadow-sm" style="max-width:600px">
  <div class="card-body">
    <form method="POST" action="<?= $action ?>" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="title" class="form-label">Module Title</label>
        <input id="title" type="text" name="title" class="form-control" required
               value="<?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?>">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3" required><?= htmlspecialchars($module['description'] ?? '', ENT_QUOTES) ?></textarea>
      </div>
      <div class="mb-3">
        <label for="photo" class="form-label">Module Photo</label>
        <?php if ($module && !empty($module['photo'])): ?>
          <div class="mb-2">
            <img src="<?= base_url('/uploads/modules/' . htmlspecialchars($module['photo'], ENT_QUOTES)) ?>" alt="<?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?>" style="max-height: 150px; border-radius: 8px;">
          </div>
        <?php endif; ?>
        <input id="photo" type="file" name="photo" accept="image/*" class="form-control">
        <small class="text-muted">Upload a new photo to replace the current one</small>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= base_url('/admin/modules') ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
