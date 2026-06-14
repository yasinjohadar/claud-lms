<?php

namespace Database\Seeders;

use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questionTypes = [
            [
                'name' => 'multiple_choice_single',
                'display_name' => 'اختيار من متعدد (إجابة واحدة)',
                'requires_manual_grading' => false,
                'supports_auto_grading' => true,
                'icon' => 'fa-check-circle',
                'is_active' => true,
            ],
            [
                'name' => 'multiple_choice_multiple',
                'display_name' => 'اختيار من متعدد (إجابات متعددة)',
                'requires_manual_grading' => false,
                'supports_auto_grading' => true,
                'icon' => 'fa-check-square',
                'is_active' => true,
            ],
            [
                'name' => 'true_false',
                'display_name' => 'صح / خطأ',
                'requires_manual_grading' => false,
                'supports_auto_grading' => true,
                'icon' => 'fa-toggle-on',
                'is_active' => true,
            ],
            [
                'name' => 'short_answer',
                'display_name' => 'إجابة قصيرة',
                'requires_manual_grading' => false,
                'supports_auto_grading' => true,
                'icon' => 'fa-pencil-alt',
                'is_active' => true,
            ],
            [
                'name' => 'essay',
                'display_name' => 'مقالي (إجابة طويلة)',
                'requires_manual_grading' => true,
                'supports_auto_grading' => false,
                'icon' => 'fa-file-alt',
                'is_active' => true,
            ],
            [
                'name' => 'matching',
                'display_name' => 'مطابقة',
                'requires_manual_grading' => false,
                'supports_auto_grading' => true,
                'icon' => 'fa-link',
                'is_active' => true,
            ],
            [
                'name' => 'fill_blanks',
                'display_name' => 'ملء الفراغات',
                'requires_manual_grading' => false,
                'supports_auto_grading' => true,
                'icon' => 'fa-i-cursor',
                'is_active' => true,
            ],
            [
                'name' => 'ordering',
                'display_name' => 'ترتيب',
                'requires_manual_grading' => false,
                'supports_auto_grading' => true,
                'icon' => 'fa-sort-numeric-down',
                'is_active' => true,
            ],
            [
                'name' => 'numerical',
                'display_name' => 'إجابة رقمية',
                'requires_manual_grading' => false,
                'supports_auto_grading' => true,
                'icon' => 'fa-calculator',
                'is_active' => true,
            ],
            [
                'name' => 'calculated',
                'display_name' => 'محسوب (معادلات)',
                'requires_manual_grading' => false,
                'supports_auto_grading' => true,
                'icon' => 'fa-square-root-alt',
                'is_active' => true,
            ],
        ];

        foreach ($questionTypes as $type) {
            QuestionType::updateOrCreate(['name' => $type['name']], $type);
        }

        $this->command?->info('✅ تم إنشاء/تحديث ' . count($questionTypes) . ' أنواع أسئلة.');
    }
}
