<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            AdminUserSeeder::class,
            BlogCategorySeeder::class,
            BlogTagSeeder::class,
            BlogPostSeeder::class,
            CourseCatalogSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
        ]);
    }
}
