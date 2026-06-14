<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

abstract class QuestionBankTypeImportMysqlTestCase extends TestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();

        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql.database', 'claudsoft_platform');

        return $app;
    }

    /**
     * @return array<string, mixed>
     */
    protected function publishedCourseAttributes(): array
    {
        $connection = config('database.default');

        return Schema::connection($connection)->hasColumn('courses', 'status')
            ? ['status' => 'published', 'published_at' => now()]
            : ['is_published' => true];
    }
}
