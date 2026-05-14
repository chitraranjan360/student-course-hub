<?php
$module = $module ?? null;
$pageTitle = $module ? 'Edit Module' : 'New Module';
$action    = $module ? base_url('/admin/modules/' . $module['id']) : base_url('/admin/modules');
include __DIR__ . '/header.php';
?>
<h1 class="h3 mb-4"><?= $module ? 'Edit Module' : 'New Module' ?></h1>
<div class="card shadow-sm" style="max-width:600px">
  <div class="card-body">
    <form method="POST" action="<?= $action ?>">
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
        <label for="year_of_study" class="form-label">Year of Study</label>
        <select id="year_of_study" name="year_of_study" class="form-select">
          <?php for ($y = 1; $y <= 4; $y++): ?>
            <option value="<?= $y ?>" <?= ($module['year_of_study'] ?? 1) == $y ? 'selected' : '' ?>>Year <?= $y ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= base_url('/admin/modules') ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
