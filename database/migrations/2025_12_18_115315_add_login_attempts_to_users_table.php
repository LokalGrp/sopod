<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIXED 2026-08-15.
 *
 * This migration was an empty stub — both up() and down() had a bare `//` in
 * the closure — yet UserController::login reads and increments
 * users.login_attempts on every sign-in. The column existed only in production,
 * so login threw "Unknown column 'login_attempts'" (500) on any fresh install.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'login_attempts')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedInteger('login_attempts')->default(0)->after('remember_token');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'login_attempts')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('login_attempts');
            });
        }
    }
};
