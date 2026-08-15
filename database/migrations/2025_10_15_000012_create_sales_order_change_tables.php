<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration — see 2025_10_15_000001_create_customers_table.
 *
 * Backs App\Models\SalesOrderChange and App\Models\ChangeNotification, which
 * drive the sales-order changelog and its notification badge. Both tables
 * existed only in production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sales_order_changes')) {
            Schema::create('sales_order_changes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sales_order_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('field_changed')->nullable();
                $table->text('old_value')->nullable();
                $table->text('new_value')->nullable();
                $table->string('change_type')->nullable();
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('change_notifications')) {
            Schema::create('change_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('sales_order_change_id')->nullable()->index();
                $table->boolean('is_read')->default(false)->index();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('change_notifications');
        Schema::dropIfExists('sales_order_changes');
    }
};
