<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration — see 2025_10_15_000001_create_customers_table.
 *
 * Decimal columns start at 2 places; later migrations widen the quantity and
 * total_amount columns to 3 decimal places.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('delivery_items')) {
            return;
        }

        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('delivery_id')->index();
            $table->unsignedBigInteger('item_id')->nullable()->index();
            $table->unsignedBigInteger('sales_order_item_id')->nullable()->index();

            $table->string('item_code')->nullable();
            $table->text('item_description')->nullable();
            $table->string('brand')->nullable();
            $table->string('item_category')->nullable();

            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('original_quantity', 15, 2)->default(0);
            $table->decimal('remaining_quantity', 15, 2)->default(0);
            $table->string('uom')->nullable();
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            $table->string('delivery_batch')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_items');
    }
};
