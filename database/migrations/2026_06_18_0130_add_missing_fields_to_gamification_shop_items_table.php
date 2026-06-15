<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gamification_shop_items')) {
            return;
        }

        Schema::table('gamification_shop_items', function (Blueprint $table) {
            if (! Schema::hasColumn('gamification_shop_items', 'stock_quantity')) {
                $after = Schema::hasColumn('gamification_shop_items', 'price_gems') ? 'price_gems' : 'price_points';
                $table->integer('stock_quantity')->nullable()->after($after);
            }

            if (! Schema::hasColumn('gamification_shop_items', 'discount_percentage')) {
                $table->unsignedTinyInteger('discount_percentage')->default(0)->after('stock_quantity');
            }

            if (! Schema::hasColumn('gamification_shop_items', 'discount_expires_at')) {
                $table->timestamp('discount_expires_at')->nullable()->after('discount_percentage');
            }

            if (! Schema::hasColumn('gamification_shop_items', 'purchase_limit')) {
                $table->unsignedInteger('purchase_limit')->nullable()->after('required_level');
            }

            if (! Schema::hasColumn('gamification_shop_items', 'required_badge_id')) {
                $table->unsignedBigInteger('required_badge_id')->nullable()->after('purchase_limit');
            }

            if (! Schema::hasColumn('gamification_shop_items', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('required_badge_id');
            }

            if (! Schema::hasColumn('gamification_shop_items', 'in_stock')) {
                $table->boolean('in_stock')->default(true)->after('is_active');
            }

            if (! Schema::hasColumn('gamification_shop_items', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('in_stock');
            }

            if (! Schema::hasColumn('gamification_shop_items', 'total_purchases')) {
                $table->unsignedInteger('total_purchases')->default(0)->after('is_featured');
            }

            if (! Schema::hasColumn('gamification_shop_items', 'last_purchased_at')) {
                $table->timestamp('last_purchased_at')->nullable()->after('total_purchases');
            }
        });

        if (Schema::hasColumn('gamification_shop_items', 'stock')
            && Schema::hasColumn('gamification_shop_items', 'stock_quantity')) {
            DB::table('gamification_shop_items')
                ->whereNull('stock_quantity')
                ->update(['stock_quantity' => DB::raw('stock')]);

            Schema::table('gamification_shop_items', function (Blueprint $table) {
                $table->dropColumn('stock');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('gamification_shop_items')) {
            return;
        }

        Schema::table('gamification_shop_items', function (Blueprint $table) {
            if (! Schema::hasColumn('gamification_shop_items', 'stock')) {
                $table->integer('stock')->nullable()->after('price_gems');
            }
        });

        if (Schema::hasColumn('gamification_shop_items', 'stock')
            && Schema::hasColumn('gamification_shop_items', 'stock_quantity')) {
            DB::table('gamification_shop_items')
                ->whereNull('stock')
                ->update(['stock' => DB::raw('stock_quantity')]);
        }

        $columns = [
            'last_purchased_at',
            'total_purchases',
            'is_featured',
            'in_stock',
            'sort_order',
            'required_badge_id',
            'purchase_limit',
            'discount_expires_at',
            'discount_percentage',
            'stock_quantity',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('gamification_shop_items', $column)) {
                Schema::table('gamification_shop_items', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
