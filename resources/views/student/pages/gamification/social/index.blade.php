@extends('student.layouts.master')

@section('page-title')
    النشاط الاجتماعي
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid pb-3">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'النشاط الاجتماعي'],
            ],
            'title' => 'النشاط الاجتماعي',
            'subtitle' => 'تابع إنجازات أصدقائك، علّق، وأظهر دعمك بالإعجاب',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.friends.index') . '" class="btn btn-light border btn-wave">
                        <i class="ri-group-line me-1"></i>الأصدقاء
                    </a>
                    <a href="' . route('gamification.achievements.index') . '" class="btn btn-light border btn-wave">
                        <i class="ri-trophy-line me-1"></i>الإنجازات
                    </a>
                    <a href="' . route('gamification.dashboard') . '" class="btn btn-primary btn-wave">
                        <i class="ri-dashboard-line me-1"></i>لوحة التلعيب
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.social.partials.stats', compact('stats', 'activities'))

        <div id="socialAlert" class="d-none alert mb-3"></div>

        <div class="card custom-card">
            <div class="card-header border-0 pb-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="ri-rss-line text-primary me-1"></i>
                        خلاصة المجتمع
                    </h5>
                    <p class="text-muted fs-12 mb-0">آخر أنشطة أصدقائك ومنشوراتك</p>
                </div>
                <button type="button" class="btn btn-sm btn-light border btn-wave" id="socialRefreshBtn">
                    <i class="ri-refresh-line me-1"></i>تحديث
                </button>
            </div>
            <div class="card-body pt-3">
                <div class="row g-3" id="socialFeedList">
                    @forelse($activities as $index => $activity)
                        @include('student.pages.gamification.social.partials.activity-card', compact('activity', 'index'))
                    @empty
                        <div class="col-12">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon mx-auto mb-3"><i class="ri-rss-line"></i></div>
                                <p class="text-muted mb-1">لا نشاط بعد</p>
                                <p class="text-muted fs-12 mb-3">أكمل دروساً، اكسب شارات، أو أضف أصدقاء لترى نشاطهم هنا</p>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    <a href="{{ route('gamification.friends.index') }}" class="btn btn-sm btn-primary btn-wave">
                                        <i class="ri-user-add-line me-1"></i>ابحث عن أصدقاء
                                    </a>
                                    <a href="{{ route('gamification.achievements.index') }}" class="btn btn-sm btn-light border btn-wave">
                                        <i class="ri-trophy-line me-1"></i>الإنجازات
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const alertBox = document.getElementById('socialAlert');

    function showAlert(message, type = 'success') {
        if (!alertBox) return;
        alertBox.className = `alert alert-${type} mb-3`;
        alertBox.textContent = message;
        alertBox.classList.remove('d-none');
        setTimeout(() => alertBox.classList.add('d-none'), 3500);
    }

    async function postJson(url, body = null) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: body ? JSON.stringify(body) : null,
        });
        return res.json();
    }

    document.querySelectorAll('.social-like-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            const liked = this.dataset.liked === '1';
            const url = liked ? this.dataset.unlikeUrl : this.dataset.likeUrl;
            const data = await postJson(url);
            if (!data.success) {
                showAlert(data.message || 'تعذّر تحديث الإعجاب', 'warning');
                return;
            }
            const countEl = this.querySelector('.social-like-count');
            const icon = this.querySelector('i');
            const newLiked = !liked;
            this.dataset.liked = newLiked ? '1' : '0';
            this.classList.toggle('is-active', newLiked);
            icon.className = newLiked ? 'ri-thumb-up-fill' : 'ri-thumb-up-line';
            if (data.activity?.likes_count !== undefined) {
                countEl.textContent = Number(data.activity.likes_count).toLocaleString('ar-EG');
            }
        });
    });

    document.querySelectorAll('.social-toggle-comments').forEach(btn => {
        btn.addEventListener('click', function () {
            const panel = document.getElementById(this.dataset.target);
            panel?.classList.toggle('d-none');
        });
    });

    document.querySelectorAll('.social-comment-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const input = this.querySelector('input[name="content"]');
            const content = input.value.trim();
            if (!content) return;
            const data = await postJson(this.dataset.url, { content });
            if (!data.success) {
                showAlert(data.message || 'تعذّر إضافة التعليق', 'warning');
                return;
            }
            showAlert('تم إضافة التعليق!');
            location.reload();
        });
    });

    document.querySelectorAll('.social-delete-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            if (!confirm('حذف هذا النشاط؟')) return;
            const res = await fetch(this.dataset.url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                this.closest('.social-feed-item')?.remove();
                showAlert('تم حذف النشاط');
            } else {
                showAlert(data.message || 'تعذّر الحذف', 'warning');
            }
        });
    });

    document.getElementById('socialRefreshBtn')?.addEventListener('click', () => location.reload());
})();
</script>
@endpush
