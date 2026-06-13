<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 220px;">العضو</th>
                    <th style="min-width: 120px;">المجموعة</th>
                    <th style="min-width: 100px;">المصدر</th>
                    <th style="min-width: 90px;">التقييم</th>
                    <th style="min-width: 120px;">العرض</th>
                    <th style="min-width: 90px;">الحالة</th>
                    <th style="min-width: 80px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr>
                        <td class="text-muted fw-medium">{{ $members->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($member->avatar_url)
                                    <img src="{{ $member->avatar_url }}" class="row-avatar row-avatar--img" alt="">
                                @else
                                    <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                        <i class="{{ $member->avatar_icon ?: 'ri-user-line' }}"></i>
                                    </span>
                                @endif
                                <div>
                                    <a href="{{ route('admin.team-members.edit', $member) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $member->display_name }}
                                    </a>
                                    <span class="text-muted fs-11 en-text">{{ $member->role_title }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-primary">{{ $member->team_group_label }}</span>
                        </td>
                        <td>
                            @if($member->isLinkedToUser())
                                <span class="badge-soft badge-soft-info">
                                    <i class="ri-link me-1"></i> مستخدم
                                </span>
                            @else
                                <span class="badge-soft badge-soft-secondary">يدوي</span>
                            @endif
                        </td>
                        <td>
                            @if($member->rating)
                                <span class="en-text fw-medium">{{ number_format($member->rating, 1) }}</span>
                                <i class="ri-star-fill text-warning fs-12"></i>
                            @else
                                <span class="text-muted fs-12">—</span>
                            @endif
                        </td>
                        <td>
                            @if($member->show_on_home)
                                <span class="badge-soft badge-soft-success me-1">رئيسية</span>
                            @endif
                            @if($member->show_on_page)
                                <span class="badge-soft badge-soft-info">من نحن</span>
                            @endif
                            @if(!$member->show_on_home && !$member->show_on_page)
                                <span class="text-muted fs-12">—</span>
                            @endif
                        </td>
                        <td>
                            @if($member->is_published)
                                <span class="badge-soft badge-soft-success">منشور</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">مسودة</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('admin.team-members.edit', $member) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteTeamMember{{ $member->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-team-line"></i></div>
                                @if(request()->hasAny(['search', 'team_group', 'source', 'status']))
                                    <h5 class="fw-bold mb-2">لا توجد نتائج</h5>
                                    <p class="text-muted mb-3">لم يتم العثور على أعضاء مطابقين.</p>
                                    <button type="button" class="btn btn-light border btn-sm" data-ajax-reset>
                                        <i class="ri-refresh-line me-1"></i> إعادة تعيين
                                    </button>
                                @else
                                    <h5 class="fw-bold mb-2">لا يوجد أعضاء فريق</h5>
                                    <p class="text-muted mb-3">ابدأ بإضافة أعضاء الفريق لعرضهم في الموقع.</p>
                                    <a href="{{ route('admin.team-members.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i> إضافة عضو
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($members->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $members->withQueryString()->links() }}
        </div>
    @endif
</div>
