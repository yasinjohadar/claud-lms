<?php

namespace App\Listeners\Gamification;

use App\Events\VideoWatched;
use App\Services\Gamification\GamificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class VideoWatchedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    public function handle(VideoWatched $event): void
    {
        try {
            $minPercentage = (int) config('gamification.points.video_watch.min_watch_percentage', 80);
            $watchPercentage = (int) ($event->percentWatched ?? 100);

            if ($watchPercentage < $minPercentage) {
                return;
            }

            $lesson = $event->lesson;

            $result = $this->gamificationService->handleVideoWatch(
                $event->user,
                $lesson->id,
                $watchPercentage,
                [
                    'module_title' => $lesson->title ?? '',
                ]
            );

            if ($result['success'] ?? false) {
                Log::info('Gamification: Video watch rewarded', [
                    'user_id' => $event->user->id,
                    'lesson_id' => $lesson->id,
                    'watch_percentage' => $watchPercentage,
                    'points_awarded' => $result['points_awarded'] ?? 0,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gamification: Failed to handle video watch', [
                'user_id' => $event->user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
