@php
    $activityUser = $activity->user;
    $nameParts = preg_split('/\s+/u', trim($activityUser->name ?? ''), 2);
    $initials = mb_strtoupper(mb_substr($nameParts[0] ?? '?', 0, 1) . mb_substr($nameParts[1] ?? '', 0, 1));
    $photoUrl = ! empty($activityUser->photo) ? asset('storage/' . $activityUser->photo) : null;
    $delay = ($index ?? 0) * 55;

    $typeConfig = match ($activity->type) {
        'achievement_unlocked' => ['variant' => 'achievement', 'icon' => 'ri-trophy-line', 'label' => 'إنجاز'],
        'badge_earned' => ['variant' => 'badge', 'icon' => 'ri-medal-line', 'label' => 'شارة'],
        'level_up' => ['variant' => 'level', 'icon' => 'ri-arrow-up-circle-line', 'label' => 'مستوى جديد'],
        'course_completed' => ['variant' => 'course', 'icon' => 'ri-book-open-line', 'label' => 'كورس'],
        default => ['variant' => 'default', 'icon' => 'ri-sparkling-line', 'label' => 'نشاط'],
    };

    $meta = $activity->metadata ?? [];
    $isLiked = (bool) ($activity->is_liked_by_me ?? false);
    $isMine = auth()->id() === ($activityUser->id ?? null);
@endphp

<div class="col-12 social-feed-item" style="--social-delay: {{ $delay }}ms">
    <article class="gamification-social-widget gamification-social-widget--{{ $typeConfig['variant'] }}"
             data-activity-id="{{ $activity->id }}">
        <span class="gamification-social-widget__glow" aria-hidden="true"></span>
        <span class="gamification-social-widget__shine" aria-hidden="true"></span>

        <header class="gamification-social-widget__header">
            <div class="gamification-social-widget__avatar-wrap">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="{{ $activityUser->name }}" class="gamification-social-widget__avatar" loading="lazy"
                         onerror="this.hidden=true;this.nextElementSibling.hidden=false;">
                @endif
                <span class="gamification-social-widget__avatar-fallback" @if($photoUrl) hidden @endif>{{ $initials }}</span>
            </div>
            <div class="gamification-social-widget__head-meta">
                <h6 class="gamification-social-widget__name">{{ $activityUser->name ?? 'طالب' }}</h6>
                <span class="gamification-social-widget__time">{{ $activity->created_at?->diffForHumans() }}</span>
            </div>
            <span class="gamification-social-widget__type">
                <i class="{{ $typeConfig['icon'] }}"></i> {{ $typeConfig['label'] }}
            </span>
        </header>

        <div class="gamification-social-widget__body">
            <p class="gamification-social-widget__text">{{ $activity->description }}</p>

            @if(! empty($meta))
                <div class="gamification-social-widget__highlight">
                    @if(! empty($meta['achievement_name']))
                        <span><i class="ri-trophy-line"></i> {{ $meta['achievement_name'] }}</span>
                    @elseif(! empty($meta['badge_name']))
                        <span><i class="ri-medal-line"></i> {{ $meta['badge_name'] }}</span>
                    @elseif(! empty($meta['course_title']))
                        <span><i class="ri-book-open-line"></i> {{ $meta['course_title'] }}</span>
                    @elseif(! empty($meta['new_level']))
                        <span><i class="ri-shield-star-line"></i> المستوى {{ $meta['new_level'] }}</span>
                    @endif
                </div>
            @endif
        </div>

        <footer class="gamification-social-widget__footer">
            <div class="gamification-social-widget__actions">
                <button type="button"
                        class="gamification-social-widget__action social-like-btn {{ $isLiked ? 'is-active' : '' }}"
                        data-id="{{ $activity->id }}"
                        data-liked="{{ $isLiked ? '1' : '0' }}"
                        data-like-url="{{ route('gamification.social.like', $activity) }}"
                        data-unlike-url="{{ route('gamification.social.unlike', $activity) }}">
                    <i class="{{ $isLiked ? 'ri-thumb-up-fill' : 'ri-thumb-up-line' }}"></i>
                    <span class="social-like-count">{{ number_format($activity->likes_count ?? 0) }}</span>
                </button>
                <button type="button" class="gamification-social-widget__action social-toggle-comments"
                        data-target="comments-{{ $activity->id }}">
                    <i class="ri-chat-3-line"></i>
                    <span>{{ number_format($activity->comments_count ?? 0) }}</span>
                </button>
                @if($isMine)
                    <button type="button" class="gamification-social-widget__action gamification-social-widget__action--danger social-delete-btn"
                            data-id="{{ $activity->id }}"
                            data-url="{{ route('gamification.social.delete-activity', $activity) }}">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endif
            </div>

            <div id="comments-{{ $activity->id }}" class="gamification-social-widget__comments d-none">
                @forelse($activity->comments->take(5) as $comment)
                    @php
                        $commentUser = $comment->user;
                        $cParts = preg_split('/\s+/u', trim($commentUser->name ?? ''), 2);
                        $cInitials = mb_strtoupper(mb_substr($cParts[0] ?? '?', 0, 1) . mb_substr($cParts[1] ?? '', 0, 1));
                    @endphp
                    <div class="gamification-social-widget__comment">
                        <span class="gamification-social-widget__comment-avatar">{{ $cInitials }}</span>
                        <div>
                            <strong>{{ $commentUser->name ?? 'طالب' }}</strong>
                            <p>{{ $comment->content }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted fs-12 mb-2">لا تعليقات بعد — كن أول من يعلق!</p>
                @endforelse

                <form class="gamification-social-widget__comment-form social-comment-form" data-id="{{ $activity->id }}"
                      data-url="{{ route('gamification.social.comment', $activity) }}">
                    @csrf
                    <input type="text" name="content" class="form-control form-control-sm" maxlength="500"
                           placeholder="اكتب تعليقاً..." required>
                    <button type="submit" class="btn btn-sm btn-primary btn-wave">
                        <i class="ri-send-plane-line"></i>
                    </button>
                </form>
            </div>
        </footer>
    </article>
</div>
