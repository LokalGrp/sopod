<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration — see 2025_10_15_000001_create_customers_table.
 *
 * Base columns from App\Models\Deliveries::$fillable, minus those added later
 * (is_locked, counter_date*, is_hidden/hidden_*).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('deliveries')) {
            return;
        }

        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sales_order_id')->nullable()->index();
            $table->string('sales_order_number')->nullable();
            $table->string('delivery_batch')->nullable();
            $table->string('delivery_type')->nullable();
            $table->string('dr_no')->nullable()->index();
            $table->string('sales_invoice_no')->nullable();

            $table->string('customer_code')->nullable()->index();
            $table->string('customer_name')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('branch')->nullable();
            $table->string('sales_rep')->nullable();
            $table->string('sales_representative')->nullable();
            $table->string('sales_executive')->nullable();
            $table->string('po_number')->nullable();

            $table->date('request_delivery_date')->nullable();
            $table->string('status')->default('Pending');
            $table->string('plate_no')->nullable();
            $table->text('additional_instructions')->nullable();
            $table->string('attachment')->nullable();

            // Approval workflow
            $table->string('approval_status')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_by_user')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Edit-request workflow
            $table->boolean('edit_requested')->default(false);
            $table->string('edit_requested_by')->nullable();
            $table->timestamp('edit_requested_at')->nullable();
            $table->boolean('edit_approved')->default(false);
            $table->string('edit_approved_by')->nullable();
            $table->timestamp('edit_approved_at')->nullable();

            // Pull-out workflow
            $table->boolean('is_pulled_out')->default(false);
            $table->string('pulled_out_by')->nullable();
            $table->timestamp('pulled_out_at')->nullable();
            $table->text('pullout_reason')->nullable();

            $table->string('created_by')->nullable();

            // Denormalised single-item summary columns used by list views
            $table->decimal('quantity', 15, 3)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('item_code')->nullable();
            $table->text('item_description')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
