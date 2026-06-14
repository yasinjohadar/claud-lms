<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->enum('module_type', ['quiz', 'question_module']);
            $table->morphs('modulable');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_graded')->default(false);
            $table->decimal('max_score', 8, 2)->nullable();
            $table->string('completion_type')->default('auto');
            $table->unsignedInteger('time_limit')->nullable();
            $table->dateTime('available_from')->nullable();
            $table->dateTime('available_until')->nullable();
            $table->timestamps();

            $table->index(['section_id', 'sort_order']);
            $table->index(['course_id', 'module_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_modules');
    }
};
