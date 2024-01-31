<?php

namespace App\laravel;

use App\Benchmark\Params;
use App\laravel\Models\User;
use Illuminate\Database\Query\Builder;

class EloquentQueryBuilder
{
    public static function insert(Params $params): Params {
        $builder = $params->getParam('eloquentBuilder');
        $builder->from('users')->insert(User::fakeWithQuotes());

        return $params;
    }

    public static function select(Params $params): Params {
        $builder = $params->getParam('eloquentBuilder');
        $limit = $params->getParam('selectLimit');
        $builder->select()
            ->from('users')
            ->where('is_active', '=', 'true')
            ->limit($limit)
            ->get()
            ->all();

        return $params;
    }

    public static function update(Params $params): Params {
        /** @var Builder $builder */
        $builder = $params->getParam('eloquentBuilder');
        $userId = $params->getParam('userId');

        // Perform the update query
        $builder->from('users')
            ->where('user_id', $userId)
            ->update(['email' => "'" . uniqid() . '@eloquent_update_example.com' . "'"]);

        return $params;
    }

    public static function delete(Params $params): Params {
        /** @var Builder $builder */
        $builder = $params->getParam('eloquentBuilder');
        $builder->from('users')
            ->where('user_id', '=', $params->getParam('minUserId'))
            ->delete();
        $params->addParam('minUserId', $params->getParam('minUserId') + 1);

        return $params;
    }
}