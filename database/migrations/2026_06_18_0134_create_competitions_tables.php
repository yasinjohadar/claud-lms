<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->unsignedInteger('target_value')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('competition_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('current_value')->default(0);
            $table->unsignedInteger('rank')->default(1);
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['competition_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_participants');
        Schema::dropIfExists('competitions');
    }
};
