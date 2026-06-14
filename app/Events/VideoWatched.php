<?php

namespace App\Events;

use App\Models\CourseLesson;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoWatched
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public CourseLesson $lesson,
        public int $percentWatched = 100,
    ) {}
}
