@php
    $course = $course ?? null;
    $whatYouLearn = old('what_you_learn', $course?->what_you_learn ? implode("\n", $course->what_you_learn) : '');
    $requirements = old('requirements', $course?->requirements ? implode("\n", $course->requirements) : '');
@endphp
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-book-open-line me-1 text-primary"></i> المحتوى</h6>
            </div>
            <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold" for="title">العنوان <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title"
                       class="form-control form-input-enhanced @error('title') is-invalid @enderror"
                       value="{{ old('title', $course?->title) }}" required placeholder="أدخل عنوان الكورس">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" for="slug">الرابط (Slug)</label>
                <div class="input-group slug-input-group">
                    <input type="text" name="slug" id="slug"
                           class="form-control form-input-enhanced @error('slug') is-invalid @enderror"
                           value="{{ old('slug', $course?->slug) }}" dir="ltr" placeholder="course-slug">
                    <button type="button" class="btn btn-light border" id="generateSlug" type="button">
                        <i class="ri-magic-line me-1"></i> توليد تلقائي
                    </button>
                </div>
                <small class="text-muted fs-12 d-block mt-1">
                    <i class="ri-link me-1"></i> يُولَّد تلقائياً من العنوان — يدعم العربية والإنجليزية
                </small>
                @error('slug')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" for="excerpt">المقتطف</label>
                <textarea name="excerpt" id="excerpt" rows="3"
                          class="form-control form-input-enhanced @error('excerpt') is-invalid @enderror"
                          placeholder="نبذة مختصرة تظهر في بطاقة الكورس">{{ old('excerpt', $course?->excerpt) }}</textarea>
                @error('excerpt')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" for="description">الوصف التفصيلي</label>
                <textarea name="description" id="description"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $course?->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">ماذا ستتعلم (سطر لكل نقطة)</label>
                <textarea name="what_you_learn" rows="4" class="form-control">{{ $whatYouLearn }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">المتطلبات (سطر لكل نقطة)</label>
                <textarea name="requirements" rows="3" class="form-control">{{ $requirements }}</textarea>
            </div>
            @if($course)
                <div class="alert alert-light border mb-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <div class="fw-semibold mb-1"><i class="ri-list-ordered me-1 text-primary"></i> منهج الكورس</div>
                            <span class="text-muted fs-13">
                                {{ $course->sections_count ?? $course->sections()->count() }} قسم &bull;
                                {{ $course->lessons_count }} درس &bull;
                                {{ $course->duration_hours }} ساعة
                            </span>
                        </div>
                        <a href="{{ route('admin.courses.curriculum', $course) }}" class="btn btn-primary btn-sm">
                            <i class="ri-settings-3-line me-1"></i> إدارة المنهاج
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-info border-0 mb-0 fs-13">
                    <i class="ri-information-line me-1"></i>
                    بعد حفظ الكورس يمكنك إضافة الأقسام والدروس من صفحة «إدارة المنهاج».
                </div>
            @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="sidebar-sticky">
        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-settings-3-line me-1 text-primary"></i> الإعدادات</h6>
            </div>
            <div class="card-body">
            <div class="mb-3">
                <label class="form-label">التصنيف *</label>
                <select name="course_category_id" class="form-select" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('course_category_id', $course?->course_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">المدرب *</label>
                <select name="instructor_id" class="form-select" required>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id', $course?->instructor_id) == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">المستوى *</label>
                <select name="level" class="form-select" required>
                    @foreach(['beginner' => 'مبتدئ', 'intermediate' => 'متوسط', 'advanced' => 'متقدم'] as $val => $label)
                        <option value="{{ $val }}" {{ old('level', $course?->level) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label">السعر *</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $course?->price ?? 0) }}" required>
                </div>
                <div class="col-6">
                    <label class="form-label">السعر قبل الخصم</label>
                    <input type="number" step="0.01" name="compare_at_price" class="form-control" value="{{ old('compare_at_price', $course?->compare_at_price) }}">
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-6"><label class="form-label">التقييم</label><input type="number" step="0.1" name="rating_avg" class="form-control" value="{{ old('rating_avg', $course?->rating_avg ?? 0) }}"></div>
                <div class="col-6"><label class="form-label">عدد الطلاب</label><input type="number" name="students_count" class="form-control" value="{{ old('students_count', $course?->students_count ?? 0) }}"></div>
            </div>
            <div class="mb-3">
                <label class="form-label">الشارة</label>
                <input type="text" name="badge" class="form-control" value="{{ old('badge', $course?->badge) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">أيقونة FontAwesome</label>
                <input type="text" name="icon" class="form-control" value="{{ old('icon', $course?->icon) }}" placeholder="fa-laptop-code">
            </div>
            <div class="mb-3">
                <label class="form-label" for="courseThumbnail">صورة مصغرة</label>
                @if($course?->thumbnail)
                    <div class="mb-2" id="courseThumbnailCurrent">
                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->thumbnail_alt ?? $course->title }}"
                             class="rounded border" style="max-height: 120px; max-width: 100%; object-fit: cover;">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_thumbnail" value="1" id="removeCourseThumbnail">
                            <label class="form-check-label" for="removeCourseThumbnail">حذف الصورة الحالية</label>
                        </div>
                    </div>
                @endif
                <div class="d-none mb-2" id="courseThumbnailPreview">
                    <img src="" alt="معاينة الصورة" class="rounded border" style="max-height: 120px; max-width: 100%; object-fit: cover;">
                </div>
                <input type="file" name="thumbnail" id="courseThumbnail"
                       class="form-control @error('thumbnail') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif">
                <small class="text-muted fs-12 d-block mt-1">JPG, PNG, WebP أو GIF — حتى 5MB</small>
                @error('thumbnail')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">التاغات</label>
                <select name="tags[]" class="form-select" multiple size="5">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $course?->tags->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">الحالة *</label>
                <select name="status" class="form-select" required>
                    <option value="draft" {{ old('status', $course?->status) === 'draft' ? 'selected' : '' }}>مسودة</option>
                    <option value="published" {{ old('status', $course?->status) === 'published' ? 'selected' : '' }}>منشور</option>
                </select>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured', $course?->is_featured) ? 'checked' : '' }}>
                <label class="form-check-label">كورس مميز</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">حفظ الكورس</button>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-light border w-100 mt-2">إلغاء</a>
            </div>
        </div>
        </div>
    </div>
</div>
