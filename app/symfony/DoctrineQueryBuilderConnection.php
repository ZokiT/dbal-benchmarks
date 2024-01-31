<?php

namespace App\symfony;

use App\Benchmark\Benchmark;
use App\Benchmark\Params;
use App\DatabaseConfig;
use App\User;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineQueryBuilderConnection
{
    use User;

    /**
     * @throws Exception
     */
    public static function connect(Params $params): Params
    {
        $conn = DriverManager::getConnection(DatabaseConfig::getSymphonyDatabaseConfig());
        $queryBuilder = $conn->createQueryBuilder();
        $params->addParam('doctrineQueryBuilder', $queryBuilder);

        return $params;
    }

    /**
     * @throws Exception
     */
    public static function connectForUpdate(Params $params): Params
    {
        $params = self::connect($params);
        $queryBuilder = $params->getParam('doctrineQueryBuilder');

        $userId = $queryBuilder->select('u.user_id')
            ->from('users', 'u')->fetchOne();

        $params->addParam('userId', $userId);

        return $params;
    }

    /**
     * @throws Exception
     */
    public static function prepareForDelete(Params $params): Params
    {
        $params = self::connect($params);
        $iterations = $params->getParam('iterations');
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $params->getParam('doctrineQueryBuilder');
        $queryBuilder->select('us.last_value')
            ->from('users_user_id_seq', 'us');
        $firstUserId = $queryBuilder->executeQuery()->fetchOne();

        for ($i = 0; $i < $iterations; $i++) {
            $queryBuilder->insert('users')
                ->values(self::fakeWithQuotes())
                ->executeQuery();
        }
        $params->addParam('minUserId', $firstUserId + 1);

        return $params;
    }
}