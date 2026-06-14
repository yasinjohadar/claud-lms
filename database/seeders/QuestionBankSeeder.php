<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * يقوم بتشغيل جميع seeders الخاصة ببنك الأسئلة
     */
    public function run(): void
    {
        $this->command->info('🚀 بدء إنشاء بنك الأسئلة الشامل...');
        $this->command->newLine();

        $courseId = \App\Models\Course::where('slug', 'professional-web-development')->value('id');
        if ($courseId && \App\Models\QuestionBank::where('course_id', $courseId)->count() >= 100) {
            $this->command->info('⏭️  بنك الأسئلة موجود مسبقاً — تخطي.');
            $this->command->newLine();

            return;
        }

        // تشغيل seeder لغات البرمجة أولاً
        $this->call(ProgrammingLanguageSeeder::class);
        $this->command->newLine();

        // تشغيل seeders الأسئلة
        $this->call([
            HtmlCssQuestionBankSeeder::class,
            LaravelQuestionBankSeeder::class,
            JavaScriptQuestionBankSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('🎉 تم إنشاء بنك الأسئلة بنجاح!');
        $this->command->info('📊 إجمالي الأسئلة: 120 سؤال');
        $this->command->info('   - HTML & CSS: 20 سؤال');
        $this->command->info('   - Laravel: 50 سؤال');
        $this->command->info('   - JavaScript ES6+: 50 سؤال');
    }
}
