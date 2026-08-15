<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // FIXED 2026-08-15: duplicates 2026_05_06_112517_add_esignature_to_users_table,
        // which adds the same column earlier the same day. Unguarded, this failed
        // with "Duplicate column name 'esignature'" on a fresh install.
        if (Schema::hasColumn('users', 'esignature')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('esignature')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'esignature')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('esignature');
        });
    }
};
