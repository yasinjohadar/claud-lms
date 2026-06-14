<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('course_group_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['course_group_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_group_course');
        Schema::dropIfExists('course_groups');
    }
};
