<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * FIXED 2026-08-15.
 *
 * The unique index this drops was never created by any migration — it existed
 * only in the production database. On a fresh install the unconditional
 * dropUnique() failed with "Can't DROP 'unique_collection_receipt_number'",
 * aborting the run. Guarded so it drops the index where it exists and is a
 * no-op where it never did.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments') || ! $this->indexExists()) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique('unique_collection_receipt_number');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('payments') || $this->indexExists()) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->unique('collection_receipt_number', 'unique_collection_receipt_number');
        });
    }

    private function indexExists(): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'payments')
            ->where('index_name', 'unique_collection_receipt_number')
            ->exists();
    }
};
