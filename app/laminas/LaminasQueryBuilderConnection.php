<?php

namespace App\laminas;

use App\DatabaseConfig;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\TableGateway;

class LaminasQueryBuilderConnection
{
    public static function connect(DatabaseConfig $config): TableGateway
    {
        $adapter = new Adapter($config->getLaminasDatabaseConfig());

        return new TableGateway('users', $adapter);
    }
}