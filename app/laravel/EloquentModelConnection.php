<?php

namespace App\laravel;

use App\Benchmark\Params;
use App\laravel\Models\User;

class EloquentModelConnection extends BaseConnection
{
    public static function connect(Params $params): Params
    {
        $capsule = self::prepareCapsule();
        $capsule->bootEloquent();

        return $params;
    }

    public static function connectForUpdate(Params $params): Params
    {
        $params = self::connect($params);
        $user = User::first();
        $params->addParam('user', $user);

        return $params;
    }
}