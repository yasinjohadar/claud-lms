<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole('student') || ! $user->student) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'يجب تسجيل الدخول كطالب.'], 403);
            }

            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول كطالب للوصول لهذه الصفحة.');
        }

        return $next($request);
    }
}
