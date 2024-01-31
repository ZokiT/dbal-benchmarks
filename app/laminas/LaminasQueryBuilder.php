<?php

namespace App\laminas;

use App\Benchmark\Params;
use App\User;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\TableGateway\TableGateway;

class LaminasQueryBuilder
{
    use User;
    public static function insert(Params $params): Params {
//        $tableGateway->insert(self::fakeWithQuotes());

        return $params;
    }

    public static function select(Params $params): Params {
        $limit = $params->getParam('selectLimit');
        $sql = $params->getParam('laminasSql');

        $select    = $sql->select('users');
        $select->where(['is_active' => 'true']);
        $select->limit($limit);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        // This is the returned User
        $result->current();

        return $params;
//        $limit = $params->getParam('selectLimit');
//        $rowSet = $tableGateway->select(function (Select $select) {
//            $select->where->equalTo('is_active', 'true');
//            $select->limit($limit);
//        });
//
//        // the result is Traversable, Countable object, so we need to actually get them
//        $rowSet->current();
    }

    public static function update(Params $params): Params {

        /** @var Sql $sql */
        $sql = $params->getParam('laminasSql');
        /** @var User $user */
        $userId = $params->getParam('userId');

        $user->setEmail(uniqid() . '@laminas_orm@example.com');
        $update = $sql->update('users');
        $update->set([
            'email' => $user->getEmail(),
        ]);
        $update->where(['user_id' => $userId]);

        $updateStatement = $sql->prepareStatementForSqlObject($update);
        $updateStatement->execute();

        return $params;
//        /** @var TableGateway $tableGateway */
//        $tableGateway = $params[0];
//        $userId = $params[1];
//
//        // Perform the update operation
//        $tableGateway->update(
//            ['email' =>  uniqid() . "@laminas_update_example.com"],
//            ['user_id' => $userId]
//        );
    }

    public static function delete(Params $params): Params {

        /** @var Sql $sql */
        $sql = $params->getParam('laminasSql');
        /** @var User $user */
        $minUserId = $params->getParam('minUserId');

        $delete = $sql->delete('users')->where(['user_id = ?' => $minUserId]);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();

        $params->addParam('minUserId', $minUserId + 1);
        return $params;
    }
}