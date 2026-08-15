<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed migration — see 2025_10_15_000001_create_customers_table.
 *
 * App\Models\AccountsPayableInvoice lists updated_by in $fillable, but no
 * migration created it.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('accounts_payable_invoices')
            || Schema::hasColumn('accounts_payable_invoices', 'updated_by')) {
            return;
        }

        Schema::table('accounts_payable_invoices', function (Blueprint $table) {
            $table->string('updated_by')->nullable();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('accounts_payable_invoices')
            || ! Schema::hasColumn('accounts_payable_invoices', 'updated_by')) {
            return;
        }

        Schema::table('accounts_payable_invoices', function (Blueprint $table) {
            $table->dropColumn('updated_by');
        });
    }
};
