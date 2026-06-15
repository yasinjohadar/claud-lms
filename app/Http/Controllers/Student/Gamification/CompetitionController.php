<?php

namespace App\Http\Controllers\Student\Gamification;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Services\Gamification\CompetitionService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CompetitionController extends Controller
{
    protected CompetitionService $competitionService;

    public function __construct(CompetitionService $competitionService)
    {
        $this->competitionService = $competitionService;
    }

    /**
     * صفحة المسابقات
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $activeCompetitions = $this->competitionService->getUserActiveCompetitions($user);
        $completedCompetitions = $this->competitionService->getUserCompletedCompetitions($user);
        $stats = $this->competitionService->getUserCompetitionStats($user);

        foreach ($activeCompetitions as $competition) {
            $competition->my_participation = $this->competitionService->getUserParticipation($user, $competition);
        }
        foreach ($completedCompetitions as $competition) {
            $competition->my_participation = $this->competitionService->getUserParticipation($user, $competition);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'active_competitions' => $activeCompetitions,
                'completed_competitions' => $completedCompetitions,
                'stats' => $stats,
            ]);
        }

        return view('student.pages.gamification.competitions.index', compact(
            'activeCompetitions',
            'completedCompetitions',
            'stats'
        ));
    }

    /**
     * عرض المنافسات النشطة
     */
    public function active(Request $request)
    {
        $user = $request->user();

        $competitions = $this->competitionService->getUserActiveCompetitions($user);

        return response()->json([
            'success' => true,
            'active_competitions' => $competitions,
        ]);
    }

    /**
     * عرض المنافسات المكتملة
     */
    public function completed(Request $request)
    {
        $user = $request->user();

        $competitions = $this->competitionService->getUserCompletedCompetitions($user);

        return response()->json([
            'success' => true,
            'completed_competitions' => $competitions,
        ]);
    }

    /**
     * إنشاء منافسة
     */
    public function create(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:users,id',
            'type' => 'required|in:points,xp,lessons,quizzes,streak',
            'duration_days' => 'required|integer|min:1|max:30',
            'target_value' => 'nullable|integer|min:1',
        ]);

        $endsAt = now()->addDays($validated['duration_days']);

        $competition = $this->competitionService->createCompetition(
            $user,
            $validated['participant_ids'],
            $validated['type'],
            $endsAt,
            $validated['target_value'] ?? null
        );

        if (!$competition) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إنشاء المنافسة.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء المنافسة بنجاح! 🏆',
            'competition' => $competition->load('participants.user'),
        ]);
    }

    /**
     * عرض تفاصيل منافسة
     */
    public function show(Request $request, Competition $competition)
    {
        $user = $request->user();

        $competition->load(['creator:id,name,email,photo', 'participants.user:id,name,email,photo']);

        $myParticipation = $this->competitionService->getUserParticipation($user, $competition);

        return response()->json([
            'success' => true,
            'competition' => $competition,
            'my_participation' => $myParticipation,
        ]);
    }

    /**
     * مغادرة منافسة
     */
    public function leave(Request $request, Competition $competition)
    {
        $user = $request->user();

        $success = $this->competitionService->leaveCompetition($user, $competition);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'فشل مغادرة المنافسة.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم مغادرة المنافسة.',
        ]);
    }

    /**
     * حذف منافسة (للمنشئ فقط)
     */
    public function destroy(Request $request, Competition $competition)
    {
        $user = $request->user();

        $success = $this->competitionService->deleteCompetition($user, $competition);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'فشل حذف المنافسة. تأكد من أنك المنشئ.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنافسة بنجاح.',
        ]);
    }

    /**
     * إحصائيات المنافسات
     */
    public function myStats(Request $request)
    {
        $user = $request->user();

        $stats = $this->competitionService->getUserCompetitionStats($user);

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }
}
