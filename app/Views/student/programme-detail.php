<?php
$prog = $prog ?? ['id' => 0, 'title' => '', 'level' => '', 'description' => ''];
$pageTitle = htmlspecialchars($prog['title'], ENT_QUOTES);
include __DIR__ . '/../layout/header.php';
?>

<section class="py-5">
  <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary mt-2">← Back</a>
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <span class="badge <?= $prog['level'] === 'Undergraduate' ? 'bg-info' : 'bg-warning text-dark' ?> mb-2">
          <?= htmlspecialchars($prog['level'], ENT_QUOTES) ?>
        </span>
        <h1><?= htmlspecialchars($prog['title'], ENT_QUOTES) ?></h1>
        <p class="lead"><?= htmlspecialchars($prog['description'], ENT_QUOTES) ?></p>
        <a href="<?= base_url('/interest/register/' . $prog['id']) ?>" class="btn btn-primary btn-lg mt-2">Register Interest</a>
      </div>
    </div>

    <?php if (!empty($modulesByYear)): ?>
      <h2 class="mt-5 mb-3">Modules by Year</h2>
      <div class="accordion" id="modulesAccordion">
        <?php foreach ($modulesByYear as $year => $modules): ?>
          <div class="accordion-item">
            <h3 class="accordion-header" id="heading-year<?= $year ?>">
              <button class="accordion-button <?= $year > 1 ? 'collapsed' : '' ?>" type="button"
                      data-bs-toggle="collapse" data-bs-target="#collapse-year<?= $year ?>"
                      aria-expanded="<?= $year === 1 ? 'true' : 'false' ?>"
                      aria-controls="collapse-year<?= $year ?>">
                Year <?= (int)$year ?>
              </button>
            </h3>
            <div id="collapse-year<?= $year ?>" class="accordion-collapse collapse <?= $year == 1 ? 'show' : '' ?>">
              <div class="accordion-body">
                <div class="accordion accordion-flush" id="modulesYear<?= (int)$year ?>">
                  <?php foreach ($modules as $index => $m): ?>
                    <?php
                      $moduleId = 'year' . (int)$year . '-module-' . ($m['id'] ?? $index);
                      $moduleHeadingId = 'heading-' . $moduleId;
                      $moduleCollapseId = 'collapse-' . $moduleId;
                    ?>
                    <div class="accordion-item border rounded mb-3 overflow-hidden">
                      <h4 class="accordion-header" id="<?= $moduleHeadingId ?>">
                        <button class="accordion-button collapsed fw-semibold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#<?= $moduleCollapseId ?>"
                                aria-expanded="false" aria-controls="<?= $moduleCollapseId ?>">
                          <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>
                        </button>
                      </h4>
                      <div id="<?= $moduleCollapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $moduleHeadingId ?>" data-bs-parent="#modulesYear<?= (int)$year ?>">
                        <div class="accordion-body bg-body-tertiary">
                          
                          <h5 class="h6 fw-bold mb-2"><?= htmlspecialchars($m['title'], ENT_QUOTES) ?></h5>
                          <p class="mb-0 text-muted"><?= htmlspecialchars($m['description'], ENT_QUOTES) ?></p>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
