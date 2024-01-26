<?php

namespace App\laravel;

use App\laravel\Models\User;

class EloquentModel
{
    public static function insert(): void {
        User::insert(User::fake());
    }

    public static function select(): void {
        User::where('is_active', true)->first();
    }
}