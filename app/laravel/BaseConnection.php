<?php

namespace App\laravel;

use App\DatabaseConfig;
use Illuminate\Database\Capsule\Manager as Capsule;

class BaseConnection
{

    public static function prepareCapsule(DatabaseConfig $config): Capsule
    {
        $capsule = new Capsule;

        $capsule->addConnection($config->getLaravelDatabaseConfig());

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        return $capsule;
    }
}