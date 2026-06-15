<?php

namespace App\Http\Requests\Admin;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('enrollment-manage') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'source' => ['nullable', 'string', 'in:'.implode(',', ['admin_grant', 'free', 'promo'])],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $student = Student::query()->find($this->integer('student_id'));

            if ($student && $student->status === 'suspended') {
                $validator->errors()->add('student_id', 'لا يمكن تسجيل طالب موقوف. يرجى تفعيل حسابه أولاً.');
            }

            if ($student && $student->status === 'inactive') {
                $validator->errors()->add('student_id', 'لا يمكن تسجيل طالب غير نشط.');
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'يرجى اختيار الطالب.',
            'student_id.exists' => 'الطالب المحدد غير موجود.',
            'course_id.required' => 'يرجى اختيار الكورس.',
            'course_id.exists' => 'الكورس المحدد غير موجود.',
            'source.in' => 'مصدر التسجيل غير صالح.',
        ];
    }

    public function student(): Student
    {
        return Student::query()->findOrFail($this->integer('student_id'));
    }

    public function course(): Course
    {
        return Course::query()->findOrFail($this->integer('course_id'));
    }

    public function enrollmentSource(): string
    {
        return $this->input('source', 'admin_grant');
    }

    public function hadExistingEnrollment(): bool
    {
        return CourseEnrollment::query()
            ->where('student_id', $this->integer('student_id'))
            ->where('course_id', $this->integer('course_id'))
            ->exists();
    }
}
