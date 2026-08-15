<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration — see 2025_10_15_000001_create_customers_table.
 *
 * ar_ledger and sales_invoices come from their Eloquent models. ar_transactions
 * has no model; its columns are taken from the DB::table('ar_transactions')
 * inserts in AgingReportController::logARTransaction and ArAdjustmentController
 * (which write disjoint sets, so both are represented and nullable).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ar_ledger')) {
            Schema::create('ar_ledger', function (Blueprint $table) {
                $table->id();
                $table->string('customer_code')->nullable()->index();
                $table->unsignedBigInteger('invoice_id')->nullable()->index();
                $table->string('transaction_type')->nullable();
                $table->decimal('debit', 15, 2)->default(0);
                $table->decimal('credit', 15, 2)->default(0);
                $table->dateTime('transaction_date')->nullable();
                $table->string('reference_no')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('sales_invoices')) {
            Schema::create('sales_invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_no')->nullable()->index();
                $table->unsignedBigInteger('so_id')->nullable()->index();
                $table->unsignedBigInteger('dr_id')->nullable()->index();
                $table->string('customer_code')->nullable()->index();
                $table->dateTime('invoice_date')->nullable();
                $table->dateTime('due_date')->nullable();
                $table->decimal('invoice_amount', 15, 2)->default(0);
                $table->decimal('cwt_amount', 15, 2)->default(0);
                $table->decimal('net_of_cwt', 15, 2)->default(0);
                $table->string('ar_status')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ar_transactions')) {
            Schema::create('ar_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ar_aging_id')->nullable()->index();
                $table->string('customer_code')->nullable()->index();
                $table->string('transaction_type')->nullable();
                $table->decimal('amount', 15, 2)->nullable();
                $table->decimal('gross_amount', 15, 2)->nullable();
                $table->dateTime('transaction_date')->nullable();
                $table->string('reference_number')->nullable();
                $table->string('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ar_transactions');
        Schema::dropIfExists('sales_invoices');
        Schema::dropIfExists('ar_ledger');
    }
};
