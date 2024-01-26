<?php

namespace App\laminas;

use App\laminas\Models\User;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\Hydrator\ClassMethodsHydrator;

class LaminasModel
{
    public static function insert(Sql $sql): void {

        $hydrator = new ClassMethodsHydrator();
        // Hydrate a User object with data
        $user = $hydrator->hydrate(User::fake(), new User());

        // Build the SQL insert statement
        $insert = $sql->insert('users');
        $insert->values($hydrator->extract($user));

        // Execute the insert statement
        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    public static function select(Sql $sql): void {

        $select    = $sql->select('users');
        $select->where(['is_active' => 'false']);
        $select->limit(1);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        $hydrator = new ClassMethodsHydrator();
        $resultSet = new HydratingResultSet(
            $hydrator,
            new User()
        );
        $resultSet->initialize($result);

        // This is the return User
        $resultSet->current();
    }

}