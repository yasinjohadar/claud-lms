<?php

namespace App\Support;

use App\Models\User;

class AuthRedirect
{
    public static function intendedFor(User $user): string
    {
        if ($user->hasRole('admin')) {
            return route('admin.dashboard');
        }

        if ($user->hasRole('student') && $user->student) {
            return route('student.dashboard');
        }

        if ($user->hasRole('instructor')) {
            return route('admin.dashboard');
        }

        return route('home');
    }
}
