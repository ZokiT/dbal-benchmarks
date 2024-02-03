<?php

namespace App\laminas;

use App\Benchmark\Params;
use App\laminas\Models\User;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\ClassMethodsHydrator;

class LaminasORM
{
    public static function insert(Params $params): Params {
        $tableGateway = $params->getParam('laminasTableGateway');
        $hydrator = new ClassMethodsHydrator(true, false);
        $user = $hydrator->hydrate(User::fake(), new User());
        $tableGateway->insert($hydrator->extract($user));

        return $params;
    }

    public static function select(Params $params): Params {
        $limit = $params->getParam('selectLimit');
        $tableGateway = $params->getParam('laminasTableGateway');
        $resultSet = $tableGateway->select(function (Select $select) use ($limit) {
            $select->where(['is_active' => 'true'])->order('user_id DESC')->limit($limit);
        });
        // this is the first returned user as result
        $resultSet->current()->userId;

        return $params;
    }

    public static function update(Params $params): Params {
        /** @var TableGateway $tableGateway */
        $tableGateway = $params->getParam('laminasTableGateway');
        $userId = $params->getParam('userId');
        $tableGateway->update(
            ['email' => uniqid() . '@laminas_orm@example.com'],
            ['user_id' => $userId]
        );

        return $params;
    }

    public static function delete(Params $params): Params {
        $tableGateway = $params->getParam('laminasTableGateway');
        $userId = $params->getParam('minUserId');

        $tableGateway->delete((new Where())->equalTo('user_id', $userId));
        $params->addParam('minUserId', $userId + 1);

        return $params;
    }

}