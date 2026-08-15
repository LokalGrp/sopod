<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed create migration — see 2025_10_15_000001_create_customers_table.
 *
 * Columns and types from App\Models\InHouseBomHouse ($fillable + $casts);
 * 'materials' is cast to array, so it is stored as JSON.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('inhouse_bom_houses')) {
            return;
        }

        Schema::create('inhouse_bom_houses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('bom_id')->nullable()->index();
            $table->string('house_number')->nullable();
            $table->string('house_name')->nullable();

            $table->double('loading_qty')->default(0);
            $table->double('livability')->default(0);
            $table->double('alw')->default(0);
            $table->double('fcr')->default(0);
            $table->integer('age_days')->nullable();
            $table->string('bpi')->nullable();
            $table->double('harvest_qty')->default(0);
            $table->double('total_kg')->default(0);
            $table->double('feed_req_kg')->default(0);
            $table->double('doc_cost')->default(0);

            $table->json('materials')->nullable();

            $table->double('total_cost')->default(0);
            $table->double('cost_per_kg')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inhouse_bom_houses');
    }
};
