<script>
(function () {
    function initStatsCountup(root) {
        (root || document).querySelectorAll('[data-countup]').forEach(function (el) {
            const target = parseFloat(el.dataset.countup || '0');
            const isDecimal = String(target).includes('.');
            const duration = 800;
            const start = performance.now();
            function step(now) {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = target * eased;
                el.textContent = isDecimal
                    ? current.toFixed(1) + (el.dataset.suffix || '')
                    : new Intl.NumberFormat('ar-EG').format(Math.round(current)) + (el.dataset.suffix || '');
                if (progress < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        });
    }
    window.initStatsCountup = initStatsCountup;
    initStatsCountup();
})();
</script>
