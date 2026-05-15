<?php
$staff = $staff ?? null;
$pageTitle = $staff ? 'Edit Staff' : 'Add Staff';
include __DIR__ . '/../header.php';

$errors = $errors ?? [];
?>

<h1 class="mb-4"><?= $staff ? 'Edit Staff Member' : 'Create New Staff' ?></h1>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
      <?php foreach ($errors as $field => $msg): ?>
        <li><?= htmlspecialchars($msg) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="POST" action="<?= $staff ? base_url('/admin/staff/' . $staff['id']) : base_url('/admin/staff') ?>" class="row g-3" style="max-width: 600px;">
  
  <!-- Username (read-only if editing) -->
  <div class="col-12">
    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
    <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
           id="username" name="username" value="<?= htmlspecialchars($staff['username'] ?? $_POST['username'] ?? '', ENT_QUOTES) ?>"
           <?= $staff ? 'readonly' : '' ?> required>
    <?php if (isset($errors['username'])): ?>
      <div class="invalid-feedback"><?= htmlspecialchars($errors['username']) ?></div>
    <?php endif; ?>
  </div>

  <!-- Full Name -->
  <div class="col-12">
    <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" 
           id="full_name" name="full_name" value="<?= htmlspecialchars($staff['full_name'] ?? $_POST['full_name'] ?? '', ENT_QUOTES) ?>" required>
    <?php if (isset($errors['full_name'])): ?>
      <div class="invalid-feedback"><?= htmlspecialchars($errors['full_name']) ?></div>
    <?php endif; ?>
  </div>

  <!-- Email -->
  <div class="col-12">
    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
           id="email" name="email" value="<?= htmlspecialchars($staff['email'] ?? $_POST['email'] ?? '', ENT_QUOTES) ?>" required>
    <?php if (isset($errors['email'])): ?>
      <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
    <?php endif; ?>
  </div>

  <!-- Password -->
  <div class="col-12">
    <label for="password" class="form-label">
      Password <?= !$staff ? '<span class="text-danger">*</span>' : '<small class="text-muted">(leave blank to keep current)</small>' ?>
    </label>
    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
           id="password" name="password" <?= !$staff ? 'required' : '' ?>>
    <?php if (isset($errors['password'])): ?>
      <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
    <?php endif; ?>
  </div>

  <!-- Confirm Password -->
  <div class="col-12">
    <label for="confirm_password" class="form-label">
      Confirm Password <?= !$staff ? '<span class="text-danger">*</span>' : '' ?>
    </label>
    <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
           id="confirm_password" name="confirm_password" <?= !$staff ? 'required' : '' ?>>
    <?php if (isset($errors['confirm_password'])): ?>
      <div class="invalid-feedback"><?= htmlspecialchars($errors['confirm_password']) ?></div>
    <?php endif; ?>
  </div>

  <!-- Status -->
  <div class="col-12">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
             <?= (empty($staff) || $staff['is_active'] ?? false) ? 'checked' : '' ?>>
      <label class="form-check-label" for="is_active">Active (Staff can login)</label>
    </div>
  </div>

  <!-- Submit -->
  <div class="col-12">
    <button type="submit" class="btn btn-primary">
      <?= $staff ? 'Update Staff' : 'Create Staff' ?>
    </button>
    <a href="<?= base_url('/admin/staff') ?>" class="btn btn-secondary">Cancel</a>
  </div>

</form>

<?php include __DIR__ . '/../footer.php'; ?>
