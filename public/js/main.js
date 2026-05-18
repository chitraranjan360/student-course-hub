/* UniHub – main.js */

document.addEventListener('DOMContentLoaded', () => {
  const appBaseUrl = (window.APP_BASE_URL || '').replace(/\/$/, '');
  const apiUrl = path => `${appBaseUrl}${path.startsWith('/') ? path : `/${path}`}`;

  // ── Publish toggle ─────────────────────────────────────────────
  document.querySelectorAll('.publish-toggle').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
      const res = await fetch(apiUrl(`/admin/programmes/${id}/publish`), { method: 'POST' });
      if (!res.ok) return;
      const data = await res.json();
      const published = data.is_published == 1;
      btn.textContent  = published ? 'Published' : 'Draft';
      btn.className    = `btn btn-sm ${published ? 'btn-success' : 'btn-secondary'} publish-toggle`;
      btn.setAttribute('aria-label', `${published ? 'Unpublish' : 'Publish'} programme`);

      // Update any visible status badge for this programme
      try {
        const badge = document.querySelector(`.publish-badge[data-id="${id}"]`);
        if (badge) {
          badge.textContent = published ? 'Published' : 'Draft';
          badge.className = `badge ${published ? 'text-bg-success' : 'text-bg-secondary'} publish-badge`;
          badge.setAttribute('data-id', id);
        }
      } catch (e) {
        // silent
      }
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

  // ── Publish option button (select + set) ─────────────────────────
  document.querySelectorAll('.publish-option-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
      const select = document.querySelector(`.publish-select[data-id="${id}"]`);
      if (!select) {
        console.error('Select element not found for id:', id);
        alert('Error: Could not find the module selector');
        return;
      }
      const value = select.value;
      const formData = new FormData();
      formData.append('is_published', value);

      try {
        const res = await fetch(apiUrl(`/admin/programmes/${id}/publish`), { method: 'POST', body: formData });
        if (!res.ok) {
          alert('Failed to update publish status');
          return;
        }
        const data = await res.json();
        const published = data.is_published == 1;

        // Update badge
        const badge = document.querySelector(`.publish-badge[data-id="${id}"]`);
        if (badge) {
          badge.textContent = published ? 'Published' : 'Draft';
          badge.className = `badge ${published ? 'text-bg-success' : 'text-bg-secondary'} publish-badge`;
          badge.setAttribute('data-id', id);
        }

        // Reflect state in select
        select.value = published ? '1' : '0';
      } catch (e) {
        console.error(e);
        alert('Error updating publish status');
      }
    });
  });

  // ── Auto-dismiss flash messages ─────────────────────────────────
  document.querySelectorAll('.auto-dismiss').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .5s';
      el.style.opacity = '0';
      setTimeout(() => el.remove(), 300);
    }, 2000);
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
