<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration — see 2025_10_15_000001_create_customers_table.
 *
 * Note these are the customer-side receiving reports (App\Models\ReceivingReport),
 * distinct from the supplier_receiving_reports tables created in 2026_02_16.
 * Only the supplier ones had migrations; these existed solely in production,
 * which is also why the ar_adjustments foreign key in 2026_03_24 had no target.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('receiving_reports')) {
            Schema::create('receiving_reports', function (Blueprint $table) {
                $table->id();

                $table->string('rr_number')->unique();
                $table->string('sales_order_number')->nullable()->index();
                $table->string('customer_name')->nullable();
                $table->string('customer_code')->nullable()->index();
                $table->string('tin_no')->nullable();
                $table->string('branch')->nullable();
                $table->string('sales_representative')->nullable();
                $table->string('sales_executive')->nullable();
                $table->string('po_number')->nullable();
                $table->string('plate_no')->nullable();
                $table->string('sales_invoice_no')->nullable();
                $table->string('received_by')->nullable();
                $table->string('delivery_batch')->nullable();
                $table->string('delivery_type')->nullable();
                $table->text('additional_instructions')->nullable();
                $table->date('request_delivery_date')->nullable();
                $table->string('status')->default('Pending')->index();
                $table->dateTime('received_date')->nullable();
                $table->string('attachment')->nullable();
                $table->string('created_by')->nullable();

                $table->timestamps();
            });
        }

        if (! Schema::hasTable('receiving_report_items')) {
            Schema::create('receiving_report_items', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('receiving_report_id')->index();
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
                $table->text('notes')->nullable();

                $table->timestamps();

                $table->foreign('receiving_report_id', 'rr_items_rr_id_foreign')
                      ->references('id')->on('receiving_reports')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('receiving_report_items');
        Schema::dropIfExists('receiving_reports');
    }
};
