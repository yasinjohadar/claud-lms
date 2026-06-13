<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Services\CourseAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEnrolledInCourse
{
    public function __construct(
        protected CourseAccessService $accessService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $lesson = $request->route('lesson');

        if ($lesson instanceof CourseLesson) {
            if (! $this->accessService->canAccessLesson($user, $lesson)) {
                return $this->deny($request, 'لا يمكنك مشاهدة هذا الدرس. يرجى التسجيل في الكورس أولاً.');
            }

            return $next($request);
        }

        $course = $request->route('course');
        if ($course instanceof Course && ! $this->accessService->canAccessCourse($user, $course)) {
            return $this->deny($request, 'لا يمكنك الوصول لهذا المحتوى. يرجى التسجيل في الكورس أولاً.');
        }

        return $next($request);
    }

    private function deny(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        return redirect()->route('courses')->with('error', $message);
    }
}
