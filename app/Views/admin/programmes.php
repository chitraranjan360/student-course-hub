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
        <th>Title</th><th>Level</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($programmes as $p): ?>
        <tr id="prog-row-<?= $p['id'] ?>">
          <td><?= htmlspecialchars($p['title'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($p['level'], ENT_QUOTES) ?></td>
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
</div>
<?php include __DIR__ . '/footer.php'; ?>
  <script>
  function showFlash(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.table-responsive');
    container.parentNode.insertBefore(alertDiv, container);
    
    // Auto dismiss after 3 seconds
    setTimeout(() => {
      alertDiv.remove();
    }, 3000);
  }

  document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', async (e) => {
      const id = e.target.dataset.id;
      const status = e.target.value;
      const previousStatus = status === 'publish' ? 'draft' : 'publish';
      
      try {
        const response = await fetch('<?= base_url('/admin/programmes') ?>/' + id + '/publish', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'status=' + encodeURIComponent(status)
        });
        
        if (!response.ok) {
          showFlash('Failed to update status', 'danger');
          e.target.value = previousStatus;
        } else {
          const statusText = status === 'publish' ? 'Published' : 'Draft';
          showFlash(`Status updated to ${statusText}`);
        }
      } catch (error) {
        console.error('Error:', error);
        showFlash('Error updating status', 'danger');
        e.target.value = previousStatus;
      }
    });
  });
  </script>