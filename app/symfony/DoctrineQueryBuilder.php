<?php

namespace App\symfony;

use App\User;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineQueryBuilder
{
    use User;

    /**
     * @throws Exception
     */
    public static function insert(QueryBuilder $queryBuilder): void {
        $queryBuilder->insert('users')
            ->values(self::fakeWithQuotes())
            ->executeQuery(); // Execute the query
    }

    /**
     * @throws Exception
     */
    public static function select(QueryBuilder $queryBuilder): void {
        $queryBuilder->select('u.*')
            ->from('users', 'u')
            ->where('u.is_active = :active')
            ->setParameter('active', true)
            ->setMaxResults(1);

        $queryBuilder->executeQuery()->fetchAllAssociative();
    }
}