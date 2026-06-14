<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\QuestionBank;
use App\Models\QuestionModule;
use App\Models\QuestionPool;
use App\Models\QuestionPoolItem;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamDemoInfrastructureSeeder extends Seeder
{
    public const DEMO_PREFIX = '[Exam Demo]';

    /** @var array<string, mixed> */
    public static array $infrastructure = [];

    public function run(): void
    {
        $course = Course::where('slug', 'professional-web-development')->first();
        $creator = User::where('email', 'admin@admin.com')->first()
            ?? User::where('email', 'instructor@edumatic.com')->first()
            ?? User::first();

        if (!$course || !$creator) {
            $this->command?->error('❌ يتطلب كورس ومستخدم أدمن.');

            return;
        }

        $demoQuestions = QuestionBank::whereJsonContains('tags', ExamDemoQuestionsSeeder::DEMO_TAG)
            ->with('options')
            ->get();

        if ($demoQuestions->isEmpty()) {
            $this->command?->error('❌ لا توجد أسئلة تجريبية. شغّل ExamDemoQuestionsSeeder أولاً.');

            return;
        }

        $bankQuestions = QuestionBank::where('course_id', $course->id)
            ->where('is_active', true)
            ->whereDoesntHave('questionType', fn ($q) => $q->whereIn('name', ['essay']))
            ->limit(30)
            ->get();

        DB::transaction(function () use ($course, $creator, $demoQuestions, $bankQuestions) {
            self::$infrastructure['pool'] = $this->seedQuestionPool($course, $creator, $bankQuestions);
            self::$infrastructure['comprehensive_quiz'] = $this->seedComprehensiveQuiz($course, $creator, $demoQuestions);
            self::$infrastructure['laravel_quiz'] = $this->seedLaravelQuiz($course, $creator, $bankQuestions);
            self::$infrastructure['practice_quiz'] = $this->seedPracticeQuiz($course, $creator, $bankQuestions);
            self::$infrastructure['question_module'] = $this->seedQuestionModule($course, $creator, $demoQuestions);
            self::$infrastructure['training_module'] = $this->seedTrainingModule($course, $creator, $demoQuestions);
        });

        $this->command?->info('✅ تم إنشاء مجموعات أسئلة، اختبارات، ووحدات تدريب تجريبية.');
    }

    private function seedQuestionPool(Course $course, User $creator, $bankQuestions): QuestionPool
    {
        $pool = QuestionPool::updateOrCreate(
            ['name' => self::DEMO_PREFIX . ' مجموعة Laravel'],
            [
                'description' => 'مجموعة أسئلة Laravel للاختبارات العشوائية',
                'course_id' => $course->id,
                'created_by' => $creator->id,
            ]
        );

        $pool->poolItems()->delete();
        foreach ($bankQuestions->take(15) as $question) {
            QuestionPoolItem::create([
                'pool_id' => $pool->id,
                'question_id' => $question->id,
            ]);
        }

        return $pool;
    }

    private function seedComprehensiveQuiz(Course $course, User $creator, $demoQuestions): Quiz
    {
        $title = self::DEMO_PREFIX . ' اختبار شامل - جميع الأنواع';
        $maxScore = $demoQuestions->sum('default_grade');

        $quiz = Quiz::updateOrCreate(
            ['title' => $title, 'course_id' => $course->id],
            [
                'description' => 'اختبار تجريبي يغطي جميع أنواع الأسئلة العشرة',
                'instructions' => 'أجب على جميع الأسئلة. الأسئلة المقالية والقصيرة قد تحتاج تصحيحاً يدوياً.',
                'quiz_type' => 'graded',
                'passing_grade' => 60,
                'max_score' => $maxScore,
                'time_limit' => 45,
                'attempts_allowed' => 3,
                'shuffle_questions' => false,
                'shuffle_answers' => true,
                'show_correct_answers' => true,
                'show_correct_answers_after' => 'after_graded',
                'feedback_mode' => 'after_submission',
                'allow_review' => true,
                'show_grade_immediately' => false,
                'available_from' => now()->subDay(),
                'due_date' => now()->addMonth(),
                'available_until' => now()->addMonths(2),
                'is_published' => true,
                'is_visible' => true,
                'created_by' => $creator->id,
            ]
        );

        $this->syncQuizQuestions($quiz, $demoQuestions);

        CourseModule::updateOrCreate(
            [
                'course_id' => $course->id,
                'modulable_type' => Quiz::class,
                'modulable_id' => $quiz->id,
            ],
            [
                'section_id' => $this->firstSectionId($course),
                'module_type' => 'quiz',
                'title' => $quiz->title,
                'description' => $quiz->description,
                'sort_order' => 100,
                'is_visible' => true,
                'is_required' => true,
                'is_graded' => true,
                'max_score' => $maxScore,
            ]
        );

        return $quiz;
    }

    private function seedLaravelQuiz(Course $course, User $creator, $bankQuestions): ?Quiz
    {
        if ($bankQuestions->count() < 10) {
            return null;
        }

        $questions = $bankQuestions->take(15);
        $maxScore = $questions->sum('default_grade');
        $title = self::DEMO_PREFIX . ' اختبار Laravel - متوسط';

        $quiz = Quiz::updateOrCreate(
            ['title' => $title, 'course_id' => $course->id],
            [
                'description' => 'اختبار من بنك أسئلة Laravel',
                'instructions' => 'اختر الإجابة الصحيحة لكل سؤال.',
                'quiz_type' => 'graded',
                'passing_grade' => 65,
                'max_score' => $maxScore,
                'time_limit' => 30,
                'attempts_allowed' => 2,
                'shuffle_questions' => true,
                'shuffle_answers' => true,
                'show_correct_answers' => true,
                'show_correct_answers_after' => 'after_due',
                'feedback_mode' => 'after_submission',
                'allow_review' => true,
                'show_grade_immediately' => true,
                'available_from' => now()->subDay(),
                'due_date' => now()->addWeeks(2),
                'available_until' => now()->addMonth(),
                'is_published' => true,
                'is_visible' => true,
                'created_by' => $creator->id,
            ]
        );

        $this->syncQuizQuestions($quiz, $questions);

        return $quiz;
    }

    private function seedPracticeQuiz(Course $course, User $creator, $bankQuestions): ?Quiz
    {
        if ($bankQuestions->isEmpty()) {
            return null;
        }

        $questions = $bankQuestions->where('difficulty_level', 'easy')->take(10);
        if ($questions->isEmpty()) {
            $questions = $bankQuestions->take(10);
        }

        $maxScore = $questions->sum('default_grade');
        $title = self::DEMO_PREFIX . ' اختبار تدريبي';

        $quiz = Quiz::updateOrCreate(
            ['title' => $title, 'course_id' => $course->id],
            [
                'description' => 'اختبار تدريبي بدون ضغط زمني',
                'instructions' => 'تدرب على الأسئلة — يمكنك المحاولة عدة مرات.',
                'quiz_type' => 'practice',
                'passing_grade' => 0,
                'max_score' => $maxScore,
                'time_limit' => null,
                'attempts_allowed' => null,
                'shuffle_questions' => true,
                'shuffle_answers' => true,
                'show_correct_answers' => true,
                'show_correct_answers_after' => 'immediately',
                'feedback_mode' => 'immediate',
                'allow_review' => true,
                'show_grade_immediately' => true,
                'available_from' => now()->subDay(),
                'is_published' => true,
                'is_visible' => true,
                'created_by' => $creator->id,
            ]
        );

        $this->syncQuizQuestions($quiz, $questions);

        return $quiz;
    }

    private function seedQuestionModule(Course $course, User $creator, $demoQuestions): QuestionModule
    {
        $autoGradable = $demoQuestions->filter(fn ($q) => ! in_array($q->questionType->name ?? '', ['essay', 'short_answer'], true));

        $module = QuestionModule::updateOrCreate(
            ['title' => self::DEMO_PREFIX . ' وحدة تدريب - تصحيح تلقائي'],
            [
                'description' => 'وحدة أسئلة للتدريب مع تصحيح فوري',
                'instructions' => 'أجب على الأسئلة — النتيجة تظهر بعد الإرسال.',
                'is_published' => true,
                'is_visible' => true,
                'available_from' => now()->subDay(),
                'available_until' => now()->addMonths(3),
                'time_limit' => 20,
                'shuffle_questions' => false,
                'show_results' => true,
                'pass_percentage' => 60,
                'attempts_allowed' => 5,
                'sort_order' => 1,
                'created_by' => $creator->id,
            ]
        );

        $module->questions()->detach();
        $order = 1;
        foreach ($autoGradable as $question) {
            $module->questions()->attach($question->id, [
                'question_order' => $order++,
                'question_grade' => $question->default_grade,
            ]);
        }

        CourseModule::updateOrCreate(
            [
                'course_id' => $course->id,
                'modulable_type' => QuestionModule::class,
                'modulable_id' => $module->id,
            ],
            [
                'section_id' => $this->firstSectionId($course),
                'module_type' => 'question_module',
                'title' => $module->title,
                'description' => $module->description,
                'sort_order' => 101,
                'is_visible' => true,
                'is_required' => false,
                'is_graded' => false,
            ]
        );

        return $module;
    }

    private function seedTrainingModule(Course $course, User $creator, $demoQuestions): QuestionModule
    {
        $module = QuestionModule::updateOrCreate(
            ['title' => self::DEMO_PREFIX . ' وحدة تدريب - جميع الأنواع'],
            [
                'description' => 'وحدة تشمل أسئلة مقالية وقصيرة للتصحيح اليدوي',
                'instructions' => 'أكمل جميع الأسئلة بما فيها المقالي.',
                'is_published' => true,
                'is_visible' => true,
                'available_from' => now()->subDay(),
                'time_limit' => 30,
                'shuffle_questions' => false,
                'show_results' => true,
                'pass_percentage' => 50,
                'attempts_allowed' => 3,
                'sort_order' => 2,
                'created_by' => $creator->id,
            ]
        );

        $module->questions()->detach();
        $order = 1;
        foreach ($demoQuestions as $question) {
            $module->questions()->attach($question->id, [
                'question_order' => $order++,
                'question_grade' => $question->default_grade,
            ]);
        }

        return $module;
    }

    private function syncQuizQuestions(Quiz $quiz, $questions): void
    {
        $quiz->quizQuestions()->delete();
        $order = 1;
        foreach ($questions as $question) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_id' => $question->id,
                'question_order' => $order++,
                'question_grade' => $question->default_grade,
                'is_required' => true,
            ]);
        }

        $quiz->update(['max_score' => $questions->sum('default_grade')]);
    }

    private function firstSectionId(Course $course): ?int
    {
        return $course->sections()->orderBy('sort_order')->value('id');
    }
}
