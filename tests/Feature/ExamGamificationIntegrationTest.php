<?php

namespace Tests\Feature;

use App\Models\QuestionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has question types after seeder', function () {
    $this->seed(\Database\Seeders\QuestionTypeSeeder::class);

    expect(QuestionType::count())->toBeGreaterThanOrEqual(10);
});

it('registers exam and gamification routes', function () {
    $this->artisan('route:list', ['--name' => 'quizzes'])
        ->assertSuccessful();

    $this->artisan('route:list', ['--name' => 'gamification'])
        ->assertSuccessful();
});

it('allows admin to access question bank index when permitted', function () {
    $this->seed(\Database\Seeders\PermissionSeeder::class);
    $this->seed(\Database\Seeders\QuestionTypeSeeder::class);

    $admin = User::factory()->create(['is_active' => true]);
    $admin->givePermissionTo('question-bank-list');

    $response = $this->actingAs($admin)->get('/admin/question-bank');

    $response->assertOk();
});
