@php
    $showQuestionText = $showQuestionText ?? true;
    $questionTextLabel = $questionTextLabel ?? 'نص السؤال';
    $questionTextPlaceholder = $questionTextPlaceholder ?? 'اكتب نص السؤال هنا...';
    $questionTextRows = $questionTextRows ?? 4;
    $gradeLabel = $gradeLabel ?? 'الدرجة';
    $defaultGrade = $defaultGrade ?? 1;
    $gradeMin = $gradeMin ?? 0.5;
    $gradeStep = $gradeStep ?? 0.5;
    $tagsPlaceholder = $tagsPlaceholder ?? 'مثال: رياضيات، جبر';
    $difficultyFieldName = $difficultyFieldName ?? 'difficulty';
    $showExpertDifficulty = $showExpertDifficulty ?? false;
@endphp

<div class="card custom-card form-card qb-type-card mb-4">
    @include('admin.pages.question-bank.partials.type-form-section-title', [
        'icon' => 'ri-information-line',
        'color' => 'primary',
        'title' => 'معلومات السؤال',
        'subtitle' => 'الكورس، الصعوبة، والدرجة',
    ])
    <div class="card-body pt-2">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">الكورس <span class="text-danger">*</span></label>
                @if($selectedCourseId ?? null)
                    <input type="hidden" name="course_id" value="{{ $selectedCourseId }}">
                    <select class="form-select form-input-enhanced" disabled>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $selectedCourseId == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <select name="course_id" class="form-select form-input-enhanced @error('course_id') is-invalid @enderror" required>
                        <option value="">اختر الكورس</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('course_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">الصعوبة <span class="text-danger">*</span></label>
                <select name="{{ $difficultyFieldName }}" class="form-select form-input-enhanced @error($difficultyFieldName) is-invalid @enderror" required>
                    @if($showExpertDifficulty)
                        <option value="">اختر مستوى الصعوبة</option>
                    @endif
                    <option value="easy" {{ old($difficultyFieldName) == 'easy' ? 'selected' : '' }}>سهل</option>
                    <option value="medium" {{ old($difficultyFieldName, 'medium') == 'medium' ? 'selected' : '' }}>متوسط</option>
                    <option value="hard" {{ old($difficultyFieldName) == 'hard' ? 'selected' : '' }}>صعب</option>
                    @if($showExpertDifficulty)
                        <option value="expert" {{ old($difficultyFieldName) == 'expert' ? 'selected' : '' }}>خبير</option>
                    @endif
                </select>
                @error($difficultyFieldName)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($showQuestionText)
                <div class="col-12">
                    <label class="form-label">{{ $questionTextLabel }} <span class="text-danger">*</span></label>
                    <textarea name="question_text" class="form-control form-input-enhanced @error('question_text') is-invalid @enderror qb-rich-text"
                              rows="{{ $questionTextRows }}" placeholder="{{ $questionTextPlaceholder }}" required>{{ old('question_text') }}</textarea>
                    @error('question_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <div class="col-md-6">
                <label class="form-label">{{ $gradeLabel }} <span class="text-danger">*</span></label>
                <input type="number" name="default_grade" class="form-control form-input-enhanced @error('default_grade') is-invalid @enderror"
                       value="{{ old('default_grade', $defaultGrade) }}" min="{{ $gradeMin }}" step="{{ $gradeStep }}" required>
                @error('default_grade')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">الوسوم</label>
                <input type="text" name="tags" class="form-control form-input-enhanced"
                       placeholder="{{ $tagsPlaceholder }}" value="{{ old('tags') }}">
            </div>
        </div>
    </div>
</div>
