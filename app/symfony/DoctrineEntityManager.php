<?php

namespace App\symfony;

use App\Benchmark\Benchmark;
use App\Benchmark\Params;
use App\DatabaseConfig;
use App\symfony\Models\User;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Exception\CacheException;

class DoctrineEntityManager
{
    /**
     * @throws MissingMappingDriverImplementation|Exception
     */
    public static function connect(Params $params): Params
    {
        // Specify the path to your entity classes
        $entityPath = [__DIR__ . '/Models'];

        // Create a configuration using ORMSetup
        $metadataConfig = ORMSetup::createAttributeMetadataConfiguration($entityPath);

//        $metaCache = new PhpFilesAdapter('doctrine_metadata', 0, __DIR__ . '/../symfony/cache');
//        $metadataConfig->setMetadataCache($metaCache);

        $connection = DriverManager::getConnection(DatabaseConfig::getSymphonyDatabaseConfig(), $metadataConfig);
        $params->addParam('doctrineEntityManager', new EntityManager($connection, $metadataConfig));

        return $params;
    }

    /**
     * @throws MissingMappingDriverImplementation|Exception
     * @throws NotSupported
     */
    public static function connectForUpdate(Params $params): Params
    {
        $params = self::connect($params);

        // Get the repository for the User entity
        $userRepository = $params->getParam('doctrineEntityManager')->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->findOneBy([]);
        $params->addParam('user', $user);

        return $params;
    }
}