@php
    $isEarned = $isEarned ?? false;
    $progressData = $progress ?? ['progress' => $isEarned ? 100 : 0];
    $progressPct = (float) ($progressData['progress'] ?? 0);
    $points = $badge->points_value ?? $badge->points_reward ?? 0;
    $icon = $badge->icon ?? '🏅';
    $isIconClass = is_string($icon) && preg_match('/\b(fa[srb]?|fe|bi|ri)-/', $icon);
    $rarityKey = $badge->rarity ?? 'common';

    $rarityMap = [
        'common' => ['label' => 'عادية'],
        'rare' => ['label' => 'نادرة'],
        'epic' => ['label' => 'ملحمية'],
        'legendary' => ['label' => 'أسطورية'],
        'mythic' => ['label' => 'خرافية'],
    ];
    $rarity = $rarityMap[$rarityKey] ?? $rarityMap['common'];
    $showUrl = isset($badge->id) ? route('gamification.badges.show', $badge) : null;
    $delay = ($index ?? 0) * 45;
@endphp

<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 badge-grid-item"
     data-badge-rarity="{{ $rarityKey }}"
     style="--badge-delay: {{ $delay }}ms">
    @if($showUrl)
        <a href="{{ $showUrl }}" class="gamification-badge-widget gamification-badge-widget--{{ $rarityKey }} {{ $isEarned ? 'is-earned' : 'is-locked' }}">
    @else
        <article class="gamification-badge-widget gamification-badge-widget--{{ $rarityKey }} {{ $isEarned ? 'is-earned' : 'is-locked' }}">
    @endif
        <span class="gamification-badge-widget__glow" aria-hidden="true"></span>
        <span class="gamification-badge-widget__shine" aria-hidden="true"></span>

        <span class="gamification-badge-widget__rarity">{{ $rarity['label'] }}</span>

        <div class="gamification-badge-widget__icon-wrap">
            <span class="gamification-badge-widget__icon">
                @if($isIconClass)
                    <i class="{{ $icon }}"></i>
                @else
                    {{ $icon }}
                @endif
            </span>
            @if($isEarned)
                <span class="gamification-badge-widget__earned-mark" aria-hidden="true">
                    <i class="ri-checkbox-circle-fill"></i>
                </span>
            @endif
        </div>

        <h6 class="gamification-badge-widget__title">{{ $badge->name ?? 'شارة' }}</h6>

        @if(!empty($badge->description))
            <p class="gamification-badge-widget__desc">{{ Str::limit($badge->description, 88) }}</p>
        @endif

        <span class="gamification-badge-widget__points">+{{ number_format($points) }} نقطة</span>

        @if(!$isEarned && $progressPct > 0 && $progressPct < 100)
            <div class="gamification-badge-widget__progress">
                <div class="gamification-badge-widget__progress-meta">
                    <span>التقدم</span>
                    <span>{{ number_format($progressPct, 0) }}%</span>
                </div>
                <div class="gamification-badge-widget__progress-track">
                    <div class="gamification-badge-widget__progress-bar" style="width: {{ max(6, min(100, $progressPct)) }}%"></div>
                </div>
            </div>
        @endif

        <span class="gamification-badge-widget__status">
            @if($isEarned)
                <i class="ri-checkbox-circle-line"></i>
                @if(!empty($awardedAt))
                    {{ $awardedAt instanceof \Carbon\Carbon ? $awardedAt->format('Y/m/d') : \Carbon\Carbon::parse($awardedAt)->format('Y/m/d') }}
                @else
                    مكتسبة
                @endif
            @else
                <i class="ri-lock-line"></i> غير مكتسبة
            @endif
        </span>
    @if($showUrl)
        </a>
    @else
        </article>
    @endif
</div>
