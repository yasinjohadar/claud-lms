@if($globalResources->isEmpty())
    <div class="card-body">
        <div class="empty-state py-4">
            <div class="empty-state-icon"><i class="ri-folder-open-line"></i></div>
            <h5 class="fw-bold mb-2">لا توجد موارد عامة</h5>
            <p class="text-muted mb-3">الموارد العامة تظهر في صفحة مستقلة للطلاب ولا ترتبط بقسم محدد.</p>
            <button type="button" class="btn btn-primary btn-sm btn-wave"
                    data-bs-toggle="modal" data-bs-target="#resourceModal"
                    data-resource-mode="create"
                    data-resource-scope="global">
                <i class="ri-add-line me-1"></i> إضافة مورد عام
            </button>
        </div>
    </div>
@else
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th>
                        <th style="min-width: 220px;">المورد</th>
                        <th style="min-width: 100px;">النوع</th>
                        <th style="min-width: 200px;">التفاصيل</th>
                        <th style="min-width: 110px;">الحالة</th>
                        <th style="min-width: 80px;">إجراء</th>
                    </tr>
                </thead>
                <tbody class="curriculum-resources-builder" id="globalResourcesList" data-resource-scope="global">
                    @foreach($globalResources as $resource)
                        <tr class="curriculum-resource-row" data-resource-id="{{ $resource->id }}">
                            <td>
                                <span class="curriculum-drag-handle text-muted" title="سحب لإعادة الترتيب">
                                    <i class="ri-drag-move-2-line"></i>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                        <i class="{{ $resource->isLink() ? 'ri-link' : 'ri-file-line' }}"></i>
                                    </span>
                                    <div>
                                        <span class="fw-bold d-block">{{ $resource->title }}</span>
                                        @if($resource->description)
                                            <span class="text-muted fs-11">{{ Str::limit($resource->description, 50) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-soft {{ $resource->isLink() ? 'badge-soft-info' : 'badge-soft-warning' }}">
                                    <i class="{{ $resource->isLink() ? 'ri-link' : 'ri-file-line' }} me-1"></i>{{ $resource->type_label }}
                                </span>
                            </td>
                            <td>
                                @if($resource->isLink())
                                    <span class="email-copy-wrap">
                                        <span dir="ltr" class="text-primary">{{ Str::limit($resource->url, 40) }}</span>
                                        <button type="button" class="copy-btn" data-copy="{{ $resource->url }}" title="نسخ">
                                            <i class="ri-file-copy-line"></i>
                                        </button>
                                    </span>
                                @elseif($resource->file_original_name)
                                    <span class="meta-text">
                                        <i class="ri-file-line"></i>
                                        {{ Str::limit($resource->file_original_name, 35) }}
                                    </span>
                                    @if($resource->formatted_file_size)
                                        <span class="text-muted fs-11 d-block">{{ $resource->formatted_file_size }}</span>
                                    @endif
                                @else
                                    <span class="text-muted fs-12">—</span>
                                @endif
                            </td>
                            <td>
                                @if($resource->is_published)
                                    <span class="badge-soft badge-soft-success">منشور</span>
                                @else
                                    <span class="badge-soft badge-soft-secondary">غير منشور</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    @if($resource->is_published)
                                        <a href="{{ route('courses.resources.show', [$course->slug, $resource->slug]) }}"
                                           class="action-btn action-btn--view" target="_blank" title="عرض الصفحة">
                                            <i class="ri-external-link-line"></i>
                                        </a>
                                    @endif
                                    <button type="button" class="action-btn action-btn--edit"
                                            data-bs-toggle="modal" data-bs-target="#resourceModal"
                                            data-resource-mode="edit"
                                            data-resource-scope="global"
                                            data-resource-id="{{ $resource->id }}"
                                            data-resource-title="{{ $resource->title }}"
                                            data-resource-type="{{ $resource->type }}"
                                            data-resource-url="{{ $resource->url }}"
                                            data-resource-description="{{ $resource->description }}"
                                            data-resource-section-id="global"
                                            data-resource-file-name="{{ $resource->file_original_name }}"
                                            data-resource-published="{{ $resource->is_published ? '1' : '0' }}"
                                            title="تعديل">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button type="button" class="action-btn action-btn--delete"
                                            data-resource-delete="{{ $resource->id }}"
                                            data-resource-title="{{ $resource->title }}"
                                            title="حذف">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
