<?php

namespace App\laminas;

use App\DatabaseConfig;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\TableGateway\TableGateway;

class LaminasQueryBuilderConnection
{
    public static function connect(DatabaseConfig $config): TableGateway
    {
        $adapter = new Adapter($config->getLaminasDatabaseConfig());

        return new TableGateway('users', $adapter);
    }

    public static function connectForUpdate(DatabaseConfig $config): array
    {
        $adapter = new Adapter($config->getLaminasDatabaseConfig());
        $tableGateway = new TableGateway('users', $adapter);

        $resultSet = $tableGateway->select(function (Select $select) {
            $select->limit(1);
        });
        $userId = (int)$resultSet->current()->offsetGet('user_id');

        return [$tableGateway, $userId];
    }
}