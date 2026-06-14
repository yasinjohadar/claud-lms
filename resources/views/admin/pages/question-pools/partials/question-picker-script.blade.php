<script>
(function () {
    const selectAll = document.getElementById('select-all');
    const countEl = document.getElementById('selected-count');
    const applyBtn = document.getElementById('apply-filters');
    const searchInput = document.getElementById('filter-search');
    const typeFilter = document.getElementById('filter-type');
    const difficultyFilter = document.getElementById('filter-difficulty');
    const rows = document.querySelectorAll('.question-row');

    function visibleCheckboxes() {
        return document.querySelectorAll('.question-checkbox:not([data-hidden="1"])');
    }

    function updateSelectedCount() {
        if (!countEl) return;
        const count = document.querySelectorAll('.question-checkbox:checked').length;
        countEl.textContent = count;
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            visibleCheckboxes().forEach(function (cb) {
                cb.checked = selectAll.checked;
            });
            updateSelectedCount();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('question-checkbox')) {
            updateSelectedCount();
        }
    });

    function applyFilters() {
        const typeVal = typeFilter ? typeFilter.value : '';
        const diffVal = difficultyFilter ? difficultyFilter.value : '';
        const searchVal = searchInput ? searchInput.value.trim().toLowerCase() : '';

        rows.forEach(function (row) {
            const type = String(row.dataset.type || '');
            const difficulty = String(row.dataset.difficulty || '');
            const text = String(row.dataset.text || '');

            let show = true;
            if (typeVal && type !== typeVal) show = false;
            if (diffVal && difficulty !== diffVal) show = false;
            if (searchVal && !text.includes(searchVal)) show = false;

            row.style.display = show ? '' : 'none';
            row.querySelectorAll('.question-checkbox').forEach(function (cb) {
                cb.dataset.hidden = show ? '0' : '1';
            });
        });

        if (selectAll) selectAll.checked = false;
        updateSelectedCount();
    }

    if (applyBtn) applyBtn.addEventListener('click', applyFilters);
    if (searchInput) {
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyFilters();
            }
        });
    }

    updateSelectedCount();
})();
</script>
