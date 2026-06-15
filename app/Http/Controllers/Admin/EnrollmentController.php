<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEnrollmentRequest;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Student;
use App\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    use RespondsWithAjaxTable;

    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {
        $this->middleware('auth');
        $this->middleware('permission:enrollment-manage');
    }

    public function index(Request $request)
    {
        $data = $this->buildIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.pages.enrollments.partials.list',
            'admin.pages.enrollments.partials.modals'
        )) {
            return $response;
        }

        return view('admin.pages.enrollments.index', $data);
    }

    public function store(StoreEnrollmentRequest $request): RedirectResponse
    {
        return $this->persistGrant($request);
    }

    public function storeForStudent(Request $request, Student $student): RedirectResponse
    {
        $request->merge(['student_id' => $student->id]);

        $formRequest = StoreEnrollmentRequest::createFrom($request);
        $formRequest->setContainer(app());
        $formRequest->validateResolved();

        return $this->persistGrant($formRequest);
    }

    private function persistGrant(StoreEnrollmentRequest $request): RedirectResponse
    {
        $wasExisting = $request->hadExistingEnrollment();

        $this->enrollmentService->grant(
            $request->student(),
            $request->course(),
            $request->enrollmentSource(),
            $request->user(),
            null,
            'active'
        );

        $message = $wasExisting
            ? 'تم تحديث التسجيل وتفعيله بنجاح'
            : 'تم تسجيل الطالب في الكورس بنجاح';

        if (! $request->course()->isPublished()) {
            $message .= ' (تنبيه: الكورس غير منشور حالياً)';
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    public function destroy(CourseEnrollment $enrollment): RedirectResponse
    {
        $this->enrollmentService->cancel($enrollment);

        return redirect()
            ->back()
            ->with('success', 'تم إلغاء تسجيل الكورس');
    }

    public function searchStudents(Request $request): JsonResponse
    {
        $query = Student::query()->with('user');

        if ($request->filled('search') || $request->filled('q') || $request->filled('term')) {
            $search = $request->input('search', $request->input('q', $request->input('term')));
            $query->where(function ($q) use ($search) {
                $q->where('student_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });

                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        if ($request->filled('ids')) {
            $ids = collect(explode(',', $request->input('ids')))
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => $id > 0)
                ->all();
            $query->whereIn('id', $ids);
        }

        $students = $query
            ->whereIn('status', ['active', 'graduated'])
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn (Student $student) => [
                'id' => $student->id,
                'text' => $this->formatStudentLabel($student),
            ]);

        return response()->json(['results' => $students]);
    }

    public function searchCourses(Request $request): JsonResponse
    {
        $query = Course::query();

        if ($request->filled('search') || $request->filled('q') || $request->filled('term')) {
            $search = $request->input('search', $request->input('q', $request->input('term')));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        if ($request->filled('ids')) {
            $ids = collect(explode(',', $request->input('ids')))
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => $id > 0)
                ->all();
            $query->whereIn('id', $ids);
        }

        $courses = $query
            ->orderBy('title')
            ->limit(50)
            ->get(['id', 'title', 'status'])
            ->map(fn (Course $course) => [
                'id' => $course->id,
                'text' => $course->title.($course->status !== 'published' ? ' (مسودة)' : ''),
            ]);

        return response()->json(['results' => $courses]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildIndexData(Request $request): array
    {
        $query = CourseEnrollment::query()
            ->with([
                'student.user',
                'course',
                'order',
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('course', function ($cq) use ($search) {
                    $cq->where('title', 'like', "%{$search}%");
                })->orWhereHas('order', function ($oq) use ($search) {
                    $oq->where('order_number', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status') && in_array($request->status, CourseEnrollment::STATUSES, true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source') && in_array($request->source, CourseEnrollment::SOURCES, true)) {
            $query->where('source', $request->source);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', (int) $request->student_id);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', (int) $request->course_id);
        }

        if ($request->filled('order_id')) {
            $query->where('order_id', (int) $request->order_id);
        }

        $enrollments = $query->orderByDesc('enrolled_at')->orderByDesc('id')->paginate(20)->withQueryString();

        $stats = [
            'total' => CourseEnrollment::count(),
            'active' => CourseEnrollment::where('status', 'active')->count(),
            'admin_granted' => CourseEnrollment::where('source', 'admin_grant')->count(),
            'via_order' => CourseEnrollment::whereNotNull('order_id')->count(),
            'filtered' => $enrollments->total(),
        ];

        return compact('enrollments', 'stats');
    }

    private function formatStudentLabel(Student $student): string
    {
        $name = $student->user?->name ?? 'طالب';
        $email = $student->user?->email;
        $code = $student->student_code;

        $parts = array_filter([$name, $code ? "#{$code}" : null, $email]);

        return implode(' — ', $parts);
    }
}
