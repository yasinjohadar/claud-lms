<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    use RespondsWithAjaxTable;

    public function __construct(
        protected StudentService $studentService
    ) {
        $this->middleware('auth');
        $this->middleware('permission:student-list')->only('index');
        $this->middleware('permission:student-create')->only(['create', 'store']);
        $this->middleware('permission:student-show')->only('show');
        $this->middleware('permission:student-edit')->only(['edit', 'update']);
        $this->middleware('permission:student-toggle-status')->only('toggleStatus');
        $this->middleware('permission:student-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $data = $this->buildIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.pages.students.partials.list',
            'admin.pages.students.partials.modals'
        )) {
            return $response;
        }

        return view('admin.pages.students.index', $data);
    }

    public function create(): View
    {
        $availableUsers = User::query()
            ->whereDoesntHave('student')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.pages.students.create', compact('availableUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->studentService->validateStore($request);
        $this->studentService->store($validated, $request->user()->id);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'تم إنشاء ملف الطالب بنجاح');
    }

    public function show(Student $student): View
    {
        $student->load([
            'user',
            'enrollments.course',
            'orders.items',
            'lessonProgress.lesson',
        ]);

        $courses = Course::published()->orderBy('title')->get(['id', 'title']);

        return view('admin.pages.students.show', compact('student', 'courses'));
    }

    public function edit(Student $student): View
    {
        $student->load('user');

        return view('admin.pages.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $this->studentService->validateUpdate($request, $student);
        $this->studentService->update($student, $validated);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'تم تحديث ملف الطالب بنجاح');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->user?->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'تم حذف الطالب بنجاح');
    }

    public function toggleStatus(Request $request, Student $student)
    {
        $user = $student->user;

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد حساب مستخدم مرتبط بهذا الطالب',
            ], 404);
        }

        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك إيقاف حسابك',
            ], 400);
        }

        $activate = $request->boolean('is_active', ! ($student->status === 'active' && $user->is_active));

        if ($activate) {
            $student->update(['status' => 'active']);
            $user->update(['is_active' => true]);
        } else {
            $student->update(['status' => 'suspended']);
            $user->update(['is_active' => false]);
        }

        $student->refresh();
        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => $activate
                ? 'تم تفعيل الطالب بنجاح'
                : 'تم إيقاف الطالب بنجاح',
            'is_active' => $activate,
            'status' => $student->status,
            'status_label' => $student->status_label,
        ]);
    }

    /**
     * @return array{students: \Illuminate\Contracts\Pagination\LengthAwarePaginator, stats: array<string, int>}
     */
    private function buildIndexData(Request $request): array
    {
        $query = Student::query()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && in_array($request->status, Student::STATUSES, true)) {
            $query->where('status', $request->status);
        }

        $students = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'enrolled' => Student::whereHas('enrollments', fn ($q) => $q->where('status', 'active'))->count(),
            'filtered' => $students->total(),
        ];

        return compact('students', 'stats');
    }
}
