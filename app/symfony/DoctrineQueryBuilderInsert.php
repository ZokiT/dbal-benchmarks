<?php

namespace App\symfony;

use App\User;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineQueryBuilderInsert
{
    use User;

    /**
     * @throws Exception
     */
    public static function insert(QueryBuilder $queryBuilder): void {
        $queryBuilder->insert('users')
            ->values(self::fake())
            ->executeQuery(); // Execute the query
    }
}