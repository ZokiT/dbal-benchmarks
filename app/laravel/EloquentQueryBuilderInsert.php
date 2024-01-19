<?php

namespace App\laravel;

use App\laravel\Models\User;
use Illuminate\Database\Query\Builder;

class EloquentQueryBuilderInsert
{
    public static function insert(Builder $queryBuilder): void {
        $queryBuilder->from('users')->insert(User::fake());
    }
}