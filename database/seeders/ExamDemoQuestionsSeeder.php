<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\ProgrammingLanguage;
use App\Models\QuestionBank;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamDemoQuestionsSeeder extends Seeder
{
    public const DEMO_TAG = 'exam-demo';

    /** @var array<string, QuestionBank> */
    public static array $questionsByKey = [];

    public function run(): void
    {
        $course = Course::where('slug', 'professional-web-development')->first();
        $creator = User::where('email', 'admin@admin.com')->first()
            ?? User::where('email', 'instructor@edumatic.com')->first()
            ?? User::first();

        if (!$course || !$creator) {
            $this->command?->error('❌ يتطلب كورس professional-web-development ومستخدم أدمن/مدرب.');

            return;
        }

        $types = QuestionType::pluck('id', 'name');
        $required = [
            'multiple_choice_single', 'multiple_choice_multiple', 'true_false',
            'short_answer', 'essay', 'matching', 'fill_blanks', 'ordering',
            'numerical', 'calculated',
        ];

        foreach ($required as $name) {
            if (!$types->has($name)) {
                $this->command?->error("❌ نوع السؤال {$name} غير موجود. شغّل QuestionTypeSeeder أولاً.");

                return;
            }
        }

        DB::transaction(function () use ($course, $creator, $types) {
            $this->seedAllTypes($course, $creator, $types);
            $this->seedExtraMultipleChoice($course, $creator, $types);
        });

        $this->command?->info('✅ تم إنشاء ' . count(self::$questionsByKey) . ' سؤال تجريبي (جميع الأنواع).');
    }

    private function seedAllTypes(Course $course, User $creator, $types): void
    {
        self::$questionsByKey['mc_single'] = $this->createQuestion([
            'key' => 'mc_single',
            'course_id' => $course->id,
            'question_type_id' => $types['multiple_choice_single'],
            'question_text' => '<p>[Exam Demo] أي ملف في Laravel يُعرّف مسارات التطبيق؟</p>',
            'lesson_name' => 'Laravel Routing',
            'explanation' => '<p>ملف routes/web.php هو المكان الافتراضي لمسارات الويب.</p>',
            'default_grade' => 2,
            'difficulty_level' => 'easy',
            'created_by' => $creator->id,
            'options' => [
                ['text' => 'routes/web.php', 'correct' => true],
                ['text' => 'app/Http/Kernel.php', 'correct' => false],
                ['text' => 'config/app.php', 'correct' => false],
                ['text' => 'bootstrap/app.php', 'correct' => false],
            ],
        ]);

        self::$questionsByKey['mc_multiple'] = $this->createQuestion([
            'key' => 'mc_multiple',
            'course_id' => $course->id,
            'question_type_id' => $types['multiple_choice_multiple'],
            'question_text' => '<p>[Exam Demo] أي من التالي من طرق HTTP؟ (اختر الكل)</p>',
            'lesson_name' => 'HTTP Methods',
            'default_grade' => 3,
            'difficulty_level' => 'medium',
            'created_by' => $creator->id,
            'options' => [
                ['text' => 'GET', 'correct' => true],
                ['text' => 'POST', 'correct' => true],
                ['text' => 'FETCH', 'correct' => false],
                ['text' => 'DELETE', 'correct' => true],
            ],
        ]);

        self::$questionsByKey['true_false'] = $this->createQuestion([
            'key' => 'true_false',
            'course_id' => $course->id,
            'question_type_id' => $types['true_false'],
            'question_text' => '<p>[Exam Demo] Eloquent ORM جزء من Laravel.</p>',
            'default_grade' => 1,
            'difficulty_level' => 'easy',
            'created_by' => $creator->id,
            'options' => [
                ['text' => 'صح', 'correct' => true],
                ['text' => 'خطأ', 'correct' => false],
            ],
        ]);

        self::$questionsByKey['short_answer'] = $this->createQuestion([
            'key' => 'short_answer',
            'course_id' => $course->id,
            'question_type_id' => $types['short_answer'],
            'question_text' => '<p>[Exam Demo] ما الأمر الذي ينشئ Controller في Laravel؟</p>',
            'default_grade' => 2,
            'difficulty_level' => 'medium',
            'created_by' => $creator->id,
            'options' => [
                ['text' => 'php artisan make:controller', 'correct' => true],
                ['text' => 'make:controller', 'correct' => true],
            ],
        ]);

        self::$questionsByKey['essay'] = $this->createQuestion([
            'key' => 'essay',
            'course_id' => $course->id,
            'question_type_id' => $types['essay'],
            'question_text' => '<p>[Exam Demo] اشرح باختصار نمط MVC في Laravel.</p>',
            'default_grade' => 5,
            'difficulty_level' => 'hard',
            'created_by' => $creator->id,
            'options' => [],
        ]);

        $matching = $this->createQuestion([
            'key' => 'matching',
            'course_id' => $course->id,
            'question_type_id' => $types['matching'],
            'question_text' => '<p>[Exam Demo] طابق كل مصطلح Laravel مع تعريفه:</p>',
            'default_grade' => 4,
            'difficulty_level' => 'medium',
            'created_by' => $creator->id,
            'options' => [],
        ]);
        $matchingPairs = [
            ['question' => 'Route', 'answer' => 'يربط URL بدالة'],
            ['question' => 'Middleware', 'answer' => 'طبقة تصفية الطلبات'],
            ['question' => 'Migration', 'answer' => 'إدارة بنية قاعدة البيانات'],
        ];
        foreach ($matchingPairs as $i => $pair) {
            QuestionOption::create([
                'question_id' => $matching->id,
                'option_text' => $pair['question'],
                'is_correct' => true,
                'option_order' => $i + 1,
                'match_pair_id' => $i + 1,
                'feedback' => $pair['answer'],
                'grade_percentage' => 100,
            ]);
        }
        self::$questionsByKey['matching'] = $matching;

        $fillBlanks = $this->createQuestion([
            'key' => 'fill_blanks',
            'course_id' => $course->id,
            'question_type_id' => $types['fill_blanks'],
            'question_text' => '<p>[Exam Demo] في Blade نستخدم [[blank]] لطباعة متغير و [[blank]] للتحقق من الشرط.</p>',
            'default_grade' => 3,
            'difficulty_level' => 'medium',
            'created_by' => $creator->id,
            'options' => [],
        ]);
        QuestionOption::create([
            'question_id' => $fillBlanks->id,
            'option_text' => '{{ }}',
            'is_correct' => true,
            'option_order' => 1,
            'grade_percentage' => 100,
        ]);
        QuestionOption::create([
            'question_id' => $fillBlanks->id,
            'option_text' => '@if',
            'is_correct' => true,
            'option_order' => 2,
            'grade_percentage' => 100,
        ]);
        self::$questionsByKey['fill_blanks'] = $fillBlanks;

        $ordering = $this->createQuestion([
            'key' => 'ordering',
            'course_id' => $course->id,
            'question_type_id' => $types['ordering'],
            'question_text' => '<p>[Exam Demo] رتّب مراحل طلب HTTP في Laravel:</p>',
            'default_grade' => 3,
            'difficulty_level' => 'medium',
            'created_by' => $creator->id,
            'options' => [],
        ]);
        $orderItems = ['استقبال الطلب', 'Middleware', 'Controller', 'إرجاع Response'];
        foreach ($orderItems as $i => $text) {
            QuestionOption::create([
                'question_id' => $ordering->id,
                'option_text' => $text,
                'is_correct' => true,
                'option_order' => $i + 1,
                'grade_percentage' => 100,
            ]);
        }
        self::$questionsByKey['ordering'] = $ordering;

        self::$questionsByKey['numerical'] = $this->createQuestion([
            'key' => 'numerical',
            'course_id' => $course->id,
            'question_type_id' => $types['numerical'],
            'question_text' => '<p>[Exam Demo] كم يساوي 15 + 27؟</p>',
            'default_grade' => 2,
            'difficulty_level' => 'easy',
            'created_by' => $creator->id,
            'metadata' => ['correct_answer' => 42, 'tolerance' => 0],
            'options' => [],
        ]);

        self::$questionsByKey['calculated'] = $this->createQuestion([
            'key' => 'calculated',
            'course_id' => $course->id,
            'question_type_id' => $types['calculated'],
            'question_text' => '<p>[Exam Demo] إذا كان x=5 و y=3، ما قيمة x+y؟</p>',
            'default_grade' => 2,
            'difficulty_level' => 'easy',
            'created_by' => $creator->id,
            'metadata' => ['formula' => 'x+y', 'correct_answer' => 8, 'tolerance' => 0],
            'options' => [],
        ]);
    }

    private function seedExtraMultipleChoice(Course $course, User $creator, $types): void
    {
        $topics = [
            ['text' => 'أي أمر ينشئ Model؟', 'correct' => 'php artisan make:model', 'wrong' => ['make:view', 'create:model', 'artisan model']],
            ['text' => 'أين تُخزَّن ملفات Blade؟', 'correct' => 'resources/views', 'wrong' => ['app/views', 'storage/views', 'public/views']],
            ['text' => 'ما الافتراضي لـ APP_ENV؟', 'correct' => 'local', 'wrong' => ['production', 'development', 'staging']],
            ['text' => 'أي Facade للتخزين؟', 'correct' => 'Storage', 'wrong' => ['File', 'Disk', 'Save']],
            ['text' => 'ما الجدول الافتراضي للمستخدمين؟', 'correct' => 'users', 'wrong' => ['accounts', 'members', 'auth_users']],
        ];

        foreach ($topics as $i => $topic) {
            $wrong = is_array($topic['wrong']) ? $topic['wrong'] : [$topic['wrong']];
            $options = [['text' => $topic['correct'], 'correct' => true]];
            foreach ($wrong as $w) {
                $options[] = ['text' => $w, 'correct' => false];
            }

            self::$questionsByKey['extra_mc_' . ($i + 1)] = $this->createQuestion([
                'key' => 'extra_mc_' . ($i + 1),
                'course_id' => $course->id,
                'question_type_id' => $types['multiple_choice_single'],
                'question_text' => '<p>[Exam Demo] ' . $topic['text'] . '</p>',
                'default_grade' => 1,
                'difficulty_level' => 'easy',
                'created_by' => $creator->id,
                'options' => $options,
            ]);
        }

        $php = ProgrammingLanguage::where('slug', 'php')->first();
        $laravel = ProgrammingLanguage::where('slug', 'laravel')->first();
        if ($php && $laravel) {
            self::$questionsByKey['mc_single']->programmingLanguages()->syncWithoutDetaching([$php->id, $laravel->id]);
        }
    }

    private function createQuestion(array $data): QuestionBank
    {
        $existing = QuestionBank::whereJsonContains('tags', self::DEMO_TAG)
            ->where('metadata->seed_key', $data['key'])
            ->first();

        if ($existing) {
            $existing->options()->delete();
            $existing->update([
                'course_id' => $data['course_id'],
                'question_type_id' => $data['question_type_id'],
                'question_text' => $data['question_text'],
                'lesson_name' => $data['lesson_name'] ?? null,
                'explanation' => $data['explanation'] ?? null,
                'default_grade' => $data['default_grade'],
                'difficulty_level' => $data['difficulty_level'],
                'metadata' => array_merge(['seed_key' => $data['key']], $data['metadata'] ?? []),
                'is_active' => true,
                'created_by' => $data['created_by'],
            ]);
            $question = $existing;
        } else {
            $question = QuestionBank::create([
                'course_id' => $data['course_id'],
                'question_type_id' => $data['question_type_id'],
                'question_text' => $data['question_text'],
                'lesson_name' => $data['lesson_name'] ?? null,
                'explanation' => $data['explanation'] ?? null,
                'default_grade' => $data['default_grade'],
                'difficulty_level' => $data['difficulty_level'],
                'metadata' => array_merge(['seed_key' => $data['key']], $data['metadata'] ?? []),
                'tags' => [self::DEMO_TAG],
                'is_active' => true,
                'created_by' => $data['created_by'],
            ]);
        }

        foreach ($data['options'] as $index => $option) {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $option['text'],
                'is_correct' => $option['correct'],
                'option_order' => $index + 1,
                'grade_percentage' => $option['correct'] ? 100 : 0,
            ]);
        }

        return $question->fresh(['options', 'questionType']);
    }
}
