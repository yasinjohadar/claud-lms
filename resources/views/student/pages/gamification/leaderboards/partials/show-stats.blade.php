@php
    $catalog = $catalog ?? app(\App\Services\Gamification\LeaderboardCatalog::class);
    $metricLabel = $catalog->getMetricLabel($catalog->resolveMetric($leaderboard));

    $statCards = [
        [
            'variant' => 'purple',
            'icon' => 'ri-group-line',
            'label' => 'المشاركون',
            'value' => number_format($stats['total_participants'] ?? 0),
            'hint' => 'طلاب في هذه اللوحة',
        ],
        [
            'variant' => 'orange',
            'icon' => 'ri-trophy-line',
            'label' => 'أعلى ' . $metricLabel,
            'value' => isset($stats['highest_score']) ? number_format($stats['highest_score']) : '—',
            'hint' => 'أفضل نتيجة مسجّلة',
        ],
        [
            'variant' => 'cyan',
            'icon' => 'ri-bar-chart-line',
            'label' => 'المتوسط',
            'value' => isset($stats['average_score']) ? number_format(round($stats['average_score'])) : '—',
            'hint' => 'متوسط النتائج',
        ],
    ];

    if (!empty($userRank)) {
        $statCards[] = [
            'variant' => 'green',
            'icon' => 'ri-user-star-line',
            'label' => 'ترتيبك',
            'value' => '#' . $userRank['rank'],
            'hint' => 'من ' . number_format($userRank['total_participants']) . ' مشارك',
        ];
    } else {
        $statCards[] = [
            'variant' => 'green',
            'icon' => 'ri-user-star-line',
            'label' => 'ترتيبك',
            'value' => '—',
            'hint' => 'لم تدخل الترتيب بعد',
        ];
    }
@endphp

<div class="row g-3 mb-4">
    @foreach ($statCards as $card)
        @include('admin.partials.ui.stat-card-gradient', $card)
    @endforeach
</div>

@if (!empty($userRank))
    <div class="student-leaderboard-my-rank mb-4">
        <div class="student-leaderboard-my-rank__item">
            <span class="student-leaderboard-my-rank__label">ترتيبك</span>
            <strong>#{{ $userRank['rank'] }}</strong>
            <small>من {{ number_format($userRank['total_participants']) }}</small>
        </div>
        <div class="student-leaderboard-my-rank__item">
            <span class="student-leaderboard-my-rank__label">{{ $metricLabel }}</span>
            <strong class="text-primary">{{ number_format($userRank['score']) }}</strong>
        </div>
        <div class="student-leaderboard-my-rank__item">
            <span class="student-leaderboard-my-rank__label">الفئة</span>
            @include('student.pages.gamification.leaderboards.partials.division-badge', [
                'division' => $userRank['division'],
                'catalog' => $catalog,
                'size' => 'sm',
            ])
        </div>
        @if (($userRank['rank_change'] ?? 0) !== 0)
            <div class="student-leaderboard-my-rank__item">
                <span class="student-leaderboard-my-rank__label">التغيّر</span>
                <strong class="{{ $userRank['rank_change'] > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $userRank['rank_change'] > 0 ? '↑' : '↓' }}{{ abs($userRank['rank_change']) }}
                </strong>
            </div>
        @endif
    </div>
@endif
