@php
    $student = $student ?? null;
    $isEdit = isset($student);
@endphp

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card custom-card">
            <div class="card-header"><span class="fw-bold">بيانات الحساب</span></div>
            <div class="card-body">
                @unless($isEdit)
                <div class="mb-4">
                    <label class="form-label fw-semibold">نوع الإضافة</label>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="source_type" id="source_new" value="new_user"
                                   {{ old('source_type', 'new_user') === 'new_user' ? 'checked' : '' }}>
                            <label class="form-check-label" for="source_new">مستخدم جديد</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="source_type" id="source_existing" value="existing_user"
                                   {{ old('source_type') === 'existing_user' ? 'checked' : '' }}>
                            <label class="form-check-label" for="source_existing">ربط مستخدم موجود</label>
                        </div>
                    </div>
                </div>

                <div id="new-user-fields">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $student?->user?->name) }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">البريد <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $student?->user?->email) }}" dir="ltr">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الهاتف</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $student?->user?->phone) }}" dir="ltr">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @unless($isEdit)
                        <div class="col-md-6">
                            <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        @endunless
                    </div>
                </div>

                <div id="existing-user-fields" class="d-none">
                    <label class="form-label">اختر المستخدم</label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                        <option value="">— اختر —</option>
                        @foreach(($availableUsers ?? collect()) as $u)
                            <option value="{{ $u->id }}" {{ (string) old('user_id') === (string) $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                @else
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $student->user?->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">البريد <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $student->user?->email) }}" dir="ltr" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الهاتف</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $student->user?->phone) }}" dir="ltr">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">رمز الطالب</label>
                        <input type="text" class="form-control" value="{{ $student->student_code }}" disabled>
                    </div>
                </div>
                @endunless
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-header"><span class="fw-bold">الملف الشخصي</span></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">الجنس</label>
                        <select name="gender" class="form-select">
                            <option value="">—</option>
                            @foreach(['male' => 'ذكر', 'female' => 'أنثى'] as $val => $label)
                                <option value="{{ $val }}" {{ old('gender', $student?->gender) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">تاريخ الميلاد</label>
                        <input type="date" name="date_of_birth" class="form-control"
                               value="{{ old('date_of_birth', $student?->date_of_birth?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الجنسية</label>
                        <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $student?->nationality) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الدولة</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $student?->country) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">المدينة</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $student?->city) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">المستوى التعليمي</label>
                        <input type="text" name="education_level" class="form-control" value="{{ old('education_level', $student?->education_level) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الجامعة</label>
                        <input type="text" name="university" class="form-control" value="{{ old('university', $student?->university) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">التخصص</label>
                        <input type="text" name="major" class="form-control" value="{{ old('major', $student?->major) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">العنوان</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $student?->address) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">أهداف التعلم</label>
                        <textarea name="learning_goals" class="form-control" rows="3">{{ old('learning_goals', $student?->learning_goals) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card custom-card">
            <div class="card-header"><span class="fw-bold">الحالة والإعدادات</span></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">حالة الطالب</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach(\App\Models\Student::STATUSES as $status)
                            <option value="{{ $status }}" {{ old('status', $student?->status ?? 'active') === $status ? 'selected' : '' }}>
                                {{ (new \App\Models\Student(['status' => $status]))->status_label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">ملاحظات الأدمن</label>
                    <textarea name="admin_notes" class="form-control" rows="4">{{ old('admin_notes', $student?->admin_notes) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ri-save-line me-1"></i> {{ $isEdit ? 'حفظ التعديلات' : 'إنشاء الطالب' }}
                </button>
            </div>
        </div>
    </div>
</div>

@unless($isEdit)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="source_type"]');
    const newFields = document.getElementById('new-user-fields');
    const existingFields = document.getElementById('existing-user-fields');
    function toggle() {
        const isExisting = document.getElementById('source_existing')?.checked;
        newFields?.classList.toggle('d-none', isExisting);
        existingFields?.classList.toggle('d-none', !isExisting);
    }
    radios.forEach(r => r.addEventListener('change', toggle));
    toggle();
});
</script>
@endpush
@endunless
