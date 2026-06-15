<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="min-width: 180px;">الطالب</th>
                    <th style="min-width: 200px;">الكورس</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 110px;">المصدر</th>
                    <th style="min-width: 120px;">التقدم</th>
                    <th style="min-width: 110px;">التاريخ</th>
                    <th style="min-width: 100px;">الطلب</th>
                    <th style="min-width: 80px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                    @php
                        $student = $enrollment->student;
                        $user = $student?->user;
                        $course = $enrollment->course;
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
                                <a href="{{ route('admin.students.show', $student) }}" class="fw-bold row-title-link text-decoration-none">
                                    {{ $user?->name ?? '—' }}
                                </a>
                                <div class="text-muted fs-11">{{ $student->student_code }}</div>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($course)
                                <span class="fw-semibold">{{ $course->title }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td><span class="badge-soft {{ $statusBadge }}">{{ $enrollment->status_label }}</span></td>
                        <td>{{ $enrollment->source_label }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-fill rounded-pill" style="height: 6px; min-width: 60px;">
                                    <div class="progress-bar bg-success rounded-pill" style="width: {{ max($enrollment->progress_percent, $enrollment->progress_percent > 0 ? 6 : 0) }}%"></div>
                                </div>
                                <span class="fs-12 text-muted">{{ $enrollment->progress_percent }}%</span>
                            </div>
                        </td>
                        <td class="text-muted fs-12">
                            {{ $enrollment->enrolled_at?->locale('ar')->translatedFormat('j M Y') ?? '—' }}
                        </td>
                        <td>
                            @if($enrollment->order)
                                <a href="{{ route('admin.orders.show', $enrollment->order) }}" class="badge-soft badge-soft-warning text-decoration-none">
                                    {{ $enrollment->order->order_number }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($enrollment->status !== 'cancelled')
                                <div class="action-btn-group">
                                    <button type="button"
                                            class="action-btn action-btn--delete"
                                            title="إلغاء التسجيل"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cancelEnrollment{{ $enrollment->id }}">
                                        <i class="ri-close-circle-line"></i>
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon"><i class="ri-book-mark-line"></i></div>
                                <h5 class="fw-bold mb-2">لا توجد تسجيلات</h5>
                                <p class="text-muted mb-0">جرّب تغيير الفلاتر أو أنشئ تسجيلاً جديداً.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($enrollments->hasPages())
        <div class="card-footer border-top">
            {{ $enrollments->links() }}
        </div>
    @endif
</div>
