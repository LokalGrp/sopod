<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed migration — see 2025_10_15_000001_create_customers_table.
 *
 * create_payments_table (2026_01_07) omits ten columns that PaymentController
 * reads and that four later migrations anchor their ->after() clauses to.
 * `status` and `ewt` are the important ones: nothing creates them, yet
 * add_confirmation_fields_to_payments_table positions `confirmed` after
 * `status`, and add_discount_to_payments_table positions `discount_rate` after
 * `ewt`, so both aborted the run on a fresh database.
 *
 * Runs immediately after the create migration so those anchors resolve.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            // Anchor columns for later migrations
            if (! Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('posted')->index();
            }
            if (! Schema::hasColumn('payments', 'ewt')) {
                $table->decimal('ewt', 15, 2)->default(0);
            }
            // Summed by the treasury confirmation and collection-report screens.
            if (! Schema::hasColumn('payments', 'gross_amount')) {
                $table->decimal('gross_amount', 15, 2)->default(0);
            }
            if (! Schema::hasColumn('payments', 'check_amount')) {
                $table->decimal('check_amount', 15, 2)->default(0);
            }

            // Referenced by PaymentController but never created
            if (! Schema::hasColumn('payments', 'dr_no')) {
                $table->string('dr_no')->nullable()->index();
            }
            if (! Schema::hasColumn('payments', 'invoice_no')) {
                $table->string('invoice_no')->nullable()->index();
            }
            if (! Schema::hasColumn('payments', 'payment_date')) {
                $table->date('payment_date')->nullable();
            }
            if (! Schema::hasColumn('payments', 'bank')) {
                $table->string('bank')->nullable();
            }
            if (! Schema::hasColumn('payments', 'branch')) {
                $table->string('branch')->nullable();
            }
            if (! Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (! Schema::hasColumn('payments', 'reference_no')) {
                $table->string('reference_no')->nullable();
            }
            if (! Schema::hasColumn('payments', 'payment_means_data')) {
                $table->json('payment_means_data')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            foreach ([
                'status', 'ewt', 'gross_amount', 'check_amount', 'dr_no', 'invoice_no',
                'payment_date', 'bank', 'branch', 'payment_method', 'reference_no',
                'payment_means_data',
            ] as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
