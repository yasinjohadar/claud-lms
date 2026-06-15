@php
    $user = $friend ?? $user ?? null;
    $nameParts = preg_split('/\s+/u', trim($user->name ?? ''), 2);
    $initials = mb_strtoupper(mb_substr($nameParts[0] ?? '?', 0, 1) . mb_substr($nameParts[1] ?? '', 0, 1));
    $level = $user->stats?->current_level ?? 1;
    $points = $user->stats?->total_points ?? 0;
    $badges = $user->stats?->total_badges ?? 0;
    $photoUrl = ! empty($user->photo) ? asset('storage/' . $user->photo) : null;
    $delay = ($index ?? 0) * 45;
    $variant = $variant ?? 'friend';
@endphp

<div class="col-xl-4 col-lg-6 friend-grid-item" style="--friend-delay: {{ $delay }}ms">
    <article class="gamification-friend-widget gamification-friend-widget--{{ $variant }}">
        <span class="gamification-friend-widget__glow" aria-hidden="true"></span>
        <span class="gamification-friend-widget__shine" aria-hidden="true"></span>

        <div class="gamification-friend-widget__avatar-wrap">
            @if($photoUrl)
                <img src="{{ $photoUrl }}" alt="{{ $user->name }}" class="gamification-friend-widget__avatar" loading="lazy"
                     onerror="this.hidden=true;this.nextElementSibling.hidden=false;">
            @endif
            <span class="gamification-friend-widget__avatar-fallback" @if($photoUrl) hidden @endif>{{ $initials }}</span>
        </div>

        <h6 class="gamification-friend-widget__name">{{ $user->name }}</h6>
        <p class="gamification-friend-widget__email">{{ $user->email }}</p>

        <div class="gamification-friend-widget__meta">
            <span><i class="ri-shield-star-line"></i> مستوى {{ $level }}</span>
            <span><i class="ri-coin-line"></i> {{ number_format($points) }}</span>
            @if($badges > 0)
                <span><i class="ri-medal-line"></i> {{ $badges }}</span>
            @endif
        </div>

        <div class="gamification-friend-widget__actions">
            @if(($mode ?? 'friend') === 'friend')
                <button type="button" class="gamification-friend-widget__btn gamification-friend-widget__btn--danger friend-unfriend-btn"
                        data-friend-id="{{ $user->id }}">
                    <i class="ri-user-unfollow-line"></i> إلغاء الصداقة
                </button>
            @elseif(($mode ?? '') === 'incoming')
                <button type="button" class="gamification-friend-widget__btn gamification-friend-widget__btn--success friend-accept-btn"
                        data-id="{{ $requestId }}">
                    <i class="ri-check-line"></i> قبول
                </button>
                <button type="button" class="gamification-friend-widget__btn gamification-friend-widget__btn--muted friend-reject-btn"
                        data-id="{{ $requestId }}">
                    <i class="ri-close-line"></i> رفض
                </button>
            @elseif(($mode ?? '') === 'outgoing')
                <button type="button" class="gamification-friend-widget__btn gamification-friend-widget__btn--muted friend-cancel-btn"
                        data-id="{{ $requestId }}">
                    <i class="ri-close-circle-line"></i> إلغاء الطلب
                </button>
            @elseif(($mode ?? '') === 'suggest')
                <button type="button" class="gamification-friend-widget__btn gamification-friend-widget__btn--primary friend-send-btn"
                        data-friend-id="{{ $user->id }}">
                    <i class="ri-user-add-line"></i> إرسال طلب
                </button>
            @endif
        </div>
    </article>
</div>
