<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_stats', function (Blueprint $table) {
            if (! Schema::hasColumn('user_stats', 'total_gems')) {
                $table->unsignedInteger('total_gems')->default(0)->after('spent_points');
            }
            if (! Schema::hasColumn('user_stats', 'available_gems')) {
                $table->unsignedInteger('available_gems')->default(0)->after('total_gems');
            }
            if (! Schema::hasColumn('user_stats', 'spent_gems')) {
                $table->unsignedInteger('spent_gems')->default(0)->after('available_gems');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_stats', function (Blueprint $table) {
            $columns = ['total_gems', 'available_gems', 'spent_gems'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('user_stats', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
