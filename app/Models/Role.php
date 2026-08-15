<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * FIXED 2026-08-15.
 *
 * This file previously contained a verbatim copy of
 * Database\Seeders\DatabaseSeeder — same namespace, same class name — so
 * App\Models\Role did not exist, and loading both files fataled with
 * "Cannot declare class Database\Seeders\DatabaseSeeder, because the name is
 * already in use". That broke `php artisan db:seed` outright. Composer also
 * reported it as a PSR-4 violation on every `composer install`.
 *
 * Restored to the model the filename and DatabaseSeeder both expect.
 */
class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
    ];
}
