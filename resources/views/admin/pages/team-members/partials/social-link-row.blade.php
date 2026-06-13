<div class="row g-2 align-items-end social-link-row mb-2">
    <div class="col-md-4">
        <label class="form-label fs-12 text-muted mb-1">المنصة</label>
        <select name="social_links[{{ $index }}][platform]" class="form-select form-input-enhanced">
            <option value="">—</option>
            @foreach($socialPlatforms as $key => $platform)
                <option value="{{ $key }}" {{ ($link['platform'] ?? '') === $key ? 'selected' : '' }}>{{ $platform['label'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-7">
        <label class="form-label fs-12 text-muted mb-1">الرابط</label>
        <input type="url" name="social_links[{{ $index }}][url]" class="form-control form-input-enhanced"
               value="{{ $link['url'] ?? '' }}" placeholder="https://" dir="ltr">
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-light border w-100 social-link-remove" title="حذف">
            <i class="ri-delete-bin-line text-danger"></i>
        </button>
    </div>
</div>
