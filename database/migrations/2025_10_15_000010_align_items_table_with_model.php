<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed migration — see 2025_10_15_000001_create_customers_table.
 *
 * create_items_table builds an early shape of the table (description, uom,
 * unit_selling_price). App\Models\Item and the item/approval controllers use a
 * later shape that only ever existed in production. The columns below are the
 * difference; `type` and `is_locked` are deliberately excluded because later
 * migrations add them (2026_05_04 after `unit`, 2026_02_19 after `is_enabled`),
 * and both would fail without their anchor columns present first.
 *
 * The original columns are left in place — dropping them is a data decision,
 * not a schema one.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('items')) {
            return;
        }

        Schema::table('items', function (Blueprint $table) {
            if (! Schema::hasColumn('items', 'item_description')) {
                $table->text('item_description')->nullable()->after('item_code');
            }
            if (! Schema::hasColumn('items', 'item_category')) {
                $table->string('item_category')->nullable()->after('brand');
            }
            if (! Schema::hasColumn('items', 'unit')) {
                $table->string('unit')->nullable()->after('item_category');
            }
            if (! Schema::hasColumn('items', 'approval_status')) {
                $table->string('approval_status')->default('approved')->index();
            }
            if (! Schema::hasColumn('items', 'approved_by')) {
                $table->string('approved_by')->nullable();
            }
            if (! Schema::hasColumn('items', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (! Schema::hasColumn('items', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            if (! Schema::hasColumn('items', 'is_enabled')) {
                $table->boolean('is_enabled')->default(true);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('items')) {
            return;
        }

        Schema::table('items', function (Blueprint $table) {
            foreach ([
                'item_description', 'item_category', 'unit', 'approval_status',
                'approved_by', 'approved_at', 'rejection_reason', 'is_enabled',
            ] as $col) {
                if (Schema::hasColumn('items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
