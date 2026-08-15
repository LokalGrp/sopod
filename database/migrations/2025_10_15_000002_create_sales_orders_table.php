<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration — see 2025_10_15_000001_create_customers_table.
 *
 * Base columns from App\Models\SalesOrder::$fillable, minus those added later
 * (notes, is_locked, collection_terms, customer_change_approved*).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales_orders')) {
            return;
        }

        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();

            $table->string('sales_order_number')->unique();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->string('customer_name')->nullable();

            $table->string('prepared_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('status')->default('Pending');

            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('additional_instructions')->nullable();
            $table->date('request_delivery_date')->nullable();
            $table->string('po_number')->nullable();
            $table->string('branch')->nullable();
            $table->string('sales_rep')->nullable();
            $table->string('sales_executive')->nullable();

            // Denormalised single-item summary columns used by list views
            $table->string('item_code')->nullable();
            $table->text('item_description')->nullable();
            $table->string('brand')->nullable();
            $table->string('item_category')->nullable();

            $table->boolean('is_closed')->default(false);
            $table->text('shipping_address')->nullable();
            $table->string('po_image')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('request_delivery_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
