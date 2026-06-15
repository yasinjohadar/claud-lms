<?php

namespace Database\Seeders;

use App\Models\SocialActivity;
use App\Models\User;
use Illuminate\Database\Seeder;

class SocialActivitySeeder extends Seeder
{
    public function run(): void
    {
        $students = User::role('student')->where('is_active', true)->limit(6)->get();

        if ($students->isEmpty()) {
            $this->command?->warn('لا يوجد طلاب لإنشاء أنشطة اجتماعية تجريبية.');
            return;
        }

        $samples = [
            ['type' => 'level_up', 'description' => 'وصل للمستوى 5!', 'metadata' => ['new_level' => 5]],
            ['type' => 'achievement_unlocked', 'description' => 'حصل على إنجاز: أول درس', 'metadata' => ['achievement_name' => 'أول درس']],
            ['type' => 'badge_earned', 'description' => 'حصل على شارة: متعلم نشط', 'metadata' => ['badge_name' => 'متعلم نشط']],
            ['type' => 'course_completed', 'description' => 'أكمل كورس: مقدمة في البرمجة', 'metadata' => ['course_title' => 'مقدمة في البرمجة']],
            ['type' => 'level_up', 'description' => 'وصل للمستوى 10!', 'metadata' => ['new_level' => 10]],
            ['type' => 'achievement_unlocked', 'description' => 'حصل على إنجاز: سلسلة 7 أيام', 'metadata' => ['achievement_name' => 'سلسلة 7 أيام']],
        ];

        foreach ($students as $index => $student) {
            $sample = $samples[$index % count($samples)];
            SocialActivity::create([
                'user_id' => $student->id,
                'type' => $sample['type'],
                'description' => $sample['description'],
                'metadata' => $sample['metadata'],
                'is_public' => true,
                'likes_count' => random_int(0, 12),
                'comments_count' => 0,
                'created_at' => now()->subHours(random_int(1, 72)),
                'updated_at' => now(),
            ]);
        }

        $this->command?->info('تم إنشاء أنشطة اجتماعية تجريبية.');
    }
}
