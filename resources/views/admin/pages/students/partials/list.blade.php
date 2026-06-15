<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 200px;">الطالب</th>
                    <th style="min-width: 120px;">رمز الطالب</th>
                    <th style="min-width: 220px;">البريد</th>
                    <th style="min-width: 110px;">الحالة</th>
                    <th style="min-width: 110px;">التفعيل</th>
                    <th style="min-width: 100px;">كورسات</th>
                    <th style="min-width: 120px;">تاريخ التسجيل</th>
                    <th style="min-width: 80px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    @php
                        $user = $student->user;
                        $initials = $user ? mb_strtoupper(mb_substr($user->name, 0, 1)) : 'ط';
                    @endphp
                    <tr>
                        <td class="text-muted fw-medium">{{ $students->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">{{ $initials }}</span>
                                <div>
                                    <a href="{{ route('admin.students.show', $student) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $user?->name ?? '—' }}
                                    </a>
                                    @if($user?->phone)
                                        <span class="text-muted fs-11" dir="ltr">{{ $user->phone }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-soft badge-soft-primary">{{ $student->student_code ?? '—' }}</span></td>
                        <td>
                            @if ($user?->email)
                                <span dir="ltr" class="text-primary">{{ $user->email }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = match($student->status) {
                                    'active' => 'badge-soft-success',
                                    'suspended' => 'badge-soft-warning',
                                    'graduated' => 'badge-soft-info',
                                    default => 'badge-soft-secondary',
                                };
                            @endphp
                            <span class="badge-soft {{ $statusClass }}">{{ $student->status_label }}</span>
                        </td>
                        <td>
                            @php
                                $isStudentActive = $student->status === 'active' && ($user?->is_active ?? false);
                                $canToggle = $user && auth()->id() !== $user->id && in_array($student->status, ['active', 'suspended', 'inactive'], true);
                            @endphp
                            @can('student-toggle-status')
                                @if($canToggle)
                                    <x-admin.activation-toggle
                                        :entity-id="$student->id"
                                        :is-active="$isStudentActive"
                                        :subject="$user->name"
                                        :subject-meta="$user->email"
                                        entity-type="student"
                                        active-label="نشط"
                                        inactive-label="موقوف"
                                    />
                                @else
                                    <span class="text-muted fs-12">—</span>
                                @endif
                            @else
                                <span class="badge-soft {{ $isStudentActive ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                    {{ $isStudentActive ? 'نشط' : 'موقوف' }}
                                </span>
                            @endcan
                        </td>
                        <td>{{ $student->enrollments()->where('status', 'active')->count() }}</td>
                        <td>
                            <span class="meta-text">
                                {{ $student->created_at?->locale('ar')->translatedFormat('j M Y') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a class="action-btn action-btn--view" title="عرض"
                                   href="{{ route('admin.students.show', $student) }}">
                                    <i class="ri-eye-line"></i>
                                </a>
                                @can('enrollment-manage')
                                <button type="button"
                                        class="action-btn action-btn--success"
                                        title="تسجيل في كورس"
                                        data-open-enrollment-grant
                                        data-modal-id="studentsIndexGrantModal"
                                        data-student-id="{{ $student->id }}"
                                        data-student-label="{{ e(($user?->name ?? 'طالب') . ($student->student_code ? ' — #' . $student->student_code : '')) }}">
                                    <i class="ri-user-add-line"></i>
                                </button>
                                @endcan
                                <a class="action-btn action-btn--edit" title="تعديل"
                                   href="{{ route('admin.students.edit', $student) }}">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteStudent{{ $student->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-graduation-cap-line"></i></div>
                                <h5 class="fw-bold mb-2">لا يوجد طلاب</h5>
                                <p class="text-muted mb-3">لم يتم العثور على طلاب مطابقين.</p>
                                <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">إضافة طالب</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $students->withQueryString()->links() }}
        </div>
    @endif
</div>
