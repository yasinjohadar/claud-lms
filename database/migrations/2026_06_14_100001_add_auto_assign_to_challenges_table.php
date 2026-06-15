<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            if (! Schema::hasColumn('challenges', 'auto_assign')) {
                $table->boolean('auto_assign')->default(false)->after('participation_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            if (Schema::hasColumn('challenges', 'auto_assign')) {
                $table->dropColumn('auto_assign');
            }
        });
    }
};
