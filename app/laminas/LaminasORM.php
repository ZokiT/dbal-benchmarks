<?php

namespace App\laminas;

use App\Benchmark\Params;
use App\laminas\Models\User;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\Hydrator\ClassMethodsHydrator;

class LaminasORM
{
    public static function insert(Params $params): void {

        // fix it using table gateway
        $hydrator = new ClassMethodsHydrator(true, false);
        // Hydrate a User object with data
        $user = $hydrator->hydrate(User::fake(), new User());

        $sql = $params->getParam('laminasSql');

        // Build the SQL insert statement
        $insert = $sql->insert('users');
        $insert->values($hydrator->extract($user));

        // Execute the insert statement
        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    public static function select(Params $params): void {
        // fix it using table gateway
        $limit = $params->getParam('selectLimit');
        $sql = $params->getParam('laminasSql');
        $select    = $sql->select('users');
        $select->where(['is_active' => 'false']);
        $select->limit($limit);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        $hydrator = new ClassMethodsHydrator();
        $resultSet = new HydratingResultSet(
            $hydrator,
            new User()
        );
        $resultSet->initialize($result);

        // This is the returned User
        $resultSet->current();
    }

    public static function update(Params $params): void {
        // fix it using table gateway

        /** @var Sql $sql */
        $sql = $params->getParam('laminasSql');
        /** @var User $user */
        $user = $params->getParam('user');

        $user->setEmail(uniqid() . '@laminas_orm@example.com');
        $update = $sql->update('users');
        $update->set([
            'email' => $user->getEmail(),
        ]);
        $update->where(['user_id' => $user->userId]);

        $updateStatement = $sql->prepareStatementForSqlObject($update);
        $updateStatement->execute();
    }

}