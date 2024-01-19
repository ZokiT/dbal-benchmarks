<?php

namespace App\symfony;

use App\DatabaseConfig;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Exception\CacheException;

class DoctrineEntityManager
{
    /**
     * @throws MissingMappingDriverImplementation|Exception
     * @throws CacheException
     */
    public static function connect(DatabaseConfig $config): EntityManager
    {
        // Specify the path to your entity classes
        $entityPath = [__DIR__ . '/Models'];

        // Create a configuration using ORMSetup
        $metadataConfig = ORMSetup::createAttributeMetadataConfiguration($entityPath);

//        $metaCache = new PhpFilesAdapter('doctrine_metadata', 0, __DIR__ . '/../symfony/cache');
//        $metadataConfig->setMetadataCache($metaCache);

        $connection = DriverManager::getConnection($config->getSymphonyDatabaseConfig(), $metadataConfig);

        return new EntityManager($connection, $metadataConfig);
    }
}