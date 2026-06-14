<?php

namespace Database\Seeders;

use App\Models\QuestionModule;
use App\Models\QuestionModuleAttempt;
use App\Models\QuestionModuleResponse;
use App\Models\Quiz;
use App\Models\QuizAnalytics;
use App\Models\QuizAttempt;
use App\Models\QuizResponse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamDemoAttemptsSeeder extends Seeder
{
    public function run(): void
    {
        $comprehensiveQuiz = Quiz::where('title', ExamDemoInfrastructureSeeder::DEMO_PREFIX . ' اختبار شامل - جميع الأنواع')->first();
        $trainingModule = QuestionModule::where('title', ExamDemoInfrastructureSeeder::DEMO_PREFIX . ' وحدة تدريب - تصحيح تلقائي')->first();
        $fullModule = QuestionModule::where('title', ExamDemoInfrastructureSeeder::DEMO_PREFIX . ' وحدة تدريب - جميع الأنواع')->first();
        $grader = User::where('email', 'admin@admin.com')->first() ?? User::first();

        if (!$comprehensiveQuiz) {
            $this->command?->error('❌ الاختبار الشامل غير موجود. شغّل ExamDemoInfrastructureSeeder أولاً.');

            return;
        }

        $students = collect([
            User::where('email', 'student1@edumatic.com')->first(),
            User::where('email', 'student2@edumatic.com')->first(),
            User::where('email', 'student3@edumatic.com')->first(),
            User::where('email', 'student4@edumatic.com')->first(),
            User::where('email', 'student5@edumatic.com')->first(),
        ])->filter();

        if ($students->isEmpty()) {
            $this->command?->error('❌ لا يوجد طلاب. شغّل StudentSeeder أولاً.');

            return;
        }

        DB::transaction(function () use ($comprehensiveQuiz, $trainingModule, $fullModule, $students, $grader) {
            $this->seedQuizAttempts($comprehensiveQuiz, $students, $grader);

            if ($trainingModule) {
                $this->seedModuleAttempts($trainingModule, $students->take(3), gradeAll: true);
            }

            if ($fullModule) {
                $this->seedModuleAttempts($fullModule, $students->take(2), gradeAll: false);
            }
        });

        $this->command?->info('✅ تم إنشاء محاولات اختبار ووحدات تدريب تجريبية.');
    }

    private function seedQuizAttempts(Quiz $quiz, $students, User $grader): void
    {
        QuizAttempt::where('quiz_id', $quiz->id)->forceDelete();
        QuizAnalytics::where('quiz_id', $quiz->id)->delete();

        $quiz->load(['quizQuestions.question.options', 'quizQuestions.question.questionType']);

        // student1: ناجح — كل شيء مصحّح
        $this->createCompletedAttempt(
            $quiz,
            $students[0]->id,
            1,
            correctRatio: 1.0,
            gradeEssay: true,
            gradeShortAnswer: true,
            grader: $grader,
            startedAt: now()->subDays(3),
        );

        // student2: مُرسَل — مقالي بانتظار التصحيح
        $this->createCompletedAttempt(
            $quiz,
            $students[1]->id,
            1,
            correctRatio: 0.85,
            gradeEssay: false,
            gradeShortAnswer: true,
            grader: $grader,
            startedAt: now()->subDays(2),
        );

        // student3: راسب
        $this->createCompletedAttempt(
            $quiz,
            $students[2]->id,
            1,
            correctRatio: 0.35,
            gradeEssay: true,
            gradeShortAnswer: true,
            grader: $grader,
            startedAt: now()->subDay(),
        );

        // student4: قيد التقدم
        $this->createInProgressAttempt($quiz, $students[3]->id, 1);

        // student5: محاولة ثانية أفضل
        $this->createCompletedAttempt(
            $quiz,
            $students[4]->id,
            1,
            correctRatio: 0.5,
            gradeEssay: true,
            gradeShortAnswer: true,
            grader: $grader,
            startedAt: now()->subDays(5),
        );
        $this->createCompletedAttempt(
            $quiz,
            $students[4]->id,
            2,
            correctRatio: 0.9,
            gradeEssay: true,
            gradeShortAnswer: true,
            grader: $grader,
            startedAt: now()->subDays(1),
        );

        foreach ($students as $student) {
            $analytics = QuizAnalytics::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'quiz_id' => $quiz->id,
                ],
                ['course_id' => $quiz->course_id]
            );
            $analytics->recalculate();
        }
    }

    private function createInProgressAttempt(Quiz $quiz, int $studentId, int $attemptNumber): void
    {
        $questionIds = $quiz->quizQuestions()->pluck('question_id')->toArray();

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $studentId,
            'attempt_number' => $attemptNumber,
            'status' => 'in_progress',
            'started_at' => now()->subMinutes(15),
            'max_score' => $quiz->max_score,
            'questions_order' => $questionIds,
            'is_completed' => false,
        ]);

        foreach ($quiz->quizQuestions as $index => $quizQuestion) {
            $question = $quizQuestion->question;
            if (!$question) {
                continue;
            }

            $response = QuizResponse::create([
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'question_type_id' => $question->question_type_id,
                'max_score' => $quizQuestion->getGrade(),
                'answer_order' => $index + 1,
            ]);

            if ($index < 3) {
                $this->fillCorrectAnswer($response, $question);
                $response->autoGrade();
            }
        }
    }

    private function createCompletedAttempt(
        Quiz $quiz,
        int $studentId,
        int $attemptNumber,
        float $correctRatio,
        bool $gradeEssay,
        bool $gradeShortAnswer,
        User $grader,
        $startedAt,
    ): void {
        $questionIds = $quiz->quizQuestions()->pluck('question_id')->toArray();
        $started = $startedAt ?? now()->subDay();
        $submitted = (clone $started)->addMinutes(25);

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $studentId,
            'attempt_number' => $attemptNumber,
            'status' => 'submitted',
            'started_at' => $started,
            'submitted_at' => $submitted,
            'completed_at' => $submitted,
            'time_spent' => $started->diffInSeconds($submitted),
            'max_score' => $quiz->max_score,
            'questions_order' => $questionIds,
            'is_completed' => true,
            'is_late' => false,
        ]);

        $totalQuestions = $quiz->quizQuestions->count();
        $correctCount = (int) round($totalQuestions * $correctRatio);
        $questionIndex = 0;

        foreach ($quiz->quizQuestions as $index => $quizQuestion) {
            $question = $quizQuestion->question;
            if (!$question) {
                continue;
            }

            $typeName = $question->questionType->name ?? '';
            $shouldBeCorrect = $questionIndex < $correctCount;

            $response = QuizResponse::create([
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'question_type_id' => $question->question_type_id,
                'max_score' => $quizQuestion->getGrade(),
                'answer_order' => $index + 1,
            ]);

            if ($typeName === 'essay') {
                $response->update([
                    'response_text' => 'MVC يفصل التطبيق إلى Model للبيانات و View للعرض و Controller للمنطق.',
                ]);
                if ($gradeEssay) {
                    $response->update([
                        'score_obtained' => $shouldBeCorrect ? $response->max_score : $response->max_score * 0.4,
                        'is_correct' => $shouldBeCorrect,
                        'auto_graded' => false,
                        'graded_at' => now(),
                        'feedback' => $shouldBeCorrect ? 'إجابة جيدة' : 'تحتاج مزيداً من التفصيل',
                    ]);
                }
            } elseif ($typeName === 'short_answer') {
                if ($shouldBeCorrect) {
                    $this->fillCorrectAnswer($response, $question);
                } else {
                    $response->update(['response_text' => 'إجابة خاطئة']);
                }
                if ($gradeShortAnswer) {
                    $response->update([
                        'score_obtained' => $shouldBeCorrect ? $response->max_score : 0,
                        'is_correct' => $shouldBeCorrect,
                        'auto_graded' => false,
                        'graded_at' => now(),
                    ]);
                }
            } else {
                if ($shouldBeCorrect) {
                    $this->fillCorrectAnswer($response, $question);
                } else {
                    $this->fillWrongAnswer($response, $question);
                }
                $response->autoGrade();
            }

            $questionIndex++;
        }

        $attempt->update(['graded_by' => $grader->id, 'graded_at' => now()]);
        $attempt->grade();
    }

    private function seedModuleAttempts(QuestionModule $module, $students, bool $gradeAll): void
    {
        QuestionModuleAttempt::where('question_module_id', $module->id)->delete();

        $module->load(['questions.options', 'questions.questionType']);

        foreach ($students as $i => $student) {
            $questionIds = $module->questions->pluck('id')->toArray();
            $started = now()->subDays(2 - $i);
            $completed = (clone $started)->addMinutes(12);

            $attempt = QuestionModuleAttempt::create([
                'question_module_id' => $module->id,
                'student_id' => $student->id,
                'attempt_number' => 1,
                'status' => 'completed',
                'started_at' => $started,
                'completed_at' => $completed,
                'time_spent' => $started->diffInSeconds($completed),
                'question_order' => $questionIds,
            ]);

            $correctRatio = $gradeAll ? 0.9 : 0.6;

            foreach ($module->questions as $index => $question) {
                $shouldBeCorrect = ($index / max(1, $module->questions->count())) < $correctRatio;

                $response = QuestionModuleResponse::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'max_score' => $question->pivot->question_grade ?? $question->default_grade,
                    'student_answer' => $this->buildModuleAnswer($question, $shouldBeCorrect),
                ]);

                if (in_array($question->questionType->name ?? '', ['essay', 'short_answer'], true) && ! $gradeAll) {
                    continue;
                }

                $response->gradeResponse();
            }

            $attempt->calculateScores();
        }
    }

    private function fillCorrectAnswer(QuizResponse $response, $question): void
    {
        $type = $question->questionType->name ?? '';

        match ($type) {
            'multiple_choice_single' => $response->update([
                'response_data' => ['answer' => $question->options()->where('is_correct', true)->value('id')],
                'selected_option_ids' => [$question->options()->where('is_correct', true)->value('id')],
            ]),
            'multiple_choice_multiple' => $response->update([
                'selected_option_ids' => $question->options()->where('is_correct', true)->pluck('id')->toArray(),
            ]),
            'true_false' => $response->update([
                'response_data' => ['answer' => 'true'],
            ]),
            'matching' => $this->fillMatchingAnswer($response, $question, correct: true),
            'ordering' => $response->update([
                'response_data' => ['answer' => $question->options()->orderBy('option_order')->pluck('id')->toArray()],
            ]),
            'fill_blanks' => $response->update([
                'response_data' => ['answer' => $this->fillBlanksMap($question, correct: true)],
            ]),
            'numerical', 'calculated' => $response->update([
                'response_text' => (string) ($question->metadata['correct_answer'] ?? 0),
            ]),
            default => null,
        };
    }

    private function fillWrongAnswer(QuizResponse $response, $question): void
    {
        $type = $question->questionType->name ?? '';

        match ($type) {
            'multiple_choice_single' => $response->update([
                'response_data' => ['answer' => $question->options()->where('is_correct', false)->value('id')],
            ]),
            'multiple_choice_multiple' => $response->update([
                'selected_option_ids' => [$question->options()->where('is_correct', false)->value('id')],
            ]),
            'true_false' => $response->update(['response_data' => ['answer' => 'false']]),
            'matching' => $this->fillMatchingAnswer($response, $question, correct: false),
            'ordering' => $response->update([
                'response_data' => ['answer' => $question->options()->orderByDesc('option_order')->pluck('id')->toArray()],
            ]),
            'fill_blanks' => $response->update([
                'response_data' => ['answer' => $this->fillBlanksMap($question, correct: false)],
            ]),
            'numerical', 'calculated' => $response->update(['response_text' => '0']),
            default => null,
        };
    }

    private function fillMatchingAnswer(QuizResponse $response, $question, bool $correct): void
    {
        $options = $question->options;
        $answer = [];

        foreach ($options as $option) {
            if ($correct) {
                $answer[$option->id] = $option->feedback;
            } else {
                $answer[$option->id] = 'إجابة خاطئة';
            }
        }

        $response->update(['response_data' => ['answer' => $answer]]);
    }

    private function fillBlanksMap($question, bool $correct): array
    {
        $blankCount = substr_count((string) $question->question_text, '[[blank]]');
        $map = [];

        for ($i = 0; $i < $blankCount; $i++) {
            $correctOption = $question->options()
                ->where('is_correct', true)
                ->where('option_order', $i + 1)
                ->first();

            $map[$i] = $correct
                ? ($correctOption->option_text ?? 'wrong')
                : 'wrong';
        }

        return $map;
    }

    private function buildModuleAnswer($question, bool $correct): array
    {
        $type = $question->questionType->name ?? '';

        return match ($type) {
            'multiple_choice_single' => [
                'selected_option' => $correct
                    ? $question->options()->where('is_correct', true)->value('id')
                    : $question->options()->where('is_correct', false)->value('id'),
            ],
            'multiple_choice_multiple' => [
                'selected_options' => $correct
                    ? $question->options()->where('is_correct', true)->pluck('id')->toArray()
                    : [$question->options()->where('is_correct', false)->value('id')],
            ],
            'true_false' => ['answer' => $correct ? 'true' : 'false'],
            'ordering' => $correct
                ? $question->options()->orderBy('option_order')->pluck('id')->toArray()
                : $question->options()->orderByDesc('option_order')->pluck('id')->toArray(),
            'matching' => $this->buildModuleMatchingAnswer($question, $correct),
            'fill_blanks' => $this->fillBlanksMap($question, $correct),
            'essay' => ['answer' => 'شرح MVC: Model للبيانات، View للعرض، Controller للمنطق.'],
            'short_answer' => ['answer' => $correct ? 'php artisan make:controller' : 'wrong command'],
            default => [],
        };
    }

    private function buildModuleMatchingAnswer($question, bool $correct): array
    {
        $answer = [];
        foreach ($question->options as $option) {
            $answer[$option->id] = $correct ? $option->feedback : 'خطأ';
        }

        return $answer;
    }
}
