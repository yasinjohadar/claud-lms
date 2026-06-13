<div class="courses-filter-block">
    <label class="courses-filter-label" for="search-input">بحث سريع</label>
    <div class="courses-filter-search">
        <i class="fas fa-search" aria-hidden="true"></i>
        <input type="text" id="search-input" name="search" placeholder="ابحث عن كورس أو مدرب..." autocomplete="off" value="{{ request('search') }}">
    </div>
</div>

<div class="courses-filter-block">
    <span class="courses-filter-label">التصنيف</span>
    <div class="filter-chip-group">
        @foreach($categories as $category)
            <label class="filter-chip">
                <input class="filter-checkbox filter-category" type="checkbox" name="categories[]" value="{{ $category->slug }}"
                    {{ in_array($category->slug, (array) request('categories', request('category') ? [request('category')] : [])) ? 'checked' : '' }}>
                <span>
                    @if($category->icon)<i class="{{ $category->icon }}"></i>@endif
                    {{ $category->name }}
                </span>
            </label>
        @endforeach
    </div>
</div>

@if($tags->isNotEmpty())
<div class="courses-filter-block">
    <span class="courses-filter-label">التاغات</span>
    <div class="filter-chip-group">
        @foreach($tags as $tag)
            <label class="filter-chip">
                <input class="filter-checkbox filter-tag" type="checkbox" name="tags[]" value="{{ $tag->slug }}"
                    {{ in_array($tag->slug, (array) request('tags', [])) ? 'checked' : '' }}>
                <span>{{ $tag->name }}</span>
            </label>
        @endforeach
    </div>
</div>
@endif

<div class="courses-filter-block">
    <span class="courses-filter-label">السعر — حتى <strong id="price-val" class="en-text">${{ (int) $maxPrice }}</strong></span>
    <div class="courses-price-range-wrap">
        <input type="range" class="courses-price-range" min="0" max="{{ (int) $maxPrice }}" value="{{ request('price_max', $maxPrice) }}" id="price-range" name="price_max">
        <div class="courses-price-labels en-text">
            <span>$0</span>
            <span>${{ (int) $maxPrice }}</span>
        </div>
    </div>
</div>

<div class="courses-filter-block">
    <span class="courses-filter-label">المستوى</span>
    <div class="filter-level-group">
        @foreach(['beginner' => 'مبتدئ', 'intermediate' => 'متوسط', 'advanced' => 'متقدم'] as $level => $label)
            <label class="filter-level">
                <input class="filter-checkbox level filter-level-input" type="checkbox" name="levels[]" value="{{ $level }}"
                    {{ in_array($level, (array) request('levels', [])) ? 'checked' : '' }}>
                <span>{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>
