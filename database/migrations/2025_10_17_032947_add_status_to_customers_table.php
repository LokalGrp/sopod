<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIXED 2026-08-15.
 *
 * This file previously contained a verbatim copy of the body of
 * 2025_10_14_035654_create_items_table — Schema::create('items', ...) — despite
 * its name. On any fresh database it aborted the whole migration run with
 * "Table 'items' already exists", which is why 39 later migrations never ran.
 *
 * Restored to the behaviour its name describes. The column now ships in
 * 2025_10_15_000001_create_customers_table, so this is a guarded no-op on a
 * fresh install and a genuine fix on any database predating that migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers') && ! Schema::hasColumn('customers', 'status')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('status')->default('Active')->after('tin_no');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'status')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
