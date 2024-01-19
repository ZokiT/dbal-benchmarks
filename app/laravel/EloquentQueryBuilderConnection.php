<?php

namespace App\laravel;

use App\DatabaseConfig;
use Illuminate\Database\Query\Builder;

class EloquentQueryBuilderConnection extends BaseConnection
{
    public static function connect(DatabaseConfig $config): Builder
    {
        $capsule = self::prepareCapsule($config);

        // Do not boot Eloquent in this case
        // Create a new query builder instance

        return new Builder($capsule->getConnection());
    }
}