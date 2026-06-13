@php $tag = $tag ?? null; @endphp
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card custom-card form-card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-price-tag-3-line me-1 text-primary"></i> بيانات التاغ</h6>
            </div>
            <div class="card-body">
    <div class="mb-3">
        <label class="form-label">الاسم *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $tag?->name) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">الوصف</label>
        <textarea name="description" rows="2" class="form-control">{{ old('description', $tag?->description) }}</textarea>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label">اللون</label>
            <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $tag?->color ?? '#059669') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">الترتيب</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', $tag?->order ?? 0) }}">
        </div>
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $tag?->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label">نشط</label>
    </div>
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.courses.tags.index') }}" class="btn btn-light border">إلغاء</a>
            </div>
        </div>
    </div>
</div>
