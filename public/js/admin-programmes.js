(function () {
  const scriptEl = document.getElementById('admin-programmes-js');
  const publishBaseUrl = scriptEl ? scriptEl.dataset.publishUrlBase || '' : '';

  function showFlash(message, type) {
    const kind = type || 'success';
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + kind + ' alert-dismissible fade show';
    alertDiv.role = 'alert';
    alertDiv.innerHTML =
      message +
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

    const container = document.querySelector('.table-responsive');
    if (!container || !container.parentNode) {
      return;
    }

    container.parentNode.insertBefore(alertDiv, container);

    setTimeout(function () {
      alertDiv.remove();
    }, 3000);
  }

  document.querySelectorAll('.status-select').forEach(function (select) {
    select.addEventListener('change', async function (e) {
      const id = e.target.dataset.id;
      const status = e.target.value;
      const previousStatus = status === 'publish' ? 'draft' : 'publish';

      try {
        const response = await fetch(publishBaseUrl + '/' + id + '/publish', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'status=' + encodeURIComponent(status)
        });

        if (!response.ok) {
          showFlash('Failed to update status', 'danger');
          e.target.value = previousStatus;
        } else {
          const statusText = status === 'publish' ? 'Published' : 'Draft';
          showFlash('Status updated to ' + statusText);
        }
      } catch (error) {
        console.error('Error:', error);
        showFlash('Error updating status', 'danger');
        e.target.value = previousStatus;
      }
    });
  });

  const programmeSearch = document.getElementById('programmeSearch');
  const programmeRows = Array.from(document.querySelectorAll('#programmesTable tbody .programme-row'));
  const noProgrammeResults = document.getElementById('noProgrammeResults');

  const filterProgrammes = function () {
    if (!programmeSearch || !noProgrammeResults) {
      return;
    }

    const query = programmeSearch.value.trim().toLowerCase();
    let visibleCount = 0;

    programmeRows.forEach(function (row) {
      const titleLink = row.querySelector('td:first-child .prog-link');
      const titleText = (titleLink ? titleLink.textContent : '').trim().toLowerCase();
      const matches = query === '' || titleText.includes(query);
      row.classList.toggle('d-none', !matches);
      if (matches) {
        visibleCount += 1;
      }
    });

    noProgrammeResults.classList.toggle('d-none', visibleCount > 0);
  };

  if (programmeSearch) {
    programmeSearch.addEventListener('input', filterProgrammes);
  }
})();
