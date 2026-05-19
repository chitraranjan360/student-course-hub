<?php
$interests = $interests ?? [];
$flash = $flash ?? [];
$pageTitle = 'All Interest Registrations';
include __DIR__ . '/header.php';
?>

<section class="interest-shell mb-4">
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3">
    <div>
      <h1 class="h3 mb-1">Interest Registrations</h1>
      <p class="interest-subtext mb-0">Target a programme and send one email to all students registered for interest.</p>
    </div>
    <span class="badge text-bg-light px-3 py-2">Total Registrations: <?= count($interests) ?></span>
  </div>
</section>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success auto-dismiss"><?= htmlspecialchars($flash['success'], ENT_QUOTES) ?></div>
<?php endif; ?>
<?php if (!empty($flash['error'])): ?>
  <div class="alert alert-danger auto-dismiss"><?= htmlspecialchars($flash['error'], ENT_QUOTES) ?></div>
<?php endif; ?>

<?php if (empty($interests)): ?>
  <p class="text-muted">No registrations yet.</p>
<?php else: ?>
  <?php
  $groupedInterests = [];
  $programmeMeta = [];
  foreach ($interests as $interestRow) {
      $programmeTitle = $interestRow['programme_title'] ?? 'N/A';
      $programmeId = (int)($interestRow['programme_id'] ?? 0);
      $groupedInterests[$programmeTitle][] = $interestRow;
      if ($programmeId > 0 && !isset($programmeMeta[$programmeId])) {
          $programmeMeta[$programmeId] = ['title' => $programmeTitle, 'count' => 0];
      }
      if ($programmeId > 0) {
          $programmeMeta[$programmeId]['count']++;
      }
  }

  $defaultProgrammeId = array_key_first($programmeMeta);
  $defaultProgrammeTitle = $defaultProgrammeId ? $programmeMeta[$defaultProgrammeId]['title'] : 'Programme';
  $defaultSubject = 'Update for ' . $defaultProgrammeTitle . ' Applicants';
  $defaultBody = "Dear student,\n\nThis is an update regarding " . $defaultProgrammeTitle . ".\n\nRegards,\nAdmin Team";
  ?>

  <section class="card bulk-mail-card mb-4">
    <div class="card-body p-4">
      <h2 class="h5 mb-3">Bulk Email by Programme</h2>
      <form method="POST" action="<?= base_url('/admin/interests/send-programme') ?>">
        <div class="row g-3 align-items-end">
          <div class="col-lg-4">
            <label for="programmeTarget" class="form-label fw-semibold">Select target programme</label>
            <select id="programmeTarget" name="programme_id" class="form-select" required>
              <?php foreach ($programmeMeta as $programmeId => $meta): ?>
                <option value="<?= (int)$programmeId ?>" <?= $defaultProgrammeId === $programmeId ? 'selected' : '' ?>>
                  <?= htmlspecialchars($meta['title'], ENT_QUOTES) ?> (<?= (int)$meta['count'] ?> students)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-4">
            <label for="bulkSubject" class="form-label fw-semibold">Email subject</label>
            <input id="bulkSubject" name="subject" type="text" class="form-control" value="<?= htmlspecialchars($defaultSubject, ENT_QUOTES) ?>" required>
          </div>
          <div class="col-lg-4 d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Send Email to Selected Programme</button>
          </div>
        </div>
        <div class="mt-3">
          <label for="bulkBody" class="form-label fw-semibold">Email message</label>
          <textarea id="bulkBody" name="body" rows="5" class="form-control" required><?= htmlspecialchars($defaultBody, ENT_QUOTES) ?></textarea>
        </div>
      </form>
    </div>
  </section>

  <?php foreach ($groupedInterests as $programmeTitle => $programmeInterests): ?>
    <?php $programmeIdForSection = (int)($programmeInterests[0]['programme_id'] ?? 0); ?>
    <section class="card programme-block mb-4">
      <div class="programme-head d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
        <div class="d-flex flex-wrap align-items-center gap-2">
          <h2 class="h5 mb-0"><?= htmlspecialchars($programmeTitle, ENT_QUOTES) ?></h2>
          <span class="badge rounded-pill"><?= count($programmeInterests) ?> registered</span>
        </div>
        
      </div>
      <div class="table-responsive">
        <table class="table interest-table table-bordered bg-white align-middle mb-0">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Registered Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($programmeInterests as $i): ?>
              <tr>
                <td><?= htmlspecialchars($i['first_name'] . ' ' . $i['last_name'], ENT_QUOTES) ?></td>
                <td>
                  <span class="email-chip"><?= htmlspecialchars($i['email'], ENT_QUOTES) ?></span>
                </td>
                <td><?= htmlspecialchars($i['registered_at'], ENT_QUOTES) ?></td>
                <td>
                  <div class="d-flex gap-2">
                    <?php
                      $rowSubject = rawurlencode('Update for ' . ($i['programme_title'] ?? 'Programme'));
                      $rowBody = rawurlencode("Dear " . ($i['first_name'] ?? 'student') . ",\n\nThis is an update regarding your interest registration.\n\nRegards,\nAdmin Team");
                    ?>
                    <button type="button"
                      class="btn btn-sm btn-primary compose-btn"
                      data-id="<?= (int)$i['id'] ?>"
                      data-email="<?= htmlspecialchars($i['email'], ENT_QUOTES) ?>"
                      data-name="<?= htmlspecialchars($i['first_name'], ENT_QUOTES) ?>"
                      data-subject="<?= $rowSubject ?>"
                      data-body="<?= $rowBody ?>"
                    >Compose</button>
                    <form method="POST" action="<?= base_url('/admin/interests/' . $i['id'] . '/delete') ?>" class="delete-form">
                      <button class="btn btn-sm btn-danger" aria-label="Remove <?= htmlspecialchars($i['email'], ENT_QUOTES) ?>">Remove</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  <?php endforeach; ?>
<?php endif; ?>

<!-- Compose modal -->
<div class="modal fade" id="composeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="composeForm" method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title">Compose Email</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="_target_id" id="composeTargetId" value="">
          <div class="mb-3">
            <label class="form-label">To</label>
            <input type="text" id="composeTo" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" id="composeSubject" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="body" id="composeBody" class="form-control" rows="8" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="composeSendBtn">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function () {
    const composeBtns = document.querySelectorAll('.compose-btn');
    const composeModalEl = document.getElementById('composeModal');
    if (!composeModalEl) return;
    const composeForm = document.getElementById('composeForm');
    const composeTo = document.getElementById('composeTo');
    const composeSubject = document.getElementById('composeSubject');
    const composeBody = document.getElementById('composeBody');
    const composeTargetId = document.getElementById('composeTargetId');

    function initAndShow(btn) {
      const id = btn.getAttribute('data-id');
      const email = btn.getAttribute('data-email');
      const name = btn.getAttribute('data-name');
      const subject = decodeURIComponent(btn.getAttribute('data-subject') || '');
      const body = decodeURIComponent(btn.getAttribute('data-body') || '');

      composeTargetId.value = id;
      composeTo.value = name + ' <' + email + '>';
      composeSubject.value = subject;
      composeBody.value = body.replace(/\\n/g, '\n');

      composeForm.setAttribute('action', '<?= base_url('/admin/interests/') ?>' + id + '/send-mail');

      const composeModal = new bootstrap.Modal(composeModalEl);
      composeModal.show();
    }

    window.addEventListener('load', function () {
      composeBtns.forEach(btn => {
        btn.addEventListener('click', () => initAndShow(btn));
      });
    });
  })();
</script>

<?php include __DIR__ . '/footer.php'; ?>
