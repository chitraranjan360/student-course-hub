<?php
$programme = $programme ?? null;
$modulesByYear = $modulesByYear ?? [];
$assignedStaff = $assignedStaff ?? [];
$availableModules = $availableModules ?? [];
$interestCount = $interestCount ?? 0;
$flash = $flash ?? [];

$moduleCount = 0;
foreach ($modulesByYear as $yearModules) {
  $moduleCount += count($yearModules);
}

$pageTitle = $programme['title'] ?? 'Programme Details';
include __DIR__ . '/header.php';
?>

<?php if ($programme): ?>
  <!-- Hero Section -->
  <div class="prog-hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
      <div>
        <p class="text-uppercase text-muted small mb-2 ">Programme Overview</p>
        <h1 class="h2 mb-0"><?= htmlspecialchars($programme['title'] ?? 'Programme', ENT_QUOTES) ?></h1>
      </div>
    </div>
    <div class="prog-stats">
      
      <span class="stat-badge">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M14 0a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12zM2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z" />
        </svg>
        <strong><?= $moduleCount ?></strong> Modules
      </span>
      <span class="stat-badge">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
        </svg>
        <strong><?= count($assignedStaff) ?></strong> Staff
      </span>
      <span class="stat-badge">
        <strong><?= (int) $interestCount ?></strong> Interests
      </span>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if (!$programme): ?>
  <div class="alert alert-warning">Programme not found.</div>
<?php else: ?>
  <!-- Action Buttons -->
  <div class="prog-actions d-flex gap-2 flex-wrap">
    <a href="<?= base_url('/admin/programmes/' . $programme['id'] . '/edit') ?>" class="btn btn-warning">Edit Programme</a>
    <form method="POST" action="<?= base_url('/admin/programmes/' . $programme['id'] . '/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this programme?');">
      <button type="submit" class="btn btn-outline-danger">Delete Programme</button>
    </form>
    <a href="<?= base_url('/admin/programmes') ?>" class="btn btn-outline-secondary">Back to Programmes</a>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card h-100 shadow-sm">
        <?php if (!empty($programme['image_url'])): ?>
          <?php
          $img = $programme['image_url'] ?? '';
          $src = preg_match('#^https?://#i', $img) ? $img : base_url('/' . ltrim($img, '/'));
          ?>
          <figure class="mb-0 w-100 rounded-top overflow-hidden" style="height:280px;">
            <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($programme['title'] ?? '', ENT_QUOTES) ?>" class="w-100 h-100" style="object-fit:cover; display:block;">
          </figure>
        <?php else: ?>
          <div class="bg-light border rounded-top d-flex align-items-center justify-content-center w-100" style="height:280px;">
            <span class="text-muted">No Image</span>
          </div>
        <?php endif; ?>

        <div class="card-body">
          <dl class="row mb-0">
            <dt class="col-sm-5">Level:</dt>
            <dd class="col-sm-7"><?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?></dd>
            <dt class="col-sm-5">Status:</dt>
            <dd class="col-sm-7"><span class="badge <?= !empty($programme['is_published']) ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= !empty($programme['is_published']) ? 'Published' : 'Draft' ?></span></dd>
            <dt class="col-sm-5">Created:</dt>
            <dd class="col-sm-7"><small class="text-muted"><?= htmlspecialchars($programme['created_at'] ?? 'N/A', ENT_QUOTES) ?></small></dd>
          </dl>
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
      <?php $programmeYears = (($programme['level'] ?? '') === 'Undergraduate') ? [1, 2, 3] : [1]; ?>
      <h2 class="mt-5 mb-3">Modules by Year</h2>
      <div class="accordion" id="programmeModulesAccordion">
        <?php foreach ($programmeYears as $year): ?>
          <?php $yearModules = $modulesByYear[$year] ?? []; ?>
          <div class="accordion-item">
            <h3 class="accordion-header" id="heading-year<?= (int) $year ?>">
              <button class="accordion-button <?= $year > 1 ? 'collapsed' : '' ?>" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapse-year<?= (int) $year ?>"
                aria-expanded="<?= $year === 1 ? 'true' : 'false' ?>"
                aria-controls="collapse-year<?= (int) $year ?>">
                Year <?= (int) $year ?>
              </button>
            </h3>
            <div id="collapse-year<?= (int) $year ?>" class="accordion-collapse collapse <?= $year === 1 ? 'show' : '' ?>" aria-labelledby="heading-year<?= (int) $year ?>" data-bs-parent="#programmeModulesAccordion">
              <div class="accordion-body">
                <div class="accordion accordion-flush" id="modulesYear<?= (int) $year ?>">
                  <?php if (empty($yearModules)): ?>
                    <div class="alert alert-info mb-3">No modules have been assigned to Year <?= (int) $year ?> yet.</div>
                  <?php else: ?>
                    <?php foreach ($yearModules as $index => $module): ?>
                      <?php
                      $moduleId = 'year' . (int) $year . '-module-' . ($module['id'] ?? $index);
                      $moduleHeadingId = 'heading-' . $moduleId;
                      $moduleCollapseId = 'collapse-' . $moduleId;
                      ?>
                      <div class="accordion-item border rounded mb-3 overflow-hidden">
                        <h4 class="accordion-header" id="<?= $moduleHeadingId ?>">
                          <button class="accordion-button collapsed fw-semibold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#<?= $moduleCollapseId ?>"
                            aria-expanded="false" aria-controls="<?= $moduleCollapseId ?>">
                            <?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?>
                          </button>
                        </h4>
                        <div id="<?= $moduleCollapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $moduleHeadingId ?>" data-bs-parent="#modulesYear<?= (int) $year ?>">
                          <div class="accordion-body bg-body-tertiary">
                            <dd class="col-sm-12">
                              <?php if (!empty($module['photo'])): ?>
                                <figure class="mb-3 rounded shadow-sm overflow-hidden" style="height:180px;">
                                  <img src="<?= base_url('/uploads/' . htmlspecialchars($module['photo'], ENT_QUOTES)) ?>" alt="<?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?>" class="w-100 h-100" style="object-fit:cover; display:block;">
                                </figure>
                              <?php else: ?>
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center w-100" style="height:180px;">
                                  <span class="text-muted">No Image</span>
                                </div>
                              <?php endif; ?>
                            </dd>
                            <h5 class="h6 fw-bold mb-2"><?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?></h5>
                            <p class="mb-3 text-muted"><?= htmlspecialchars($module['description'] ?? '', ENT_QUOTES) ?></p>
                            <form method="POST" action="<?= base_url('/admin/programmes/' . $programme['id'] . '/unassign-module') ?>" class="m-0">
                              <input type="hidden" name="module_id" value="<?= (int) $module['id'] ?>">
                              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this module from the programme?')">Remove</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>

                <div class="card border-0 bg-light-subtle mt-3">
                  <div class="card-body">
                    <h3 class="h6 mb-3">Add module to Year <?= (int) $year ?></h3>
                    <?php if (empty($availableModules)): ?>
                      <div class="alert alert-info mb-0">All modules are already assigned to this programme.</div>
                    <?php else: ?>
                      <form method="POST" action="<?= base_url('/admin/programmes/' . $programme['id'] . '/assign-module') ?>" class="row g-3 align-items-end">
                        <input type="hidden" name="year_of_study" value="<?= (int) $year ?>">
                        <div class="col-md-8">
                          <label for="module_id_<?= (int) $year ?>" class="form-label">Select a module</label>
                          <select class="form-select" id="module_id_<?= (int) $year ?>" name="module_id" required>
                            <option value="">-- Choose module --</option>
                            <?php foreach ($availableModules as $module): ?>
                              <option value="<?= (int) $module['id'] ?>">
                                <?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-4 d-grid">
                          <button type="submit" class="btn btn-primary">Assign Module</button>
                        </div>
                      </form>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>