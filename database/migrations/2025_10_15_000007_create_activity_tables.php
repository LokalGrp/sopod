<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration — see 2025_10_15_000001_create_customers_table.
 *
 * activities comes from App\Models\Activity. activity_log has no model; its
 * columns are taken from the DB::table('activity_log') insert in
 * PaymentController (user_id, user_name, action, description, created_at).
 * That insert is already wrapped in a try/catch, but the table should exist.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->id();
                $table->string('user_name')->nullable();
                $table->string('action')->nullable();
                $table->string('item')->nullable();
                $table->string('target')->nullable();
                $table->string('type')->nullable();
                $table->text('message')->nullable();
                $table->unsignedBigInteger('sales_order_id')->nullable()->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('activity_log')) {
            Schema::create('activity_log', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('user_name')->nullable();
                $table->string('action')->nullable()->index();
                $table->text('description')->nullable();
                // Written explicitly by the caller; updated_at is never set.
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('activities');
    }
};
