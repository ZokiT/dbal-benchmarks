<?php

namespace App\laravel;

use App\laravel\Models\User;

class EloquentModelInsert
{
    public static function insert(): void {
        User::insert(User::fake());
    }
}