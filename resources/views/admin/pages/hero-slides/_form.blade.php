@php
    $slide = $slide ?? null;
    $isEdit = isset($slide);
    $v = fn ($key, $default = '') => old($key, $slide?->$key ?? $default);
    $buttons = old('buttons', $slide?->buttons ?? [['label' => '', 'url' => '', 'style' => 'primary', 'icon' => '']]);
    $extras = old('visual_extras', $slide?->visual_extras ?? []);
    $typingPhrases = old('heading_typing_phrases', $slide?->heading_typing_phrases ?? ['']);
@endphp

<div class="row g-4">
    <div class="col-lg-4 order-lg-2">
        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-settings-3-line me-1 text-primary"></i> النشر والإعدادات</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">العنوان الإداري <span class="text-danger">*</span></label>
                    <input type="text" name="admin_title" class="form-control form-input-enhanced" value="{{ $v('admin_title') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">تسمية النقطة</label>
                    <input type="text" name="pagination_label" class="form-control form-input-enhanced" value="{{ $v('pagination_label') }}" placeholder="مثل: تصميم">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">الترتيب</label>
                    <input type="number" name="sort_order" class="form-control form-input-enhanced" value="{{ $v('sort_order', 0) }}" min="0">
                </div>
                <div class="account-switch-panel mb-3">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                               {{ old('is_active', $slide?->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">شريحة نشطة</label>
                    </div>
                    <p class="text-muted fs-12 mb-0 mt-2">الشرائح غير النشطة لا تظهر في الصفحة الرئيسية.</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">بداية العرض</label>
                    <input type="datetime-local" name="starts_at" class="form-control form-input-enhanced"
                           value="{{ old('starts_at', $slide?->starts_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">نهاية العرض</label>
                    <input type="datetime-local" name="expires_at" class="form-control form-input-enhanced"
                           value="{{ old('expires_at', $slide?->expires_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">التخطيط</label>
                    <select name="layout" class="form-select form-input-enhanced">
                        @foreach($layouts as $layout)
                            <option value="{{ $layout }}" @selected($v('layout', 'content_right_visual_left') === $layout)>{{ $layout }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-palette-line me-1 text-primary"></i> الألوان والمظهر</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">لون accent <span class="text-danger">*</span></label>
                    <input type="color" name="accent_color" class="form-control form-control-color form-input-enhanced w-100"
                           value="{{ $v('accent_color', '#059669') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">لون accent 2</label>
                    <input type="color" name="accent_color_2" class="form-control form-control-color form-input-enhanced w-100"
                           value="{{ $v('accent_color_2', '#7c3aed') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">قالب CSS</label>
                    <select name="theme_variant" class="form-select form-input-enhanced">
                        @foreach($themeVariants as $tv)
                            <option value="{{ $tv }}" @selected($v('theme_variant', 'main') === $tv)>{{ $tv }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">الارتفاع</label>
                    <select name="min_height" class="form-select form-input-enhanced">
                        @foreach($minHeights as $h)
                            <option value="{{ $h }}" @selected($v('min_height', 'default') === $h)>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="account-switch-panel mb-3">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="show_decorative_shapes" value="1" id="show_shapes"
                               @checked(old('show_decorative_shapes', $slide?->show_decorative_shapes ?? true))>
                        <label class="form-check-label fw-semibold" for="show_shapes">أشكال زخرفية</label>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="hide_visual_on_mobile" value="1" id="hide_visual"
                               @checked(old('hide_visual_on_mobile', $slide?->hide_visual_on_mobile ?? true))>
                        <label class="form-check-label fw-semibold" for="hide_visual">إخفاء البصري على الجوال</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">CSS class إضافي</label>
                    <input type="text" name="custom_css_class" class="form-control form-input-enhanced" value="{{ $v('custom_css_class') }}">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">aria-label</label>
                    <input type="text" name="aria_label" class="form-control form-input-enhanced" value="{{ $v('aria_label') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 order-lg-1">
        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-file-text-line me-1 text-primary"></i> المحتوى النصي</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">نص الشارة</label>
                        <input type="text" name="badge_text" class="form-control form-input-enhanced" value="{{ $v('badge_text') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">أيقونة الشارة</label>
                        <input type="text" name="badge_icon" class="form-control form-input-enhanced" value="{{ $v('badge_icon') }}" placeholder="fas fa-bolt">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">وضع العنوان</label>
                        <select name="heading_mode" class="form-select form-input-enhanced" id="heading_mode">
                            @foreach($headingModes as $mode)
                                <option value="{{ $mode }}" @selected($v('heading_mode', 'static') === $mode)>{{ $mode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">بادئة العنوان</label>
                        <input type="text" name="heading_prefix" class="form-control form-input-enhanced" value="{{ $v('heading_prefix') }}">
                    </div>
                    <div class="col-md-6 heading-static">
                        <label class="form-label fw-semibold">العبارة المميزة</label>
                        <input type="text" name="heading_highlight" class="form-control form-input-enhanced" value="{{ $v('heading_highlight') }}">
                    </div>
                    <div class="col-12 heading-typing">
                        <label class="form-label fw-semibold">عبارات الكتابة المتحركة</label>
                        @foreach($typingPhrases as $i => $phrase)
                            <input type="text" name="heading_typing_phrases[]" class="form-control form-input-enhanced mb-2"
                                   value="{{ $phrase }}" placeholder="عبارة {{ $i + 1 }}">
                        @endforeach
                        <button type="button" class="btn btn-sm btn-light border" id="addTypingPhrase">+ عبارة</button>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">الوصف</label>
                        <textarea name="description" class="form-control form-input-enhanced" rows="3">{{ $v('description') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">محاذاة المحتوى</label>
                        <select name="content_align" class="form-select form-input-enhanced">
                            @foreach($contentAligns as $a)
                                <option value="{{ $a }}" @selected($v('content_align', 'start') === $a)>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">محاذاة الجوال</label>
                        <select name="mobile_content_align" class="form-select form-input-enhanced">
                            @foreach($contentAligns as $a)
                                <option value="{{ $a }}" @selected($v('mobile_content_align') === $a)>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-image-line me-1 text-primary"></i> الخلفية</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">نوع الخلفية</label>
                        <select name="background_type" class="form-select form-input-enhanced" id="background_type">
                            @foreach($backgroundTypes as $type)
                                <option value="{{ $type }}" @selected($v('background_type', 'theme') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 bg-field bg-solid">
                        <label class="form-label fw-semibold">لون الخلفية</label>
                        <input type="color" name="background_color" class="form-control form-control-color form-input-enhanced w-100"
                               value="{{ $v('background_color', '#f8fafc') }}">
                    </div>
                    <div class="col-md-4 bg-field bg-gradient">
                        <label class="form-label fw-semibold">تدرج من</label>
                        <input type="color" name="gradient_from" class="form-control form-control-color form-input-enhanced w-100"
                               value="{{ $v('gradient_from', '#059669') }}">
                    </div>
                    <div class="col-md-4 bg-field bg-gradient">
                        <label class="form-label fw-semibold">تدرج إلى</label>
                        <input type="color" name="gradient_to" class="form-control form-control-color form-input-enhanced w-100"
                               value="{{ $v('gradient_to', '#7c3aed') }}">
                    </div>
                    <div class="col-md-4 bg-field bg-gradient">
                        <label class="form-label fw-semibold">زاوية التدرج</label>
                        <input type="number" name="gradient_angle" class="form-control form-input-enhanced"
                               value="{{ $v('gradient_angle', 135) }}" min="0" max="360">
                    </div>
                    <div class="col-md-4 bg-field bg-gradient">
                        <label class="form-label fw-semibold">نوع التدرج</label>
                        <select name="gradient_type" class="form-select form-input-enhanced">
                            <option value="linear" @selected($v('gradient_type', 'linear') === 'linear')>linear</option>
                            <option value="radial" @selected($v('gradient_type') === 'radial')>radial</option>
                        </select>
                    </div>
                    <div class="col-md-6 bg-field bg-image">
                        <label class="form-label fw-semibold">صورة الخلفية</label>
                        <input type="file" name="background_image_file" class="form-control form-input-enhanced" accept="image/*">
                        @if($slide?->background_image_url)
                            <img src="{{ $slide->background_image_url }}" class="mt-2 rounded" style="max-height:80px" alt="">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_background_image" value="1" id="rm_bg">
                                <label class="form-check-label" for="rm_bg">حذف الصورة</label>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-3 bg-field bg-image">
                        <label class="form-label fw-semibold">لون التغطية</label>
                        <input type="color" name="background_overlay_color" class="form-control form-control-color form-input-enhanced w-100"
                               value="{{ $v('background_overlay_color', '#000000') }}">
                    </div>
                    <div class="col-md-3 bg-field bg-image">
                        <label class="form-label fw-semibold">شفافية التغطية</label>
                        <input type="number" name="background_overlay_opacity" class="form-control form-input-enhanced"
                               step="0.05" min="0" max="1" value="{{ $v('background_overlay_opacity', 0.35) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-layout-masonry-line me-1 text-primary"></i> الجانب البصري</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">نوع البصري</label>
                        <select name="visual_type" class="form-select form-input-enhanced" id="visual_type">
                            @foreach($visualTypes as $type)
                                <option value="{{ $type }}" @selected($v('visual_type', 'main') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 visual-field visual-image">
                        <label class="form-label fw-semibold">صورة بصرية</label>
                        <input type="file" name="visual_image_file" class="form-control form-input-enhanced" accept="image/*">
                        @if($slide?->visual_image_url)
                            <img src="{{ $slide->visual_image_url }}" class="mt-2 rounded" style="max-height:80px" alt="">
                        @endif
                    </div>
                    <div class="col-md-4 visual-field visual-icon visual-main visual-design visual-ai">
                        <label class="form-label fw-semibold">أيقونة مركزية</label>
                        <input type="text" name="visual_icon" class="form-control form-input-enhanced" value="{{ $v('visual_icon') }}" placeholder="fas fa-laptop-code">
                    </div>
                    <div class="col-md-4 visual-field visual-image">
                        <label class="form-label fw-semibold">نص بديل الصورة</label>
                        <input type="text" name="visual_image_alt" class="form-control form-input-enhanced" value="{{ $v('visual_image_alt') }}">
                    </div>
                    @if($slide?->visual_image)
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_visual_image" value="1" id="rm_visual">
                                <label class="form-check-label" for="rm_visual">حذف الصورة البصرية</label>
                            </div>
                        </div>
                    @endif
                    <div class="col-12 visual-field visual-code">
                        <label class="form-label fw-semibold">مقتطف الكود (HTML)</label>
                        <textarea name="visual_extras[code_snippet]" class="form-control form-input-enhanced font-monospace" rows="5">{{ $extras['code_snippet'] ?? '' }}</textarea>
                    </div>
                    <div class="col-12 visual-field visual-design">
                        <label class="form-label fw-semibold">أيقونة المركز (design)</label>
                        <input type="text" name="visual_extras[center_icon]" class="form-control form-input-enhanced mb-2"
                               value="{{ $extras['center_icon'] ?? 'fas fa-paint-brush' }}">
                        <label class="form-label fw-semibold">أيقونات المدار (icon|position)</label>
                        @php $orbitIcons = $extras['orbit_icons'] ?? [['icon' => '', 'position' => 1]]; @endphp
                        @foreach($orbitIcons as $i => $oi)
                            <div class="input-group mb-2">
                                <input type="text" name="visual_extras[orbit_icons][{{ $i }}][icon]" class="form-control form-input-enhanced"
                                       value="{{ $oi['icon'] ?? '' }}" placeholder="fab fa-figma">
                                <input type="number" name="visual_extras[orbit_icons][{{ $i }}][position]" class="form-control form-input-enhanced"
                                       value="{{ $oi['position'] ?? $i+1 }}" min="1" max="6" style="max-width:80px">
                            </div>
                        @endforeach
                    </div>
                    <div class="col-12 visual-field visual-ai">
                        <label class="form-label fw-semibold">وسوم AI (مفصولة بفاصلة)</label>
                        <input type="text" name="visual_extras_ai_tags" class="form-control form-input-enhanced"
                               value="{{ implode(', ', $extras['ai_tags'] ?? []) }}">
                        <input type="hidden" name="visual_extras[center_icon]" value="{{ $extras['center_icon'] ?? 'fas fa-brain' }}">
                    </div>
                    <div class="col-12 visual-field visual-main">
                        <label class="form-label fw-semibold">البطاقات العائمة</label>
                        @php $floatCards = $extras['float_cards'] ?? [['value' => '', 'title' => ''], ['value' => '', 'title' => '']]; @endphp
                        @foreach($floatCards as $i => $card)
                            <div class="row g-2 mb-2">
                                <div class="col-md-3">
                                    <input type="text" name="visual_extras[float_cards][{{ $i }}][icon]" class="form-control form-input-enhanced"
                                           value="{{ $card['icon'] ?? '' }}" placeholder="أيقونة">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="visual_extras[float_cards][{{ $i }}][value]" class="form-control form-input-enhanced"
                                           value="{{ $card['value'] ?? '' }}" placeholder="القيمة">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="visual_extras[float_cards][{{ $i }}][title]" class="form-control form-input-enhanced"
                                           value="{{ $card['title'] ?? '' }}" placeholder="العنوان">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-link me-1 text-primary"></i> الأزرار</h6>
            </div>
            <div class="card-body" id="buttonsRepeater">
                <div class="row g-2 mb-2 text-muted small fw-semibold d-none d-md-flex">
                    <div class="col-md-3">نص الزر</div>
                    <div class="col-md-3">الرابط</div>
                    <div class="col-md-2">النمط</div>
                    <div class="col-md-2">الأيقونة</div>
                    <div class="col-md-2">خيارات</div>
                </div>
                @foreach($buttons as $i => $btn)
                    <div class="row g-2 mb-3 button-row border-bottom pb-3">
                        <div class="col-md-3">
                            <input type="text" name="buttons[{{ $i }}][label]" class="form-control form-input-enhanced"
                                   placeholder="نص الزر" value="{{ $btn['label'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="buttons[{{ $i }}][url]" class="form-control form-input-enhanced"
                                   placeholder="/courses" value="{{ $btn['url'] ?? '' }}" dir="ltr">
                        </div>
                        <div class="col-md-2">
                            <select name="buttons[{{ $i }}][style]" class="form-select form-input-enhanced">
                                @foreach($buttonStyles as $s)
                                    <option value="{{ $s }}" @selected(($btn['style'] ?? 'primary') === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="buttons[{{ $i }}][icon]" class="form-control form-input-enhanced"
                                   placeholder="fas fa-arrow-left" value="{{ $btn['icon'] ?? '' }}" dir="ltr">
                        </div>
                        <div class="col-md-2">
                            <div class="form-check mt-2">
                                <input type="checkbox" name="buttons[{{ $i }}][open_in_new_tab]" value="1" id="btn_tab_{{ $i }}"
                                       @checked($btn['open_in_new_tab'] ?? false)>
                                <label class="form-check-label" for="btn_tab_{{ $i }}">تبويب جديد</label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="card custom-card form-card">
    <div class="card-body py-3">
        <div class="form-actions border-0 pt-0 mt-0">
            <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-light border px-4">
                <i class="ri-close-line me-1"></i> إلغاء
            </a>
            <button type="submit" class="btn btn-primary px-4 btn-wave">
                <i class="ri-save-line me-1"></i> {{ $isEdit ? 'حفظ التعديلات' : 'إنشاء الشريحة' }}
            </button>
        </div>
    </div>
</div>
