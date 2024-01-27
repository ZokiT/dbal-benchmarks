<?php

namespace App\laravel;

use App\laravel\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EloquentQueryBuilder
{
    public static function insert(Builder $queryBuilder): void {
        $queryBuilder->from('users')->insert(User::fakeWithQuotes());
    }

    public static function select(Builder $queryBuilder): void {
        $queryBuilder->select()
            ->from('users')
            ->where('is_active', '=', 'true')
            ->get()
            ->first();
    }

    public static function update(array $params): void {
        /** @var Builder $queryBuilder */
        $queryBuilder = $params[0];
        $userId = $params[1];

        // Perform the update query
        $queryBuilder->from('users')
            ->where('user_id', $userId)
            ->update(['email' => "'" . uniqid() . '@eloquent_update_example.com' . "'"]);
    }
}