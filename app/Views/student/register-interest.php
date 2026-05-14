<?php
$prog = $prog ?? ['id' => 0, 'title' => ''];
$success = $success ?? false;
$errors = $errors ?? [];
$pageTitle = 'Register Interest';
include __DIR__ . '/../layout/header.php';
?>

<section class="py-5">
  <a href="<?= base_url('/programmes/' . (int)$prog['id']) ?>" class="btn btn-outline-secondary mt-2">← Back</a>
  <div class="container" style="max-width:560px">
    <h1 class="mb-1">Register Your Interest</h1>
    <p class="text-muted mb-4">Programme: <strong><?= htmlspecialchars($prog['title'] ?? '', ENT_QUOTES) ?></strong></p>

    <?php if ($success): ?>
      <div class="alert alert-success" role="alert" aria-live="polite">
        ✅ Thank you! We've registered your interest. Check your email for a withdrawal link.
      </div>
    <?php else: ?>
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert" aria-live="polite">
          <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e, ENT_QUOTES) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= base_url('/interest') ?>" novalidate id="interestForm">
        <input type="hidden" name="programme_id" value="<?= (int)$prog['id'] ?>">

        <div class="mb-3">
          <label for="first_name" class="form-label">First Name</label>
          <input id="first_name" type="text" name="first_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="last_name" class="form-label">Last Name</label>
          <input id="last_name" type="text" name="last_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input id="email" type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register Interest</button>
      </form>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
