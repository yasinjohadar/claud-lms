<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StudentService
{
    public function __construct(
        protected StudentProvisioningService $provisioningService
    ) {}

    public function validateStore(Request $request): array
    {
        $sourceType = $request->input('source_type', 'new_user');

        $rules = [
            'source_type' => ['required', Rule::in(['new_user', 'existing_user'])],
            'status' => ['required', Rule::in(Student::STATUSES)],
            'gender' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'education_level' => 'nullable|string|max:100',
            'university' => 'nullable|string|max:150',
            'major' => 'nullable|string|max:150',
            'occupation' => 'nullable|string|max:150',
            'company' => 'nullable|string|max:150',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'preferred_language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:60',
            'bio' => 'nullable|string|max:5000',
            'learning_goals' => 'nullable|string|max:5000',
            'admin_notes' => 'nullable|string|max:5000',
        ];

        if ($sourceType === 'new_user') {
            $rules += [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'phone' => 'nullable|string|max:30|unique:users,phone',
                'password' => ['required', 'confirmed', Password::defaults()],
            ];
        } else {
            $rules += [
                'user_id' => 'required|exists:users,id|unique:students,user_id',
            ];
        }

        return $request->validate($rules);
    }

    public function validateUpdate(Request $request, Student $student): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'phone' => ['nullable', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($student->user_id)],
            'status' => ['required', Rule::in(Student::STATUSES)],
            'gender' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'education_level' => 'nullable|string|max:100',
            'university' => 'nullable|string|max:150',
            'major' => 'nullable|string|max:150',
            'occupation' => 'nullable|string|max:150',
            'company' => 'nullable|string|max:150',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'preferred_language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:60',
            'bio' => 'nullable|string|max:5000',
            'learning_goals' => 'nullable|string|max:5000',
            'admin_notes' => 'nullable|string|max:5000',
        ]);
    }

    public function store(array $validated, ?int $createdBy = null): Student
    {
        $profile = $this->extractProfileData($validated);

        if ($validated['source_type'] === 'existing_user') {
            $user = User::findOrFail($validated['user_id']);

            return $this->provisioningService->linkExistingUser($user, $profile, $createdBy);
        }

        return $this->provisioningService->createUserAndStudent([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_active' => true,
            'status' => 'active',
            'created_by' => $createdBy,
        ], $profile, $createdBy);
    }

    public function update(Student $student, array $validated): Student
    {
        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $student->update($this->extractProfileData($validated));

        return $student->fresh(['user']);
    }

    private function extractProfileData(array $data): array
    {
        return collect($data)->only([
            'gender', 'date_of_birth', 'nationality', 'country', 'city', 'address',
            'education_level', 'university', 'major', 'occupation', 'company',
            'emergency_contact_name', 'emergency_contact_phone',
            'preferred_language', 'timezone', 'bio', 'learning_goals',
            'admin_notes', 'status',
        ])->filter(fn ($v) => $v !== null && $v !== '')->all();
    }
}
