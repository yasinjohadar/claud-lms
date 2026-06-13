<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\LessonProgress;
use App\Models\Student;
use App\Models\User;
use App\Services\EnrollmentService;
use App\Services\StudentProvisioningService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    private const STUDENT_COUNT = 50;

    private const FIRST_NAMES = [
        'أحمد', 'فاطمة', 'يوسف', 'مريم', 'عمر', 'ليلى', 'خالد', 'نور', 'سامي', 'رنا',
        'طارق', 'هبة', 'كريم', 'سلمى', 'باسم', 'دانا', 'وليد', 'جنى', 'حسام', 'لينا',
        'زياد', 'ياسمين', 'رائد', 'سحر', 'فادي', 'ندى', 'مازن', 'رهف', 'عادل', 'تالا',
    ];

    private const LAST_NAMES = [
        'علي', 'حسن', 'كريم', 'محمود', 'خليل', 'ناصر', 'سعيد', 'عبدالله', 'حمدان', 'جابر',
        'الأحمد', 'الحسين', 'الخطيب', 'الشامي', 'الحلبي', 'الدمشقي', 'الحموي', 'اللاذقاني',
    ];

    public function run(): void
    {
        $provisioning = app(StudentProvisioningService::class);
        $enrollmentService = app(EnrollmentService::class);

        $students = collect();

        for ($i = 1; $i <= self::STUDENT_COUNT; $i++) {
            $name = $this->randomArabicName();
            $email = sprintf('student%d@edumatic.com', $i);
            $phone = sprintf('+963911%06d', $i);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => $phone,
                    'password' => Hash::make('123456789'),
                    'is_active' => true,
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );

            $student = $user->student ?? $provisioning->provisionFromAdmin($user, [
                'bio' => 'طالب في منصة إديوماتيك.',
                'learning_goals' => 'تطوير المهارات التقنية والحصول على شهادات.',
                'country' => fake()->randomElement(['سوريا', 'الإمارات', 'السعودية', 'الأردن', 'لبنان']),
                'city' => fake()->randomElement(['دمشق', 'حلب', 'دبي', 'الرياض', 'عمّان', 'بيروت']),
                'education_level' => fake()->randomElement(['ثانوي', 'جامعي', 'دراسات عليا']),
                'status' => fake()->randomElement(['active', 'active', 'active', 'inactive', 'graduated']),
            ]);

            $students->push($student);
        }

        $courses = Course::published()->get();

        if ($courses->isEmpty()) {
            $this->command?->warn('لا توجد كورسات منشورة — تم إنشاء الطلاب بدون تسجيلات.');

            return;
        }

        foreach ($students as $index => $student) {
            $course = $courses->get($index % $courses->count());

            $enrollment = CourseEnrollment::query()
                ->where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->first();

            if (! $enrollment) {
                $source = fake()->randomElement(['free', 'admin_grant', 'promo', 'purchase']);
                $enrollment = $enrollmentService->grant($student, $course, $source, null, null, 'active');
            }

            $lessons = $course->sections()->with('lessons')->get()->flatMap->lessons;
            $lessonsToSeed = $lessons->take(fake()->numberBetween(1, min(3, $lessons->count() ?: 1)));

            foreach ($lessonsToSeed as $lessonIndex => $lesson) {
                $completed = $lessonIndex === 0 && fake()->boolean(70);

                LessonProgress::updateOrCreate(
                    [
                        'enrollment_id' => $enrollment->id,
                        'course_lesson_id' => $lesson->id,
                    ],
                    [
                        'student_id' => $student->id,
                        'status' => $completed ? 'completed' : 'in_progress',
                        'watched_seconds' => $completed ? ($lesson->duration_seconds ?? 300) : fake()->numberBetween(60, 240),
                        'last_position_seconds' => $completed ? ($lesson->duration_seconds ?? 300) : fake()->numberBetween(30, 180),
                        'completed_at' => $completed ? now()->subDays(fake()->numberBetween(1, 30)) : null,
                    ]
                );
            }

            $enrollmentService->recalculateProgress($enrollment->fresh());
        }

        $this->command?->info('تم إنشاء/تحديث ' . self::STUDENT_COUNT . ' طالب.');
    }

    private function randomArabicName(): string
    {
        return fake()->randomElement(self::FIRST_NAMES) . ' ' . fake()->randomElement(self::LAST_NAMES);
    }
}
