<?php
$module = $module ?? null;
$assignedProgram = $assignedProgram ?? null;
$assignedStaff = $assignedStaff ?? [];
$flash = $flash ?? [];

$pageTitle = $module['title'] ?? 'Module Details';
include __DIR__ . '/header.php';
?>

<?php if ($module): ?>
    <div class="prog-hero">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <p class="text-uppercase text-muted small mb-2">Module Overview</p>
                <h1 class="h2 mb-0"><?= htmlspecialchars($module['title'] ?? 'Module', ENT_QUOTES) ?></h1>
                <div class="text-muted small mt-1"><?= htmlspecialchars($module['code'] ?? '', ENT_QUOTES) ?></div>
            </div>
        </div>
        <div class="prog-stats">
            <?php if (!empty($assignedPrograms)): ?>
                <?php foreach ($assignedPrograms as $ap): ?>
                    <span class="stat-badge">Program: <strong>
                        <a href="<?= base_url('/admin/programmes/' . ($ap['id'] ?? '')) ?>" class="text-decoration-none text-reset"><?= htmlspecialchars($ap['title'] ?? '', ENT_QUOTES) ?></a>
                    </strong></span>
                    <?php if (!empty($ap['year'])): ?>
                        <span class="stat-badge">Year: <strong><?= htmlspecialchars($ap['year'], ENT_QUOTES) ?></strong></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php elseif (!empty($assignedProgram)): ?>
                <span class="stat-badge">Program: <strong><?= htmlspecialchars($assignedProgram['title'] ?? '', ENT_QUOTES) ?></strong></span>
                <?php if(!empty($assignedProgram['year'])): ?>
                    <span class="stat-badge">Year: <strong><?= htmlspecialchars($assignedProgram['year'] ?? '', ENT_QUOTES) ?></strong></span>
                <?php endif; ?>
            <?php endif; ?>
            
            <span class="stat-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                </svg>
                <strong><?= count($assignedStaff) ?></strong> Staff
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

<?php if (!$module): ?>
    <div class="alert alert-warning">Module not found.</div>
<?php else: ?>
    <div class="prog-actions d-flex gap-2 flex-wrap mb-3">
        <a href="<?= base_url('/admin/modules/' . $module['id'] . '/edit') ?>" class="btn btn-warning">Edit Module</a>
        <form method="POST" action="<?= base_url('/admin/modules/' . $module['id'] . '/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this module?');">
            <button type="submit" class="btn btn-outline-danger">Delete Module</button>
        </form>
        <a href="<?= base_url('/admin/modules') ?>" class="btn btn-outline-secondary">Back to Modules</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Module Details</h2>
                    <dl class="row mb-0">
                    
                        <dt class="col-sm-5">Code:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($module['code'] ?? '—', ENT_QUOTES) ?></dd>
                        <dt class="col-sm-5">Credits:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($module['credits'] ?? '0', ENT_QUOTES) ?></dd>
                        <dt class="col-sm-5">Semester:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($module['semester'] ?? '—', ENT_QUOTES) ?></dd>
                        <dt class="col-sm-5">Duration:</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($module['duration'] ?? '—', ENT_QUOTES) ?></dd>
                        <dt class="col-sm-5">Created:</dt>
                        <dd class="col-sm-7"><small class="text-muted"><?= htmlspecialchars($module['created_at'] ?? 'N/A', ENT_QUOTES) ?></small></dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Assigned Staff</h2>
                    <?php if (empty($assignedStaff)): ?>
                        <div class="alert alert-info mb-0">No staff are assigned to this module yet.</div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($assignedStaff as $staff): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <a href="<?= base_url('/admin/staff/' . $staff['id']) ?>" class="fw-semibold text-decoration-none"><?= htmlspecialchars($staff['full_name'] ?? '', ENT_QUOTES) ?></a>
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
            <div class="card shadow-sm">
                <?php if (!empty($module['photo'])): ?>
                    <figure class="mb-0 w-100 rounded-top overflow-hidden" style="height:280px;">
                        <img src="<?= base_url('/uploads/' . $module['photo']) ?>" alt="<?= htmlspecialchars($module['title'] ?? 'Module image', ENT_QUOTES) ?>"
                         class="w-100 h-100" style="object-fit:cover; display:block;">
                    </figure>
                <?php else: ?>
                    <div class="d-flex justify-content-center align-items-center rounded-top bg-light" style="height:280px;">
                        <span class="text-muted">No Image</span>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <h2 class="h5 mb-2">About this module</h2>
                    <p class="text-muted mb-3"><?= nl2br(htmlspecialchars($module['description'] ?? 'No description provided.', ENT_QUOTES)) ?></p>
                    <?php if (!empty($assignedProgram)): ?>
                        <div class="border-top pt-3 mt-3">
                            <h3 class="h6 mb-1">Assigned Programme</h3>
                            <a href="<?= base_url('/admin/programmes/' . $assignedProgram['id']) ?>" class="fw-semibold text-decoration-none"><?= htmlspecialchars($assignedProgram['title'] ?? '', ENT_QUOTES) ?></a>
                            <div class="text-muted small"><?= htmlspecialchars($assignedProgram['level'] ?? '', ENT_QUOTES) ?></div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mt-3 mb-0">This module is not assigned to any programme.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php include __DIR__ . '/footer.php'; ?>