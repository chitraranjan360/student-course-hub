<?php
$pageTitle = 'Staff Management';
include __DIR__ . '/../header.php';
?>

<h1 class="mb-4">Staff Management</h1>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($flash['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="mb-3">
  <a href="<?= base_url('/admin/staff/create') ?>" class="btn btn-primary">+ Add New Staff</a>
</div>

<?php if (empty($staff)): ?>
  <div class="alert alert-info">No staff members found.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-hover">
      <thead class="table-light">
        <tr>
          <th>Full Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($staff as $s): ?>
          <tr>
            <td><strong><?= htmlspecialchars($s['full_name']) ?></strong></td>
            <td><code><?= htmlspecialchars($s['username']) ?></code></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td>
              <span class="badge" style="background-color: <?= 
                $s['role'] === 'instructor' ? '#0d6efd' :
                ($s['role'] === 'coordinator' ? '#198754' : '#dc3545')
              ?>">
                <?= ucfirst($s['role']) ?>
              </span>
            </td>
            <td>
              <span class="badge <?= $s['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                <?= $s['is_active'] ? 'Active' : 'Inactive' ?>
              </span>
            </td>
            <td><small><?= date('Y-m-d', strtotime($s['created_at'])) ?></small></td>
            <td>
              <a href="<?= base_url('/admin/staff/' . $s['id']) ?>" class="btn btn-sm btn-outline-info">View</a>
              <a href="<?= base_url('/admin/staff/' . $s['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <form method="POST" action="<?= base_url('/admin/staff/' . $s['id'] . '/delete') ?>" style="display:inline;" 
                    onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../footer.php'; ?>
