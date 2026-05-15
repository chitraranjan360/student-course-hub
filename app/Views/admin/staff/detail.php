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

<h1 class="mb-4">Staff Details</h1>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($flash['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if (!$staff): ?>
  <div class="alert alert-warning">Staff member not found.</div>
<?php else: ?>
  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-body">
          <h2 class="h5 mb-3">Profile</h2>
          <p class="mb-2"><strong>Name:</strong> <?= htmlspecialchars($staff['full_name'] ?? '', ENT_QUOTES) ?></p>
          <p class="mb-2"><strong>Username:</strong> <?= htmlspecialchars($staff['username'] ?? '', ENT_QUOTES) ?></p>
          <p class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($staff['email'] ?? '', ENT_QUOTES) ?></p>
          <p class="mb-2"><strong>Status:</strong> <?= !empty($staff['is_active']) ? 'Active' : 'Inactive' ?></p>
          <p class="mb-0"><strong>Created:</strong> <?= !empty($staff['created_at']) ? date('Y-m-d H:i', strtotime($staff['created_at'])) : 'N/A' ?></p>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5 mb-3">Assigned Modules</h2>
            <?php if (empty($assignedModules)): ?>
            <div class="alert alert-info mb-0">No modules has been assigned yet.</div>
          <?php else: ?>
            <ul class="list-group mb-0">
              <?php foreach ($assignedModules as $module): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span>
                    <strong><?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?></strong>
                    <span class="text-muted ms-2">Year <?= (int) ($module['year_of_study'] ?? 1) ?></span>
                  </span>
                  <form method="POST" action="<?= base_url('/admin/staff/' . $staff['id'] . '/unassign-module') ?>" style="margin:0">
                    <input type="hidden" name="module_id" value="<?= $module['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Unassign this module?')">Unassign</button>
                  </form>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5 mb-3">Assigned Programmes</h2>
            <?php if (empty($assignedProgrammes)): ?>
            <div class="alert alert-info mb-0">No program has been assigned yet.</div>
          <?php else: ?>
            <ul class="list-group mb-0">
              <?php foreach ($assignedProgrammes as $programme): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span>
                    <strong><?= htmlspecialchars($programme['title'] ?? '', ENT_QUOTES) ?></strong>
                    <span class="text-muted ms-2"><?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?></span>
                  </span>
                  <form method="POST" action="<?= base_url('/admin/staff/' . $staff['id'] . '/unassign-programme') ?>" style="margin:0">
                    <input type="hidden" name="programme_id" value="<?= $programme['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Unassign this programme?')">Unassign</button>
                  </form>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5 mb-3">Assign Program/Module</h2>

          <details class="mb-3">
            <summary class="fw-semibold">Assign Module</summary>
            <form method="POST" action="<?= base_url('/admin/staff/' . $staff['id'] . '/assign-module') ?>" class="mt-3">
              <?php if (empty($unassignedModules)): ?>
                <div class="alert alert-info mb-0">No modules are available to assign.</div>
              <?php else: ?>
                <div class="row g-2 mb-3">
                  <div class="col-md-4">
                    <label for="filter_module_year" class="form-label">Filter by Year</label>
                    <select id="filter_module_year" class="form-select">
                      <option value="">All years</option>
                      <option value="1">Year 1</option>
                      <option value="2">Year 2</option>
                      <option value="3">Year 3</option>
                      <option value="4">Year 4</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="filter_module_programme_level" class="form-label">Filter by Programme Level</label>
                    <select id="filter_module_programme_level" class="form-select">
                      <option value="">All levels</option>
                      <option value="Undergraduate">Undergraduate</option>
                      <option value="Postgraduate">Postgraduate</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="filter_module_programme_name" class="form-label">Filter by Programme</label>
                    <select id="filter_module_programme_name" class="form-select">
                      <option value="">All programmes</option>
                      <?php
                        $seenProg = [];
                        foreach ($unassignedModules as $module):
                          $pid = $module['programme_id'] ?? null;
                          $ptitle = $module['programme_title'] ?? null;
                          if ($pid && $ptitle && !in_array($pid, $seenProg, true)) { $seenProg[] = $pid; ?>
                            <option value="<?= $pid ?>"><?= htmlspecialchars($ptitle, ENT_QUOTES) ?></option>
                      <?php }
                        endforeach;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="row g-2 mb-3">
                  <div class="col-12">
                    <label for="module_id" class="form-label">Choose a module</label>
                    <select class="form-select" id="module_id" name="module_id" required>
                      <option value="">Select module</option>
                      <?php foreach ($unassignedModules as $module): ?>
                        <option value="<?= $module['id'] ?>" data-year="<?= (int) ($module['year_of_study'] ?? 1) ?>" data-prog-id="<?= htmlspecialchars($module['programme_id'] ?? '', ENT_QUOTES) ?>" data-prog-level="<?= htmlspecialchars($module['programme_level'] ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?><?php if (!empty($module['programme_title'])) echo ' — ' . htmlspecialchars($module['programme_title'], ENT_QUOTES); ?> (Year <?= (int) ($module['year_of_study'] ?? 1) ?>)</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Assign Module</button>
              <?php endif; ?>
            </form>
          </details>

          <details>
            <summary class="fw-semibold">Assign Programme</summary>
            <form method="POST" action="<?= base_url('/admin/staff/' . $staff['id'] . '/assign-programme') ?>" class="mt-3">
              <?php if (empty($unassignedProgrammes)): ?>
                <div class="alert alert-info mb-0">No programmes are available to assign.</div>
              <?php else: ?>
                <div class="row g-2 mb-3">
                  <div class="col-md-6">
                    <label for="filter_programme_level" class="form-label">Filter by Level</label>
                    <select id="filter_programme_level" class="form-select">
                      <option value="">All levels</option>
                      <option value="Undergraduate">Undergraduate</option>
                      <option value="Postgraduate">Postgraduate</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="programme_id" class="form-label">Choose a programme</label>
                    <select class="form-select" id="programme_id" name="programme_id" required>
                      <option value="">Select programme</option>
                      <?php foreach ($unassignedProgrammes as $programme): ?>
                        <option value="<?= $programme['id'] ?>" data-level="<?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($programme['title'] ?? '', ENT_QUOTES) ?> (<?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?>)</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Assign Programme</button>
              <?php endif; ?>
            </form>
          </details>
        </div>
      </div>

      <a href="<?= base_url('/admin/staff') ?>" class="btn btn-secondary">Back</a>
    </div>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../footer.php'; ?>
<script>
  (function(){
    // Module filters: year, programme level, programme name
    const moduleYear = document.getElementById('filter_module_year');
    const moduleProgLevel = document.getElementById('filter_module_programme_level');
    const moduleProgName = document.getElementById('filter_module_programme_name');
    const moduleSelect = document.getElementById('module_id');
    function applyModuleFilters() {
      if (!moduleSelect) return;
      const year = moduleYear ? moduleYear.value : '';
      const level = moduleProgLevel ? moduleProgLevel.value : '';
      const progId = moduleProgName ? moduleProgName.value : '';
      Array.from(moduleSelect.options).forEach(opt => {
        if (!opt.value) return;
        const optYear = opt.getAttribute('data-year') || '';
        const optProgLevel = opt.getAttribute('data-prog-level') || '';
        const optProgId = opt.getAttribute('data-prog-id') || '';
        let hidden = false;
        if (year && optYear !== year) hidden = true;
        if (level && optProgLevel !== level) hidden = true;
        if (progId && optProgId !== progId) hidden = true;
        opt.hidden = hidden;
      });
      if (moduleSelect.selectedOptions.length && moduleSelect.selectedOptions[0].hidden) {
        moduleSelect.value = '';
      }
    }
    if (moduleYear) moduleYear.addEventListener('change', applyModuleFilters);
    if (moduleProgLevel) moduleProgLevel.addEventListener('change', applyModuleFilters);
    if (moduleProgName) moduleProgName.addEventListener('change', applyModuleFilters);

    // Programme level filter
    const programmeLevel = document.getElementById('filter_programme_level');
    const programmeSelect = document.getElementById('programme_id');
    if (programmeLevel && programmeSelect) {
      programmeLevel.addEventListener('change', () => {
        const level = programmeLevel.value;
        Array.from(programmeSelect.options).forEach(opt => {
          if (!opt.value) return;
          const optLevel = opt.getAttribute('data-level');
          opt.hidden = level && optLevel !== level;
        });
        if (programmeSelect.selectedOptions.length && programmeSelect.selectedOptions[0].hidden) {
          programmeSelect.value = '';
        }
      });
    }
  })();
</script>