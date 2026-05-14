/* UniHub – main.js */

document.addEventListener('DOMContentLoaded', () => {

  // ── Publish toggle ─────────────────────────────────────────────
  document.querySelectorAll('.publish-toggle').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
      const res = await fetch(`/admin/programmes/${id}/publish`, { method: 'POST' });
      if (!res.ok) return;
      const data = await res.json();
      const published = data.is_published == 1;
      btn.textContent  = published ? 'Published' : 'Draft';
      btn.className    = `btn btn-sm ${published ? 'btn-success' : 'btn-secondary'} publish-toggle`;
      btn.setAttribute('aria-label', `${published ? 'Unpublish' : 'Publish'} programme`);
    });
  });

  // ── Confirm before delete ───────────────────────────────────────
  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', e => {
      if (!confirm('Are you sure you want to delete this? This cannot be undone.')) {
        e.preventDefault();
      }
    });
  });

  // ── Auto-dismiss flash messages ─────────────────────────────────
  document.querySelectorAll('.auto-dismiss').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .5s';
      el.style.opacity = '0';
      setTimeout(() => el.remove(), 500);
    }, 4000);
  });

  // ── Bootstrap form validation (interest form) ───────────────────
  const interestForm = document.getElementById('interestForm');
  if (interestForm) {
    interestForm.addEventListener('submit', e => {
      if (!interestForm.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      interestForm.classList.add('was-validated');
    });
  }
});
