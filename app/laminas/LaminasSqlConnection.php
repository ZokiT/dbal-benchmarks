<?php

namespace App\laminas;

use App\DatabaseConfig;
use App\laminas\Models\User;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\Hydrator\ClassMethodsHydrator;

class LaminasSqlConnection
{
    public static function connect(DatabaseConfig $config): Sql
    {
        $adapter = new Adapter($config->getLaminasDatabaseConfig());

        return new Sql($adapter);
    }

    public static function connectForUpdate(DatabaseConfig $config): array
    {
        $sql = self::connect($config);

        $select    = $sql->select('users');
        $select->limit(1);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        $hydrator = new ClassMethodsHydrator();
        $resultSet = new HydratingResultSet(
            $hydrator,
            new User()
        );
        $resultSet->initialize($result);

        return [$sql, $resultSet->current()];
    }
}