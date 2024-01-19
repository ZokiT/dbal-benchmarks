<?php

namespace App\laminas;

use App\DatabaseConfig;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;

class LaminasSqlConnection
{
    public static function connect(DatabaseConfig $config): Sql
    {
        $adapter = new Adapter($config->getLaminasDatabaseConfig());

        return new Sql($adapter);
    }
}