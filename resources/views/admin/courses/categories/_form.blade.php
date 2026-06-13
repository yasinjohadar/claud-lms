@php $category = $category ?? null; @endphp
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card custom-card form-card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-folder-line me-1 text-primary"></i> بيانات التصنيف</h6>
            </div>
            <div class="card-body">
            <div class="mb-3">
                <label class="form-label">الاسم *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category?->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $category?->description) }}</textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">الأيقونة</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $category?->icon) }}" placeholder="fas fa-code">
                </div>
                <div class="col-md-4">
                    <label class="form-label">اللون</label>
                    <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $category?->color ?? '#059669') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">الترتيب</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', $category?->order ?? 0) }}" min="0">
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="sidebar-sticky">
        <div class="card custom-card form-card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-settings-3-line me-1 text-primary"></i> الإعدادات</h6>
            </div>
            <div class="card-body">
            <div class="mb-3">
                <label class="form-label">التصنيف الأب</label>
                <select name="parent_id" class="form-select">
                    <option value="">— رئيسي —</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category?->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">نشط</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $category?->is_featured ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_featured">مميز في الرئيسية</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">حفظ</button>
            <a href="{{ route('admin.courses.categories.index') }}" class="btn btn-light border w-100 mt-2">إلغاء</a>
            </div>
        </div>
        </div>
    </div>
</div>
