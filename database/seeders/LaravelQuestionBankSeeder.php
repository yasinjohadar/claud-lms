<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionBank;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Course;
use App\Models\ProgrammingLanguage;
use Illuminate\Support\Facades\DB;

class LaravelQuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على كورس Laravel
        $course = Course::where('slug', 'professional-web-development')->first();

        if (!$course) {
            $this->command->error('❌ كورس تطوير الويب غير موجود! يرجى تشغيل CourseCatalogSeeder أولاً');
            return;
        }

        // الحصول على المستخدم (instructor)
        $instructor = $course->instructor ?? \App\Models\User::first();

        if (!$instructor) {
            $this->command->error('❌ لا يوجد مستخدم لإنشاء الأسئلة!');
            return;
        }

        // الحصول على أنواع الأسئلة
        $trueFalseType = QuestionType::where('name', 'true_false')->first();
        $multipleChoiceType = QuestionType::where('name', 'multiple_choice_single')->first();

        if (!$trueFalseType || !$multipleChoiceType) {
            $this->command->error('❌ أنواع الأسئلة غير موجودة! يرجى تشغيل QuestionTypeSeeder أولاً');
            return;
        }

        // الحصول على لغات البرمجة
        $phpLang = ProgrammingLanguage::where('slug', 'php')->first();
        $laravelLang = ProgrammingLanguage::where('slug', 'laravel')->first();

        if (!$phpLang || !$laravelLang) {
            $this->command->error('❌ لغات PHP و Laravel غير موجودة! يرجى تشغيل ProgrammingLanguageSeeder أولاً');
            return;
        }

        // بدء المعاملة
        DB::beginTransaction();

        try {
            // ========== أسئلة صح وخطأ (25 سؤالاً) ==========

            $trueFalseQuestions = [
                // Routing & Controllers (5 أسئلة)
                [
                    'question_text' => '<p>في Laravel، يمكن تعريف المسارات (Routes) في ملف web.php فقط</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>Route Model Binding يسمح بربط نموذج Eloquent تلقائياً مع المسار</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>Resource Controllers في Laravel توفر 7 طرق افتراضية للعمليات CRUD</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>Middleware في Laravel يتم تنفيذه بعد معالجة الطلب (Request)</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],
                [
                    'question_text' => '<p>يمكن استخدام Route::fallback() لتحديد مسار احتياطي عند عدم العثور على مسار مطابق</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],

                // Eloquent ORM (5 أسئلة)
                [
                    'question_text' => '<p>Eloquent ORM يدعم العلاقات من نوع Many-to-Many</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>الخاصية $fillable في نماذج Eloquent تحمي من Mass Assignment</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>Soft Deletes في Laravel يحذف البيانات نهائياً من قاعدة البيانات</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>يمكن استخدام Eager Loading لتحسين أداء الاستعلامات وتجنب مشكلة N+1</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],
                [
                    'question_text' => '<p>Mutators في Eloquent تسمح بتعديل قيم الحقول تلقائياً عند الحفظ أو القراءة</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],

                // Blade Templates (5 أسئلة)
                [
                    'question_text' => '<p>Blade هو محرك القوالب الافتراضي في Laravel</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>التعليمة @csrf في Blade تقوم بإنشاء حقل CSRF token لحماية النماذج</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>في Blade، الرمز {{ $variable }} يعرض القيمة دون تنظيف XSS</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>يمكن استخدام @include لتضمين قالب Blade داخل قالب آخر</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>Components في Blade تدعم Slots لتمرير المحتوى الديناميكي</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],

                // Validation & Forms (5 أسئلة)
                [
                    'question_text' => '<p>Form Requests في Laravel تفصل منطق التحقق عن Controllers</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>قاعدة التحقق "unique:users,email" تتحقق من عدم تكرار البريد الإلكتروني في جدول users</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>يمكن إنشاء قواعد تحقق مخصصة في Laravel</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>التعليمة @error في Blade تعرض رسائل الخطأ الخاصة بحقل معين</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
                [
                    'question_text' => '<p>قاعدة "required_if" تجعل الحقل مطلوباً فقط عند تحقق شرط معين</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],

                // Authentication & Authorization (5 أسئلة)
                [
                    'question_text' => '<p>Laravel Sanctum مخصص فقط لتطبيقات SPA (Single Page Applications)</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>Gates في Laravel تستخدم لتحديد صلاحيات الوصول على مستوى الإجراءات</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],
                [
                    'question_text' => '<p>Policies في Laravel تُستخدم لتنظيم منطق التفويض المتعلق بنموذج معين</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'hard',
                    'points' => 3,
                ],
                [
                    'question_text' => '<p>التعليمة @can في Blade تتحقق من صلاحية المستخدم لتنفيذ إجراء معين</p>',
                    'correct_answer' => 'true',
                    'difficulty' => 'medium',
                    'points' => 2,
                ],
                [
                    'question_text' => '<p>Laravel Breeze يوفر واجهة مستخدم كاملة للمصادقة باستخدام Bootstrap</p>',
                    'correct_answer' => 'false',
                    'difficulty' => 'easy',
                    'points' => 1,
                ],
            ];

            foreach ($trueFalseQuestions as $questionData) {
                $question = QuestionBank::create([
                    'question_type_id' => $trueFalseType->id,
                    'course_id' => $course->id,
                    'question_text' => $questionData['question_text'],
                    'explanation' => null,
                    'difficulty_level' => $questionData['difficulty'],
                    'default_grade' => $questionData['points'],
                    'is_active' => true,
                    'times_used' => 0,
                    'created_by' => $instructor->id,
                ]);

                // ربط السؤال باللغات البرمجية (PHP + Laravel)
                $question->programmingLanguages()->attach([$phpLang->id, $laravelLang->id]);

                // إنشاء خيارات صح وخطأ
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => '<p>صحيح</p>',
                    'is_correct' => $questionData['correct_answer'] === 'true',
                    'grade_percentage' => $questionData['correct_answer'] === 'true' ? 100 : 0,
                    'option_order' => 1,
                ]);

                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => '<p>خطأ</p>',
                    'is_correct' => $questionData['correct_answer'] === 'false',
                    'grade_percentage' => $questionData['correct_answer'] === 'false' ? 100 : 0,
                    'option_order' => 2,
                ]);
            }

            // ========== أسئلة اختيار من متعدد (25 سؤالاً) ==========

            $multipleChoiceQuestions = [
                // Routing & MVC (5 أسئلة)
                [
                    'question_text' => '<p>ما هو الأمر الصحيح لإنشاء Controller في Laravel؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>php artisan make:controller UserController</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan create:controller UserController</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan new:controller UserController</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan generate:controller UserController</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الطرق التالية تُستخدم للحصول على جميع معاملات الطلب (Request parameters)؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>$request->all()</p>', 'is_correct' => true],
                        ['text' => '<p>$request->get()</p>', 'is_correct' => false],
                        ['text' => '<p>$request->params()</p>', 'is_correct' => false],
                        ['text' => '<p>$request->input()</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هي الطريقة الصحيحة لإرجاع استجابة JSON في Laravel؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>return response()->json($data);</p>', 'is_correct' => true],
                        ['text' => '<p>return json($data);</p>', 'is_correct' => false],
                        ['text' => '<p>return Response::json($data);</p>', 'is_correct' => false],
                        ['text' => '<p>return $data->toJson();</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الخيارات التالية يُستخدم لتعريف مجموعة مسارات مع بادئة (prefix)؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>Route::prefix(\'admin\')->group(function() {...});</p>', 'is_correct' => true],
                        ['text' => '<p>Route::group([\'prefix\' => \'admin\'], function() {...});</p>', 'is_correct' => false],
                        ['text' => '<p>Route::addPrefix(\'admin\')->routes(function() {...});</p>', 'is_correct' => false],
                        ['text' => '<p>كل ما سبق صحيح</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو الأمر المستخدم لعرض قائمة جميع المسارات المسجلة في التطبيق؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>php artisan route:list</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan routes</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan list:routes</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan show:routes</p>', 'is_correct' => false],
                    ],
                ],

                // Database & Migrations (5 أسئلة)
                [
                    'question_text' => '<p>ما هو الأمر الصحيح لإنشاء ملف Migration جديد؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>php artisan make:migration create_users_table</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan migration:create create_users_table</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan create:migration create_users_table</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan new:migration create_users_table</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الطرق التالية تُستخدم لتنفيذ جميع الـ Migrations المعلقة؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>php artisan migrate</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan migrate:run</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan migration:up</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan db:migrate</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هي الطريقة الصحيحة لإضافة عمود جديد في Migration؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>$table->string(\'email\');</p>', 'is_correct' => true],
                        ['text' => '<p>$table->addColumn(\'email\', \'string\');</p>', 'is_correct' => false],
                        ['text' => '<p>$table->column(\'email\')->string();</p>', 'is_correct' => false],
                        ['text' => '<p>$table->add(\'email\', \'varchar\');</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الأوامر التالية يقوم بالتراجع عن آخر دفعة من الـ Migrations؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>php artisan migrate:rollback</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan migrate:undo</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan migrate:back</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan migrate:revert</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو الأمر المستخدم لحذف جميع الجداول وإعادة تشغيل الـ Migrations؟</p>',
                    'difficulty' => 'hard',
                    'points' => 4,
                    'options' => [
                        ['text' => '<p>php artisan migrate:fresh</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan migrate:reset</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan migrate:refresh</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan migrate:clean</p>', 'is_correct' => false],
                    ],
                ],

                // Eloquent ORM (5 أسئلة)
                [
                    'question_text' => '<p>ما هو الأمر الصحيح لإنشاء Model جديد مع Migration؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>php artisan make:model User -m</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan make:model User --migration</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan make:model User -mig</p>', 'is_correct' => false],
                        ['text' => '<p>كل الإجابات صحيحة</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الطرق التالية تُستخدم لاسترجاع أول سجل من قاعدة البيانات؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>User::first()</p>', 'is_correct' => true],
                        ['text' => '<p>User::get()[0]</p>', 'is_correct' => false],
                        ['text' => '<p>User::take(1)</p>', 'is_correct' => false],
                        ['text' => '<p>User::one()</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هي الطريقة الصحيحة لتعريف علاقة One-to-Many في Eloquent؟</p>',
                    'difficulty' => 'hard',
                    'points' => 4,
                    'options' => [
                        ['text' => '<p>return $this->hasMany(Post::class);</p>', 'is_correct' => true],
                        ['text' => '<p>return $this->belongsToMany(Post::class);</p>', 'is_correct' => false],
                        ['text' => '<p>return $this->hasOne(Post::class);</p>', 'is_correct' => false],
                        ['text' => '<p>return $this->morphMany(Post::class);</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الطرق التالية تُستخدم للحصول على عدد السجلات؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>User::count()</p>', 'is_correct' => true],
                        ['text' => '<p>User::total()</p>', 'is_correct' => false],
                        ['text' => '<p>User::length()</p>', 'is_correct' => false],
                        ['text' => '<p>User::size()</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هي الطريقة الصحيحة لاستخدام Soft Deletes في Model؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>use SoftDeletes; في النموذج</p>', 'is_correct' => true],
                        ['text' => '<p>protected $softDelete = true;</p>', 'is_correct' => false],
                        ['text' => '<p>protected $table = \'soft_deletes\';</p>', 'is_correct' => false],
                        ['text' => '<p>public $softDeletes = enabled;</p>', 'is_correct' => false],
                    ],
                ],

                // Queues & Jobs (5 أسئلة)
                [
                    'question_text' => '<p>ما هو الأمر المستخدم لإنشاء Job جديد؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>php artisan make:job SendEmailJob</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan create:job SendEmailJob</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan job:make SendEmailJob</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan new:job SendEmailJob</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الأوامر التالية يقوم بتشغيل Queue Worker؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>php artisan queue:work</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan queue:start</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan queue:run</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan worker:start</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو الـ Queue Driver الافتراضي في Laravel؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>sync</p>', 'is_correct' => true],
                        ['text' => '<p>database</p>', 'is_correct' => false],
                        ['text' => '<p>redis</p>', 'is_correct' => false],
                        ['text' => '<p>sqs</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الطرق التالية تُستخدم لإرسال Job إلى Queue معين؟</p>',
                    'difficulty' => 'hard',
                    'points' => 4,
                    'options' => [
                        ['text' => '<p>SendEmailJob::dispatch()->onQueue(\'emails\');</p>', 'is_correct' => true],
                        ['text' => '<p>dispatch(new SendEmailJob())->queue(\'emails\');</p>', 'is_correct' => false],
                        ['text' => '<p>Queue::push(\'emails\', new SendEmailJob());</p>', 'is_correct' => false],
                        ['text' => '<p>SendEmailJob::queue(\'emails\');</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو الأمر المستخدم لإنشاء جدول jobs في قاعدة البيانات؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>php artisan queue:table</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan make:queue-table</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan queue:create-table</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan make:jobs-table</p>', 'is_correct' => false],
                    ],
                ],

                // Events & Listeners (5 أسئلة)
                [
                    'question_text' => '<p>ما هو الأمر المستخدم لإنشاء Event جديد؟</p>',
                    'difficulty' => 'easy',
                    'points' => 2,
                    'options' => [
                        ['text' => '<p>php artisan make:event UserRegistered</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan create:event UserRegistered</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan event:make UserRegistered</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan new:event UserRegistered</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أين يتم تسجيل Events و Listeners في Laravel؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>EventServiceProvider</p>', 'is_correct' => true],
                        ['text' => '<p>AppServiceProvider</p>', 'is_correct' => false],
                        ['text' => '<p>RouteServiceProvider</p>', 'is_correct' => false],
                        ['text' => '<p>config/events.php</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هي الطريقة الصحيحة لإطلاق Event؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>event(new UserRegistered($user));</p>', 'is_correct' => true],
                        ['text' => '<p>Event::fire(new UserRegistered($user));</p>', 'is_correct' => false],
                        ['text' => '<p>trigger(new UserRegistered($user));</p>', 'is_correct' => false],
                        ['text' => '<p>dispatch(new UserRegistered($user));</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>أي من الواجهات التالية يجب أن ينفذها Listener الذي يعمل في Queue؟</p>',
                    'difficulty' => 'hard',
                    'points' => 4,
                    'options' => [
                        ['text' => '<p>ShouldQueue</p>', 'is_correct' => true],
                        ['text' => '<p>Queueable</p>', 'is_correct' => false],
                        ['text' => '<p>ShouldBeQueued</p>', 'is_correct' => false],
                        ['text' => '<p>InteractsWithQueue</p>', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => '<p>ما هو الأمر المستخدم لإنشاء Events و Listeners تلقائياً من EventServiceProvider؟</p>',
                    'difficulty' => 'medium',
                    'points' => 3,
                    'options' => [
                        ['text' => '<p>php artisan event:generate</p>', 'is_correct' => true],
                        ['text' => '<p>php artisan make:events</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan events:create</p>', 'is_correct' => false],
                        ['text' => '<p>php artisan generate:events</p>', 'is_correct' => false],
                    ],
                ],
            ];

            foreach ($multipleChoiceQuestions as $questionData) {
                $question = QuestionBank::create([
                    'question_type_id' => $multipleChoiceType->id,
                    'course_id' => $course->id,
                    'question_text' => $questionData['question_text'],
                    'explanation' => null,
                    'difficulty_level' => $questionData['difficulty'],
                    'default_grade' => $questionData['points'],
                    'is_active' => true,
                    'times_used' => 0,
                    'created_by' => $instructor->id,
                ]);

                // ربط السؤال باللغات البرمجية (PHP + Laravel)
                $question->programmingLanguages()->attach([$phpLang->id, $laravelLang->id]);

                // إنشاء الخيارات
                foreach ($questionData['options'] as $optionIndex => $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $option['text'],
                        'is_correct' => $option['is_correct'],
                        'grade_percentage' => $option['is_correct'] ? 100 : 0,
                        'option_order' => $optionIndex + 1,
                    ]);
                }
            }

            DB::commit();

            $this->command->info('✅ تم إنشاء 50 سؤالاً لكورس Laravel بنجاح!');
            $this->command->info('📊 التوزيع: 25 أسئلة صح/خطأ + 25 أسئلة اختيار من متعدد');
            $this->command->info('📝 المواضيع: Routing, Eloquent, Blade, Validation, Auth, Queues, Events');
            $this->command->info('🏷️  تم ربط الأسئلة باللغات البرمجية: PHP و Laravel');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ حدث خطأ أثناء إنشاء الأسئلة: ' . $e->getMessage());
            throw $e;
        }
    }
}
