<?php

namespace App\laravel;

use App\DatabaseConfig;
use App\laravel\Models\User;

class EloquentModelConnection extends BaseConnection
{
    public static function connect(DatabaseConfig $config)
    {
        $capsule = self::prepareCapsule($config);
        $capsule->bootEloquent();
    }

    public static function connectForUpdate(DatabaseConfig $config)
    {
        self::connect($config);

        return User::first();
    }
}