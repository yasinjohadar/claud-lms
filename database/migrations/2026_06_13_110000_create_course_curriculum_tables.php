<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['course_id', 'sort_order']);
        });

        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->string('title');
            $table->enum('video_provider', ['youtube', 'vimeo', 'bunny_stream', 'bunny_cdn']);
            $table->string('video_reference', 500);
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['course_section_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_sections');
    }
};
