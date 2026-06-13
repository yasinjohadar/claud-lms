<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseResource;
use App\Models\CourseTag;
use App\Models\User;
use App\Services\CourseCurriculumService;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CourseCatalogSeeder extends Seeder
{
    private const VIDEO_PROVIDERS = ['youtube', 'vimeo', 'bunny_stream', 'bunny_cdn'];

    private const VIDEO_REFERENCES = [
        'youtube' => ['dQw4w9WgXcQ', 'jNQXAC9IVRw', 'M7lc1UVf-VE', '9bZkp7q19f0', 'kJQP7kiw5Fk'],
        'vimeo' => ['76979871', '22439234', '148751763', '357274789'],
        'bunny_stream' => [
            '{"library_id":"12345","video_id":"intro-web-001"}',
            '{"library_id":"12345","video_id":"module-css-002"}',
            '{"library_id":"67890","video_id":"flutter-lesson-03"}',
        ],
        'bunny_cdn' => [
            'https://vz-example.b-cdn.net/courses/lesson-01.mp4',
            'https://vz-example.b-cdn.net/courses/lesson-02.mp4',
            'https://cdn-demo.b-cdn.net/videos/chapter-03.mp4',
        ],
    ];

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

            $this->seedFullCurriculum($course, $data);
        }

        foreach (CourseCategory::all() as $category) {
            $category->updateCoursesCount();
        }

        foreach (CourseTag::all() as $tag) {
            $tag->updateCoursesCount();
        }
    }

    protected function seedFullCurriculum(Course $course, array $data): void
    {
        $course->sections()->delete();
        $course->resources()->delete();

        $blueprint = $this->curriculumBlueprint($data['slug']);
        $targetLessons = $data['lessons_count'];
        $avgDuration = max(300, (int) (($data['duration_hours'] * 3600) / max($targetLessons, 1)));

        $lessonCounter = 0;
        $providerIndex = 0;

        foreach ($blueprint as $sectionIndex => $sectionData) {
            $section = $course->sections()->create([
                'title' => $sectionData['title'],
                'sort_order' => $sectionIndex + 1,
            ]);

            $lessonTitles = $sectionData['lessons'];
            $lessonsInSection = $this->distributeLessonsForSection(
                count($lessonTitles),
                $targetLessons,
                count($blueprint),
                $sectionIndex,
                $lessonCounter
            );

            $expandedTitles = $this->expandLessonTitles($lessonTitles, $lessonsInSection, $sectionData['title']);

            foreach ($expandedTitles as $lessonIndex => $title) {
                $provider = self::VIDEO_PROVIDERS[$providerIndex % count(self::VIDEO_PROVIDERS)];
                $refs = self::VIDEO_REFERENCES[$provider];
                $reference = $refs[($lessonCounter + $lessonIndex) % count($refs)];

                $section->lessons()->create([
                    'title' => $title,
                    'video_provider' => $provider,
                    'video_reference' => $reference,
                    'duration_seconds' => $this->lessonDuration($avgDuration, $lessonCounter + $lessonIndex),
                    'sort_order' => $lessonIndex + 1,
                ]);

                $providerIndex++;
            }

            $lessonCounter += count($expandedTitles);

            if ($lessonCounter >= $targetLessons) {
                break;
            }
        }

        while ($lessonCounter < $targetLessons) {
            $section = $course->sections()->last()
                ?? $course->sections()->create(['title' => 'دروس إضافية', 'sort_order' => $course->sections()->count() + 1]);

            $provider = self::VIDEO_PROVIDERS[$providerIndex % count(self::VIDEO_PROVIDERS)];
            $refs = self::VIDEO_REFERENCES[$provider];
            $num = $lessonCounter + 1;

            $section->lessons()->create([
                'title' => 'درس تطبيقي إضافي ' . $num,
                'video_provider' => $provider,
                'video_reference' => $refs[$lessonCounter % count($refs)],
                'duration_seconds' => $this->lessonDuration($avgDuration, $lessonCounter),
                'sort_order' => $section->lessons()->count() + 1,
            ]);

            $lessonCounter++;
            $providerIndex++;
        }

        app(CourseCurriculumService::class)->syncCourseStats($course);

        $this->seedResources($course);
    }

    protected function seedResources(Course $course): void
    {
        $course->resources()->create([
            'title' => 'دليل المراجع والمصادر',
            'slug' => CourseResource::generateUniqueSlug($course->id, 'دليل المراجع والمصادر'),
            'description' => 'مجموعة روابط لمصادر إضافية ومقالات مرجعية تساعدك على تعميق فهمك لموضوعات الكورس.',
            'type' => 'link',
            'url' => 'https://developer.mozilla.org/ar/',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $course->resources()->create([
            'title' => 'قائمة المصطلحات',
            'slug' => CourseResource::generateUniqueSlug($course->id, 'قائمة المصطلحات'),
            'description' => 'ملف يضم أهم المصطلحات والمفاهيم المستخدمة طوال الدورة مع شرح مختصر لكل منها.',
            'type' => 'link',
            'url' => 'https://www.w3schools.com/',
            'sort_order' => 2,
            'is_published' => true,
        ]);

        $firstSection = $course->sections()->orderBy('sort_order')->first();

        if ($firstSection) {
            $course->resources()->create([
                'course_section_id' => $firstSection->id,
                'title' => 'ملخص القسم الأول',
                'slug' => CourseResource::generateUniqueSlug($course->id, 'ملخص القسم الأول'),
                'type' => 'link',
                'url' => 'https://github.com/',
                'sort_order' => 1,
                'is_published' => true,
            ]);
        }
    }

    protected function distributeLessonsForSection(int $baseCount, int $targetTotal, int $sectionCount, int $sectionIndex, int $alreadyCreated): int
    {
        $remaining = max(0, $targetTotal - $alreadyCreated);
        $remainingSections = max(1, $sectionCount - $sectionIndex);

        if ($sectionIndex === $sectionCount - 1) {
            return max($baseCount, $remaining);
        }

        $share = (int) ceil($remaining / $remainingSections);

        return max($baseCount, min($share, $remaining));
    }

    /**
     * @param  array<int, string>  $baseTitles
     * @return array<int, string>
     */
    protected function expandLessonTitles(array $baseTitles, int $targetCount, string $sectionTitle): array
    {
        if ($targetCount <= count($baseTitles)) {
            return array_slice($baseTitles, 0, $targetCount);
        }

        $titles = $baseTitles;
        $i = 1;

        while (count($titles) < $targetCount) {
            $titles[] = 'تمرين عملي — ' . $sectionTitle . ' (' . $i . ')';
            $i++;
        }

        return $titles;
    }

    protected function lessonDuration(int $average, int $index): int
    {
        $variance = [-120, -60, 0, 60, 120, 180][$index % 6];

        return max(180, $average + $variance);
    }

    /**
     * @return array<int, array{title: string, lessons: array<int, string>}>
     */
    protected function curriculumBlueprint(string $slug): array
    {
        return match ($slug) {
            'professional-web-development' => [
                ['title' => 'مقدمة في تطوير الويب', 'lessons' => [
                    'مرحباً بك في الدورة الشاملة', 'كيف يعمل الإنترنت والمتصفحات', 'تثبيت VS Code والأدوات الأساسية',
                    'هيكل مشروع ويب احترافي', 'Git و GitHub للمبتدئين', 'أفضل ممارسات كتابة الكود',
                ]],
                ['title' => 'أساسيات HTML5', 'lessons' => [
                    'أول صفحة HTML', 'العناوين والفقرات والروابط', 'القوائم والجداول', 'النماذج والمدخلات',
                    'Semantic HTML', 'SEO أساسيات للصفحات', 'مشروع: صفحة هبوط شخصية',
                ]],
                ['title' => 'تنسيق CSS3 المتقدم', 'lessons' => [
                    'المحددات والخصائص', 'Box Model و Flexbox', 'CSS Grid Layout', 'التجاوب Responsive Design',
                    'المتغيرات والحركات CSS', 'SASS أساسيات', 'مشروع: واجهة متجاوبة كاملة',
                ]],
                ['title' => 'JavaScript للواجهات', 'lessons' => [
                    'المتغيرات والدوال', 'DOM Manipulation', 'الأحداث Events', 'Fetch API و AJAX',
                    'ES6+ الميزات الحديثة', 'التعامل مع JSON', 'مشروع: تطبيق Todo List',
                ]],
                ['title' => 'إطار React.js', 'lessons' => [
                    'مقدمة React و JSX', 'المكونات Components', 'State و Props', 'Hooks الأساسية',
                    'React Router', 'إدارة الحالة', 'مشروع: لوحة تحكم تفاعلية',
                ]],
                ['title' => 'Laravel للواجهة الخلفية', 'lessons' => [
                    'تثبيت Laravel', 'Routing و Controllers', 'Eloquent ORM', 'Blade Templates',
                    'المصادقة Authentication', 'RESTful API', 'مشروع: API للمدونة',
                ]],
                ['title' => 'مشاريع نهائية ونشر', 'lessons' => [
                    'ربط React مع Laravel API', 'إدارة الأخطاء والاختبار', 'تحسين الأداء',
                    'النشر على السيرفر', 'مراجعة شاملة للمشروع', 'نصائح التوظيف والبورتفوليو',
                ]],
            ],
            'interactive-ui-ux-design' => [
                ['title' => 'أساسيات تجربة المستخدم', 'lessons' => [
                    'ما الفرق بين UI و UX', 'رحلة المستخدم User Journey', 'بحوث المستخدم', 'بناء Personas',
                    'تحليل المنافسين', 'مبادئ التصميم البصري',
                ]],
                ['title' => 'Figma من الصفر', 'lessons' => [
                    'واجهة Figma والأدوات', 'الإطارات Frames والشبكات', 'المكونات Components',
                    'Auto Layout', 'النماذج الأولية Prototyping', 'التعاون والمشاركة',
                ]],
                ['title' => 'أنظمة التصميم Design Systems', 'lessons' => [
                    'الألوان والطباعة', 'Spacing و Grid', 'مكتبة مكونات', 'Dark Mode',
                    'إمكانية الوصول Accessibility', 'توثيق النظام',
                ]],
                ['title' => 'تصميم تطبيقات الموبايل', 'lessons' => [
                    'إرشادات iOS و Android', 'تصميم شاشات التسجيل', 'التنقل Navigation Patterns',
                    'Micro-interactions', 'مشروع: تطبيق توصيل طعام',
                ]],
                ['title' => 'تصميم مواقع الويب', 'lessons' => [
                    'هيكل الصفحة الرئيسية', 'صفحات المنتج والتفاصيل', 'لوحات التحكم Dashboard',
                    'حالات التحميل والأخطاء', 'مشروع: موقع SaaS كامل',
                ]],
                ['title' => 'اختبار وتسليم التصاميم', 'lessons' => [
                    'اختبار قابلية الاستخدام', 'تسليم Handoff للمطورين', 'Design Critique',
                    'بناء Portfolio احترافي',
                ]],
            ],
            'digital-marketing-campaigns' => [
                ['title' => 'مقدمة التسويق الرقمي', 'lessons' => [
                    'مفهوم التسويق الرقمي', 'رحلة العميل', 'بناء استراتيجية تسويقية', 'تحديد الجمهور المستهدف',
                ]],
                ['title' => 'التسويق عبر محركات البحث SEO', 'lessons' => [
                    'أساسيات SEO', 'بحث الكلمات المفتاحية', 'تحسين On-Page', 'بناء الروابط Backlinks',
                    'Google Search Console', 'قياس أداء SEO',
                ]],
                ['title' => 'الإعلانات المدفوعة PPC', 'lessons' => [
                    'مقدمة Google Ads', 'حملات البحث Search', 'حملات العرض Display', 'إعادة الاستهداف Remarketing',
                    'Facebook و Instagram Ads', 'تحسين معدل التحويل',
                ]],
                ['title' => 'التسويق بالمحتوى', 'lessons' => [
                    'استراتيجية المحتوى', 'كتابة محتوى جذاب', 'التسويق عبر البريد Email Marketing',
                    'أتمتة التسويق', 'تحليلات المحتوى',
                ]],
                ['title' => 'وسائل التواصل الاجتماعي', 'lessons' => [
                    'خطة محتوى شهرية', 'إدارة المجتمعات', 'التعامل مع الأزمات', 'Influencer Marketing',
                ]],
                ['title' => 'التحليلات والتقارير', 'lessons' => [
                    'Google Analytics 4', 'لوحات المتابعة', 'ROI وحساب العائد', 'تقرير حملة كامل',
                ]],
            ],
            'generative-ai-models' => [
                ['title' => 'أساسيات الذكاء الاصطناعي', 'lessons' => [
                    'تاريخ وتطور AI', 'التعلم الآلي Machine Learning', 'الشبكات العصبية', 'مفهوم Deep Learning',
                ]],
                ['title' => 'نماذج اللغة الكبيرة LLMs', 'lessons' => [
                    'كيف تعمل GPT', 'Prompt Engineering', 'Fine-tuning أساسيات', 'RAG والبحث المعزز',
                    'تقييم جودة المخرجات', 'أخلاقيات استخدام AI',
                ]],
                ['title' => 'النماذج التوليدية للصور', 'lessons' => [
                    'مقدمة Diffusion Models', 'Stable Diffusion', 'Midjourney و DALL-E', 'ControlNet',
                    'تحرير الصور بالذكاء الاصطناعي',
                ]],
                ['title' => 'بناء تطبيقات AI', 'lessons' => [
                    'OpenAI API', 'LangChain أساسيات', 'بناء Chatbot', 'وكلاء AI Agents',
                    'نشر التطبيقات', 'مراقبة التكلفة',
                ]],
                ['title' => 'مشاريع متقدمة', 'lessons' => [
                    'مشروع: مساعد ذكي للشركات', 'مشروع: مولّد محتوى', 'مشروع: تحليل مستندات',
                    'مراجعة وخطة تطوير مستقبلية',
                ]],
            ],
            'business-english-mastery' => [
                ['title' => 'أساسيات التواصل المهني', 'lessons' => [
                    'التعارف في بيئة العمل', 'البريد الإلكتروني الاحترافي', 'المكالمات الهاتفية', 'آداب الاجتماعات',
                ]],
                ['title' => 'المفردات التجارية', 'lessons' => [
                    'مصطلحات المالية', 'مصطلحات التسويق', 'مصطلحات الموارد البشرية', 'مصطلحات التقنية',
                    'اختبار المفردات الأول',
                ]],
                ['title' => 'العروض التقديمية', 'lessons' => [
                    'هيكل العرض Presentation Structure', 'لغة الإقناع', 'التعامل مع الأسئلة', 'عرض مشروع عملي',
                ]],
                ['title' => 'المفاوضات والاجتماعات', 'lessons' => [
                    'لغة التفاوض', 'صياغة الاقتراحات', 'حل النزاعات', 'محاكاة اجتماع Board Meeting',
                ]],
                ['title' => 'الكتابة المهنية', 'lessons' => [
                    'التقارير Reports', 'المذكرات Memos', 'خطابات التغطية Cover Letters', 'السيرة الذاتية CV',
                ]],
                ['title' => 'محاكاة بيئة العمل', 'lessons' => [
                    'مقابلة عمل بالإنجليزية', 'يوم عمل كامل بالإنجليزية', 'مراجعة شاملة', 'خطة تطوير لغوية',
                ]],
            ],
            'agile-scrum-project-management' => [
                ['title' => 'مقدمة إدارة المشاريع', 'lessons' => [
                    'دورة حياة المشروع', 'Waterfall vs Agile', 'قيم وأ principles أجايل', 'متى نستخدم Scrum',
                ]],
                ['title' => 'أدوار Scrum', 'lessons' => [
                    'دور Product Owner', 'دور Scrum Master', 'دور فريق التطوير', 'مسؤوليات كل دور',
                ]],
                ['title' => 'فعاليات Scrum', 'lessons' => [
                    'Sprint Planning', 'Daily Standup', 'Sprint Review', 'Sprint Retrospective', 'إدارة Backlog',
                ]],
                ['title' => 'أدوات ولوحات العمل', 'lessons' => [
                    'Kanban Board', 'Jira أساسيات', 'تقدير المهام Estimation', 'Velocity و Burndown Chart',
                ]],
                ['title' => 'إدارة الفرق والمخاطر', 'lessons' => [
                    'بناء فريق عالي الأداء', 'إدارة أصحاب المصلحة', 'تحديد المخاطر', 'خطط التصحيح',
                ]],
                ['title' => 'تطبيق عملي', 'lessons' => [
                    'محاكاة Sprint كامل', 'تسليم مشروع Agile', 'مراجعة شهادة PSM', 'نصائح التطبيق في الشركات',
                ]],
            ],
            'flutter-mobile-development' => [
                ['title' => 'مقدمة Flutter و Dart', 'lessons' => [
                    'لماذا Flutter', 'تثبيت Flutter SDK', 'أساسيات Dart', 'أول تطبيق Hello World',
                    'Hot Reload و الأدوات', 'هيكل المشروع',
                ]],
                ['title' => 'واجهات Flutter', 'lessons' => [
                    'Widgets أساسية', 'Layout: Row و Column', 'ListView و GridView', 'التنقل Navigation',
                    'الثيمات Themes', 'التجاوب مع الشاشات',
                ]],
                ['title' => 'إدارة الحالة', 'lessons' => [
                    'setState', 'Provider', 'Riverpod مقدمة', 'BLoC Pattern', 'مقارنة الحلول',
                ]],
                ['title' => 'التعامل مع البيانات', 'lessons' => [
                    'HTTP و REST API', 'JSON Serialization', 'التخزين المحلي SharedPreferences', 'SQLite و Hive',
                    'Firebase Integration',
                ]],
                ['title' => 'ميزات الجهاز', 'lessons' => [
                    'الكاميرا والمعرض', 'الموقع GPS', 'الإشعارات Push', 'الصلاحيات Permissions',
                ]],
                ['title' => 'النشر والمشاريع', 'lessons' => [
                    'بناء APK و IPA', 'Google Play Store', 'App Store', 'مشروع: تطبيق تجارة إلكترونية',
                    'مراجعة شاملة',
                ]],
            ],
            'python-pandas-data-analysis' => [
                ['title' => 'أساسيات Python للتحليل', 'lessons' => [
                    'تثبيت Python و Jupyter', 'المتغيرات والهياكل', 'الدوال والوحدات', 'قراءة الملفات',
                ]],
                ['title' => 'مكتبة Pandas', 'lessons' => [
                    'Series و DataFrame', 'تصفية البيانات', 'التجميع GroupBy', 'دمج الجداول Merge',
                    'معالجة القيم المفقودة', 'تحويل أنواع البيانات',
                ]],
                ['title' => 'تنظيف البيانات', 'lessons' => [
                    'اكتشاف الشذوذ Outliers', 'تطبيع البيانات', 'استخراج الميزات Feature Engineering',
                    'أتمتة خط التنظيف',
                ]],
                ['title' => 'التحليل الإحصائي', 'lessons' => [
                    'الإحصاء الوصفي', 'الارتباط Correlation', 'اختبار الفرضيات', 'NumPy للعمليات الرياضية',
                ]],
                ['title' => 'التصور البياني', 'lessons' => [
                    'Matplotlib أساسيات', 'Seaborn للرسوم الإحصائية', 'لوحات Plotly التفاعلية', 'تصميم تقارير بصرية',
                ]],
                ['title' => 'مشاريع تحليل حقيقية', 'lessons' => [
                    'تحليل مبيعات متجر', 'تحليل بيانات موظفين', 'تحليل وسائل التواصل', 'مشروع نهائي شامل',
                ]],
            ],
            'accounting-finance-basics' => [
                ['title' => 'مقدمة المحاسبة', 'lessons' => [
                    'ما هي المحاسبة', 'المعادلة المحاسبية', 'القوائم المالية الأساسية', 'دورة العمل المحاسبية',
                ]],
                ['title' => 'القيود اليومية', 'lessons' => [
                    'المدين والدائن', 'تسجيل العمليات', 'دفتر الأستاذ', 'ميزان المراجعة',
                ]],
                ['title' => 'الأصول والخصوم', 'lessons' => [
                    'الأصول المتداولة', 'الأصول الثابتة والإهلاك', 'الخصوم والالتزامات', 'حقوق الملكية',
                ]],
                ['title' => 'الإيرادات والمصروفات', 'lessons' => [
                    'معرفة الإيراد', 'تصنيف المصروفات', 'تكلفة البضاعة المباعة', 'هامش الربح',
                ]],
                ['title' => 'التحليل المالي', 'lessons' => [
                    'النسب المالية', 'التدفقات النقدية', 'قراءة قائمة الدخل', 'اتخاذ قرارات مالية',
                ]],
                ['title' => 'تطبيقات عملية', 'lessons' => [
                    'محاكاة شركة ناشئة', 'إعداد ميزانية', 'مراجعة شاملة', 'أسئلة شائعة لغير الماليين',
                ]],
            ],
            'unity-game-development' => [
                ['title' => 'مقدمة Unity', 'lessons' => [
                    'تثبيت Unity Hub', 'واجهة المحرر', 'GameObjects و Components', 'المشهد Scene الأول',
                ]],
                ['title' => 'البرمجة بـ C#', 'lessons' => [
                    'أساسيات C#', 'المتغيرات والدوال', 'الكلاسات في Unity', 'الوراثة والواجهات',
                    'التعامل مع Input',
                ]],
                ['title' => 'فيزياء وحركة', 'lessons' => [
                    'Rigidbody و Colliders', 'حركة اللاعب', 'الجاذبية والقوى', 'كشف التصادم',
                ]],
                ['title' => 'الرسوميات والصوت', 'lessons' => [
                    'المواد Materials', 'الإضاءة Lighting', 'الرسوم المتحركة Animation', 'المؤثرات الصوتية',
                ]],
                ['title' => 'تصميم اللعبة', 'lessons' => [
                    'نظام النقاط والحياة', 'واجهة المستخدم UI', 'إدارة المشاهد', 'حفظ التقدم',
                ]],
                ['title' => 'مشاريع ونشر', 'lessons' => [
                    'لعبة منصات 2D', 'لعبة مطاردة 3D', 'تحسين الأداء', 'بناء ونشر اللعبة',
                ]],
            ],
            default => [
                ['title' => 'مقدمة الدورة', 'lessons' => [
                    'مرحباً بك', 'أهداف التعلم', 'تجهيز الأدوات', 'نظرة عامة على المنهاج',
                ]],
                ['title' => 'الوحدة الأساسية', 'lessons' => [
                    'المفاهيم الأولى', 'تطبيق عملي', 'مراجعة الوحدة', 'اختبار قصير',
                ]],
                ['title' => 'الوحدة المتقدمة', 'lessons' => [
                    'تعميق المعرفة', 'حالات عملية', 'أخطاء شائعة', 'أفضل الممارسات',
                ]],
                ['title' => 'المشروع النهائي', 'lessons' => [
                    'تخطيط المشروع', 'تنفيذ المشروع', 'مراجعة وتسليم', 'الخطوات التالية',
                ]],
            ],
        };
    }
}
