<?php

namespace App\laminas;

use App\Benchmark\Benchmark;
use App\Benchmark\Params;
use App\DatabaseConfig;
use App\laminas\Models\User;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\Hydrator\ClassMethodsHydrator;

class LaminasSqlConnection
{
    public static function connect(Params $params): Params
    {
        $adapter = new Adapter(DatabaseConfig::getLaminasDatabaseConfig());
        $params->addParam('laminasSql', new Sql($adapter));
        return $params;
    }

    public static function connectForUpdate(Params $params): Params
    {
        $params = self::connect($params);
        $sql = $params->getParam('laminasSql');

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
        $params->addParam('user', $resultSet->current());

        return $params;
    }
}