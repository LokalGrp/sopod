<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration — see 2025_10_15_000001_create_customers_table.
 *
 * Base columns from App\Models\ArAging::$fillable, minus those added later
 * (collection_terms, ewt, annual, factoring*, others_*, check_amount, remarks).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ar_aging')) {
            return;
        }

        Schema::create('ar_aging', function (Blueprint $table) {
            $table->id();

            $table->date('aging_date')->nullable();
            $table->date('counter_date')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('record_date')->nullable();
            $table->date('due_date')->nullable();

            $table->string('invoice_no')->nullable()->index();
            $table->string('po_no')->nullable();
            $table->string('dr_no')->nullable()->index();

            $table->string('customer_code')->nullable()->index();
            $table->string('client_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('sales_executive')->nullable();
            $table->string('se2')->nullable();
            $table->string('terms')->nullable();
            $table->string('sales_week_no')->nullable();

            $table->integer('age')->nullable();
            $table->string('age_category')->nullable();

            $table->decimal('invoice_amount', 15, 2)->default(0);
            $table->decimal('ar_adjustments', 15, 2)->default(0);
            $table->decimal('settled_invoice_amount', 15, 2)->default(0);
            $table->decimal('gross_ar_balance', 15, 2)->default(0);
            $table->decimal('net_ar', 15, 2)->default(0);
            $table->decimal('cwt', 15, 2)->default(0);
            $table->decimal('net_of_cwt', 15, 2)->default(0);
            $table->decimal('net_ar_balance', 15, 2)->default(0);
            $table->decimal('factored_ar_amount', 15, 2)->default(0);

            $table->string('status')->nullable()->index();
            $table->boolean('include_flag')->default(true);
            $table->string('ar_class')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ar_aging');
    }
};
