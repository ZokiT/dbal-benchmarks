<?php

namespace App\laminas;

use App\laminas\Models\User;
use Laminas\Db\Sql\Sql;
use Laminas\Hydrator\ClassMethodsHydrator;

class LaminasModelInsert
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

}