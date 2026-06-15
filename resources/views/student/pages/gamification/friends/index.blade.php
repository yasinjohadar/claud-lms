@extends('student.layouts.master')

@section('page-title')
    الأصدقاء
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid pb-3">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'الأصدقاء'],
            ],
            'title' => 'الأصدقاء',
            'subtitle' => 'تواصل مع زملائك، تابع تقدّمهم، وشارك رحلة التعلم',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.social.index') . '" class="btn btn-light border btn-wave">
                        <i class="ri-rss-line me-1"></i>النشاط الاجتماعي
                    </a>
                    <a href="' . route('gamification.dashboard') . '" class="btn btn-primary btn-wave">
                        <i class="ri-trophy-line me-1"></i>لوحة التلعيب
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.friends.partials.stats', compact('stats', 'suggestions'))

        <div id="friendsAlert" class="d-none alert mb-3"></div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card custom-card mb-4">
                    <div class="card-header border-0 pb-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="ri-group-line text-primary me-1"></i>
                                قائمة الأصدقاء
                            </h5>
                            <p class="text-muted fs-12 mb-0">{{ number_format($stats['total_friends'] ?? count($friends)) }} صديق</p>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            @forelse($friends as $index => $friend)
                                @include('student.pages.gamification.friends.partials.friend-card', [
                                    'friend' => $friend,
                                    'index' => $index,
                                    'mode' => 'friend',
                                    'variant' => 'friend',
                                ])
                            @empty
                                <div class="col-12">
                                    <div class="empty-state py-5">
                                        <div class="empty-state-icon mx-auto mb-3"><i class="ri-group-line"></i></div>
                                        <p class="text-muted mb-1">لا أصدقاء بعد</p>
                                        <p class="text-muted fs-12 mb-0">ابحث عن زملائك أو اقبل طلبات الصداقة الواردة</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if(($suggestions ?? collect())->isNotEmpty())
                    <div class="card custom-card">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-1">
                                <i class="ri-user-search-line text-success me-1"></i>
                                اقتراحات أصدقاء
                            </h5>
                            <p class="text-muted fs-12 mb-0">طلاب قد تعرفهم من نفس المستوى أو شبكة أصدقائك</p>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                @foreach($suggestions as $index => $suggestion)
                                    @include('student.pages.gamification.friends.partials.friend-card', [
                                        'friend' => $suggestion,
                                        'index' => $index,
                                        'mode' => 'suggest',
                                        'variant' => 'suggest',
                                    ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card custom-card mb-4 gamification-friends-search-card">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-1">
                            <i class="ri-search-line text-info me-1"></i>
                            إضافة صديق
                        </h5>
                        <p class="text-muted fs-12 mb-0">ابحث بالاسم أو البريد</p>
                    </div>
                    <div class="card-body pt-3">
                        <div class="input-group mb-3">
                            <input type="text" id="friendSearchInput" class="form-control" placeholder="اسم أو بريد الطالب..." minlength="2">
                            <button type="button" class="btn btn-primary btn-wave" id="friendSearchBtn">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                        <div id="friendSearchResults" class="gamification-friends-search-results"></div>
                        <hr class="my-3">
                        <label class="form-label fs-12 text-muted mb-1">أو أرسل بالبريد مباشرة</label>
                        <input type="email" id="friendEmail" class="form-control form-control-sm mb-2" placeholder="student@example.com">
                        <button type="button" id="sendFriendBtn" class="btn btn-primary btn-wave w-100">
                            <i class="ri-send-plane-line me-1"></i>إرسال طلب صداقة
                        </button>
                    </div>
                </div>

                <div class="card custom-card mb-4">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-1">
                            <i class="ri-mail-unread-line text-warning me-1"></i>
                            طلبات واردة
                            @if(($stats['pending_requests'] ?? 0) > 0)
                                <span class="badge bg-warning-transparent text-warning ms-1">{{ $stats['pending_requests'] }}</span>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            @forelse($pendingRequests ?? [] as $index => $req)
                                @include('student.pages.gamification.friends.partials.friend-card', [
                                    'friend' => $req->user,
                                    'index' => $index,
                                    'mode' => 'incoming',
                                    'variant' => 'incoming',
                                    'requestId' => $req->id,
                                ])
                            @empty
                                <div class="col-12 text-center text-muted fs-13 py-2">لا توجد طلبات واردة</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="card custom-card">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-1">
                            <i class="ri-send-plane-line text-info me-1"></i>
                            طلبات مرسلة
                        </h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            @forelse($sentRequests ?? [] as $index => $req)
                                @include('student.pages.gamification.friends.partials.friend-card', [
                                    'friend' => $req->friend,
                                    'index' => $index,
                                    'mode' => 'outgoing',
                                    'variant' => 'outgoing',
                                    'requestId' => $req->id,
                                ])
                            @empty
                                <div class="col-12 text-center text-muted fs-13 py-2">لا توجد طلبات مرسلة</div>
                            @endforelse
                        </div>
                    </div>
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
    const alertEl = document.getElementById('friendsAlert');
    const baseUrl = @json(url('/student/gamification/friends'));

    function showAlert(message, type) {
        if (!alertEl) return;
        alertEl.classList.remove('d-none', 'alert-success', 'alert-danger');
        alertEl.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
        alertEl.textContent = message;
    }

    async function postJson(url, body) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: body ? JSON.stringify(body) : undefined,
        });
        return res.json();
    }

    document.getElementById('sendFriendBtn')?.addEventListener('click', async function () {
        const email = document.getElementById('friendEmail')?.value?.trim();
        if (!email) {
            showAlert('أدخل البريد الإلكتروني للطالب', 'danger');
            return;
        }
        const data = await postJson(@json(route('gamification.friends.send-request')), { email });
        showAlert(data.message || (data.success ? 'تم الإرسال' : 'فشل الإرسال'), data.success ? 'success' : 'danger');
        if (data.success) setTimeout(() => location.reload(), 1000);
    });

    async function runSearch() {
        const query = document.getElementById('friendSearchInput')?.value?.trim();
        const resultsEl = document.getElementById('friendSearchResults');
        if (!query || query.length < 2 || !resultsEl) return;

        const res = await fetch(@json(route('gamification.friends.search')) + '?query=' + encodeURIComponent(query), {
            headers: { 'Accept': 'application/json' },
        });
        const data = await res.json();
        resultsEl.innerHTML = '';

        if (!data.results?.length) {
            resultsEl.innerHTML = '<p class="text-muted fs-12 mb-0">لا نتائج</p>';
            return;
        }

        data.results.forEach(function (user) {
            const row = document.createElement('div');
            row.className = 'gamification-friends-search-item';
            row.innerHTML = '<div><strong class="fs-13">' + user.name + '</strong><span class="d-block text-muted fs-11">' + user.email + '</span></div>';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-primary-light btn-wave';
            btn.textContent = 'إرسال';
            btn.addEventListener('click', async function () {
                const result = await postJson(@json(route('gamification.friends.send-request')), { friend_id: user.id });
                showAlert(result.message || '', result.success ? 'success' : 'danger');
                if (result.success) setTimeout(() => location.reload(), 1000);
            });
            row.appendChild(btn);
            resultsEl.appendChild(row);
        });
    }

    document.getElementById('friendSearchBtn')?.addEventListener('click', runSearch);
    document.getElementById('friendSearchInput')?.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); runSearch(); }
    });

    document.querySelectorAll('.friend-accept-btn').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            const data = await postJson(baseUrl + '/' + this.dataset.id + '/accept');
            showAlert(data.message || '', data.success ? 'success' : 'danger');
            if (data.success) setTimeout(() => location.reload(), 800);
        });
    });

    document.querySelectorAll('.friend-reject-btn').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            const data = await postJson(baseUrl + '/' + this.dataset.id + '/reject');
            showAlert(data.message || '', data.success ? 'success' : 'danger');
            if (data.success) setTimeout(() => location.reload(), 800);
        });
    });

    document.querySelectorAll('.friend-cancel-btn').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            const data = await postJson(baseUrl + '/' + this.dataset.id + '/cancel');
            showAlert(data.message || '', data.success ? 'success' : 'danger');
            if (data.success) setTimeout(() => location.reload(), 800);
        });
    });

    document.querySelectorAll('.friend-send-btn').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            const data = await postJson(@json(route('gamification.friends.send-request')), { friend_id: parseInt(this.dataset.friendId, 10) });
            showAlert(data.message || '', data.success ? 'success' : 'danger');
            if (data.success) setTimeout(() => location.reload(), 800);
        });
    });

    document.querySelectorAll('.friend-unfriend-btn').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            if (!confirm('هل تريد إلغاء الصداقة؟')) return;
            const data = await postJson(@json(route('gamification.friends.unfriend')), { friend_id: parseInt(this.dataset.friendId, 10) });
            showAlert(data.message || '', data.success ? 'success' : 'danger');
            if (data.success) setTimeout(() => location.reload(), 800);
        });
    });
})();
</script>
@endpush
