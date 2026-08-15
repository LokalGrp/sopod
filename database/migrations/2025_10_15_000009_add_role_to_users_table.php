<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed migration — see 2025_10_15_000001_create_customers_table.
 *
 * users.role existed only in the production database. Three later migrations
 * depend on it — add_roles_column_to_users_table places `roles` after it,
 * add_full_aging_access_to_users_table places its column after it, and
 * consolidate_user_roles reads and rewrites it — so all three failed on a
 * fresh install. App\Models\User also lists 'role' in $fillable and falls back
 * to it in the roles accessor.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
