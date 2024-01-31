<?php

namespace App\symfony;

use App\Benchmark\Params;
use App\User;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineQueryBuilder
{
    use User;

    /**
     * @throws Exception
     */
    public static function insert(Params $params): Params {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $params->getParam('doctrineQueryBuilder');
        $queryBuilder->insert('users')
            ->values(self::fakeWithQuotes())
            ->executeQuery(); // Execute the query

        return $params;
    }

    /**
     * @throws Exception
     */
    public static function select(Params $params): Params {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $params->getParam('doctrineQueryBuilder');
        $limit = $params->getParam('selectLimit');
        $queryBuilder->select('u.*')
            ->from('users', 'u')
            ->where('u.is_active = :active')
            ->setParameter('active', true)
            ->setMaxResults($limit);
        $queryBuilder->executeQuery()->fetchAllAssociative();

        return $params;
    }

    /**
     * @param Params $params
     * @return Params
     * @throws Exception
     */
    public static function update(Params $params): Params {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $params->getParam('doctrineQueryBuilder');
        $userId = $params->getParam('userId');

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

        return $params;
    }

    /**
     * @param Params $params
     * @return Params
     * @throws Exception
     */
    public static function delete(Params $params): Params {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $params->getParam('doctrineQueryBuilder');
        $queryBuilder
            ->delete('users', 'u')
            ->where('u.user_id = :user_id')
            ->setParameter('user_id', $params->getParam('minUserId'));
        $queryBuilder->executeQuery();
        $params->addParam('minUserId', $params->getParam('minUserId') + 1);

        return $params;
    }
}