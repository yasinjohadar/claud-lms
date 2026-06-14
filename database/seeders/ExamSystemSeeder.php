<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamSystemSeeder extends Seeder
{
    /**
     * Seed كامل لنظام الاختبارات: أنواع، بنك أسئلة، مجموعات، كويزات، وحدات، محاولات.
     *
     * المتطلبات: PermissionSeeder, AdminUserSeeder, CourseCatalogSeeder, StudentSeeder
     *
     * التشغيل:
     *   php artisan db:seed --class=ExamSystemSeeder
     */
    public function run(): void
    {
        $this->command?->info('');
        $this->command?->info('═══════════════════════════════════════════');
        $this->command?->info('  🎓 بدء seed نظام الاختبارات الشامل');
        $this->command?->info('═══════════════════════════════════════════');
        $this->command?->newLine();

        $this->call([
            QuestionTypeSeeder::class,
            ProgrammingLanguageSeeder::class,
            QuestionBankSeeder::class,
            ExamDemoQuestionsSeeder::class,
            ExamDemoInfrastructureSeeder::class,
            ExamDemoAttemptsSeeder::class,
        ]);

        $this->command?->newLine();
        $this->command?->info('═══════════════════════════════════════════');
        $this->command?->info('  🎉 اكتمل seed نظام الاختبارات!');
        $this->command?->info('═══════════════════════════════════════════');
        $this->command?->info('');
        $this->command?->info('📋 ما تم إنشاؤه:');
        $this->command?->info('   • 10 أنواع أسئلة + 120+ سؤال من بنك Laravel/HTML/JS');
        $this->command?->info('   • 15+ سؤال تجريبي يغطي كل الأنواع');
        $this->command?->info('   • مجموعة أسئلة + 3 اختبارات + 2 وحدة تدريب');
        $this->command?->info('   • محاولات طلاب (ناجح، راسب، جزئي، قيد التقدم)');
        $this->command?->info('');
        $this->command?->info('🔑 حسابات للتجربة:');
        $this->command?->info('   أدمن: admin@admin.com / 123456789');
        $this->command?->info('   طالب: student1@edumatic.com / 123456789');
        $this->command?->info('');
    }
}
