<?php

namespace App\laravel;

use App\Benchmark\Benchmark;
use App\Benchmark\Params;
use App\laravel\Models\User;
use Illuminate\Database\Query\Builder;

class EloquentQueryBuilderConnection extends BaseConnection
{
    public static function connect(Params $params): Params
    {
        $capsule = self::prepareCapsule();
        $builder = new Builder($capsule->getConnection());
        // Do not boot Eloquent in this case
        // Create a new query builder instance

        $params->addParam('eloquentBuilder', $builder);
        return $params;
    }

    public static function connectForUpdate(Params $params): Params
    {
        $params = self::connect($params);
        $builder = $params->getParam('eloquentBuilder');
        $user = $builder->select('user_id')->from('users')->first();
        $params->addParam('userId', $user->user_id);

        return $params;
    }

    public static function prepareForDelete(Params $params): Params
    {
        $params = self::connect($params);
        $iterations = $params->getParam('iterations');
        $builder = $params->getParam('eloquentBuilder');
        $userSequence = $builder->select('last_value')->from('users_user_id_seq')->first();

        for ($i = 0; $i < $iterations; $i++) {
            $builder->from('users')->insert(User::fakeWithQuotes());
        }
        $params->addParam('minUserId', $userSequence->last_value + 1);

        return $params;
    }
}