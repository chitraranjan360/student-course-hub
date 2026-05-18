<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'Admin', ENT_QUOTES) ?> | UniHub Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
  <?php
    $hdrCss = __DIR__ . '/../../public/css/admin-header.css';
    $hdrVer = is_file($hdrCss) ? filemtime($hdrCss) : time();
  ?>
  <link rel="stylesheet" href="<?= base_url('/css/admin-header.css') ?>?v=<?= $hdrVer ?>">
</head>
<body class="admin-page bg-light">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
<nav class="navbar navbar-expand-lg navbar-dark admin-navbar shadow-sm" role="navigation" aria-label="Admin navigation">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('/admin') ?>">
      <img src="<?= base_url('/uploads/logo.png') ?>" alt="UniHub" class="brand-img me-2" onerror="this.style.display='none'">
      <div class="d-flex flex-column">
        <strong class="mb-0">UniHub Admin</strong>
        <small class="text-muted">Control center</small>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link px-3" href="<?= base_url('/admin/programmes') ?>">Programmes</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="<?= base_url('/admin/modules') ?>">Modules</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="<?= base_url('/admin/interests') ?>">Interests</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="<?= base_url('/admin/staff') ?>">Staff</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= base_url('/uploads/admin-avatar.png') ?>" alt="Admin" class="avatar me-2" onerror="this.style.display='none'">
            <span class="d-none d-lg-inline">Admin</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
            <li><a class="dropdown-item" href="<?= base_url('/admin/profile') ?>">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= base_url('/admin/logout') ?>">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<main id="main-content" class="container py-4">
