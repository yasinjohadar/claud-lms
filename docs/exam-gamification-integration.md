# تركيب نظام الاختبارات والتحفيز

## المتطلبات

- PHP 8.2+
- `phpoffice/phpspreadsheet` (مثبت عبر Composer)
- `laravel/ai` (موجود مسبقاً)

## بعد النشر

```bash
php artisan migrate
php artisan db:seed --class=QuestionTypeSeeder
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=LevelSeeder
php artisan db:seed --class=BadgeSeeder
```

## المسارات الرئيسية

| القسم | المسار |
|-------|--------|
| بنك الأسئلة | `/admin/question-bank` |
| الكويزات | `/admin/quizzes` |
| وحدات الأسئلة | `/admin/question-modules` |
| التحفيز (أدمن) | `/admin/gamification` |
| اختباراتي (طالب) | `/student/quizzes` |
| تدريباتي | `/student/question-modules/stats` |
| التحفيز (طالب) | `/student/gamification` |

## المنهج الهجين

- **فيديو:** `course_lessons` (بدون تغيير)
- **كويز / وحدة أسئلة:** `course_modules` مرتبطة بـ `course_sections`

## الأحداث والتحفيز

- `QuizCompleted` — عند تسليم الاختبار
- `LessonCompleted` / `VideoWatched` — عند إكمال درس فيديو
- `CourseCompleted` — عند وصول التقدم إلى 100%

## ملاحظات AI

ميزات إنشاء الأسئلة بالذكاء الاصطناعي تتطلب تكوين موديلات في `/admin/ai/models` و`questions_engine` في `config/ai.defaults.php`.

## Composer

بعد تثبيت `phpoffice/phpspreadsheet` شغّل:

```bash
composer dump-autoload --ignore-platform-req=ext-ftp
```

إذا تعطل الأمر، تأكد أن الحزم `phpoffice/phpspreadsheet` و`composer/pcre` و`maennchen/zipstream-php` موجودة في `vendor/`.
