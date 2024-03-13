<?php

namespace App\laminas;

use App\Benchmark\Params;
use App\DatabaseConfig;
use App\User;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;

class LaminasQueryBuilderConnection
{
    use User;

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
        $userId    = (int)$result->current()["user_id"];

        $params->addParam('userId', $userId);

        return $params;
    }

    public static function prepareForDelete(Params $params): Params
    {
        $params = self::connect($params);
        /** @var Sql $sql */
        $sql = $params->getParam('laminasSql');
        $iterations = $params->getParam('iterations');

        $select    = $sql->select('users_user_id_seq');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();
        $minUserId = (int)$result->current()["last_value"];

        for ($i = 0; $i < $iterations; $i++) {
            // Build the SQL insert statement
            $insert = $sql->insert('users');
            $insert->values(self::fake());

            // Execute the insert statement
            $statement = $sql->prepareStatementForSqlObject($insert);
            $statement->execute();
        }
        $params->addParam('minUserId', $minUserId + 1);
        
        return $params;
    }
}