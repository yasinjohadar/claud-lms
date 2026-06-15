<div class="card custom-card data-table-card mt-4">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold fs-16">الطلاب المسجّلون</span>
            <span class="table-count-badge">{{ $enrollments->total() }}</span>
        </div>
        @can('enrollment-manage')
            <button type="button" class="btn btn-success btn-sm btn-wave" data-open-enrollment-grant
                    data-modal-id="courseEditEnrollmentModal"
                    data-course-id="{{ $course->id }}"
                    data-course-label="{{ $course->title }}">
                <i class="ri-user-add-line me-1"></i> إضافة طالب
            </button>
        @endcan
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th>الطالب</th>
                        <th>الحالة</th>
                        <th>المصدر</th>
                        <th>التقدم</th>
                        <th>تاريخ التسجيل</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                        @php
                            $student = $enrollment->student;
                            $user = $student?->user;
                            $statusBadge = match ($enrollment->status) {
                                'active' => 'badge-soft-success',
                                'pending' => 'badge-soft-warning',
                                'completed' => 'badge-soft-primary',
                                'cancelled', 'refunded' => 'badge-soft-danger',
                                default => 'badge-soft-secondary',
                            };
                        @endphp
                        <tr>
                            <td>
                                @if($student)
                                    <a href="{{ route('admin.students.show', $student) }}" class="fw-semibold row-title-link text-decoration-none">
                                        {{ $user?->name ?? '—' }}
                                    </a>
                                    <div class="text-muted fs-11">{{ $student->student_code }}</div>
                                @else
                                    —
                                @endif
                            </td>
                            <td><span class="badge-soft {{ $statusBadge }}">{{ $enrollment->status_label }}</span></td>
                            <td>{{ $enrollment->source_label }}</td>
                            <td>{{ $enrollment->progress_percent }}%</td>
                            <td class="text-muted fs-12">{{ $enrollment->enrolled_at?->locale('ar')->translatedFormat('j M Y') }}</td>
                            <td>
                                @if($enrollment->status !== 'cancelled')
                                    <button type="button" class="action-btn action-btn--delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cancelCourseEnrollment{{ $enrollment->id }}"
                                            title="إلغاء">
                                        <i class="ri-close-circle-line"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">لا يوجد طلاب مسجّلون في هذا الكورس بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($enrollments->hasPages())
            <div class="card-footer border-top">
                {{ $enrollments->withQueryString()->links() }}
            </div>
        @endif
    </div>
    <div class="card-footer bg-transparent border-top-0 pt-0">
        <a href="{{ route('admin.enrollments.index', ['course_id' => $course->id]) }}" class="btn btn-sm btn-light border">
            <i class="ri-external-link-line me-1"></i> عرض كل التسجيلات لهذا الكورس
        </a>
    </div>
</div>

@foreach($enrollments as $enrollment)
    @if($enrollment->status !== 'cancelled')
        <x-admin.confirm-modal
            :id="'cancelCourseEnrollment' . $enrollment->id"
            title="إلغاء التسجيل"
            message="سيتم إلغاء وصول الطالب إلى هذا الكورس."
            :subject="$enrollment->student?->user?->name"
            :action="route('admin.enrollments.destroy', $enrollment)"
            method="DELETE"
            variant="warning"
            confirm-text="نعم، ألغِ التسجيل"
        />
    @endif
@endforeach
