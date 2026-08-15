<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_receiving_report_items', function (Blueprint $table) {
            $table->id();
            // The auto-generated constraint name
            // "supplier_receiving_report_items_supplier_receiving_report_id_foreign"
            // is 66 chars and exceeds MySQL's 64-char identifier limit, which made
            // this migration fail on a fresh database. Name it explicitly instead.
            $table->unsignedBigInteger('supplier_receiving_report_id');
            $table->foreign('supplier_receiving_report_id', 'srr_items_srr_id_foreign')
                  ->references('id')->on('supplier_receiving_reports')
                  ->onDelete('cascade');
            $table->integer('item_no');
            $table->string('item_code')->nullable();
            $table->string('item_description')->nullable();
            $table->string('brand')->nullable();
            $table->integer('no_of_boxes')->default(0);
            $table->decimal('net_weight_pd', 12, 2)->default(0);
            $table->date('expiry_date')->nullable();
            $table->string('pallet_no')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_receiving_report_items');
    }
};
