<?php
$prog = $prog ?? ['id' => 0, 'title' => ''];
$interests = $interests ?? [];
$pageTitle = 'Interests';
include __DIR__ . '/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Interests – <?= htmlspecialchars($prog['title'] ?? '', ENT_QUOTES) ?></h1>
  <a href="<?= base_url('/admin/interests/' . $prog['id'] . '/export') ?>" class="btn btn-success">Export CSV</a>
</div>

<?php if (empty($interests)): ?>
  <p class="text-muted">No registrations yet.</p>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-bordered bg-white">
      <thead class="table-dark"><tr><th>Name</th><th>Email</th><th>Registered</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach ($interests as $i): ?>
          <tr>
            <td><?= htmlspecialchars($i['first_name'] . ' ' . $i['last_name'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($i['email'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($i['registered_at'], ENT_QUOTES) ?></td>
            <td>
              <form method="POST" action="<?= base_url('/admin/interests/' . $i['id'] . '/delete') ?>" class="delete-form">
                <button class="btn btn-sm btn-danger" aria-label="Remove <?= htmlspecialchars($i['email'], ENT_QUOTES) ?>">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
<a href="<?= base_url('/admin/programmes') ?>" class="btn btn-outline-secondary mt-2">← Back</a>
<?php include __DIR__ . '/footer.php'; ?>
