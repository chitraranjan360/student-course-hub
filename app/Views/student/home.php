<?php $pageTitle = 'Explore Our Programmes'; include __DIR__ . '/../layout/header.php'; ?>

<section class="hero bg-primary text-white py-5">
  <div class="container text-center">
    <h1 class="display-5 fw-bold">Find Your Perfect Course</h1>
    <p class="lead">Explore undergraduate and postgraduate programmes at UniHub</p>
    <form method="GET" action="<?= base_url('/') ?>" class="row g-2 justify-content-center mt-3" role="search" aria-label="Search programmes">
      <div class="col-auto">
        <label for="search" class="visually-hidden">Search programmes</label>
        <input id="search" type="search" name="search" class="form-control" placeholder="Search programmes…"
               value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>">
      </div>
      <div class="col-auto">
        <label for="level" class="visually-hidden">Filter by level</label>
        <select id="level" name="level" class="form-select">
          <option value="">All Levels</option>
          <option value="Undergraduate" <?= ($level ?? '') === 'Undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
          <option value="Postgraduate"  <?= ($level ?? '') === 'Postgraduate'  ? 'selected' : '' ?>>Postgraduate</option>
        </select>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-light fw-semibold">Search</button>
      </div>
    </form>
  </div>
</section>

<section class="py-5" aria-label="Programme listings">
  <div class="container">
    <?php if (empty($programmes)): ?>
      <p class="text-center text-muted">No programmes found. Try a different search.</p>
    <?php else: ?>
      <div class="row row-cols-1 row-cols-md-3 g-4" id="programme-grid">
        <?php foreach ($programmes as $p): ?>
          <div class="col">
            <article class="card h-100 shadow-sm">
              <?php if (!empty($p['image_url'])): ?>
                <?php
                  $img = $p['image_url'];
                  $src = preg_match('#^https?://#i', $img) ? $img : base_url('/' . ltrim($img, '/'));
                ?>
                <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"
                     class="card-img-top" alt="<?= htmlspecialchars($p['title'], ENT_QUOTES) ?> programme image"
                     style="height:180px;object-fit:cover">
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <span class="badge <?= $p['level'] === 'Undergraduate' ? 'bg-info' : 'bg-warning text-dark' ?> mb-2 align-self-start">
                  <?= htmlspecialchars($p['level'], ENT_QUOTES) ?>
                </span>
                <h2 class="card-title h5"><?= htmlspecialchars($p['title'], ENT_QUOTES) ?></h2>
                
                <a href="<?= base_url('/programmes/' . $p['id']) ?>" class="btn btn-primary mt-auto">View Programme</a>
              </div>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
