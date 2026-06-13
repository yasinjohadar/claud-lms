<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseTag;
use App\Models\User;
use App\Services\CourseCurriculumService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CourseCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $instructorRole = Role::firstOrCreate(['name' => 'instructor']);

        $instructor = User::firstOrCreate(
            ['email' => 'instructor@edumatic.com'],
            [
                'name' => 'م. أحمد سعيد',
                'password' => bcrypt('123456789'),
                'email_verified_at' => now(),
            ]
        );
        $instructor->assignRole($instructorRole);

        $instructor2 = User::firstOrCreate(
            ['email' => 'sara@edumatic.com'],
            [
                'name' => 'سارة محمد',
                'password' => bcrypt('123456789'),
                'email_verified_at' => now(),
            ]
        );
        $instructor2->assignRole($instructorRole);

        $categories = [
            ['name' => 'البرمجة والتطوير', 'slug' => 'programming', 'icon' => 'fas fa-code', 'color' => '#059669', 'order' => 1],
            ['name' => 'التصميم الجرافيكي', 'slug' => 'design', 'icon' => 'fas fa-paint-brush', 'color' => '#ec4899', 'order' => 2],
            ['name' => 'التسويق الرقمي', 'slug' => 'marketing', 'icon' => 'fas fa-bullhorn', 'color' => '#f59e0b', 'order' => 3],
            ['name' => 'الذكاء الاصطناعي', 'slug' => 'ai', 'icon' => 'fas fa-robot', 'color' => '#06b6d4', 'order' => 4],
            ['name' => 'اللغات', 'slug' => 'languages', 'icon' => 'fas fa-language', 'color' => '#10b981', 'order' => 5],
            ['name' => 'الأعمال والإدارة', 'slug' => 'business', 'icon' => 'fas fa-briefcase', 'color' => '#8b5cf6', 'order' => 6],
        ];

        foreach ($categories as $cat) {
            CourseCategory::updateOrCreate(['slug' => $cat['slug']], array_merge($cat, [
                'description' => 'كورسات في مجال ' . $cat['name'],
                'is_active' => true,
                'is_featured' => true,
            ]));
        }

        $tags = [
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'React', 'slug' => 'react'],
            ['name' => 'UI/UX', 'slug' => 'ui-ux'],
            ['name' => 'Python', 'slug' => 'python'],
            ['name' => 'تسويق', 'slug' => 'marketing-tag'],
            ['name' => 'Flutter', 'slug' => 'flutter'],
            ['name' => 'ذكاء اصطناعي', 'slug' => 'ai-tag'],
            ['name' => 'إدارة', 'slug' => 'management'],
        ];
        foreach ($tags as $i => $tag) {
            CourseTag::updateOrCreate(['slug' => $tag['slug']], [
                'name' => $tag['name'],
                'is_active' => true,
                'order' => $i + 1,
            ]);
        }

        $courses = [
            ['title' => 'الدورة الشاملة في تطوير واجهات الويب المحترفة', 'slug' => 'professional-web-development', 'category' => 'programming', 'instructor' => $instructor, 'level' => 'beginner', 'price' => 49, 'compare_at_price' => 199, 'badge' => 'الأكثر مبيعاً', 'icon' => 'fa-laptop-code', 'rating_avg' => 4.8, 'students_count' => 12500, 'lessons_count' => 140, 'duration_hours' => 45, 'is_featured' => true],
            ['title' => 'تصميم واجهات المستخدم UI/UX التفاعلية', 'slug' => 'interactive-ui-ux-design', 'category' => 'design', 'instructor' => $instructor2, 'level' => 'beginner', 'price' => 35, 'compare_at_price' => 150, 'badge' => 'جديد', 'icon' => 'fa-pen-nib', 'rating_avg' => 4.9, 'students_count' => 8400, 'lessons_count' => 85, 'duration_hours' => 28, 'is_featured' => true],
            ['title' => 'التسويق الرقمي وإدارة الحملات الإعلانية', 'slug' => 'digital-marketing-campaigns', 'category' => 'marketing', 'instructor' => $instructor, 'level' => 'intermediate', 'price' => 29, 'compare_at_price' => 120, 'badge' => '', 'icon' => 'fa-bullhorn', 'rating_avg' => 4.7, 'students_count' => 5600, 'lessons_count' => 60, 'duration_hours' => 20, 'is_featured' => false],
            ['title' => 'تعلم الذكاء الاصطناعي وبناء النماذج التوليدية', 'slug' => 'generative-ai-models', 'category' => 'ai', 'instructor' => $instructor, 'level' => 'advanced', 'price' => 65, 'compare_at_price' => 250, 'badge' => 'الأعلى تقييماً', 'icon' => 'fa-robot', 'rating_avg' => 4.9, 'students_count' => 2100, 'lessons_count' => 72, 'duration_hours' => 35, 'is_featured' => true],
            ['title' => 'إتقان اللغة الإنجليزية للمحترفين في بيئة العمل', 'slug' => 'business-english-mastery', 'category' => 'languages', 'instructor' => $instructor2, 'level' => 'beginner', 'price' => 19, 'compare_at_price' => 90, 'badge' => '', 'icon' => 'fa-language', 'rating_avg' => 4.6, 'students_count' => 15000, 'lessons_count' => 95, 'duration_hours' => 30, 'is_featured' => false],
            ['title' => 'إدارة المشاريع الرشيقة Agile & Scrum', 'slug' => 'agile-scrum-project-management', 'category' => 'business', 'instructor' => $instructor, 'level' => 'intermediate', 'price' => 45, 'compare_at_price' => 175, 'badge' => 'موصى به', 'icon' => 'fa-chart-pie', 'rating_avg' => 4.8, 'students_count' => 4300, 'lessons_count' => 48, 'duration_hours' => 18, 'is_featured' => true],
            ['title' => 'احترف برمجة تطبيقات فلاتر (Flutter)', 'slug' => 'flutter-mobile-development', 'category' => 'programming', 'instructor' => $instructor, 'level' => 'intermediate', 'price' => 55, 'compare_at_price' => 210, 'badge' => 'تحديث جديد', 'icon' => 'fa-mobile-alt', 'rating_avg' => 4.9, 'students_count' => 6200, 'lessons_count' => 110, 'duration_hours' => 40, 'is_featured' => true],
            ['title' => 'دورة شاملة في تحليل البيانات Python & Pandas', 'slug' => 'python-pandas-data-analysis', 'category' => 'ai', 'instructor' => $instructor2, 'level' => 'intermediate', 'price' => 39, 'compare_at_price' => 160, 'badge' => 'الأكثر طلباً', 'icon' => 'fa-chart-line', 'rating_avg' => 4.8, 'students_count' => 9500, 'lessons_count' => 88, 'duration_hours' => 32, 'is_featured' => false],
            ['title' => 'أساسيات المحاسبة والمالية لغير الماليين', 'slug' => 'accounting-finance-basics', 'category' => 'business', 'instructor' => $instructor, 'level' => 'beginner', 'price' => 25, 'compare_at_price' => 110, 'badge' => '', 'icon' => 'fa-calculator', 'rating_avg' => 4.5, 'students_count' => 3100, 'lessons_count' => 42, 'duration_hours' => 15, 'is_featured' => false],
            ['title' => 'تطوير الألعاب باستخدام Unity', 'slug' => 'unity-game-development', 'category' => 'programming', 'instructor' => $instructor2, 'level' => 'beginner', 'price' => 45, 'compare_at_price' => 180, 'badge' => '', 'icon' => 'fa-gamepad', 'rating_avg' => 4.7, 'students_count' => 4800, 'lessons_count' => 75, 'duration_hours' => 28, 'is_featured' => false],
        ];

        $tagModels = CourseTag::all()->keyBy('slug');

        foreach ($courses as $i => $data) {
            $category = CourseCategory::where('slug', $data['category'])->first();
            if (! $category) {
                continue;
            }

            $course = Course::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'excerpt' => 'تعلم ' . $data['title'] . ' مع أفضل المدربين العرب.',
                    'description' => '<p>دورة شاملة تغطي جميع الجوانب العملية لـ ' . $data['title'] . '.</p>',
                    'course_category_id' => $category->id,
                    'instructor_id' => $data['instructor']->id,
                    'level' => $data['level'],
                    'price' => $data['price'],
                    'compare_at_price' => $data['compare_at_price'],
                    'currency' => 'USD',
                    'badge' => $data['badge'] ?: null,
                    'icon' => $data['icon'],
                    'rating_avg' => $data['rating_avg'],
                    'rating_count' => rand(50, 500),
                    'students_count' => $data['students_count'],
                    'lessons_count' => 0,
                    'duration_hours' => 0,
                    'language' => 'ar',
                    'what_you_learn' => ['مهارات عملية قابلة للتطبيق', 'مشاريع حقيقية', 'شهادة إتمام'],
                    'requirements' => ['جهاز كمبيوتر', 'اتصال بالإنترنت', 'رغبة في التعلم'],
                    'status' => 'published',
                    'published_at' => now()->subDays($i),
                    'is_featured' => $data['is_featured'],
                    'order' => $i + 1,
                ]
            );

            $tagSlug = match ($data['category']) {
                'programming' => 'laravel',
                'design' => 'ui-ux',
                'marketing' => 'marketing-tag',
                'ai' => 'ai-tag',
                default => null,
            };
            if ($tagSlug && $tagModels->has($tagSlug)) {
                $course->tags()->syncWithoutDetaching([$tagModels[$tagSlug]->id]);
            }

            $this->seedSampleCurriculum($course, $i);
        }

        foreach (CourseCategory::all() as $category) {
            $category->updateCoursesCount();
        }

        foreach (CourseTag::all() as $tag) {
            $tag->updateCoursesCount();
        }
    }

    protected function seedSampleCurriculum(Course $course, int $index): void
    {
        $course->sections()->delete();

        $providers = ['youtube', 'vimeo', 'bunny_stream', 'bunny_cdn'];
        $references = [
            'dQw4w9WgXcQ',
            '76979871',
            json_encode(['library_id' => '12345', 'video_id' => 'sample-guid-' . $index], JSON_UNESCAPED_UNICODE),
            'https://cdn.example.b-cdn.net/courses/intro.mp4',
        ];

        $section = $course->sections()->create([
            'title' => 'مقدمة الدورة',
            'sort_order' => 1,
        ]);

        $section->lessons()->createMany([
            [
                'title' => 'مرحباً بك في الدورة',
                'video_provider' => $providers[$index % 4],
                'video_reference' => $references[$index % 4],
                'duration_seconds' => 480,
                'sort_order' => 1,
            ],
            [
                'title' => 'إعداد بيئة العمل',
                'video_provider' => 'youtube',
                'video_reference' => 'dQw4w9WgXcQ',
                'duration_seconds' => 750,
                'sort_order' => 2,
            ],
        ]);

        $course->sections()->create([
            'title' => 'الوحدة التطبيقية',
            'sort_order' => 2,
        ])->lessons()->create([
            'title' => 'مشروع عملي أول',
            'video_provider' => 'vimeo',
            'video_reference' => '76979871',
            'duration_seconds' => 1200,
            'sort_order' => 1,
        ]);

        app(CourseCurriculumService::class)->syncCourseStats($course);
    }
}
