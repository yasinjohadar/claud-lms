<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('course_categories')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('courses_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
            $table->index('parent_id');
        });

        Schema::create('course_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('courses_count')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('course_category_id')->constrained('course_categories')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->restrictOnDelete();
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('badge')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('thumbnail_alt')->nullable();
            $table->string('icon')->nullable();
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedInteger('students_count')->default(0);
            $table->unsignedInteger('lessons_count')->default(0);
            $table->unsignedInteger('duration_hours')->default(0);
            $table->string('language')->default('ar');
            $table->json('what_you_learn')->nullable();
            $table->json('requirements')->nullable();
            $table->json('curriculum_outline')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
            $table->index('is_featured');
            $table->index('course_category_id');
            $table->index('instructor_id');
            $table->index('level');
            $table->index('price');
        });

        Schema::create('course_course_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('course_tag_id')->constrained('course_tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['course_id', 'course_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_course_tag');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_tags');
        Schema::dropIfExists('course_categories');
    }
};
