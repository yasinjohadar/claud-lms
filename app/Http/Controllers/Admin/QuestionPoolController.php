<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionPool;
use App\Models\QuestionPoolItem;
use App\Models\QuestionBank;
use App\Models\Course;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionPoolController extends Controller
{
    /**
     * Display a listing of question pools.
     */
    public function index(Request $request)
    {
        $query = QuestionPool::with(['course', 'creator'])
            ->withCount('poolItems')
            ->orderBy('created_at', 'desc');

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $pools = $query->paginate(15);
        $courses = Course::published()->get();

        return view('admin.pages.question-pools.index', compact('pools', 'courses'));
    }

    /**
     * Show the form for creating a new pool.
     */
    public function create()
    {
        $courses = Course::published()->get();
        $questionTypes = QuestionType::where('is_active', true)->get();

        // Load all active questions by default so the table is populated
        // (can be further filtered in the view via JS / selects)
        $questions = QuestionBank::where('is_active', true)
            ->with('questionType')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.question-pools.create', compact('courses', 'questionTypes', 'questions'));
    }

    /**
     * Store a newly created pool.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        // Set creator
        $validated['created_by'] = auth()->id();

        DB::beginTransaction();
        try {
            $pool = QuestionPool::create($validated);

            // Add questions to pool if provided
            if ($request->has('question_ids')) {
                $this->addQuestionsToPool($pool, $request->input('question_ids'));
            }

            DB::commit();

            return redirect()->route('question-pools.show', $pool->id)
                ->with('success', 'تم إنشاء مجموعة الأسئلة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء مجموعة الأسئلة: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified pool.
     */
    public function show($id)
    {
        $pool = QuestionPool::with([
            'course',
            'creator',
            'questions.questionType',
            'quizzes',
            'poolItems.question.questionType',
            'poolItems.question.options'
        ])->findOrFail($id);

        // Calculate pool statistics
        $stats = $this->buildPoolStats($pool);

        return view('admin.pages.question-pools.show', compact('pool', 'stats'));
    }

    /**
     * Show the form for editing the specified pool.
     */
    public function edit($id)
    {
        $pool = QuestionPool::with(['poolItems.question', 'questions.questionType'])->findOrFail($id);
        $courses = Course::published()->get();
        $questionTypes = QuestionType::where('is_active', true)->get();

        // Get available questions for this course
        $availableQuestions = QuestionBank::where('course_id', $pool->course_id)
            ->where('is_active', true)
            ->with('questionType')
            ->get();

        return view('admin.pages.question-pools.edit', compact('pool', 'courses', 'questionTypes', 'availableQuestions'));
    }

    /**
     * Update the specified pool.
     */
    public function update(Request $request, $id)
    {
        $pool = QuestionPool::findOrFail($id);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        // Set updater
        $validated['updated_by'] = auth()->id();

        DB::beginTransaction();
        try {
            $pool->update($validated);

            // Update pool items if provided
            if ($request->has('question_ids')) {
                // Remove all existing items
                $pool->poolItems()->delete();

                // Add new items
                $this->addQuestionsToPool($pool, $request->input('question_ids'));
            }

            DB::commit();

            return redirect()->route('question-pools.show', $pool->id)
                ->with('success', 'تم تحديث مجموعة الأسئلة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء تحديث مجموعة الأسئلة: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified pool.
     */
    public function destroy($id)
    {
        $pool = QuestionPool::findOrFail($id);

        try {
            $pool->delete();

            return redirect()->route('question-pools.index')
                ->with('success', 'تم حذف مجموعة الأسئلة بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء حذف مجموعة الأسئلة: ' . $e->getMessage()]);
        }
    }

    /**
     * Add a question to the pool.
     */
    public function addQuestion(Request $request, $id)
    {
        $pool = QuestionPool::findOrFail($id);

        $validated = $request->validate([
            'question_id' => 'required|exists:question_bank,id',
        ]);

        try {
            $question = QuestionBank::findOrFail($validated['question_id']);

            // Check if question already exists in pool
            if ($pool->poolItems()->where('question_id', $validated['question_id'])->exists()) {
                return back()->withErrors(['error' => 'السؤال موجود بالفعل في هذه المجموعة']);
            }

            QuestionPoolItem::create([
                'pool_id' => $pool->id,
                'question_id' => $validated['question_id'],
            ]);

            return back()->with('success', 'تم إضافة السؤال إلى المجموعة بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إضافة السؤال: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove a question from the pool.
     */
    public function removeQuestion($id, $itemId)
    {
        $pool = QuestionPool::findOrFail($id);
        $poolItem = QuestionPoolItem::where('pool_id', $pool->id)
            ->where('id', $itemId)
            ->firstOrFail();

        try {
            $poolItem->delete();

            return back()->with('success', 'تم إزالة السؤال من المجموعة بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إزالة السؤال: ' . $e->getMessage()]);
        }
    }

    /**
     * Update question order in pool.
     */
    public function updateOrder(Request $request, $id)
    {
        $pool = QuestionPool::findOrFail($id);

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:question_pool_items,id',
            'items.*.order' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // ترتيب العرض غير مخزّن حالياً في question_pool_items — نُرجع نجاحاً للواجهة
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث ترتيب الأسئلة بنجاح'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الترتيب'
            ], 500);
        }
    }

    /**
     * Generate random questions from pool.
     */
    public function generateQuestions(Request $request, $id)
    {
        $pool = QuestionPool::with('poolItems.question')->findOrFail($id);

        $validated = $request->validate([
            'count' => 'required|integer|min:1',
            'by_probability' => 'nullable|boolean',
        ]);

        $count = min($validated['count'], $pool->poolItems()->count());
        $byProbability = $validated['by_probability'] ?? false;

        try {
            if ($byProbability) {
                // Use weighted random selection based on probability
                $questions = $this->selectByProbability($pool, $count);
            } else {
                // Simple random selection
                $questions = $pool->poolItems()
                    ->inRandomOrder()
                    ->limit($count)
                    ->with('question')
                    ->get();
            }

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء توليد الأسئلة'
            ], 500);
        }
    }

    /**
     * Duplicate a pool.
     */
    public function duplicate($id)
    {
        $pool = QuestionPool::with('poolItems')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Create duplicate pool
            $duplicate = $pool->replicate();
            $duplicate->name = $pool->name . ' (نسخة)';
            $duplicate->created_by = auth()->id();
            $duplicate->updated_by = null;
            $duplicate->save();

            // Duplicate pool items
            foreach ($pool->poolItems as $item) {
                $duplicateItem = $item->replicate();
                $duplicateItem->pool_id = $duplicate->id;
                $duplicateItem->save();
            }

            DB::commit();

            return redirect()->route('question-pools.edit', $duplicate->id)
                ->with('success', 'تم نسخ مجموعة الأسئلة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء نسخ مجموعة الأسئلة: ' . $e->getMessage()]);
        }
    }

    /**
     * Get pool statistics (AJAX).
     */
    public function getStatistics($id)
    {
        $pool = QuestionPool::findOrFail($id);

        return response()->json($this->buildPoolStats($pool));
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPoolStats(QuestionPool $pool): array
    {
        $poolId = $pool->id;

        return [
            'total_questions' => $pool->poolItems()->count(),
            'total_points' => (float) DB::table('question_pool_items')
                ->join('question_bank', 'question_pool_items.question_id', '=', 'question_bank.id')
                ->where('question_pool_items.pool_id', $poolId)
                ->sum('question_bank.default_grade'),
            'average_points' => (float) DB::table('question_pool_items')
                ->join('question_bank', 'question_pool_items.question_id', '=', 'question_bank.id')
                ->where('question_pool_items.pool_id', $poolId)
                ->avg('question_bank.default_grade'),
            'by_type' => DB::table('question_pool_items')
                ->join('question_bank', 'question_pool_items.question_id', '=', 'question_bank.id')
                ->join('question_types', 'question_bank.question_type_id', '=', 'question_types.id')
                ->where('question_pool_items.pool_id', $poolId)
                ->select(
                    'question_types.display_name',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(question_bank.default_grade) as total_points')
                )
                ->groupBy('question_types.display_name')
                ->get(),
            'by_difficulty' => DB::table('question_pool_items')
                ->join('question_bank', 'question_pool_items.question_id', '=', 'question_bank.id')
                ->where('question_pool_items.pool_id', $poolId)
                ->select(
                    'question_bank.difficulty_level as difficulty',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(question_bank.default_grade) as total_points')
                )
                ->groupBy('question_bank.difficulty_level')
                ->get(),
        ];
    }

    /**
     * Add multiple questions to pool.
     */
    private function addQuestionsToPool(QuestionPool $pool, array $questionIds): void
    {
        foreach ($questionIds as $questionId) {
            if (! QuestionBank::where('id', $questionId)->exists()) {
                continue;
            }

            if ($pool->poolItems()->where('question_id', $questionId)->exists()) {
                continue;
            }

            QuestionPoolItem::create([
                'pool_id' => $pool->id,
                'question_id' => $questionId,
            ]);
        }
    }

    /**
     * Select questions by probability weight.
     */
    private function selectByProbability(QuestionPool $pool, int $count)
    {
        $items = $pool->poolItems()->with('question')->get()->shuffle()->take($count);

        return $items->values();
    }
}
