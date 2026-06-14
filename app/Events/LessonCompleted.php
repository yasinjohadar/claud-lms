<?php

namespace App\Events;

use App\Models\CourseLesson;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessonCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public CourseLesson $lesson,
    ) {}
}
