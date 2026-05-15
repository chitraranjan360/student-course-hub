<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'Admin', ENT_QUOTES) ?> | UniHub Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
</head>
<body class="bg-light">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
<nav class="navbar navbar-dark bg-dark" role="navigation" aria-label="Admin navigation">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url('/admin') ?>">🛠 Admin Panel</a>
    <div class="d-flex gap-3 align-items-center">
      <a href="<?= base_url('/admin/programmes') ?>" class="text-white text-decoration-none small">Programmes</a>
      <a href="<?= base_url('/admin/modules') ?>"    class="text-white text-decoration-none small">Modules</a>
      <a href="<?= base_url('/admin/staff') ?>"      class="text-white text-decoration-none small">Staff</a>
      <a href="<?= base_url('/admin/logout') ?>"     class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </div>
</nav>
<main id="main-content" class="container py-4">
