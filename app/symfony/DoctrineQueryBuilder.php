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

        // use getFirstResult() maybe

        $queryBuilder->executeQuery()->fetchAllAssociative();
    }

    /**
     * @param array $params
     * @throws Exception
     */
    public static function update(array $params): void {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $params[0];
        $userId = $params[1];

        $queryBuilder
            ->update('users')
            ->set('email', ':email')
            ->where('user_id = :user_id')
            ->setParameters([
                'email' => uniqid() . '@doctrine_updated.com',
                'user_id' => $userId
            ]);

        $queryBuilder->executeQuery();
        $queryBuilder->resetQueryPart('set');
    }
}