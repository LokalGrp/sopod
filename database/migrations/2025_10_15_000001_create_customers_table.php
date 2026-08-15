<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration.
 *
 * The customers table had no create migration in this repo — it existed only in
 * the production database. Columns here are the base set derived from
 * App\Models\Customer::$fillable, minus the ones added by later migrations
 * (is_active, is_locked, parent_customer_code), which still apply on top.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers')) {
            return;
        }

        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // customer_code is referenced as a foreign key target by
            // customer_terms_history, so it must carry a unique index.
            $table->string('customer_code')->unique();
            $table->string('customer_name');
            $table->string('business_style')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('branch')->nullable();
            $table->string('tin_no')->nullable();

            $table->string('status')->default('Active');
            $table->string('customer_group')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('currency')->default('PHP');

            $table->string('telephone_1')->nullable();
            $table->string('telephone_2')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('name_of_contact')->nullable();

            $table->decimal('whtrate', 8, 4)->nullable();
            $table->string('whtcode')->nullable();
            $table->boolean('require_si')->default(false);
            $table->string('ar_type')->nullable();
            $table->string('collection_terms')->nullable();
            $table->string('sales_rep')->nullable();
            // Not in $fillable, but AgingReportController selects
            // customers.sales_exec_2 when building AR aging reports.
            $table->string('sales_exec_2')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->string('assigned_bank')->nullable();
            $table->boolean('is_flagged')->default(false);

            $table->timestamps();

            $table->index('customer_name');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
