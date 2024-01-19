<?php

namespace App\pdo;

use App\DatabaseConfig;
use PDO;

class PDOConnection
{
    public static function connect(DatabaseConfig $config): ?PDO
    {
        // Create a PDO instance
        try {
            return new PDO(...$config->getPDODatabaseConfig());
        } catch (\Throwable $throwable) {
        }

        return null;
    }

}