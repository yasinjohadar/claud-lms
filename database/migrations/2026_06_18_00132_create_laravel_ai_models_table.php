<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laravel_ai_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider')->default('openai');
            $table->string('model_id');
            $table->json('capabilities')->nullable();
            $table->unsignedInteger('max_tokens')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laravel_ai_models');
    }
};
