<?php

namespace Database\Seeders;

use App\Models\ExperienceLevel;
use Illuminate\Database\Seeder;

class ExperienceLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['level' => 1, 'name' => 'مبتدئ', 'xp_required' => 0, 'points_reward' => 0],
            ['level' => 2, 'name' => 'متعلم', 'xp_required' => 100, 'points_reward' => 10],
            ['level' => 3, 'name' => 'طالب', 'xp_required' => 250, 'points_reward' => 15],
            ['level' => 4, 'name' => 'دارس', 'xp_required' => 500, 'points_reward' => 20],
            ['level' => 5, 'name' => 'مجتهد', 'xp_required' => 800, 'points_reward' => 25],
            ['level' => 6, 'name' => 'متفوق', 'xp_required' => 1200, 'points_reward' => 30],
            ['level' => 7, 'name' => 'نابغ', 'xp_required' => 1700, 'points_reward' => 35],
            ['level' => 8, 'name' => 'عالم', 'xp_required' => 2300, 'points_reward' => 40],
            ['level' => 9, 'name' => 'خبير', 'xp_required' => 3000, 'points_reward' => 50],
            ['level' => 10, 'name' => 'أستاذ', 'xp_required' => 3800, 'points_reward' => 60],
            ['level' => 11, 'name' => 'محترف', 'xp_required' => 4700, 'points_reward' => 70],
            ['level' => 12, 'name' => 'متميز', 'xp_required' => 5700, 'points_reward' => 80],
            ['level' => 13, 'name' => 'رائد', 'xp_required' => 6800, 'points_reward' => 90],
            ['level' => 14, 'name' => 'قائد', 'xp_required' => 8000, 'points_reward' => 100],
            ['level' => 15, 'name' => 'بطل', 'xp_required' => 9500, 'points_reward' => 120],
            ['level' => 16, 'name' => 'أسطورة', 'xp_required' => 11000, 'points_reward' => 140],
            ['level' => 17, 'name' => 'عبقري', 'xp_required' => 13000, 'points_reward' => 160],
            ['level' => 18, 'name' => 'نجم', 'xp_required' => 15500, 'points_reward' => 180],
            ['level' => 19, 'name' => 'فائق', 'xp_required' => 18000, 'points_reward' => 200],
            ['level' => 20, 'name' => 'ماسي', 'xp_required' => 21000, 'points_reward' => 250],
        ];

        $sorted = collect($levels)->sortBy('level')->values();

        foreach ($sorted as $index => $level) {
            $next = $sorted->get($index + 1);
            $xpToNext = $next ? max(0, $next['xp_required'] - $level['xp_required']) : 0;

            ExperienceLevel::updateOrCreate(
                ['level' => $level['level']],
                [
                    'name' => $level['name'],
                    'title' => $level['name'],
                    'xp_required' => $level['xp_required'],
                    'xp_to_next' => $xpToNext,
                    'points_reward' => $level['points_reward'],
                    'is_active' => true,
                    'sort_order' => $level['level'],
                ]
            );
        }

        $this->command?->info('Experience levels seeded: ' . count($levels));
    }
}
