<?php

namespace App\symfony;

use App\DatabaseConfig;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineQueryBuilderConnection
{
    /**
     * @throws Exception
     */
    public static function connect(DatabaseConfig $config): QueryBuilder
    {
        $conn = DriverManager::getConnection($config->getSymphonyDatabaseConfig());

        // Return the query builder
        return $conn->createQueryBuilder();
    }
}