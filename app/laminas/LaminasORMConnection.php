<?php

namespace App\laminas;

use App\Benchmark\Benchmark;
use App\Benchmark\Params;
use App\DatabaseConfig;
use App\laminas\Models\User;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\ClassMethodsHydrator;

class LaminasORMConnection
{
    public static function connect(Params $params): Params
    {
        $adapter = new Adapter(DatabaseConfig::getLaminasDatabaseConfig());
        $hydrator = new ClassMethodsHydrator();
        $resultSet = new HydratingResultSet(
            $hydrator,
            new User()
        );

        $tableGateway = new TableGateway('users', $adapter, null, $resultSet);
        // here add the hydrator
        $params->addParam('laminasTableGateway', $tableGateway);

        return $params;
    }

    public static function connectForUpdate(Params $params): Params
    {
        $params = self::connect($params);
        $tableGateway = $params->getParam('laminasTableGateway');

        $resultSet = $tableGateway->select(function (Select $select) {
            $select->limit(1);
        });
        $userId = $resultSet->current()->userId;
        $params->addParam('userId', $userId);

        return $params;
    }

    public static function prepareForDelete(Params $params): Params
    {
        $params = self::connect($params);
        $tableGateway = $params->getParam('laminasTableGateway');
        $iterations = $params->getParam('iterations');

        $hydrator = new ClassMethodsHydrator(true, false);
        $user = $hydrator->hydrate(User::fake(), new User());
        $tableGateway->insert($hydrator->extract($user));

        $resultSet = $tableGateway->select(function (Select $select) {
            $select->order('user_id DESC')->limit(1);
        });
        $params->addParam('minUserId', $resultSet->current()->userId);

        for ($i = 1; $i < $iterations; $i++) {
            $user = $hydrator->hydrate(User::fake(), new User());
            $tableGateway->insert($hydrator->extract($user));
        }

        return $params;
    }
}