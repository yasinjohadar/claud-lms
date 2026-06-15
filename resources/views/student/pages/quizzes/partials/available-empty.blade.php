<div class="col-12">
    <div class="empty-state py-5">
        <div class="empty-state-icon mx-auto mb-3"><i class="ri-clipboard-line"></i></div>
        <p class="text-muted mb-1">لا توجد اختبارات متاحة حالياً</p>
        <p class="text-muted fs-12 mb-3">جرّب تغيير الفلاتر أو عد لاحقاً عند إضافة اختبارات جديدة</p>
        @if(request()->hasAny(['course_id', 'quiz_type']))
            <a href="{{ route('student.quizzes.index') }}" class="btn btn-sm btn-primary btn-wave">
                <i class="ri-refresh-line me-1"></i>إعادة تعيين الفلاتر
            </a>
        @endif
    </div>
</div>
