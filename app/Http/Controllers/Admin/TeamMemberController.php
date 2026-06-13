<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Services\TeamMemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    use RespondsWithAjaxTable;

    public function __construct(
        protected TeamMemberService $teamService
    ) {}

    public function index(Request $request)
    {
        $data = $this->buildIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.pages.team-members.partials.list',
            'admin.pages.team-members.partials.modals'
        )) {
            return $response;
        }

        return view('admin.pages.team-members.index', $data);
    }

    public function create(): View
    {
        return view('admin.pages.team-members.create', [
            'roleFilters' => $this->teamService->roleFilterOptions(),
            'socialPlatforms' => TeamMember::SOCIAL_PLATFORMS,
            'teamGroups' => TeamMember::TEAM_GROUPS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->teamService->validatePayload($request);
        $this->teamService->store($validated, $request->file('avatar_file'));

        return redirect()
            ->route('admin.team-members.index')
            ->with('success', 'تم إضافة عضو الفريق بنجاح');
    }

    public function edit(TeamMember $teamMember): View
    {
        $teamMember->load('user');

        return view('admin.pages.team-members.edit', [
            'member' => $teamMember,
            'roleFilters' => $this->teamService->roleFilterOptions(),
            'socialPlatforms' => TeamMember::SOCIAL_PLATFORMS,
            'teamGroups' => TeamMember::TEAM_GROUPS,
        ]);
    }

    public function update(Request $request, TeamMember $teamMember): RedirectResponse
    {
        $validated = $this->teamService->validatePayload($request, true, $teamMember);
        $this->teamService->update($teamMember, $validated, $request->file('avatar_file'));

        return redirect()
            ->route('admin.team-members.index')
            ->with('success', 'تم تحديث عضو الفريق بنجاح');
    }

    public function destroy(Request $request, TeamMember $teamMember): RedirectResponse|JsonResponse
    {
        $this->teamService->destroy($teamMember);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم حذف عضو الفريق بنجاح']);
        }

        return redirect()
            ->route('admin.team-members.index')
            ->with('success', 'تم حذف عضو الفريق بنجاح');
    }

    public function usersPicker(Request $request): JsonResponse
    {
        return response()->json([
            'users' => $this->teamService->usersForPicker(
                $request->query('role'),
                $request->query('search')
            ),
        ]);
    }

    /**
     * @return array{members: \Illuminate\Contracts\Pagination\LengthAwarePaginator, stats: array<string, int>}
     */
    private function buildIndexData(Request $request): array
    {
        $query = TeamMember::query()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('role_title', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('team_group') && in_array($request->team_group, TeamMember::TEAM_GROUPS, true)) {
            $query->where('team_group', $request->team_group);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        if ($request->filled('source')) {
            if ($request->source === 'user') {
                $query->whereNotNull('user_id');
            } elseif ($request->source === 'manual') {
                $query->whereNull('user_id');
            }
        }

        $members = $query->orderBy('sort_order')->orderBy('id')->paginate(20)->withQueryString();

        $stats = [
            'total' => TeamMember::count(),
            'published' => TeamMember::where('is_published', true)->count(),
            'instructors' => TeamMember::where('team_group', 'instructor')->count(),
            'linked' => TeamMember::whereNotNull('user_id')->count(),
            'filtered' => $members->total(),
        ];

        return compact('members', 'stats');
    }
}
