<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ar_adjustments', function (Blueprint $table) {
            if (! Schema::hasColumn('ar_adjustments', 'receiving_report_id')) {
                $table->unsignedBigInteger('receiving_report_id')->nullable()->after('gl_account_id');
            }
        });

        // FIXED 2026-08-15: the constraint was added unconditionally, but
        // `receiving_reports` is created by a later migration, so this aborted
        // the run on a fresh install. The column is what the application reads;
        // the constraint is added only once its target exists.
        if (Schema::hasTable('receiving_reports')) {
            Schema::table('ar_adjustments', function (Blueprint $table) {
                $table->foreign('receiving_report_id')->references('id')->on('receiving_reports')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('ar_adjustments', function (Blueprint $table) {
            $table->dropForeign(['receiving_report_id']);
            $table->dropColumn('receiving_report_id');
        });
    }
};
