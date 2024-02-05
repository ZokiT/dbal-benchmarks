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
        $sql = $params->getParam('laminasSql');
//        $sql->table('users')->insert(self::fake());

        $insert = $sql->insert('users');
        $insert->values(self::fake());

        // Execute the insert statement
        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();

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
    }

    public static function update(Params $params): Params {

        /** @var Sql $sql */
        $sql = $params->getParam('laminasSql');

        $update = $sql->update('users');
        $update->set([
            'email' => uniqid() . '@laminas_orm@example.com',
        ]);
        $update->where(['user_id' => $params->getParam('userId')]);

        $updateStatement = $sql->prepareStatementForSqlObject($update);
        $updateStatement->execute();

        return $params;
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