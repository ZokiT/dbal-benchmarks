<?php

namespace App\symfony;

use App\Benchmark\Benchmark;
use App\DatabaseConfig;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineQueryBuilderConnection
{
    /**
     * @throws Exception
     */
    public static function connect(Benchmark $benchmark): QueryBuilder
    {
        $conn = DriverManager::getConnection(DatabaseConfig::getSymphonyDatabaseConfig());

        // Return the query builder
        return $conn->createQueryBuilder();
    }

    /**
     * @throws Exception
     */
    public static function connectForUpdate(Benchmark $benchmark): array
    {
        $queryBuilder = self::connect($benchmark);

        $userId = $queryBuilder->select('u.user_id')
            ->from('users', 'u')->fetchOne();

        return [$queryBuilder, $userId];
    }
}