<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed migration — see 2025_10_15_000001_create_customers_table.
 *
 * App\Models\User lists is_locked / locked_at / locked_by in $fillable and the
 * account-lock flow reads them, but no migration ever created them.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_locked')) {
                $table->boolean('is_locked')->default(false);
            }
            if (! Schema::hasColumn('users', 'locked_at')) {
                $table->timestamp('locked_at')->nullable();
            }
            if (! Schema::hasColumn('users', 'locked_by')) {
                $table->string('locked_by')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['is_locked', 'locked_at', 'locked_by'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
