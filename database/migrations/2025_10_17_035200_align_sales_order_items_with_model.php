<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed migration — see 2025_10_15_000001_create_customers_table.
 *
 * create_sales_order_items_table builds an early shape (item_code, description,
 * brand, quantity, unit_price, total_amount). App\Models\SalesOrderItem and the
 * delivery-batch flow use eight further columns that existed only in production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sales_order_items')) {
            return;
        }

        Schema::table('sales_order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('sales_order_items', 'item_id')) {
                $table->unsignedBigInteger('item_id')->nullable()->index();
            }
            if (! Schema::hasColumn('sales_order_items', 'item_description')) {
                $table->text('item_description')->nullable();
            }
            if (! Schema::hasColumn('sales_order_items', 'item_category')) {
                $table->string('item_category')->nullable();
            }
            if (! Schema::hasColumn('sales_order_items', 'unit')) {
                $table->string('unit')->nullable();
            }
            if (! Schema::hasColumn('sales_order_items', 'batch_status')) {
                $table->string('batch_status')->nullable()->index();
            }
            if (! Schema::hasColumn('sales_order_items', 'delivery_batch')) {
                $table->string('delivery_batch')->nullable();
            }
            if (! Schema::hasColumn('sales_order_items', 'request_delivery_date')) {
                $table->date('request_delivery_date')->nullable();
            }
            if (! Schema::hasColumn('sales_order_items', 'note')) {
                $table->text('note')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sales_order_items')) {
            return;
        }

        Schema::table('sales_order_items', function (Blueprint $table) {
            foreach ([
                'item_id', 'item_description', 'item_category', 'unit',
                'batch_status', 'delivery_batch', 'request_delivery_date', 'note',
            ] as $col) {
                if (Schema::hasColumn('sales_order_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
