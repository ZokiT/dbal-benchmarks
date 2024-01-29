<?php

namespace App\laminas;

use App\Benchmark\Benchmark;
use App\DatabaseConfig;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\TableGateway\TableGateway;

class LaminasQueryBuilderConnection
{
    public static function connect(Benchmark $benchmark): TableGateway
    {
        $adapter = new Adapter(DatabaseConfig::getLaminasDatabaseConfig());

        return new TableGateway('users', $adapter);
    }

    public static function connectForUpdate(Benchmark $benchmark): array
    {
        $tableGateway = self::connect($benchmark);

        $resultSet = $tableGateway->select(function (Select $select) {
            $select->limit(1);
        });
        $userId = (int)$resultSet->current()->offsetGet('user_id');

        return [$tableGateway, $userId];
    }
}