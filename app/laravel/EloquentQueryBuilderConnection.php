<?php

namespace App\laravel;

use App\Benchmark\Benchmark;
use Illuminate\Database\Query\Builder;

class EloquentQueryBuilderConnection extends BaseConnection
{
    public static function connect(Benchmark $benchmark): Builder
    {
        $capsule = self::prepareCapsule();

        // Do not boot Eloquent in this case
        // Create a new query builder instance

        return new Builder($capsule->getConnection());
    }

    public static function connectForUpdate(Benchmark $benchmark): array
    {
        $builder = self::connect($benchmark);
        $user = $builder->select('user_id')->from('users')->first();

        return [$builder, $user->user_id];
    }
}