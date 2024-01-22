<?php

namespace App\laravel;

use App\laravel\Models\User;
use Illuminate\Database\Query\Builder;

class EloquentQueryBuilder
{
    public static function insert(Builder $queryBuilder): void {
        $queryBuilder->from('users')->insert(User::fakeWithQuotes());
    }

    public static function select(Builder $queryBuilder): void {
        $queryBuilder->select()
            ->from('users')
            ->where('is_active', '=', 'true')
            ->first();
    }
}