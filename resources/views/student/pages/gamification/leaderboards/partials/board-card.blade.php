@php
    $topThree = $board->top_three ?? collect();
    $userRank = $board->user_rank ?? null;
    $medals = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
    $typeKey = $board->type ?? 'global';
    $typeClass = in_array($typeKey, ['global', 'weekly', 'monthly', 'course', 'streak', 'speed', 'accuracy', 'social'], true)
        ? $typeKey
        : 'global';
    $delay = ($index ?? 0) * 55;
    $showUrl = route('gamification.leaderboards.show', $board);
@endphp

<div class="col-xl-6 col-lg-6 leaderboard-grid-item" style="--leaderboard-delay: {{ $delay }}ms">
    <article class="gamification-leaderboard-widget gamification-leaderboard-widget--{{ $typeClass }} {{ $userRank ? 'has-rank' : '' }}">
        <span class="gamification-leaderboard-widget__glow" aria-hidden="true"></span>
        <span class="gamification-leaderboard-widget__shine" aria-hidden="true"></span>

        <div class="gamification-leaderboard-widget__header">
            <div class="gamification-leaderboard-widget__icon-wrap">
                <span class="gamification-leaderboard-widget__icon">{{ $board->icon ?? '🏆' }}</span>
            </div>
            <div class="gamification-leaderboard-widget__head-text min-w-0">
                <span class="gamification-leaderboard-widget__type">{{ $catalog->getTypeLabel($board->type) }}</span>
                <h6 class="gamification-leaderboard-widget__title">{{ $board->name }}</h6>
                @if ($board->description)
                    <p class="gamification-leaderboard-widget__desc">{{ Str::limit($board->description, 80) }}</p>
                @endif
            </div>
            @if ($userRank)
                <span class="gamification-leaderboard-widget__my-rank">#{{ $userRank['rank'] }}</span>
            @endif
        </div>

        <div class="gamification-leaderboard-widget__meta">
            <span class="gamification-leaderboard-widget__pill">{{ $catalog->getPeriodLabel($board->period) }}</span>
            <span class="gamification-leaderboard-widget__pill">
                <i class="ri-group-line"></i>{{ number_format($board->entries_count ?? 0) }}
            </span>
        </div>

        @if ($topThree->isNotEmpty())
            <div class="gamification-leaderboard-widget__podium">
                <span class="gamification-leaderboard-widget__podium-label">المتصدرون</span>
                <div class="gamification-leaderboard-widget__podium-list">
                    @foreach ($topThree as $entry)
                        <div class="gamification-leaderboard-widget__podium-item" title="{{ $entry->user->name ?? '' }}">
                            <span class="gamification-leaderboard-widget__podium-medal">{{ $medals[$entry->rank] ?? '#'.$entry->rank }}</span>
                            @include('student.pages.gamification.leaderboards.partials.user-avatar', [
                                'user' => $entry->user,
                                'size' => 'sm',
                            ])
                            <span class="gamification-leaderboard-widget__podium-name">
                                @include('student.pages.gamification.leaderboards.partials.user-name', [
                                    'user' => $entry->user,
                                    'compact' => true,
                                ])
                            </span>
                            <span class="gamification-leaderboard-widget__podium-score">{{ number_format($entry->score) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="gamification-leaderboard-widget__empty-top">
                <i class="ri-trophy-line"></i>
                <span>لا يوجد متصدرون بعد — كن الأول!</span>
            </div>
        @endif

        @if ($userRank)
            <div class="gamification-leaderboard-widget__stats">
                <div class="gamification-leaderboard-widget__stat">
                    <span>نتيجتك</span>
                    <strong>{{ number_format($userRank['score']) }}</strong>
                </div>
                <div class="gamification-leaderboard-widget__stat">
                    <span>أفضل من</span>
                    <strong>{{ $userRank['percentile'] }}%</strong>
                </div>
                <div class="gamification-leaderboard-widget__stat">
                    <span>الفئة</span>
                    @include('student.pages.gamification.leaderboards.partials.division-badge', [
                        'division' => $userRank['division'],
                        'catalog' => $catalog,
                        'size' => 'sm',
                    ])
                </div>
            </div>
            <div class="gamification-leaderboard-widget__progress-track">
                <div class="gamification-leaderboard-widget__progress-bar" style="width: {{ min(100, max(4, $userRank['percentile'])) }}%"></div>
            </div>
        @else
            <p class="gamification-leaderboard-widget__not-ranked">
                <i class="ri-information-line me-1"></i>لم تدخل الترتيب بعد — ابدأ النشاط لكسب نقاط
            </p>
        @endif

        <a href="{{ $showUrl }}" class="gamification-leaderboard-widget__cta btn-wave">
            <i class="ri-bar-chart-horizontal-line me-1"></i>عرض اللوحة
        </a>
    </article>
</div>
