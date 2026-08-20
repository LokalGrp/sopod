<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * create_items_table defines `description` and `unit_selling_price` as NOT NULL
 * with no default, but App\Models\Item and ItemController write neither — the
 * application uses `item_description` (added in
 * 2025_10_15_000010_align_items_table_with_model) and has no selling price on
 * create. Any insert therefore failed with:
 *
 *   SQLSTATE[HY000] 1364 Field 'description' doesn't have a default value
 *
 * The columns are kept rather than dropped so existing rows and any reporting
 * that still reads them are unaffected; they simply become optional.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('items')) {
            return;
        }

        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'description')) {
                $table->string('description')->nullable()->change();
            }
            if (Schema::hasColumn('items', 'unit_selling_price')) {
                $table->decimal('unit_selling_price', 10, 2)->nullable()->default(0)->change();
            }
        });
    }

    public function down(): void
    {
        // Deliberately not reverted: restoring NOT NULL would fail on any row
        // written since, which is exactly the state this migration allows.
    }
};
