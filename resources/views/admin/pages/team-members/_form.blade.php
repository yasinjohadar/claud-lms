@php
    $member = $member ?? null;
    $sourceType = old('source_type', $member?->user_id ? 'user' : 'manual');
    $avatarType = old('avatar_type', $member?->avatar_type ?? ($member?->user_id ? 'user' : 'icon'));
    $socialLinks = old('social_links', $member?->social_links ?? [['platform' => '', 'url' => '']]);
    if (empty($socialLinks)) {
        $socialLinks = [['platform' => '', 'url' => '']];
    }
@endphp

<div class="row g-4">
    <div class="col-lg-4 order-lg-2">
        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-settings-3-line me-1 text-primary"></i> الإعدادات</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">مجموعة العضو</label>
                    <select name="team_group" class="form-select form-input-enhanced @error('team_group') is-invalid @enderror" required>
                        <option value="instructor" {{ old('team_group', $member?->team_group ?? 'instructor') === 'instructor' ? 'selected' : '' }}>مدربون</option>
                        <option value="admin" {{ old('team_group', $member?->team_group) === 'admin' ? 'selected' : '' }}>فريق إداري</option>
                        <option value="management" {{ old('team_group', $member?->team_group) === 'management' ? 'selected' : '' }}>إدارة</option>
                    </select>
                    @error('team_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="memberSortOrder">الترتيب</label>
                    <input type="number" name="sort_order" id="memberSortOrder" class="form-control form-input-enhanced" min="0"
                           value="{{ old('sort_order', $member?->sort_order ?? 0) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="memberAccentColor">لون البطاقة</label>
                    <input type="color" name="accent_color" id="memberAccentColor"
                           class="form-control form-control-color form-input-enhanced w-100 @error('accent_color') is-invalid @enderror"
                           value="{{ old('accent_color', $member?->accent_color ?? '#059669') }}">
                    @error('accent_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="account-switch-panel mb-3">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="show_on_home" id="showOnHome" value="1"
                               {{ old('show_on_home', $member?->show_on_home ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="showOnHome">عرض في الصفحة الرئيسية</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="show_on_page" id="showOnPage" value="1"
                               {{ old('show_on_page', $member?->show_on_page ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="showOnPage">عرض في صفحة من نحن</label>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="is_published" id="memberPublished" value="1"
                               {{ old('is_published', $member?->is_published ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="memberPublished">عضو منشور</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-image-line me-1 text-primary"></i> معاينة الصورة</h6>
            </div>
            <div class="card-body text-center">
                <div id="teamAvatarPreview" class="team-admin-avatar-preview mb-2">
                    @if($member?->avatar_url)
                        <img src="{{ $member->avatar_url }}" alt="" id="teamAvatarPreviewImg">
                    @elseif($member?->avatar_icon)
                        <span class="team-admin-avatar-icon"><i class="{{ $member->avatar_icon }}"></i></span>
                    @else
                        <span class="team-admin-avatar-icon"><i class="fas fa-user"></i></span>
                    @endif
                </div>
                <p class="text-muted fs-12 mb-0">تتغير المعاينة حسب نوع الصورة المختار</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8 order-lg-1">
        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-search-line me-1 text-primary"></i> مصدر العضو</h6>
            </div>
            <div class="card-body">
                <div class="role-check-grid mb-3">
                    <label class="role-check-chip">
                        <input type="radio" name="source_type" value="manual" {{ $sourceType === 'manual' ? 'checked' : '' }}>
                        <span><i class="ri-edit-line"></i> إدخال يدوي</span>
                    </label>
                    <label class="role-check-chip">
                        <input type="radio" name="source_type" value="user" {{ $sourceType === 'user' ? 'checked' : '' }}>
                        <span><i class="ri-link"></i> مستخدم من النظام</span>
                    </label>
                </div>

                <div id="manualSourceFields" style="{{ $sourceType === 'user' ? 'display:none' : '' }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="memberName">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="memberName"
                               class="form-control form-input-enhanced @error('name') is-invalid @enderror"
                               value="{{ old('name', $member?->name) }}" placeholder="اسم العضو">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div id="userSourceFields" style="{{ $sourceType === 'manual' ? 'display:none' : '' }}">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="userRoleFilter">فلتر المستخدمين</label>
                            <select id="userRoleFilter" class="form-select form-input-enhanced">
                                @foreach($roleFilters as $filter)
                                    <option value="{{ $filter['value'] }}">{{ $filter['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" for="userSearchInput">بحث مستخدم</label>
                            <input type="text" id="userSearchInput" class="form-control form-input-enhanced" placeholder="ابحث بالاسم أو البريد...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="memberUserId">اختر المستخدم <span class="text-danger">*</span></label>
                        <select name="user_id" id="memberUserId" class="form-select form-input-enhanced @error('user_id') is-invalid @enderror">
                            <option value="">— اختر مستخدماً —</option>
                            @if($member?->user)
                                <option value="{{ $member->user_id }}" selected
                                        data-name="{{ $member->user->name }}"
                                        data-photo="{{ $member->user->photo ? asset('storage/' . $member->user->photo) : '' }}"
                                        data-courses="{{ $member->display_courses_count }}">
                                    {{ $member->user->name }} ({{ $member->user->email }})
                                </option>
                            @endif
                        </select>
                        @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" for="memberNameOverride">تخصيص الاسم (اختياري)</label>
                        <input type="text" name="name" id="memberNameOverride"
                               class="form-control form-input-enhanced"
                               value="{{ old('name', $member?->user_id ? $member?->name : '') }}"
                               placeholder="اتركه فارغاً لاستخدام اسم المستخدم">
                        <p class="text-muted fs-12 mb-0 mt-1">مثال: إضافة لقب مثل «م.» قبل الاسم</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-profile-line me-1 text-primary"></i> البيانات الظاهرة</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="memberRoleTitle">المسمى الوظيفي <span class="text-danger">*</span></label>
                        <input type="text" name="role_title" id="memberRoleTitle"
                               class="form-control form-input-enhanced @error('role_title') is-invalid @enderror"
                               value="{{ old('role_title', $member?->role_title) }}" placeholder="مثال: Senior Front-end Engineer" required>
                        @error('role_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="memberRating">التقييم</label>
                        <input type="number" name="rating" id="memberRating" step="0.1" min="0" max="5"
                               class="form-control form-input-enhanced @error('rating') is-invalid @enderror"
                               value="{{ old('rating', $member?->rating) }}" placeholder="4.9">
                        @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="memberCoursesCount">عدد الكورسات</label>
                        <input type="number" name="courses_count" id="memberCoursesCount" min="0"
                               class="form-control form-input-enhanced @error('courses_count') is-invalid @enderror"
                               value="{{ old('courses_count', $member?->courses_count) }}" placeholder="تلقائي للمدرب">
                        @error('courses_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" for="memberBio">نبذة مختصرة</label>
                        <textarea name="bio" id="memberBio" rows="4"
                                  class="form-control form-input-enhanced @error('bio') is-invalid @enderror"
                                  placeholder="وصف قصير يظهر في بطاقة العضو">{{ old('bio', $member?->bio) }}</textarea>
                        @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card form-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-image-add-line me-1 text-primary"></i> صورة العضو</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">نوع الصورة</label>
                    <select name="avatar_type" id="memberAvatarType" class="form-select form-input-enhanced @error('avatar_type') is-invalid @enderror" required>
                        <option value="icon" {{ $avatarType === 'icon' ? 'selected' : '' }}>أيقونة</option>
                        <option value="upload" {{ $avatarType === 'upload' ? 'selected' : '' }}>رفع صورة</option>
                        <option value="user" {{ $avatarType === 'user' ? 'selected' : '' }} id="avatarTypeUserOption">صورة المستخدم</option>
                    </select>
                    @error('avatar_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3" id="avatarIconField" style="{{ $avatarType !== 'icon' ? 'display:none' : '' }}">
                    <label class="form-label fw-semibold" for="memberAvatarIcon">أيقونة Font Awesome</label>
                    <input type="text" name="avatar_icon" id="memberAvatarIcon"
                           class="form-control form-input-enhanced @error('avatar_icon') is-invalid @enderror"
                           value="{{ old('avatar_icon', $member?->avatar_icon ?? 'fas fa-user') }}"
                           placeholder="fas fa-user-tie" dir="ltr">
                    @error('avatar_icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-0" id="avatarUploadField" style="{{ $avatarType !== 'upload' ? 'display:none' : '' }}">
                    <label class="form-label fw-semibold" for="memberAvatarFile">ملف الصورة</label>
                    <input type="file" name="avatar_file" id="memberAvatarFile" accept="image/*"
                           class="form-control form-input-enhanced @error('avatar_file') is-invalid @enderror">
                    @error('avatar_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="card custom-card form-card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-share-line me-1 text-primary"></i> روابط التواصل</h6>
                <button type="button" class="btn btn-light border btn-sm" id="addSocialLinkBtn">
                    <i class="ri-add-line"></i> إضافة رابط
                </button>
            </div>
            <div class="card-body" id="socialLinksWrap">
                @foreach($socialLinks as $index => $link)
                    @include('admin.pages.team-members.partials.social-link-row', [
                        'index' => $index,
                        'link' => $link,
                        'socialPlatforms' => $socialPlatforms,
                    ])
                @endforeach
            </div>
            <template id="socialLinkRowTemplate">
                @include('admin.pages.team-members.partials.social-link-row', [
                    'index' => '__INDEX__',
                    'link' => ['platform' => '', 'url' => ''],
                    'socialPlatforms' => $socialPlatforms,
                ])
            </template>
        </div>
    </div>
</div>

<div class="card custom-card form-card">
    <div class="card-body py-3">
        <div class="form-actions border-0 pt-0 mt-0">
            <a href="{{ route('admin.team-members.index') }}" class="btn btn-light border px-4">
                <i class="ri-close-line me-1"></i> إلغاء
            </a>
            <button type="submit" class="btn btn-primary px-4 btn-wave">
                <i class="ri-save-line me-1"></i> {{ $member ? 'حفظ التعديلات' : 'حفظ العضو' }}
            </button>
        </div>
    </div>
</div>

<style>
.team-admin-avatar-preview {
    width: 96px;
    height: 96px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    background: rgba(var(--primary-rgb, 13, 110, 253), 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
}
.team-admin-avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
.team-admin-avatar-icon { font-size: 2rem; color: var(--primary, #0d6efd); }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var pickerUrl = @json(route('admin.team-members.users-picker'));
    var sourceRadios = document.querySelectorAll('input[name="source_type"]');
    var manualFields = document.getElementById('manualSourceFields');
    var userFields = document.getElementById('userSourceFields');
    var userSelect = document.getElementById('memberUserId');
    var userRoleFilter = document.getElementById('userRoleFilter');
    var userSearchInput = document.getElementById('userSearchInput');
    var avatarType = document.getElementById('memberAvatarType');
    var avatarIconField = document.getElementById('avatarIconField');
    var avatarUploadField = document.getElementById('avatarUploadField');
    var avatarTypeUserOption = document.getElementById('avatarTypeUserOption');
    var avatarIconInput = document.getElementById('memberAvatarIcon');
    var avatarFileInput = document.getElementById('memberAvatarFile');
    var preview = document.getElementById('teamAvatarPreview');
    var roleTitleInput = document.getElementById('memberRoleTitle');
    var coursesCountInput = document.getElementById('memberCoursesCount');
    var nameOverrideInput = document.getElementById('memberNameOverride');
    var manualNameInput = document.getElementById('memberName');
    var socialWrap = document.getElementById('socialLinksWrap');
    var addSocialBtn = document.getElementById('addSocialLinkBtn');
    var socialIndex = {{ count($socialLinks) }};

    function currentSourceType() {
        var checked = document.querySelector('input[name="source_type"]:checked');
        return checked ? checked.value : 'manual';
    }

    function toggleSourceFields() {
        var isUser = currentSourceType() === 'user';
        if (manualFields) manualFields.style.display = isUser ? 'none' : '';
        if (userFields) userFields.style.display = isUser ? '' : 'none';
        if (manualNameInput) manualNameInput.disabled = isUser;
        if (nameOverrideInput) nameOverrideInput.disabled = !isUser;
        if (avatarTypeUserOption) avatarTypeUserOption.disabled = !isUser;
        if (!isUser && avatarType && avatarType.value === 'user') {
            avatarType.value = 'icon';
        }
        toggleAvatarFields();
        if (isUser) loadUsers();
    }

    function toggleAvatarFields() {
        if (!avatarType) return;
        var type = avatarType.value;
        if (avatarIconField) avatarIconField.style.display = type === 'icon' ? '' : 'none';
        if (avatarUploadField) avatarUploadField.style.display = type === 'upload' ? '' : 'none';
        updatePreview();
    }

    function updatePreview() {
        if (!preview || !avatarType) return;
        var type = avatarType.value;
        if (type === 'upload' && avatarFileInput && avatarFileInput.files && avatarFileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = '<img src="' + e.target.result + '" alt="" id="teamAvatarPreviewImg">';
            };
            reader.readAsDataURL(avatarFileInput.files[0]);
            return;
        }
        if (type === 'user' && userSelect && userSelect.selectedOptions[0]) {
            var photo = userSelect.selectedOptions[0].dataset.photo;
            if (photo) {
                preview.innerHTML = '<img src="' + photo + '" alt="" id="teamAvatarPreviewImg">';
                return;
            }
        }
        if (type === 'icon' && avatarIconInput) {
            preview.innerHTML = '<span class="team-admin-avatar-icon"><i class="' + (avatarIconInput.value || 'fas fa-user') + '"></i></span>';
            return;
        }
        if (type === 'upload') {
            preview.innerHTML = '<span class="team-admin-avatar-icon"><i class="fas fa-image"></i></span>';
        }
    }

    function loadUsers() {
        if (!userSelect) return;
        var selected = userSelect.value;
        var params = new URLSearchParams();
        if (userRoleFilter && userRoleFilter.value) params.set('role', userRoleFilter.value);
        if (userSearchInput && userSearchInput.value.trim()) params.set('search', userSearchInput.value.trim());

        fetch(pickerUrl + '?' + params.toString(), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (r) { return r.json(); }).then(function (data) {
            var html = '<option value="">— اختر مستخدماً —</option>';
            (data.users || []).forEach(function (user) {
                html += '<option value="' + user.id + '"'
                    + ' data-name="' + (user.name || '') + '"'
                    + ' data-photo="' + (user.photo_url || '') + '"'
                    + ' data-courses="' + (user.courses_count || 0) + '"'
                    + (String(user.id) === String(selected) ? ' selected' : '') + '>'
                    + user.name + ' (' + user.email + ')'
                    + '</option>';
            });
            userSelect.innerHTML = html;
            applySelectedUserData();
        });
    }

    function applySelectedUserData() {
        if (!userSelect || !userSelect.selectedOptions[0] || !userSelect.value) return;
        var option = userSelect.selectedOptions[0];
        if (!coursesCountInput || !coursesCountInput.value) {
            coursesCountInput.value = option.dataset.courses || '';
        }
        updatePreview();
    }

    sourceRadios.forEach(function (radio) {
        radio.addEventListener('change', toggleSourceFields);
    });

    if (userRoleFilter) userRoleFilter.addEventListener('change', loadUsers);
    if (userSearchInput) {
        var searchTimer;
        userSearchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(loadUsers, 300);
        });
    }
    if (userSelect) userSelect.addEventListener('change', function () {
        coursesCountInput.value = userSelect.selectedOptions[0]?.dataset.courses || '';
        updatePreview();
    });
    if (avatarType) avatarType.addEventListener('change', toggleAvatarFields);
    if (avatarIconInput) avatarIconInput.addEventListener('input', updatePreview);
    if (avatarFileInput) avatarFileInput.addEventListener('change', updatePreview);

    if (addSocialBtn && socialWrap) {
        var socialTemplate = document.getElementById('socialLinkRowTemplate');
        addSocialBtn.addEventListener('click', function () {
            if (!socialTemplate) return;
            var html = socialTemplate.innerHTML.replace(/__INDEX__/g, socialIndex++);
            socialWrap.insertAdjacentHTML('beforeend', html);
        });
        socialWrap.addEventListener('click', function (e) {
            var btn = e.target.closest('.social-link-remove');
            if (!btn) return;
            var rows = socialWrap.querySelectorAll('.social-link-row');
            if (rows.length <= 1) {
                rows[0].querySelectorAll('input, select').forEach(function (el) { el.value = ''; });
                return;
            }
            btn.closest('.social-link-row').remove();
        });
    }

    toggleSourceFields();
});
</script>
@endpush
