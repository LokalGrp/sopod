<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * FIXED 2026-08-15.
 *
 * The dropForeign() was unconditional, but on a fresh database the constraint
 * it names has not been created, so the run aborted with
 * "Can't DROP 'customer_terms_history_customer_code_foreign'".
 *
 * Both the drop and the re-add are now guarded, so this upgrades the constraint
 * where it exists and installs it where it doesn't — ending in the same state
 * either way: ON UPDATE CASCADE so renaming a customer_code follows through.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customer_terms_history') || ! Schema::hasTable('customers')) {
            return;
        }

        if ($this->constraintExists('customer_terms_history_customer_code_foreign')) {
            Schema::table('customer_terms_history', function (Blueprint $table) {
                $table->dropForeign('customer_terms_history_customer_code_foreign');
            });
        }

        Schema::table('customer_terms_history', function (Blueprint $table) {
            $table->foreign('customer_code')
                  ->references('customer_code')
                  ->on('customers')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('customer_terms_history')) {
            return;
        }

        if ($this->constraintExists('customer_terms_history_customer_code_foreign')) {
            Schema::table('customer_terms_history', function (Blueprint $table) {
                $table->dropForeign(['customer_code']);
            });
        }

        Schema::table('customer_terms_history', function (Blueprint $table) {
            $table->foreign('customer_code')
                  ->references('customer_code')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }

    private function constraintExists(string $name): bool
    {
        return DB::table('information_schema.table_constraints')
            ->where('constraint_schema', DB::getDatabaseName())
            ->where('table_name', 'customer_terms_history')
            ->where('constraint_name', $name)
            ->exists();
    }
};
