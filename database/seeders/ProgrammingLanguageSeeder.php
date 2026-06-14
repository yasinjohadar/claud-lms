<?php

namespace Database\Seeders;

use App\Models\ProgrammingLanguage;
use Illuminate\Database\Seeder;

class ProgrammingLanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['name' => 'PHP', 'slug' => 'php', 'display_name' => 'PHP', 'icon' => 'fab fa-php', 'sort_order' => 1],
            ['name' => 'Laravel', 'slug' => 'laravel', 'display_name' => 'Laravel', 'icon' => 'fab fa-laravel', 'sort_order' => 2],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'display_name' => 'JavaScript', 'icon' => 'fab fa-js', 'sort_order' => 3],
            ['name' => 'HTML', 'slug' => 'html', 'display_name' => 'HTML5', 'icon' => 'fab fa-html5', 'sort_order' => 4],
            ['name' => 'CSS', 'slug' => 'css', 'display_name' => 'CSS3', 'icon' => 'fab fa-css3-alt', 'sort_order' => 5],
        ];

        foreach ($languages as $language) {
            ProgrammingLanguage::updateOrCreate(
                ['slug' => $language['slug']],
                array_merge($language, ['is_active' => true])
            );
        }

        $this->command?->info('✅ تم إنشاء/تحديث ' . count($languages) . ' لغات برمجة.');
    }
}
