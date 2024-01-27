<?php

namespace App\laravel;

use App\DatabaseConfig;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EloquentQueryBuilderConnection extends BaseConnection
{
    public static function connect(DatabaseConfig $config): Builder
    {
        $capsule = self::prepareCapsule($config);

        // Do not boot Eloquent in this case
        // Create a new query builder instance

        return new Builder($capsule->getConnection());
    }

    public static function connectForUpdate(DatabaseConfig $config): array
    {
        $builder = self::connect($config);
        $user = $builder->select('user_id')->from('users')->first();

        return [$builder, $user->user_id];
    }
}