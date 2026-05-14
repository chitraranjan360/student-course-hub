<?php
$success = $success ?? false;
$pageTitle = 'Withdraw Interest';
include __DIR__ . '/../layout/header.php';
?>
<section class="py-5">
  <div class="container text-center" style="max-width:480px">
    <?php if ($success): ?>
      <div class="alert alert-success" role="alert">✅ Your interest registration has been removed successfully.</div>
    <?php else: ?>
      <div class="alert alert-warning" role="alert">⚠️ This withdrawal link is invalid or has already been used.</div>
    <?php endif; ?>
    <a href="<?= base_url('/') ?>" class="btn btn-primary">Back to Programmes</a>
  </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
