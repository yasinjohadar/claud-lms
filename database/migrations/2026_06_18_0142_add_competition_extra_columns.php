<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('competitions') && ! Schema::hasColumn('competitions', 'completed_at')) {
            Schema::table('competitions', function (Blueprint $table) {
                $table->timestamp('completed_at')->nullable()->after('status');
            });
        }

        if (Schema::hasTable('competition_participants') && ! Schema::hasColumn('competition_participants', 'is_winner')) {
            Schema::table('competition_participants', function (Blueprint $table) {
                $table->boolean('is_winner')->default(false)->after('rank');
            });
        }

        if (Schema::hasTable('user_stats') && ! Schema::hasColumn('user_stats', 'competitions_won')) {
            Schema::table('user_stats', function (Blueprint $table) {
                $table->unsignedInteger('competitions_won')->default(0)->after('total_friends');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('competitions') && Schema::hasColumn('competitions', 'completed_at')) {
            Schema::table('competitions', function (Blueprint $table) {
                $table->dropColumn('completed_at');
            });
        }

        if (Schema::hasTable('competition_participants') && Schema::hasColumn('competition_participants', 'is_winner')) {
            Schema::table('competition_participants', function (Blueprint $table) {
                $table->dropColumn('is_winner');
            });
        }

        if (Schema::hasTable('user_stats') && Schema::hasColumn('user_stats', 'competitions_won')) {
            Schema::table('user_stats', function (Blueprint $table) {
                $table->dropColumn('competitions_won');
            });
        }
    }
};
