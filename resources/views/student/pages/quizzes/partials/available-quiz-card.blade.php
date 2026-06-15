@php
    $typeMap = [
        'practice' => ['variant' => 'practice', 'label' => 'تدريبي', 'icon' => 'ri-edit-line'],
        'graded' => ['variant' => 'graded', 'label' => 'مُقيّم', 'icon' => 'ri-award-line'],
        'final_exam' => ['variant' => 'final', 'label' => 'نهائي', 'icon' => 'ri-flag-line'],
    ];
    $type = $typeMap[$quiz->quiz_type] ?? ['variant' => 'default', 'label' => $quiz->quiz_type, 'icon' => 'ri-question-answer-line'];
    $bestPct = $quiz->best_attempt ? (float) $quiz->best_attempt->percentage_score : 0;
    $passed = $quiz->best_attempt && $quiz->best_attempt->passed;
    $delay = ($index ?? 0) * 50;
    $hasAttempts = ($quiz->student_attempts_count ?? 0) > 0;
@endphp

<div class="col-xl-4 col-lg-6 quiz-grid-item" style="--quiz-delay: {{ $delay }}ms">
    <article class="gamification-quiz-widget gamification-quiz-widget--{{ $type['variant'] }} {{ $hasAttempts ? 'has-attempts' : '' }} {{ $passed ? 'is-passed' : ($hasAttempts ? 'is-attempted' : '') }}">
        <span class="gamification-quiz-widget__glow" aria-hidden="true"></span>
        <span class="gamification-quiz-widget__shine" aria-hidden="true"></span>

        <span class="gamification-quiz-widget__type">
            <i class="{{ $type['icon'] }}"></i> {{ $type['label'] }}
        </span>

        <div class="gamification-quiz-widget__icon-wrap">
            <span class="gamification-quiz-widget__icon"><i class="ri-clipboard-line"></i></span>
        </div>

        <h6 class="gamification-quiz-widget__title">
            <a href="{{ route('student.quizzes.show', $quiz->id) }}">{{ $quiz->title }}</a>
        </h6>

        @if($quiz->course)
            <p class="gamification-quiz-widget__course">
                <i class="ri-book-open-line"></i> {{ Str::limit($quiz->course->title, 42) }}
            </p>
        @endif

        @if($quiz->description)
            <p class="gamification-quiz-widget__desc">{{ Str::limit($quiz->description, 85) }}</p>
        @endif

        <div class="gamification-quiz-widget__meta">
            <span><i class="ri-question-line"></i> {{ $quiz->getQuestionCount() }} سؤال</span>
            <span><i class="ri-star-line"></i> {{ number_format($quiz->max_score, 0) }} درجة</span>
            <span><i class="ri-time-line"></i> {{ $quiz->time_limit ? $quiz->time_limit . ' د' : 'مفتوح' }}</span>
            <span><i class="ri-loop-left-line"></i> {{ $quiz->remaining_attempts ?? '∞' }}</span>
        </div>

        @if($quiz->due_date)
            <div class="gamification-quiz-widget__due {{ $quiz->due_date->isPast() ? 'is-expired' : '' }}">
                <i class="ri-calendar-event-line"></i>
                {{ $quiz->due_date->isPast() ? 'انتهى: ' : 'ينتهي: ' }}{{ $quiz->due_date->format('Y-m-d H:i') }}
            </div>
        @endif

        @if($hasAttempts)
            <div class="gamification-quiz-widget__progress">
                <div class="gamification-quiz-widget__progress-meta">
                    <span>المحاولات: {{ $quiz->student_attempts_count }}</span>
                    @if($quiz->best_attempt)
                        <span class="gamification-quiz-widget__score {{ $passed ? 'is-passed' : 'is-failed' }}">
                            أفضل: {{ number_format($bestPct, 1) }}%
                        </span>
                    @endif
                </div>
                <div class="gamification-quiz-widget__progress-track">
                    <div class="gamification-quiz-widget__progress-bar {{ $passed ? 'is-passed' : 'is-failed' }}"
                         style="width: {{ min(100, max(0, $bestPct)) }}%"></div>
                </div>
            </div>
        @endif

        <div class="gamification-quiz-widget__footer">
            @if($quiz->can_attempt)
                <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="btn btn-sm btn-primary btn-wave">
                    <i class="ri-play-line me-1"></i>
                    {{ $hasAttempts ? 'محاولة جديدة' : 'بدء الاختبار' }}
                </a>
            @else
                <button type="button" class="btn btn-sm btn-light border" disabled>
                    <i class="ri-lock-line me-1"></i>غير متاح
                </button>
            @endif
            @if($hasAttempts)
                <a href="{{ route('student.quizzes.review.history', $quiz->id) }}" class="btn btn-sm btn-light border btn-wave">
                    <i class="ri-history-line me-1"></i>المحاولات
                </a>
            @endif
        </div>
    </article>
</div>
