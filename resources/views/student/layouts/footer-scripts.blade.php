<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow"><i class="las la-angle-double-up"></i></span>
</div>
<div id="responsive-overlay"></div>
<!-- Scroll To Top -->

<!-- Choices JS -->
<script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

<!-- Main Theme Js -->
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- Popper JS -->
<script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>
{{-- <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Defaultmenu JS -->
<script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>

<!-- Node Waves JS -->
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<!-- Sticky JS -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>

<!-- Simplebar JS -->
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>

<!-- Color Picker JS -->
<script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

<!-- Custom-Switcher JS -->
<script src="{{ asset('assets/js/custom-switcher.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/custom.js') }}"></script>

<!-- Admin UI JS -->
<script src="{{ asset('assets/js/admin-ui.js') }}"></script>

<!-- Admin Ajax Filter (central table search/filter) -->
<script src="{{ asset('assets/js/admin-ajax-filter.js') }}"></script>

<!-- Admin exam/gamification page reveal -->
<script>
(function () {
    function markPageLoaded() {
        document.documentElement.classList.add('loaded');
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(markPageLoaded, 50);
        });
    } else {
        setTimeout(markPageLoaded, 50);
    }
})();
</script>

<!-- Page Specific Scripts -->
@yield('script')
@stack('scripts')
@auth
<script>
(function () {
    const countEl = document.getElementById('headerNotificationCount');
    const listEl = document.getElementById('header-notification-scroll');
    const emptyEl = document.getElementById('headerNotificationEmpty');
    if (!countEl || !listEl) return;

    fetch('{{ route('gamification.notifications.api') }}', { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            const items = data.notifications || data.data || [];
            const unread = data.unread_count ?? items.filter(n => !n.is_read).length;
            countEl.textContent = unread ? `لديك ${unread} إشعارات جديدة` : 'لا إشعارات جديدة';
            if (!items.length) return;
            if (emptyEl) emptyEl.remove();
            items.slice(0, 5).forEach(n => {
                const li = document.createElement('li');
                li.className = 'dropdown-item py-2';
                li.innerHTML = `<div class="fs-13 fw-semibold">${n.title || 'إشعار'}</div><div class="text-muted fs-11">${n.message || ''}</div>`;
                listEl.appendChild(li);
            });
        })
        .catch(() => { countEl.textContent = 'لا إشعارات جديدة'; });
})();
</script>
@endauth
