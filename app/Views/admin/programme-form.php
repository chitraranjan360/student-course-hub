<?php
$prog = $prog ?? null;
$pageTitle = $prog ? 'Edit Programme' : 'New Programme';
$action    = $prog ? base_url('/admin/programmes/' . $prog['id']) : base_url('/admin/programmes');
include __DIR__ . '/header.php';
?>
<h1 class="h3 mb-4"><?= $prog ? 'Edit Programme' : 'New Programme' ?></h1>
<div class="card shadow-sm" style="max-width:640px">
  <div class="card-body">
    <form method="POST" action="<?= $action ?>" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input id="title" type="text" name="title" class="form-control" required
               value="<?= htmlspecialchars($prog['title'] ?? '', ENT_QUOTES) ?>">
      </div>
      <div class="mb-3">
        <label for="level" class="form-label">Level</label>
        <select id="level" name="level" class="form-select" required>
          <option value="Undergraduate" <?= ($prog['level'] ?? '') === 'Undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
          <option value="Postgraduate"  <?= ($prog['level'] ?? '') === 'Postgraduate'  ? 'selected' : '' ?>>Postgraduate</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" class="form-control" rows="4" required><?= htmlspecialchars($prog['description'] ?? '', ENT_QUOTES) ?></textarea>
      </div>
      <div class="mb-3">
        <label for="image_url" class="form-label">Image URL (optional)</label>
        <input id="image_url" type="url" name="image_url" class="form-control"
               value="<?= htmlspecialchars($prog['image_url'] ?? '', ENT_QUOTES) ?>">
      </div>
      <?php if (!empty($prog['image_url'])): ?>
        <div class="mb-3">
          <label class="form-label">Current image</label>
          <div>
            <?php
              $img = $prog['image_url'] ?? '';
              $src = preg_match('#^https?://#i', $img) ? $img : base_url('/' . ltrim($img, '/'));
            ?>
            <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>" alt="Current image" style="max-width:220px;max-height:120px;object-fit:cover;border-radius:.375rem">
          </div>
        </div>
      <?php endif; ?>
      <div class="mb-3">
        <label for="image" class="form-label">Upload image (jpg, png, webp)</label>
        <input id="image" type="file" name="image" accept="image/png,image/jpeg,image/webp" class="form-control">
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1"
               <?= !empty($prog['is_published']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="is_published">Published</label>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= base_url('/admin/programmes') ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
