<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class StudentProvisioningService
{
    public function provisionFromRegistration(User $user, array $studentData = []): Student
    {
        return $this->provision($user, $studentData);
    }

    public function provisionFromAdmin(User $user, array $studentData = [], ?int $createdBy = null): Student
    {
        return $this->provision($user, $studentData, $createdBy);
    }

    public function createUserAndStudent(array $userData, array $studentData = [], ?int $createdBy = null): Student
    {
        return DB::transaction(function () use ($userData, $studentData, $createdBy) {
            $user = User::create($userData);
            $user->assignRole($this->studentRole());

            return $this->createStudentRecord($user, $studentData, $createdBy);
        });
    }

    public function linkExistingUser(User $user, array $studentData = [], ?int $createdBy = null): Student
    {
        if ($user->student) {
            throw new \InvalidArgumentException('المستخدم مرتبط بملف طالب بالفعل.');
        }

        return DB::transaction(function () use ($user, $studentData, $createdBy) {
            if (! $user->hasRole('student')) {
                $user->assignRole($this->studentRole());
            }

            return $this->createStudentRecord($user, $studentData, $createdBy);
        });
    }

    private function provision(User $user, array $studentData, ?int $createdBy = null): Student
    {
        if ($user->student) {
            return $user->student;
        }

        return DB::transaction(function () use ($user, $studentData, $createdBy) {
            if (! $user->hasRole('student')) {
                $user->assignRole($this->studentRole());
            }

            return $this->createStudentRecord($user, $studentData, $createdBy);
        });
    }

    private function createStudentRecord(User $user, array $studentData, ?int $createdBy): Student
    {
        return Student::create(array_merge([
            'user_id' => $user->id,
            'student_code' => Student::generateStudentCode(),
            'status' => 'active',
            'created_by' => $createdBy,
        ], $studentData));
    }

    private function studentRole(): Role
    {
        return Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
    }
}
