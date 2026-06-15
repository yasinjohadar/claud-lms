<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('social_activities')) {
            Schema::create('social_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('type', 64);
                $table->text('description');
                $table->string('related_type')->nullable();
                $table->unsignedBigInteger('related_id')->nullable();
                $table->json('metadata')->nullable();
                $table->boolean('is_public')->default(true);
                $table->unsignedInteger('likes_count')->default(0);
                $table->unsignedInteger('comments_count')->default(0);
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
                $table->index(['type', 'created_at']);
            });
        }

        if (! Schema::hasTable('social_activity_likes')) {
            Schema::create('social_activity_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('social_activity_id')->constrained('social_activities')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['social_activity_id', 'user_id']);
            });
        }

        if (! Schema::hasTable('social_activity_comments')) {
            Schema::create('social_activity_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('social_activity_id')->constrained('social_activities')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->text('content');
                $table->timestamps();

                $table->index(['social_activity_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('social_activity_comments');
        Schema::dropIfExists('social_activity_likes');
        Schema::dropIfExists('social_activities');
    }
};
