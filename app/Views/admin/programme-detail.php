<?php
$programme = $programme ?? null;
$modulesByYear = $modulesByYear ?? [];
$assignedStaff = $assignedStaff ?? [];
$availableModules = $availableModules ?? [];
$flash = $flash ?? [];

$moduleCount = 0;
foreach ($modulesByYear as $yearModules) {
    $moduleCount += count($yearModules);
}

$pageTitle = $programme['title'] ?? 'Programme Details';
include __DIR__ . '/header.php';
?>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <p class="text-uppercase text-muted small mb-1">Programme overview</p>
    <h1 class="h3 mb-1"><?= htmlspecialchars($programme['title'] ?? 'Programme', ENT_QUOTES) ?></h1>
    <div class="d-flex gap-2 flex-wrap align-items-center">
      <span class="badge text-bg-primary"><?= htmlspecialchars($programme['level'] ?? 'N/A', ENT_QUOTES) ?></span>
      <span class="badge <?= !empty($programme['is_published']) ? 'text-bg-success' : 'text-bg-secondary' ?>">
        <?= !empty($programme['is_published']) ? 'Published' : 'Draft' ?>
      </span>
      <span class="badge text-bg-dark"><?= $moduleCount ?> modules</span>
      <span class="badge text-bg-dark"><?= count($assignedStaff) ?> staff</span>
    </div>
  </div>
</div>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if (!$programme): ?>
  <div class="alert alert-warning">Programme not found.</div>
<?php else: ?>
  <div class="d-flex gap-2 flex-wrap mb-4">
    <a href="<?= base_url('/admin/programmes/' . $programme['id'] . '/edit') ?>" class="btn btn-warning">Edit Programme</a>
    <a href="<?= base_url('/admin/programmes') ?>" class="btn btn-outline-secondary">Back to Programmes</a>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h2 class="h5 mb-3">Programme Details</h2>
          <p class="mb-2"><strong>Title:</strong> <?= htmlspecialchars($programme['title'] ?? '', ENT_QUOTES) ?></p>
          <p class="mb-2"><strong>Level:</strong> <?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?></p>
          <p class="mb-2"><strong>Status:</strong> <?= !empty($programme['is_published']) ? 'Published' : 'Draft' ?></p>
          <p class="mb-0"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($programme['description'] ?? '', ENT_QUOTES)) ?></p>
        </div>
      </div>

      <div class="card shadow-sm mt-4">
        <div class="card-body">
          <h2 class="h5 mb-3">Assigned Staff</h2>
          <?php if (empty($assignedStaff)): ?>
            <div class="alert alert-info mb-0">No staff are assigned to this programme yet.</div>
          <?php else: ?>
            <ul class="list-group list-group-flush">
              <?php foreach ($assignedStaff as $staff): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                  <div>
                    <a href="<?= base_url('/admin/staff/' . $staff['id']) ?>" class="fw-semibold text-decoration-none">
                      <?= htmlspecialchars($staff['full_name'] ?? '', ENT_QUOTES) ?>
                    </a>
                    <div class="text-muted small"><?= htmlspecialchars($staff['email'] ?? '', ENT_QUOTES) ?></div>
                  </div>
                  <span class="badge text-bg-secondary"><?= htmlspecialchars(ucfirst($staff['role'] ?? 'instructor'), ENT_QUOTES) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h2 class="h5 mb-0">Assigned Modules</h2>
            <span class="text-muted small">Use the remove button beside each module to detach it from this programme.</span>
          </div>

          <?php if (empty($modulesByYear)): ?>
            <div class="alert alert-info mb-0">No modules have been assigned yet.</div>
          <?php else: ?>
            <div class="accordion" id="programmeModulesAccordion">
              <?php $index = 0; foreach ($modulesByYear as $year => $modules): $index++; ?>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="heading-<?= (int) $year ?>">
                    <button class="accordion-button <?= $index === 1 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= (int) $year ?>" aria-expanded="<?= $index === 1 ? 'true' : 'false' ?>" aria-controls="collapse-<?= (int) $year ?>">
                      Year <?= (int) $year ?> <span class="ms-2 text-muted">(<?= count($modules) ?> modules)</span>
                    </button>
                  </h2>
                  <div id="collapse-<?= (int) $year ?>" class="accordion-collapse collapse <?= $index === 1 ? 'show' : '' ?>" aria-labelledby="heading-<?= (int) $year ?>" data-bs-parent="#programmeModulesAccordion">
                    <div class="accordion-body p-0">
                      <ul class="list-group list-group-flush">
                        <?php foreach ($modules as $module): ?>
                          <li class="list-group-item d-flex justify-content-between align-items-start gap-3">
                            <div>
                              <div class="fw-semibold"><?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?></div>
                              <div class="text-muted small"><?= htmlspecialchars($module['description'] ?? '', ENT_QUOTES) ?></div>
                            </div>
                            <form method="POST" action="<?= base_url('/admin/programmes/' . $programme['id'] . '/unassign-module') ?>" class="flex-shrink-0 m-0">
                              <input type="hidden" name="module_id" value="<?= (int) $module['id'] ?>">
                              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this module from the programme?')">Remove</button>
                            </form>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <h2 class="h5 mb-3">Assign New Module</h2>
          <?php if (empty($availableModules)): ?>
            <div class="alert alert-info mb-0">All modules are already assigned to this programme.</div>
          <?php else: ?>
            <form method="POST" action="<?= base_url('/admin/programmes/' . $programme['id'] . '/assign-module') ?>" class="row g-3 align-items-end">
              <div class="col-md-9">
                <label for="module_id" class="form-label">Choose module</label>
                <select class="form-select" id="module_id" name="module_id" required>
                  <option value="">Select module</option>
                  <?php foreach ($availableModules as $module): ?>
                    <option value="<?= (int) $module['id'] ?>">
                      <?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?> (Year <?= (int) ($module['year_of_study'] ?? 1) ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Assign Module</button>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
