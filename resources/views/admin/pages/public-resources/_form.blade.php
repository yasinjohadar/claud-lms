@php
    $resource = $resource ?? null;
    $type = old('type', $resource?->type ?? 'link');
@endphp

<div class="row g-4">
    <div class="col-lg-4 order-lg-2">
        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-shield-check-line me-1 text-primary"></i> حالة المورد</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="resourceTypeSide">نوع المورد</label>
                    <select name="type" id="resourceTypeSide" class="form-select form-input-enhanced" required>
                        <option value="link" {{ $type === 'link' ? 'selected' : '' }}>رابط خارجي</option>
                        <option value="file" {{ $type === 'file' ? 'selected' : '' }}>ملف للتحميل</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="resourceSortOrder">الترتيب</label>
                    <input type="number" name="sort_order" id="resourceSortOrder"
                           class="form-control form-input-enhanced" min="0"
                           value="{{ old('sort_order', $resource?->sort_order ?? 0) }}">
                </div>
                <div class="account-switch-panel">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="is_published" id="resourcePublished" value="1"
                               {{ old('is_published', $resource?->is_published ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="resourcePublished">مورد منشور</label>
                    </div>
                    <p class="text-muted fs-12 mb-0 mt-2">الموارد المنشورة متاحة للاستخدام والربط الداخلي.</p>
                </div>
            </div>
        </div>

        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-information-line me-1 text-primary"></i> معلومات</h6>
            </div>
            <div class="card-body text-muted fs-13 lh-lg">
                <p class="mb-2">الموارد العامة <strong>غير مرتبطة بأي كورس</strong>.</p>
                <p class="mb-0">يمكن أن يكون المورد رابطاً خارجياً أو ملفاً مرفوعاً.</p>
                @if($resource?->isLink() && $resource->url)
                    <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer"
                       class="btn btn-light border btn-sm w-100 mt-3">
                        <i class="ri-external-link-line me-1"></i> فتح الرابط
                    </a>
                @elseif($resource?->isFile() && $resource->file_url)
                    <a href="{{ $resource->file_url }}" target="_blank"
                       class="btn btn-light border btn-sm w-100 mt-3">
                        <i class="ri-download-line me-1"></i> فتح الملف
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8 order-lg-1">
        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-folder-open-line me-1 text-primary"></i> البيانات الأساسية</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="resourceTitle">العنوان <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="resourceTitle"
                           class="form-control form-input-enhanced @error('title') is-invalid @enderror"
                           value="{{ old('title', $resource?->title) }}" placeholder="عنوان المورد" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="publicResourceLinkField" style="{{ $type === 'file' ? 'display:none' : '' }}">
                    <label class="form-label fw-semibold" for="resourceUrl">الرابط <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="resourceUrl"
                           class="form-control form-input-enhanced @error('url') is-invalid @enderror"
                           dir="ltr" value="{{ old('url', $resource?->url) }}" placeholder="https://">
                    @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="publicResourceFileField" style="{{ $type === 'link' ? 'display:none' : '' }}">
                    <label class="form-label fw-semibold" for="resourceFile">الملف @if(!$resource)<span class="text-danger">*</span>@endif</label>
                    <input type="file" name="file" id="resourceFile"
                           class="form-control form-input-enhanced @error('file') is-invalid @enderror">
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($resource?->file_original_name)
                        <p class="text-muted fs-12 mb-0 mt-2">
                            الملف الحالي: <strong>{{ $resource->file_original_name }}</strong>
                            @if($resource->formatted_file_size) ({{ $resource->formatted_file_size }}) @endif
                        </p>
                    @endif
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold" for="resourceDescription">الوصف</label>
                    <textarea name="description" id="resourceDescription" rows="5"
                              class="form-control form-input-enhanced @error('description') is-invalid @enderror"
                              placeholder="وصف اختياري للمورد">{{ old('description', $resource?->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card custom-card form-card">
    <div class="card-body py-3">
        <div class="form-actions border-0 pt-0 mt-0">
            <a href="{{ route('admin.public-resources.index') }}" class="btn btn-light border px-4">
                <i class="ri-close-line me-1"></i> إلغاء
            </a>
            <button type="submit" class="btn btn-primary px-4 btn-wave">
                <i class="ri-save-line me-1"></i> {{ $resource ? 'حفظ التعديلات' : 'حفظ المورد' }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var typeSelect = document.getElementById('resourceTypeSide');
    var linkField = document.getElementById('publicResourceLinkField');
    var fileField = document.getElementById('publicResourceFileField');
    var fileInput = document.getElementById('resourceFile');
    var urlInput = document.getElementById('resourceUrl');
    var isEdit = {{ $resource ? 'true' : 'false' }};

    function toggleFields() {
        if (!typeSelect) return;
        var isLink = typeSelect.value === 'link';
        if (linkField) linkField.style.display = isLink ? '' : 'none';
        if (fileField) fileField.style.display = isLink ? 'none' : '';
        if (urlInput) urlInput.required = isLink;
        if (fileInput && !isEdit) fileInput.required = !isLink;
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', toggleFields);
        toggleFields();
    }
});
</script>
@endpush
