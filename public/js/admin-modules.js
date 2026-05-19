(function () {
  const searchInput = document.getElementById('moduleSearch');
  const rows = Array.from(document.querySelectorAll('#modulesTable tbody .module-row'));
  const noResults = document.getElementById('noModuleResults');

  if (!searchInput || !noResults || rows.length === 0) {
    return;
  }

  const filterRows = function () {
    const query = searchInput.value.trim().toLowerCase();
    let visibleCount = 0;

    rows.forEach(function (row) {
      const titleLink = row.querySelector('td:first-child .prog-link');
      const titleText = (titleLink ? titleLink.textContent : '').trim().toLowerCase();
      const matches = query === '' || titleText.includes(query);
      row.classList.toggle('d-none', !matches);
      if (matches) {
        visibleCount += 1;
      }
    });

    noResults.classList.toggle('d-none', visibleCount > 0);
  };

  searchInput.addEventListener('input', filterRows);
})();
