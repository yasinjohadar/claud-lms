<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gamification\ShopCategory;

class ShopCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'التخصيص والمظهر',
                'slug' => 'cosmetics',
                'description' => 'أفاتارات، إطارات الملف الشخصي، ثيمات خاصة',
                'icon' => '🎨',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'المعززات والمضاعفات',
                'slug' => 'boosters',
                'description' => 'مضاعفات XP، مضاعفات النقاط، حماية السلسلة',
                'icon' => '⚡',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'الوصول للكورسات',
                'slug' => 'course-access',
                'description' => 'فتح كورسات مميزة مبكراً',
                'icon' => '🔓',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'الميزات الخاصة',
                'slug' => 'features',
                'description' => 'ميزات فريدة تساعدك في التعلم',
                'icon' => '✨',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'الجوائز الحقيقية',
                'slug' => 'physical-rewards',
                'description' => 'شهادات مطبوعة، هدايا، جوائز ملموسة',
                'icon' => '🎁',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            unset($category['slug'], $category['sort_order']);
            ShopCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('✅ تم إنشاء ' . count($categories) . ' فئة متجر بنجاح!');
    }
}
