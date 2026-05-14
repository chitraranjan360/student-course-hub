<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'Student Course Hub', ENT_QUOTES) ?> | UniHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>">
</head>
<body>
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
<header>
  <nav class="navbar navbar-expand-lg navbar-dark uni-navbar" role="navigation" aria-label="Main navigation">
    <div class="container py-2 py-lg-3">
      <a class="navbar-brand d-flex align-items-center gap-3" href="<?= base_url('/') ?>">
        <span class="brand-mark" aria-hidden="true">U</span>
        <span class="brand-copy">
          <span class="brand-title">UniHub</span>
          <span class="brand-subtitle">Student Course Hub</span>
        </span>
      </a>
      <button class="navbar-toggler uni-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
              aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <div class="ms-auto pt-3 pt-lg-0">
          <a class="btn btn-light btn-lg rounded-pill px-4 login-button" href="<?= base_url('/admin/login') ?>">
            <span>Login</span>
            <span class="login-arrow" aria-hidden="true">→</span>
          </a>
        </div>
      </div>
    </div>
  </nav>
</header>
<main id="main-content">
