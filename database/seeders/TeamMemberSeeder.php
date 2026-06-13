<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name' => 'م. أحمد سعيد',
                'role_title' => 'Senior Front-end Engineer',
                'bio' => 'خبير في بناء وتطوير واجهات الويب بأحدث التقنيات مع خبرة 10 سنوات.',
                'avatar_type' => 'icon',
                'avatar_icon' => 'fas fa-user-tie',
                'accent_color' => '#059669',
                'rating' => 4.9,
                'courses_count' => 14,
                'team_group' => 'instructor',
                'social_links' => [
                    ['platform' => 'youtube', 'url' => 'https://youtube.com'],
                    ['platform' => 'linkedin', 'url' => 'https://linkedin.com'],
                ],
            ],
            [
                'name' => 'سارة محمد',
                'role_title' => 'UX/UI Designer',
                'bio' => 'متخصصة في تصميم تجربة المستخدم وتصميم الويب وتطبيقات الجوال.',
                'avatar_type' => 'icon',
                'avatar_icon' => 'fas fa-paint-brush',
                'accent_color' => '#ec4899',
                'rating' => 4.8,
                'courses_count' => 8,
                'team_group' => 'instructor',
                'social_links' => [
                    ['platform' => 'behance', 'url' => 'https://behance.net'],
                    ['platform' => 'dribbble', 'url' => 'https://dribbble.com'],
                ],
            ],
            [
                'name' => 'عمر مصطفى',
                'role_title' => 'Machine Learning Engineer',
                'bio' => 'شغوف بعلوم البيانات والذكاء الاصطناعي، يربط النظريات بالتطبيق العملي.',
                'avatar_type' => 'icon',
                'avatar_icon' => 'fas fa-robot',
                'accent_color' => '#7c3aed',
                'rating' => 4.7,
                'courses_count' => 11,
                'team_group' => 'instructor',
                'social_links' => [
                    ['platform' => 'github', 'url' => 'https://github.com'],
                    ['platform' => 'linkedin', 'url' => 'https://linkedin.com'],
                ],
            ],
            [
                'name' => 'طارق زياد',
                'role_title' => 'Business Analyst & Marketer',
                'bio' => 'خبرة في إدارة الأعمال والتسويق الرقمي بتركيز على تحقيق النمو والمبيعات.',
                'avatar_type' => 'icon',
                'avatar_icon' => 'fas fa-chart-pie',
                'accent_color' => '#f59e0b',
                'rating' => 4.5,
                'courses_count' => 6,
                'team_group' => 'instructor',
                'social_links' => [
                    ['platform' => 'twitter', 'url' => 'https://twitter.com'],
                    ['platform' => 'linkedin', 'url' => 'https://linkedin.com'],
                ],
            ],
            [
                'name' => 'م. خالد أحمد',
                'role_title' => 'Flutter / Mobile Developer',
                'bio' => 'متخصص في تطوير تطبيقات الجوال بـ Flutter وDart للـ iOS والـ Android.',
                'avatar_type' => 'icon',
                'avatar_icon' => 'fas fa-mobile-alt',
                'accent_color' => '#0891b2',
                'rating' => 5.0,
                'courses_count' => 9,
                'team_group' => 'instructor',
                'social_links' => [
                    ['platform' => 'github', 'url' => 'https://github.com'],
                    ['platform' => 'youtube', 'url' => 'https://youtube.com'],
                ],
            ],
        ];

        foreach ($members as $index => $data) {
            TeamMember::updateOrCreate(
                ['name' => $data['name'], 'role_title' => $data['role_title']],
                array_merge($data, [
                    'sort_order' => $index + 1,
                    'show_on_home' => true,
                    'show_on_page' => true,
                    'is_published' => true,
                ])
            );
        }
    }
}
