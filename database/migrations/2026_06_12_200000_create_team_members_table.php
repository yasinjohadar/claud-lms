<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('role_title');
            $table->text('bio')->nullable();
            $table->string('avatar_type', 20)->default('icon');
            $table->string('avatar_icon')->nullable();
            $table->string('avatar_path')->nullable();
            $table->string('accent_color', 20)->default('#059669');
            $table->decimal('rating', 2, 1)->nullable();
            $table->unsignedInteger('courses_count')->nullable();
            $table->json('social_links')->nullable();
            $table->string('team_group', 30)->default('instructor');
            $table->boolean('show_on_home')->default(true);
            $table->boolean('show_on_page')->default(true);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_published', 'show_on_home', 'sort_order']);
            $table->index(['is_published', 'show_on_page', 'sort_order']);
            $table->index('team_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
