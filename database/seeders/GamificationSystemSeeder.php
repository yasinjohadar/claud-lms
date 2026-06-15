<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GamificationSystemSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BadgeSeeder::class,
            AchievementSeeder::class,
            ExperienceLevelSeeder::class,
            LeaderboardSeeder::class,
            ChallengeSeeder::class,
            ShopCategorySeeder::class,
            ShopItemSeeder::class,
        ]);

        $this->command?->info('Gamification system seeded successfully.');
    }
}
