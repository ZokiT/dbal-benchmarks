<?php

namespace App\laravel;

use App\DatabaseConfig;

class EloquentModelConnection extends BaseConnection
{
    public static function connect(DatabaseConfig $config)
    {
        $capsule = self::prepareCapsule($config);
        $capsule->bootEloquent();
    }
}