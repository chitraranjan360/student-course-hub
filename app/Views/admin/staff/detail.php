<?php
$staff = $staff ?? null;
$unassignedModules = $unassignedModules ?? [];
$unassignedProgrammes = $unassignedProgrammes ?? [];
$assignedModules = $assignedModules ?? [];
$assignedProgrammes = $assignedProgrammes ?? [];
$flash = $flash ?? [];

$assignedModuleIds = array_column($assignedModules, 'id');
$assignedProgrammeIds = array_column($assignedProgrammes, 'id');

$pageTitle = 'Staff Details';
include __DIR__ . '/../header.php';
?>

<style>
  .staff-hero{background:linear-gradient(135deg,#0b1220 0%, #12243a 60%);color:#fff;padding:2rem;border-radius:12px;margin-bottom:2rem;box-shadow:0 8px 24px rgba(2,6,23,0.3)}
  .staff-hero h1{font-weight:600;margin-bottom:.5rem}
  .staff-stats{display:flex;gap:1rem;flex-wrap:wrap;margin-top:1.5rem}
  .stat-badge{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1rem;background:rgba(255,255,255,0.12);border-radius:8px;font-size:.9rem;border:1px solid rgba(255,255,255,0.2)}
  .staff-actions{margin-bottom:1.5rem}
</style>

<?php if ($staff): ?>
  <!-- Hero Section -->
  <div class="staff-hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
      <div>
        <p class="text-uppercase text-muted small mb-2">Staff Member</p>
        <h1 class="h2 mb-0"><?= htmlspecialchars($staff['full_name'] ?? 'Staff', ENT_QUOTES) ?></h1>
        <p class="text-muted mb-0 mt-1"><?= htmlspecialchars($staff['email'] ?? '', ENT_QUOTES) ?></p>
      </div>
    </div>
    <div class="staff-stats">
      <span class="stat-badge">
        <strong><?= htmlspecialchars($staff['username'] ?? 'N/A', ENT_QUOTES) ?></strong> username
      </span>
      <span class="stat-badge">
        <?= !empty($staff['is_active']) ? '✓ Active' : '⊘ Inactive' ?>
      </span>
      <span class="stat-badge">
        Member since <?= !empty($staff['created_at']) ? date('Y', strtotime($staff['created_at'])) : 'N/A' ?>
      </span>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($flash['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if (!$staff): ?>
  <div class="alert alert-warning">Staff member not found.</div>
<?php else: ?>
  <!-- Action Buttons -->
  <div class="staff-actions d-flex gap-2 flex-wrap">
    <a href="<?= base_url('/admin/staff/' . $staff['id'] . '/edit') ?>" class="btn btn-warning">Edit Staff</a>
    <a href="<?= base_url('/admin/staff') ?>" class="btn btn-outline-secondary">Back to Staff</a>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h2 class="h5 mb-3">Profile Information</h2>
          <dl class="row mb-0">
            <dt class="col-sm-5">Username:</dt>
            <dd class="col-sm-7"><code><?= htmlspecialchars($staff['username'] ?? 'N/A', ENT_QUOTES) ?></code></dd>
            <dt class="col-sm-5">Status:</dt>
            <dd class="col-sm-7"><span class="badge <?= !empty($staff['is_active']) ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= !empty($staff['is_active']) ? 'Active' : 'Inactive' ?></span></dd>
            <dt class="col-sm-5">Joined:</dt>
            <dd class="col-sm-7"><small class="text-muted"><?= !empty($staff['created_at']) ? date('M d, Y', strtotime($staff['created_at'])) : 'N/A' ?></small></dd>
          </dl>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h2 class="h5 mb-3">Assigned Modules</h2>
          <?php if (empty($assignedModules)): ?>
            <div class="alert alert-info mb-0">No modules have been assigned yet.</div>
          <?php else: ?>
            <ul class="list-group list-group-flush">
              <?php foreach ($assignedModules as $module): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                  <div>
                    <strong><?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?></strong>
                  </div>
                  <form method="POST" action="<?= base_url('/admin/staff/' . $staff['id'] . '/unassign-module') ?>" class="m-0">
                    <input type="hidden" name="module_id" value="<?= (int) $module['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Unassign this module?')">Remove</button>
                  </form>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>

      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h2 class="h5 mb-3">Assigned Programmes</h2>
          <?php if (empty($assignedProgrammes)): ?>
            <div class="alert alert-info mb-0">No programmes have been assigned yet.</div>
          <?php else: ?>
            <ul class="list-group list-group-flush">
              <?php foreach ($assignedProgrammes as $programme): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                  <div>
                    <strong><?= htmlspecialchars($programme['title'] ?? '', ENT_QUOTES) ?></strong>
                    <div class="text-muted small"><?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?></div>
                  </div>
                  <form method="POST" action="<?= base_url('/admin/staff/' . $staff['id'] . '/unassign-programme') ?>" class="m-0">
                    <input type="hidden" name="programme_id" value="<?= (int) $programme['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Unassign this programme?')">Remove</button>
                  </form>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>

      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h2 class="h5 mb-3">Add Modules & Programmes</h2>

          <div class="accordion" id="assignAccordion">
            <!-- Assign Module Section -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingModule">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseModule" aria-expanded="true" aria-controls="collapseModule">
                  <strong>+ Assign Module</strong>
                </button>
              </h2>
              <div id="collapseModule" class="accordion-collapse collapse show" aria-labelledby="headingModule" data-bs-parent="#assignAccordion">
                <div class="accordion-body pt-4">
                  <form method="POST" action="<?= base_url('/admin/staff/' . $staff['id'] . '/assign-module') ?>">
                    <?php if (empty($unassignedModules)): ?>
                      <div class="alert alert-info mb-0">No modules are available to assign.</div>
                    <?php else: ?>
                      <div class="row g-3 mb-3">
                        <div class="col-md-6">
                          <label for="filter_module_programme_level" class="form-label">Level</label>
                          <select id="filter_module_programme_level" class="form-select form-select-sm">
                            <option value="">All levels</option>
                            <option value="Undergraduate">Undergraduate</option>
                            <option value="Postgraduate">Postgraduate</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label for="filter_module_programme_name" class="form-label">Programme</label>
                          <select id="filter_module_programme_name" class="form-select form-select-sm">
                            <option value="">Select programmes</option>
                            <?php
                              $seenProg = [];
                              foreach ($unassignedModules as $module):
                                $pid = $module['programme_id'] ?? null;
                                $ptitle = $module['programme_title'] ?? null;
                                $plevel = $module['programme_level'] ?? '';
                                if ($pid && $ptitle && !in_array($pid, $seenProg, true)) { $seenProg[] = $pid; ?>
                                  <option value="<?= (int) $pid ?>" data-level="<?= htmlspecialchars($plevel, ENT_QUOTES) ?>"><?= htmlspecialchars($ptitle, ENT_QUOTES) ?></option>
                            <?php }
                              endforeach;
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label for="module_id" class="form-label">Select Module</label>
                        <select class="form-select" id="module_id" name="module_id" required>
                          <option value="">-- Select a module --</option>
                          <?php foreach ($unassignedModules as $module): ?>
                            <option value="<?= (int) $module['id'] ?>" data-prog-id="<?= htmlspecialchars($module['programme_id'] ?? '', ENT_QUOTES) ?>" data-prog-level="<?= htmlspecialchars($module['programme_level'] ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?><?php if (!empty($module['programme_title'])) echo ' — ' . htmlspecialchars($module['programme_title'], ENT_QUOTES); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-primary">Assign Module</button>
                    <?php endif; ?>
                  </form>
                </div>
              </div>
            </div>

            <!-- Assign Programme Section -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingProgramme">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProgramme" aria-expanded="false" aria-controls="collapseProgramme">
                  <strong>+ Assign Programme</strong>
                </button>
              </h2>
              <div id="collapseProgramme" class="accordion-collapse collapse" aria-labelledby="headingProgramme" data-bs-parent="#assignAccordion">
                <div class="accordion-body pt-4">
                  <form method="POST" action="<?= base_url('/admin/staff/' . $staff['id'] . '/assign-programme') ?>">
                    <?php if (empty($unassignedProgrammes)): ?>
                      <div class="alert alert-info mb-0">No programmes are available to assign.</div>
                    <?php else: ?>
                      <div class="row g-3 mb-3">
                        <div class="col-md-6">
                          <label for="filter_programme_level" class="form-label">Filter by Level</label>
                          <select id="filter_programme_level" class="form-select form-select-sm">
                            <option value="">All levels</option>
                            <option value="Undergraduate">Undergraduate</option>
                            <option value="Postgraduate">Postgraduate</option>
                          </select>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label for="programme_id" class="form-label">Select Programme</label>
                        <select class="form-select" id="programme_id" name="programme_id" required>
                          <option value="">-- Select a programme --</option>
                          <?php foreach ($unassignedProgrammes as $programme): ?>
                            <option value="<?= (int) $programme['id'] ?>" data-level="<?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($programme['title'] ?? '', ENT_QUOTES) ?> <small class="text-muted">(<?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?>)</small></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-primary">Assign Programme</button>
                    <?php endif; ?>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../footer.php'; ?>
<script>
  (function(){
    // Module filters: programme level, programme name
    const moduleProgLevel = document.getElementById('filter_module_programme_level');
    const moduleProgName = document.getElementById('filter_module_programme_name');
    const moduleSelect = document.getElementById('module_id');

    function normalize(value) {
      return (value || '').toString().trim().toLowerCase();
    }

    function applyModuleFilters() {
      if (!moduleSelect) return;
      const level = normalize(moduleProgLevel ? moduleProgLevel.value : '');
      const progId = normalize(moduleProgName ? moduleProgName.value : '');
      Array.from(moduleSelect.options).forEach(opt => {
        if (!opt.value) return;
        const optProgLevel = normalize(opt.getAttribute('data-prog-level'));
        const optProgId = normalize(opt.getAttribute('data-prog-id'));
        let hidden = false;
        if (level && optProgLevel !== level) hidden = true;
        if (progId && optProgId !== progId) hidden = true;
        opt.hidden = hidden;
      });
      if (moduleSelect.selectedOptions.length && moduleSelect.selectedOptions[0].hidden) {
        moduleSelect.value = '';
      }
    }

    if (moduleProgLevel) {
      moduleProgLevel.addEventListener('change', () => {
        applyModuleFilters();
        if (moduleProgName) {
          const selectedLevel = normalize(moduleProgLevel.value);
          Array.from(moduleProgName.options).forEach(opt => {
            if (!opt.value) return;
            const optLevel = normalize(opt.getAttribute('data-level'));
            opt.hidden = selectedLevel && optLevel !== selectedLevel;
          });
          if (moduleProgName.selectedOptions.length && moduleProgName.selectedOptions[0].hidden) {
            moduleProgName.value = '';
            applyModuleFilters();
          }
        }
      });
    }

    if (moduleProgName) {
      moduleProgName.addEventListener('change', applyModuleFilters);
    }

    applyModuleFilters();

    // Programme level filter
    const programmeLevel = document.getElementById('filter_programme_level');
    const programmeSelect = document.getElementById('programme_id');
    if (programmeLevel && programmeSelect) {
      programmeLevel.addEventListener('change', () => {
        const level = normalize(programmeLevel.value);
        Array.from(programmeSelect.options).forEach(opt => {
          if (!opt.value) return;
          const optLevel = normalize(opt.getAttribute('data-level'));
          opt.hidden = level && optLevel !== level;
        });
        if (programmeSelect.selectedOptions.length && programmeSelect.selectedOptions[0].hidden) {
          programmeSelect.value = '';
        }
      });
    }
  })();
</script>
<?php include __DIR__ . '/../footer.php'; ?>