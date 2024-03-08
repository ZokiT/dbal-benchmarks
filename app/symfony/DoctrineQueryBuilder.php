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

    /**
     * @throws Exception
     */
    public static function complexQuerySelect(Params $params): Params {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $params->getParam('doctrineQueryBuilder');
        $limit = $params->getParam('selectLimit');

        $queryBuilder->select('u.username AS user_name')
           ->select('o.order_id as order_id')
           ->select('o.order_date as order_date')
           ->select('o.status AS order_status')
           ->addSelect('SUM(od.quantity) AS total_ordered_quantity')
           ->addSelect('AVG(p.price) AS avg_product_price')
           ->addSelect('MAX(p.price) AS max_product_price')
           ->addSelect('COUNT(DISTINCT p.product_id) AS unique_products_ordered')
           ->from('users', 'u')
           ->innerJoin('u', 'orders', 'o', 'u.user_id = o.user_id')
           ->innerJoin('o', 'order_details', 'od', 'o.order_id = od.order_id')
           ->innerJoin('od', 'products', 'p', 'od.product_id = p.product_id')
           ->leftJoin('u', 'addresses', 'a', 'u.user_id = a.user_id')
           ->where('u.is_active = :isActive')
           ->andWhere('o.status IN (:status1, :status2)')
           ->setParameter('isActive', true)
           ->setParameter('status1', 'completed')
           ->setParameter('status2', 'pending')
           ->groupBy('u.username, o.order_id, o.order_date, o.status')
           ->having('SUM(od.quantity)  > :quantity')
           ->setParameter('quantity', 5)
           ->orderBy('o.order_date', 'DESC')
           ->setMaxResults($limit);

        $queryBuilder->executeQuery()->fetchAllAssociative();
        $queryBuilder->resetQueryParts();

        return $params;
    }
}