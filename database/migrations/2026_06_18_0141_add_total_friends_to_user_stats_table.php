<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user_stats')) {
            return;
        }

        Schema::table('user_stats', function (Blueprint $table) {
            if (! Schema::hasColumn('user_stats', 'total_friends')) {
                $table->unsignedInteger('total_friends')->default(0)->after('helpful_count');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('user_stats')) {
            return;
        }

        Schema::table('user_stats', function (Blueprint $table) {
            if (Schema::hasColumn('user_stats', 'total_friends')) {
                $table->dropColumn('total_friends');
            }
        });
    }
};
