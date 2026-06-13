<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->longText('curriculum_outline')->nullable()->change();
        });

        DB::table('courses')->orderBy('id')->each(function ($course) {
            $raw = $course->curriculum_outline;

            if (empty($raw)) {
                return;
            }

            $decoded = json_decode($raw, true);

            if (! is_array($decoded)) {
                return;
            }

            $html = '<ul>';

            foreach ($decoded as $module) {
                $title = htmlspecialchars($module['title'] ?? 'وحدة', ENT_QUOTES, 'UTF-8');
                $html .= '<li><strong>' . $title . '</strong>';

                if (! empty($module['lessons']) && is_array($module['lessons'])) {
                    $html .= '<ul>';

                    foreach ($module['lessons'] as $lesson) {
                        $lessonTitle = htmlspecialchars($lesson['title'] ?? '', ENT_QUOTES, 'UTF-8');
                        $duration = htmlspecialchars($lesson['duration'] ?? '', ENT_QUOTES, 'UTF-8');
                        $html .= '<li>' . $lessonTitle . ($duration ? ' <em>(' . $duration . ')</em>' : '') . '</li>';
                    }

                    $html .= '</ul>';
                }

                $html .= '</li>';
            }

            $html .= '</ul>';

            DB::table('courses')->where('id', $course->id)->update([
                'curriculum_outline' => $html,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->json('curriculum_outline')->nullable()->change();
        });
    }
};
