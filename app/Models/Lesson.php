<?php

namespace App\Models;

/**
 * Alias for course video lessons — exam module references Lesson model.
 */
class Lesson extends CourseLesson
{
    protected $table = 'course_lessons';
}
