<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use App\Models\HeroSliderSetting;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        HeroSliderSetting::instance();

        $slides = [
            [
                'admin_title' => 'الشريحة الرئيسية',
                'sort_order' => 1,
                'pagination_label' => 'الرئيسية',
                'theme_variant' => 'main',
                'accent_color' => '#059669',
                'accent_color_2' => '#7c3aed',
                'badge_text' => 'المنصة الأولى عربياً للتعليم التفاعلي',
                'badge_icon' => 'fas fa-bolt',
                'heading_mode' => 'typing',
                'heading_prefix' => 'إبدأ رحلتك في',
                'heading_typing_phrases' => ['البرمجة والتطوير', 'التصميم الجرافيكي', 'التسويق الرقمي', 'الذكاء الاصطناعي'],
                'description' => 'طور مهاراتك مع أفضل المدربين العرب وانضم لأكثر من 100,000 طالب في مجالات التكنولوجيا والإبداع.',
                'buttons' => [
                    ['label' => 'تصفح الكورسات', 'url' => '/courses', 'style' => 'primary', 'icon' => 'fas fa-arrow-left'],
                    ['label' => 'اكتشف المزيد', 'url' => '/about', 'style' => 'glass', 'icon' => 'fas fa-play-circle'],
                ],
                'visual_type' => 'main',
                'visual_icon' => 'fas fa-laptop-code',
                'visual_extras' => [
                    'float_cards' => [
                        ['type' => 'rating', 'icon' => 'fas fa-star text-warning', 'value' => '4.9/5', 'title' => 'تقييم المتعلمين'],
                        ['type' => 'counter', 'icon' => 'fas fa-user-graduate', 'value' => '100000', 'title' => 'طالب مسجل', 'counter' => true],
                    ],
                ],
            ],
            [
                'admin_title' => 'مسار البرمجة',
                'sort_order' => 2,
                'pagination_label' => 'برمجة',
                'theme_variant' => 'code',
                'accent_color' => '#14b8a6',
                'accent_color_2' => '#059669',
                'badge_text' => 'مسار البرمجة',
                'badge_icon' => 'fas fa-code',
                'heading_mode' => 'static',
                'heading_prefix' => 'أتقن',
                'heading_highlight' => 'البرمجة والتطوير',
                'description' => 'HTML, CSS, JavaScript, React وغيرها — كورسات عملية مع مشاريع حقيقية تجهّزك لسوق العمل.',
                'buttons' => [
                    ['label' => 'ابدأ التعلم', 'url' => '/categories#programming', 'style' => 'primary', 'icon' => 'fas fa-arrow-left'],
                    ['label' => '500+ كورس', 'url' => '/courses', 'style' => 'glass', 'icon' => 'fas fa-book-open'],
                ],
                'visual_type' => 'code',
                'visual_extras' => [
                    'code_snippet' => "<span class=\"c-keyword\">const</span> developer = {\n  skills: [<span class=\"c-string\">'React'</span>, <span class=\"c-string\">'Node.js'</span>],\n  ready: <span class=\"c-bool\">true</span>\n};",
                ],
            ],
            [
                'admin_title' => 'مسار التصميم',
                'sort_order' => 3,
                'pagination_label' => 'تصميم',
                'theme_variant' => 'design',
                'accent_color' => '#ec4899',
                'accent_color_2' => '#8b5cf6',
                'badge_text' => 'مسار التصميم',
                'badge_icon' => 'fas fa-paint-brush',
                'heading_mode' => 'static',
                'heading_prefix' => 'اصنع',
                'heading_highlight' => 'تصاميم تلهم',
                'description' => 'UI/UX، Figma، وAdobe — تعلّم من مصممين محترفين وابنِ portfolio يفتح لك الأبواب.',
                'buttons' => [
                    ['label' => 'استكشف التصميم', 'url' => '/categories#design', 'style' => 'primary', 'icon' => 'fas fa-arrow-left'],
                    ['label' => '150+ مدرب', 'url' => '/courses', 'style' => 'glass', 'icon' => 'fas fa-palette'],
                ],
                'visual_type' => 'design',
                'visual_extras' => [
                    'center_icon' => 'fas fa-paint-brush',
                    'orbit_icons' => [
                        ['icon' => 'fab fa-figma', 'position' => 1],
                        ['icon' => 'fas fa-pen-nib', 'position' => 2],
                        ['icon' => 'fas fa-mobile-alt', 'position' => 3],
                    ],
                ],
            ],
            [
                'admin_title' => 'مسار الذكاء الاصطناعي',
                'sort_order' => 4,
                'pagination_label' => 'AI',
                'theme_variant' => 'ai',
                'accent_color' => '#10b981',
                'accent_color_2' => '#06b6d4',
                'badge_text' => 'مسار الذكاء الاصطناعي',
                'badge_icon' => 'fas fa-robot',
                'heading_mode' => 'static',
                'heading_prefix' => 'مستقبلك يبدأ بـ',
                'heading_highlight' => 'الذكاء الاصطناعي',
                'description' => 'Machine Learning، ChatGPT، وبناء النماذج التوليدية — كن جزءاً من ثورة التكنولوجيا.',
                'buttons' => [
                    ['label' => 'اكتشف AI', 'url' => '/categories#ai', 'style' => 'primary', 'icon' => 'fas fa-arrow-left'],
                    ['label' => 'سجّل مجاناً', 'url' => '/register', 'style' => 'glass', 'icon' => 'fas fa-user-plus'],
                ],
                'visual_type' => 'ai',
                'visual_extras' => [
                    'center_icon' => 'fas fa-brain',
                    'ai_tags' => ['ML', 'GPT', 'Python', 'Data'],
                ],
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::updateOrCreate(
                ['admin_title' => $slide['admin_title']],
                array_merge([
                    'is_active' => true,
                    'layout' => 'content_right_visual_left',
                    'content_align' => 'start',
                    'min_height' => 'default',
                    'background_type' => 'theme',
                    'show_decorative_shapes' => true,
                    'hide_visual_on_mobile' => true,
                ], $slide)
            );
        }
    }
}
